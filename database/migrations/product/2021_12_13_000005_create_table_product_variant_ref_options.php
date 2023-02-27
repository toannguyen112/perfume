<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTableProductVariantRefOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variant_ref_options', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('option_id')->index();
            $table->boolean('is_required')->default(false);

            $table->primary(['product_variant_id', 'product_id', 'option_id'])->name('product_variant_ref_options_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variant_ref_options');
    }
}
