<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('product_id')->default(0)->nullable();
            $table->json('variant_ids')->nullable();
            $table->integer('position')->nullable()->default(0);

            $table->integer('image_id')->default(0)->nullable();
            $table->string('image_url')->nullable();

            $table->string('source_url')->nullable();

            $table->string('alt', 255)->nullable();

            $table->timestamps();

            $table->index('product_id', 'product_id');
            $table->index('image_id', 'image_id');
            $table->index('deleted_at', 'deleted_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
