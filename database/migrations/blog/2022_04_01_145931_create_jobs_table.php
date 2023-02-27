<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Blog\Job;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);
            $table->string('slug', 255);

            $table->longText('description')->nullable();

            $table->string('position')->nullable();
            $table->string('salary')->nullable();
            $table->string('address')->nullable();
            $table->smallInteger('status')->nullable()->default(0);
            $table->boolean('is_featured')->default(0);
            $table->date('posted_at')->nullable();
            $table->date('expected_time')->nullable();
            $table->integer('count')->nullable()->default(0);
            $table->string('work_form')->nullable();
            $table->string('work_time')->nullable();

            $table->string('meta_title', 255)->nullable();
            $table->text('custom_slug')->nullable();
            $table->string('meta_description', 255)->nullable();

            $table->bigInteger('parent_id')->nullable()->default(0);
            $table->integer('creator_id')->nullable()->default(0);
            $table->integer('editor_id')->nullable()->default(0);

            $table->index('status', 'status');
            $table->timestamps();
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
        Schema::dropIfExists('jobs');
    }
};
