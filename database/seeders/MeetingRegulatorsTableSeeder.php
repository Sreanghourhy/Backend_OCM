<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MeetingRegulatorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('meeting_regulators')->delete();
        
        \DB::table('meeting_regulators')->insert(array (
            0 => 
            array (
                'id' => 21,
                'meeting_id' => 3,
                'regulator_id' => 64843,
            ),
            1 => 
            array (
                'id' => 22,
                'meeting_id' => 3,
                'regulator_id' => 19155,
            ),
            2 => 
            array (
                'id' => 23,
                'meeting_id' => 3,
                'regulator_id' => 17254,
            ),
            3 => 
            array (
                'id' => 24,
                'meeting_id' => 3,
                'regulator_id' => 16826,
            ),
        ));
        
        
    }
}