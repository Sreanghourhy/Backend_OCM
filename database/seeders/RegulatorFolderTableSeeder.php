<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RegulatorFolderTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('regulator_folder')->delete();
        
        \DB::table('regulator_folder')->insert(array (
            0 => 
            array (
                'id' => 1,
                'regulator_id' => 62952,
                'folder_id' => 1,
                'created_at' => '2024-10-20 20:50:57',
                'updated_at' => '2024-10-20 20:50:57',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 3,
                'regulator_id' => 62945,
                'folder_id' => 2,
                'created_at' => '2024-12-06 16:00:04',
                'updated_at' => '2025-10-07 10:34:01',
                'deleted_at' => '2025-10-07 10:34:01',
            ),
        ));
        
        
    }
}