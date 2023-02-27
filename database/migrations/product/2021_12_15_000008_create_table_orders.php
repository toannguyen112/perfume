<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('customer_id')->nullable()->default(0);
            $table->string('customer_name', 50)->nullable();
            $table->smallInteger('source')->nullable()->default(0);
            $table->bigInteger('source_id')->nullable()->default(0);
            $table->bigInteger('source_order_number')->nullable()->default(0);
            $table->string('source_name', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('order_number', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('number', 255)->nullable();
            $table->string('token', 255)->nullable();
            $table->string('gateway', 255)->nullable();
            $table->smallInteger('status')->default(0);
            $table->smallInteger('delivery_method')->default(0);
            $table->boolean('test')->default(false);
            $table->decimal('total_price', 18, 2)->default(0);
            $table->decimal('subtotal_price', 18, 2)->default(0);
            $table->decimal('total_tax', 18, 2)->default(0);
            $table->decimal('total_discounts', 18, 2)->default(0);
            $table->decimal('total_line_items_price', 18, 2)->default(0);
            $table->integer('total_weight')->default(0);
            $table->boolean('taxes_included')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->boolean('buyer_accepts_marketing')->default(false);
            $table->string('currency', 10)->nullable();
            $table->string('financial_status', 50)->nullable();
            $table->string('cart_token')->nullable();
            $table->string('referring_site')->nullable();
            $table->string('landing_site')->nullable();
            $table->text('note')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('checkout_token')->nullable();
            $table->string('reference')->nullable();
            $table->string('source_identifier')->nullable();
            $table->string('source_url')->nullable();
            $table->string('device_id')->nullable();
            $table->string('browser_ip')->nullable();
            $table->string('landing_site_ref')->nullable();
            $table->bigInteger('user_id')->default(0);
            $table->bigInteger('location_id')->default(0);
            $table->bigInteger('checkout_id')->default(0);
            $table->integer('creator_id')->nullable()->default(0);
            $table->integer('editor_id')->nullable()->default(0);
            $table->text('discount_codes')->nullable();
            $table->string('coupon_code', 255)->nullable();
            $table->text('note_attributes')->nullable();
            $table->integer('payment_method')->default(1)->nullable();
            $table->text('payment_gateway_names')->nullable();
            $table->text('shipping_lines')->nullable();
            $table->text('tax_lines')->nullable();
            $table->text('tags')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('fulfillments')->nullable();
            $table->text('client_details')->nullable();
            $table->text('refunds')->nullable();
            $table->text('payment_details')->nullable();
            $table->string('processing_method')->nullable();
            $table->string('fulfillment_status')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id', 'customer_id');
            $table->index(['source_id', 'source'], 'source_name');
            $table->index('order_number', 'order_number');
            $table->index('deleted_at', 'deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
