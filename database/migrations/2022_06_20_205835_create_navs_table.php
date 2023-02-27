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
        Schema::create('navs', function (Blueprint $table) {
            $table->id();

            $table->string('type');
            $table->bigInteger('type_id')->nullable();
            $table->bigInteger('parent_id')->nullable()->default(0);

            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('link')->nullable();
            $table->string('target')->nullable();

            $table->text('icon')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_url_mobile')->nullable();

            $table->integer('position')->default(0);
            $table->smallInteger('status')->default(0);

            $table->integer('creator_id')->nullable()->default(0);
            $table->integer('editor_id')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id', 'parent_id');
            $table->index('type', 'type');
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
        Schema::dropIfExists('navs');
    }
};
