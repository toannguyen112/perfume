<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->default(0);

            $table->string('name');
            $table->string('slug');
            $table->integer('position')->nullable()->default(0);

            $table->string('image_url', 255)->nullable();

            $table->smallInteger('status')->default(0);
            $table->integer('creator_id')->nullable()->default(0);
            $table->integer('editor_id')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('options');
    }
}
