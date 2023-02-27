<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\Searchable;
use App\Traits\HashId;

class Order extends BaseModel
{
    use SoftDeletes;
    use Searchable;
    use HashId;

    const STATUS_NEW = 1; // Trạng thái mới
    const STATUS_VERIFIED = 3; // Xác nhận
    const STATUS_PROCESSING = 5; // Đang xử lý
    const STATUS_DELIVERING = 7; // Đang giao hàng
    const STATUS_DELIVERED = 9; // Đã giao hàng
    const STATUS_COMPLETED = 11; // Hoàn thành
    const STATUS_CANCELLED = 13; // Huỷ"

    public const PAYMENT_COD = 1; // COD
    public const PAYMENT_MOMO = 2; //  Momo
    public const PAYMENT_PAYPAL = 3; // Paypal
    public const PAYMENT_ATM = 4; // ATM

    public const STATUS_LIST = [
        self::STATUS_NEW => 'Đơn hàng mới',
        self::STATUS_VERIFIED => 'Xác nhận',
        self::STATUS_PROCESSING => 'Đang xử lý',
        self::STATUS_DELIVERING => 'Đang giao hàng',
        self::STATUS_DELIVERED => 'Đã giao hàng',
        self::STATUS_COMPLETED => 'Hoàn thành',
        self::STATUS_CANCELLED => 'Huỷ',
    ];

    public const PAYMENT_LIST = [
        self::PAYMENT_COD => 'Thanh toán khi nhận hàng',
        self::PAYMENT_MOMO => 'Thanh toán qua ví Momo',
        self::PAYMENT_PAYPAL => 'Thanh toán qua ví Paypal',
        self::PAYMENT_ATM => 'Thanh toán chuyển khoản',
    ];

    protected $table = 'orders';

    public $fillable = [
        'id',
        'customer_name',
        'source',
        'source_id',
        'source_name',
        'name',
        'order_number',
        'email',
        'phone',
        'number',
        'total_price',
        'subtotal_price',
        'total_shipping',
        'total_tax',
        'total_discounts',
        'total_line_items_price',
        'total_weight',
        'currency',
        'payment_method',
        'note',
        'cancelled_at',
        'source_identifier',
        'status',
        'location_id',
        'checkout_id',
        'creator_id',
        'editor_id',
        'note_attributes',
        'gateway',
        'payment_gateway_names',
        'shipping_lines',
        'tax_lines',
        'tags',
        'billing_address',
        'shipping_address',
        'fulfillments',
        'client_details',
        'payment_details',
        'processing_method',
        'delivery_method',
        'fulfillment_status',
        'closed_at',
        'creator_id',
        'editor_id',
        'city',
        'district',
        'ward',
    ];

    protected $casts = [
        'tax_lines' => 'array',
        'shipping_address' => 'array',
        'subtotal_price' => 'int',
        'total_price' => 'int',
        'total_discounts' => 'int',
        'total_tax' => 'int',
        'total_line_items_price' => 'int',
    ];

    protected $appends = [
        'formatted_payment_method',
        'formatted_status',
    ];

    protected $dates = [
        'closed_at',
    ];

    public $searchable = [
        'columns' => [
            'orders.order_number' => 10,
            'orders.id' => 9,
            'orders.customer_name' => 7,
            'orders.email' => 5,
            'orders.status' => 2,
            'orders.total_price' => 2,
        ],
    ];

    public function modelRules(): array
    {
        return [
            'all' => [
                'customer_name' => 'required|string|max:255',
                'phone' => 'required|min:10|numeric',
                'city' => 'required',
                'district' => 'required',
                'ward' => 'required',
                'payment_method' => 'required',
                'shipping_address' => 'required|string|max:255',
            ],
        ];
    }

    public function orderLines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }

    public function getFormattedStatusAttribute()
    {
        return self::STATUS_LIST[$this->status];
    }

    public function getFormattedPaymentMethodAttribute()
    {
        return self::PAYMENT_LIST[$this->payment_method] ?? '--';
    }

    public function transform(): array
    {

        $lineItems = $this->orderLines;

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'created_at' => $this->created_at,
            'first_product_title' => $lineItems->first()->title,
            'other_product_count' => $lineItems->count() - 1,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'fulfillment_status' => $this->fulfillment_status,
            'shipping_address' => $this->shipping_address,
            'shipping_lines' => $this->shipping_lines,
            'total_price' => $this->total_price,
            'total_shipping' => $this->total_shipping,
            'subtotal_price' => $this->subtotal_price,
        ];
    }

    public function transformDetails(): array
    {
        $lineItems = OrderLine::query()
            ->where('order_id', $this->id)
            ->get()
            ->map(function (OrderLine $item) {
                return $item->transform();
            });

        return [
            'customer_name' => $this->customer_name,
            'order_number' => $this->order_number,
            'email' => $this->email,
            'phone' => $this->phone,
            'total_price' => $this->total_price,
            'subtotal_price' => $this->subtotal_price,
            'formatted_payment_method' => $this->formatted_payment_method,
            'note' => $this->note,
            'shipping_lines' => $this->shipping_lines,
            'total_shipping' => $this->total_shipping,
            'shipping_address' => $this->shipping_address,
            'line_items' => $lineItems->toArray(),

        ];
    }

    public function transformEmail()
    {
        return [
            'Loại form' => 'Đặt hàng',
            'Họ tên' => $this->customer_name,
            'Mã đơn hàng' => $this->order_number,
            'Email' => $this->email,
            'Số điện thoại' => $this->phone,
            'Tổng tiền' => price_format($this->total_price),
            'url' => $this->getUrl()
        ];
    }

    public function transformEmailDetails()
    {
        $order_url = route('checkout.payment.tracking', [ 'orderNumber' => $this->order_number ]);
        return [
            'Loại form' => 'Đặt hàng',
            'Họ tên' => $this->customer_name,
            'Mã đơn hàng' => $this->order_number,
            'Email' => $this->email,
            'Số điện thoại' => $this->phone,
            'Địa chỉ khách hàng' => $this->shipping_address,
            'Phương thức thanh toán' => $this->formatted_payment_method,
            'Ghi chú' => $this->note,
            'Phí ship' => price_format($this->total_shipping),
            'Tổng tiền' => price_format($this->total_price),
            'order_url' => $order_url
        ];
    }

    public function getUrl()
    {
        return route('sidebar.orders.form', [ 'id' => $this->id ]);
    }
}
