<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MeetingAttendantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('meeting_attendants')->delete();
        
        \DB::table('meeting_attendants')->insert(array (
            0 => 
            array (
                'id' => 1,
                'meeting_member_id' => 1,
                'people_id' => 2423,
                'remark' => NULL,
                'checktime' => NULL,
                'created_at' => '2024-11-01 12:46:53',
                'updated_at' => '2024-11-01 12:46:53',
                'deleted_at' => NULL,
                'created_by' => 0,
                'updated_by' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'meeting_member_id' => 2,
                'people_id' => 2020,
                'remark' => NULL,
                'checktime' => NULL,
                'created_at' => '2024-11-01 12:49:58',
                'updated_at' => '2024-11-01 12:49:58',
                'deleted_at' => NULL,
                'created_by' => 0,
                'updated_by' => 0,
            ),
        ));
        
        
    }
}