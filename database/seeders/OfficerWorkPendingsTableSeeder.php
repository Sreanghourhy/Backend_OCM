<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficerWorkPendingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('officer_work_pendings')->delete();
        
        \DB::table('officer_work_pendings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'officer_id' => 1342,
                'start' => '2021-06-01',
                'end' => '2023-06-01',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'position' => '',
                'total_months' => '24',
                'pdf' => '',
                'type' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-06-01 13:58:13',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'officer_id' => 1342,
                'start' => '2000-06-01',
                'end' => '2001-06-01',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'position' => '12',
                'total_months' => '12',
                'pdf' => '',
                'type' => 0,
                'created_by' => 1,
                'updated_by' => 1342,
                'deleted_by' => 0,
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-08-05 00:00:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}