<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by TablePlus 2.10(268)
 * @author https://tableplus.com
 * @source https://github.com/TablePlus/tabledump
 */
class CreateTableOptionRefCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_ref_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('option_id')->index();
            $table->unsignedBigInteger('product_category_id')->index();

            $table->primary(['option_id', 'product_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_ref_categories');
    }
}