<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FoldersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('folders')->delete();
        
        \DB::table('folders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 268,
                'name' => 'ផែនទីចង្អុលផ្លូវផែនការយុទ្ធសាស្ត្របរិវត្តកម្មឌីជីថលទីស្ដីការគណៈរដ្ឋមន្ត្រី ២០២៤ ២០២៨',
                'description' => NULL,
                'image' => NULL,
                'pdf' => NULL,
                'pid' => 0,
                'active' => 1,
                'accessibility' => 0,
                'created_at' => '2024-10-20 20:50:51',
                'updated_at' => '2024-10-20 20:50:51',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1342,
                'name' => 'កម្រងឯកសារកិច្ចប្រជុំជំរុញបរិវត្តកម្មឌីជីថល',
                'description' => NULL,
                'image' => NULL,
                'pdf' => NULL,
                'pid' => 0,
                'active' => 1,
                'accessibility' => 0,
                'created_at' => '2024-12-06 14:29:16',
                'updated_at' => '2024-12-06 14:29:16',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}