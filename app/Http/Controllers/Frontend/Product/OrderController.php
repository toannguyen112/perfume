<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Frontend\BaseController;
use App\Models\Product\Order;
use App\Models\Product\OrderLine;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ShoppingCart;
use App\Models\Region;
use Gloudemans\Shoppingcart\Facades\Cart;
use Inertia\Inertia;
use Illuminate\Support\Facades\Notification;
use Jamstackvietnam\Support\Notifications\CommonNotification;

final class OrderController extends BaseController
{
    public $model = Order::class;

    public function index()
    {
        $items = $this->getCart();
        if (count($items['items']) < 1) {
            return redirect('/');
        }
        return Inertia::render('Checkout/Index', [
            'modelForm' => form(new $this->model),
        ]);
    }

    public function getCart()
    {
        $shoppingCart = new ShoppingCart();
        return $shoppingCart->getContent();
    }

    public function checkout()
    {
        $params = request()->all();
        $items = $this->getCart();

        if (count($items['items']) < 1) {
            return redirect()
                ->back()
                ->with('error', 'Giỏ hàng trống, Vui lòng thêm sản phẩm vào giỏ hàng.');
        }

        $orderId = 0;

        $dataInsertOrder = [
            'order_number' => Order::generateIdByPrefix('SO', 'order_number'),
            'status' => Order::STATUS_NEW,
            'customer_name' => $params['customer_name'],
            'email' => $params['email'] ?? null,
            'phone' => $params['phone'],
            'note' => $params['note'],
            'city' => Region::codeToName($params['city']),
            'district' => Region::codeToName($params['district']),
            'ward' => Region::codeToName($params['ward']),
            'payment_method' => $params['payment_method'],
            'shipping_address' => $params['shipping_address'],
        ];

        $dataInsertOrder['total_price'] =  $items['total_price'];
        $dataInsertOrder['subtotal_price'] =  $items['total_sub_price'];
        $dataInsertOrder['total_shipping'] =  $items['shipping_cost'];

        $order = Order::query()->create($dataInsertOrder);
        $orderId = $order->id;

        foreach ($items['items'] as $item) {

            $productVariant = $item->options;
            $product = Product::query()->where('id', $productVariant['product_id'])->first(['name']);
            $productName = $product->name;
            $price = $item->price;
            $quantity = $item->qty;
            $productId = $productVariant['product_id'];

            $dataOrderLine = [
                'product_id' => $productId,
                'order_id' => $orderId,
                'variant_id' => $productVariant['id'] ?? 0,
                'variant_title' => $productVariant['title'],
                'title' => $productName,
                'name' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'total_discount' => 0,
                'grams' => 0,
                'sku' => $productVariant['sku'] ?? 0,
                'variant_title' => $productVariant['title'] ?? ''
            ];

            OrderLine::create($dataOrderLine);
        }

        $this->sendEmail($order);

        Cart::destroy();

        if (request()->wantsJson()) {
            return $order;
        }

        return redirect(route('checkout.payment.tracking', ['orderNumber' => $order['order_number']]));
    }

    public function tracking(string $orderNumber)
    {
        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect('/');
        }

        if (request()->wantsJson()) {
            return $order->transformDetails();
        }

        return Inertia::render('Checkout/Success', [
            'order' => $order->transformDetails(),
        ]);
    }

    public function sendEmail($order)
    {
        if ($emailTo = env('MAIL_NOTIFICATION_TO')) {
            $emails = explode(',', $emailTo);

            $data['mail_title'] = 'Bạn nhận được đơn hàng mới';

            $data = array_merge($data, $order->transformEmail());

            foreach($emails as $email)
            {
                Notification::route('mail', $email)
                    ->notify(new CommonNotification($data));
            }

            $data = $order->transformEmailDetails();

            $data['mail_title'] = 'Bạn đã đặt hàng thành công';
            if (isset($data['Email'])) {
                $emailTo = $data['Email'];

                Notification::route('mail', $emailTo)
                    ->notify(new CommonNotification($data));
            }
        }

    }
}
