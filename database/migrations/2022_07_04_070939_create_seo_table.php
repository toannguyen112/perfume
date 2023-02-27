<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id();

            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image_url')->nullable();
            $table->string('author')->nullable();

            $table->bigInteger('brand_id')->nullable();
            $table->bigInteger('product_category_id')->nullable();
            $table->json('option_ids')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo');
    }
};
