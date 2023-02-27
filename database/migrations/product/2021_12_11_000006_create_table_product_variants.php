
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTableProductVariants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->autoIncrement();
            $table->bigInteger('product_id')->nullable()->default(0);
            $table->string('product_name')->nullable();
            $table->bigInteger('source_id')->nullable()->default(0);
            $table->string('sku', 255)->nullable();
            $table->string('barcode', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->decimal('price', 18, 2)->nullable();
            $table->decimal('old_price', 18, 2)->nullable();
            $table->integer('position')->nullable()->default(0);
            $table->smallInteger('status')->nullable()->default(1)->index();
            $table->integer('quantity')->nullable()->default(0);
            $table->boolean('is_default')->default(0);

            $table->string('image_url')->nullable();

            $table->string('source', 255)->nullable();
            $table->string('source_sku', 255)->nullable();
            $table->string('source_product_id')->nullable();
            $table->bigInteger('source_variant_id')->nullable();
            $table->bigInteger('source_category_id')->nullable();
            $table->string('source_url', 255)->nullable();
            $table->longText('source_raw')->nullable();

            $table->integer('creator_id')->nullable()->default(0);
            $table->integer('editor_id')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id', 'product_id');
            $table->index('deleted_at', 'deleted_at');
            $table->index(['source_sku', 'source'], 'source_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
