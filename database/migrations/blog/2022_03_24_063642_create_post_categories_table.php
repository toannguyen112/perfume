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
        Schema::create('post_categories', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->string('title', 255);
            $table->string('slug', 255);

            $table->smallInteger('status')->nullable()->default(0);

            $table->string('meta_title', 255)->nullable();
            $table->text('custom_slug')->nullable();
            $table->string('meta_description', 255)->nullable();

            $table->integer('creator_id')->nullable()->default(0);
            $table->integer('editor_id')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('status', 'status');
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
        Schema::dropIfExists('post_categories');
    }
};
