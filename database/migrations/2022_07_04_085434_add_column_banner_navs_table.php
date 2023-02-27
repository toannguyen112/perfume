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
        Schema::table('navs', function (Blueprint $table) {
            $table->dropColumn('image_url');
            $table->dropColumn('image_url_mobile');
            $table->string('left_banner_url')->nullable()->after('icon');
            $table->string('right_banner_url')->nullable()->after('icon');
            $table->string('center_banner_url')->nullable()->after('icon');
            $table->string('link_header_left')->nullable()->after('icon');
            $table->string('link_header_center')->nullable()->after('icon');
            $table->string('link_header_right')->nullable()->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navs', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('icon');
            $table->string('image_url_mobile')->nullable()->after('icon');
            $table->dropColumn('left_banner_url');
            $table->dropColumn('right_banner_url');
            $table->dropColumn('center_banner_url');
            $table->dropColumn('link_header_left');
            $table->dropColumn('link_header_center');
            $table->dropColumn('link_header_right');
        });
    }
};
