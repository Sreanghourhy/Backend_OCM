<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficerPenaltyHistoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('officer_penalty_histories')->delete();
        
        \DB::table('officer_penalty_histories')->insert(array (
            0 => 
            array (
                'id' => 2,
                'officer_id' => 1342,
                'fid' => '034',
                'date' => '2001-06-01',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'position' => NULL,
                'type' => 'វិន័យ',
                'desp' => 'ការមកធ្វើការមិនទៀតទាត់',
                'pdf' => '',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-06-01 00:00:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}