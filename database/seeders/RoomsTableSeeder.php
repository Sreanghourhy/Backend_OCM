<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('rooms')->delete();
        
        \DB::table('rooms')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'សាល ៧០៨',
                'desp' => 'អាគាភាតរភាព បន្ទប់លេខ ៧០៨',
                'image' => 'admin/roomcontroller/1/mfeI0tO3U8qOWYp73h49q3DsrN0BEEcwUwtGu8lr.png',
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-10-14 09:22:49',
                'updated_at' => '2024-11-01 12:22:07',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'សាល ៦០៧ B',
                'desp' => 'អាគាភាតរភាព ជាន់ទី ៦ បន្ទប់លេខ ៦០៧ B',
                'image' => 'admin/roomcontroller/1/lxkkm0oMmKBP9w4eF530VsyFHqQoVd75OhCrWQdz.jpg',
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-10-14 09:23:14',
                'updated_at' => '2024-11-01 12:22:44',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'សាល ៦០៧ A',
                'desp' => 'សាល ៦០៧ A',
                'image' => 'admin/roomcontroller/1/5iFRW1771OLb1Wh9Eh29XjHCVyHWUUZLBswbkVOX.jpg',
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-11-01 12:13:36',
                'updated_at' => '2024-11-01 12:14:17',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'សាល A',
                'desp' => 'អាគារ មិត្តភាព',
                'image' => 'admin/roomcontroller/1/yWyViEcrJ0Ts8RRQhiOtJ7i4SyO2ygCBSvamOXX6.jpg',
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-11-01 12:42:35',
                'updated_at' => '2024-11-01 12:44:22',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'សាល B',
                'desp' => 'អាគារ មិត្តភាព',
                'image' => 'admin/roomcontroller/1/ZxwTyAbNU4KiBDdFEV1CQK7AR3nncZaJSUzfuzQ9.jpg',
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-11-01 12:42:41',
                'updated_at' => '2024-11-01 12:45:10',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'សាល C',
                'desp' => 'អាគារ មិត្តភាព',
                'image' => 'admin/roomcontroller/1/HxjFhuqXkA0CcH0tK2kPq2kuYpGuJy0IROSOVl6g.jpg',
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-11-01 12:42:46',
                'updated_at' => '2024-11-01 12:44:56',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'សាលរំដួល',
                'desp' => 'អាគារសន្តិភាព',
                'image' => NULL,
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-12-03 11:00:29',
                'updated_at' => '2024-12-03 11:00:29',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'សាលប្រជុំពេញអង្គ',
                'desp' => 'អាគារសន្តិភាព',
                'image' => NULL,
                'pdf' => NULL,
                'record_index' => NULL,
                'active' => 1,
                'created_at' => '2024-12-03 11:01:02',
                'updated_at' => '2024-12-03 11:01:02',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}