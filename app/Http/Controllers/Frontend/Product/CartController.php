<?php


namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Frontend\BaseController;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ShoppingCart;
use Gloudemans\Shoppingcart\Facades\Cart;
use Inertia\Inertia;

final class CartController extends BaseController
{

    public function index()
    {
        $shoppingCart = new ShoppingCart();
        $cart = $shoppingCart->getContent();

        if (request()->wantsJson()) {
            return $cart;
        }

        return Inertia::render('Cart', [
            'cart' => $cart,
        ]);
    }

    public function add()
    {
        $params = request()->only(['product_id', 'quantity', 'variant_id']);

        $product = Product::active()->where('id', $params['product_id'])->first();
        $productVariant = ProductVariant::query()->where('id', $params['variant_id'])->first();

        if (!empty($product->id) && !empty($productVariant->id)) {
            $options = $productVariant->transform($product);
            $options['image_url'] = $options['image_url'] ?? $product->image_url;

            $isAddCart = true;
            foreach (Cart::content() as $cartItem) {
                if ($cartItem->options->product_id == $params['product_id'] && $cartItem->options->id == $params['variant_id']) {

                    $quantity = $params['quantity'] + $cartItem->qty;
                    Cart::update($cartItem->rowId, $quantity);

                    $isAddCart = false;
                }
            }

            if ($isAddCart) {
                $price = $productVariant->price;
                Cart::add([
                    'id' => uniqid(),
                    'name' => $product->name,
                    'price' => $price,
                    'qty' => $params['quantity'],
                    'weight' => 0,
                    'options' => $options,
                ]);
            }
        }

        $shoppingCart = new ShoppingCart();
        $data = $shoppingCart->getContent();

        if (request()->wantsJson()) {
            return $data;
        }

        redirect()->back()->with('message', 'Thêm giỏ hàng thành công');
    }

    public function update($rowId)
    {
        $quantity = request('quantity');

        Cart::update($rowId, $quantity);

        if (request()->wantsJson()) {
            return $this->getCartContent();
        }

        redirect()->back()->with('message', 'Cập nhật giỏ hàng thành công');
    }

    public function destroy($rowId)
    {
        Cart::remove($rowId);

        if (request()->wantsJson()) {
            return $this->getCartContent();
        }
        redirect()->back()->with('message', 'Xóa thành công');
    }

    private function getCartContent()
    {
        $shoppingCart = new ShoppingCart();
        return $shoppingCart->getContent();
    }
}
