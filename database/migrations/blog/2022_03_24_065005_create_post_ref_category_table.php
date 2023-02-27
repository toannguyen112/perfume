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
        Schema::create('post_ref_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('post_category_id')->index();

            $table->primary(['post_id', 'post_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_ref_categories');
    }
};
