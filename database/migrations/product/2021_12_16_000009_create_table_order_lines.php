<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTableOrderLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('product_id')->nullable()->default(0);
            $table->bigInteger('order_id')->nullable()->default(0);
            $table->smallInteger('source')->nullable()->default(0);
            $table->bigInteger('source_id')->nullable()->default(0);
            $table->bigInteger('variant_id')->nullable()->default(0);
            $table->string('title', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->decimal('price', 18, 2)->default(0);
            $table->decimal('total_discount', 18, 2)->default(0);
            $table->string('grams', 255)->nullable();
            $table->string('sku', 255)->nullable();
            $table->string('variant_title', 255)->nullable();
            $table->string('variant_inventory_management', 50)->nullable();
            $table->string('vendor', 255)->nullable();
            $table->string('fulfillment_service', 255)->nullable();
            $table->boolean('requires_shipping')->default(false);
            $table->boolean('taxable')->default(false);
            $table->boolean('gift_card')->default(false);
            $table->boolean('product_exists')->default(false);
            $table->boolean('is_combo')->default(false);
            $table->bigInteger('combo_id')->nullable()->default(0);
            $table->text('properties')->nullable();
            $table->text('tax_lines')->nullable();
            $table->string('fulfillment_status', 50)->nullable();
            $table->integer('fulfillable_quantity')->nullable()->default(0);
            $table->timestamps();

            $table->index('order_id', 'order_id');
            $table->index('product_id', 'product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_lines');
    }
}
