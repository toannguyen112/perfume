<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SlidersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('sliders')->delete();

        \DB::table('sliders')->insert(array (
            0 =>
            array (
                'id' => 1,
                'link' => NULL,
                'title' => 'slider 1',
                'slug' => 'slider-1',
                'position' => 'HOME_SLIDER',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'link' => NULL,
                'title' => 'slider 2',
                'slug' => 'slider-2',
                'position' => 'HOME_SLIDER',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'link' => NULL,
                'title' => 'slider 3',
                'slug' => 'slider-3',
                'position' => 'HOME_SLIDER',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'link' => NULL,
                'title' => 'slider 4',
                'slug' => 'slider-4',
                'position' => 'HOME_SLIDER',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'link' => NULL,
                'title' => 'home top left 1',
                'slug' => 'home-top-left-1',
                'position' => 'HOME_TOP_LEFT',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'link' => NULL,
                'title' => 'home top right 1',
                'slug' => 'home-top-right-1',
                'position' => 'HOME_TOP_RIGHT',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'link' => NULL,
                'title' => 'home footer top 1',
                'slug' => 'home-footer-top-1',
                'position' => 'HOME_FOOTER_TOP',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'link' => NULL,
                'title' => 'home footer bottom left 1',
                'slug' => 'home-footer-bottom-left-1',
                'position' => 'HOME_FOOTER_BOTTOM_LEFT',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'link' => NULL,
                'title' => 'home footer bottom center 1',
                'slug' => 'home-footer-bottom-center-1',
                'position' => 'HOME_FOOTER_BOTTOM_CENTER',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'link' => NULL,
                'title' => 'home footer bottom right 1',
                'slug' => 'home-footer-bottom-right-1',
                'position' => 'HOME_FOOTER_BOTTOM_RIGHT',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'link' => NULL,
                'title' => 'post center 1',
                'slug' => 'post-center-1',
                'position' => 'POST_CENTER',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'link' => NULL,
                'title' => 'post right 1',
                'slug' => 'post-right-1',
                'position' => 'POST_RIGHT',
                'status' => 1,
                'created_at' => '2022-04-20 11:54:01',
                'updated_at' => '2022-04-20 12:54:01',
                'deleted_at' => NULL,
            ),
        ));
    }
}
