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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->longText('description')->nullable();
            $table->smallInteger('status')->nullable()->default(0);
            $table->boolean('is_hot')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->string('meta_title', 255)->nullable();
            $table->text('custom_slug')->nullable();
            $table->string('meta_description', 255)->nullable();

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
        Schema::dropIfExists('brands');
    }
};
