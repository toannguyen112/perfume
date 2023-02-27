<?php

namespace App\Models\Product;

use Gloudemans\Shoppingcart\Facades\Cart;

class ShoppingCart
{
    public function getContent(): array
    {
        $items = Cart::content();

        $data = [];
        $quantity = 0;

        $products = Product::query()
            ->whereIn('id', $items->pluck('options.product_id'))
            ->get();

        foreach ($items as $item) {
            $item->slug = $item->options->slug ?? '';
            $item->variant_id = $item->options->id ?? 0;
            $item->is_default = $item->options->is_default ?? 0;
            $item->total_price = $item->qty * $item->price;

            $product = $products
                ->firstWhere('id', $item->options->product_id)
                ->transform($item->variant_id);

            $item->product_detail = $product ?? [];

            $data[] = $item;

            $quantity += $item->qty;
        }

        $shipping_cost = 0;
        $sub_total = (int) self::subTotal();

        if ($sub_total > 0 && $sub_total < 700000) {
            $shipping_cost  = 30000;
        }

        $totalPrice = $sub_total + $shipping_cost;

        return [
            'total_price' => $totalPrice,
            'total_sub_price' => (int)self::subTotal(),
            'total_quantity' => $quantity,
            'shipping_cost' => $shipping_cost,
            'items' => $data,
        ];
    }

    public static function priceTotal()
    {
        $priceTotal = Cart::priceTotal(0);

        return filter_var($priceTotal, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function subTotal()
    {
        $priceTotal = Cart::subtotal(0);

        return filter_var($priceTotal, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
