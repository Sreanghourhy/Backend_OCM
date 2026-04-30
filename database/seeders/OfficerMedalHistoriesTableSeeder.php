<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficerMedalHistoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('officer_medal_histories')->delete();
        
        \DB::table('officer_medal_histories')->insert(array (
            0 => 
            array (
                'id' => 3,
                'officer_id' => 1342,
                'fid' => '3840',
                'date' => '2018-06-04',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'position' => NULL,
                'type' => 'សំរឹទ្ធ',
                'desp' => '',
                'pdf' => 'bkXlf0reTHA9KDJszICWqScRHOXJs1uB4iDwnCfp.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 11:53:08',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 4,
                'officer_id' => 1342,
                'fid' => '2027',
                'date' => '2023-04-19',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'position' => NULL,
                'type' => 'ប្រាក់',
                'desp' => '',
                'pdf' => 'FQQTasdURQKNBL2cq9JaJdoBy7qNPdHNZSJncvQz.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 11:29:45',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}