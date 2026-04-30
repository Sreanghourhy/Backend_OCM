<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficerRankByWorkingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('officer_rank_by_workings')->delete();
        
        \DB::table('officer_rank_by_workings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'officer_id' => 1342,
                'previous_rank_id' => 34,
                'rank_id' => 33,
                'date' => '2023-01-01',
                'changing_type' => '0',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'sub_organization' => 'អគ្គនាយកដ្ឋានបរិវត្តកម្ម',
                'sub_sub_organization' => 'អភិវឌ្ឍសុវ័រ',
                'desp' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'pdf' => '',
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-06-01 00:00:00',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'officer_id' => 1342,
                'previous_rank_id' => 33,
                'rank_id' => 32,
                'date' => '2021-01-01',
                'changing_type' => '0',
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'sub_organization' => 'អគ្គនាយកដ្ឋានបរិវត្តកម្ម',
                'sub_sub_organization' => 'អភិវឌ្ឍសុវ័រ',
                'desp' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'pdf' => '',
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-06-01 00:00:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}