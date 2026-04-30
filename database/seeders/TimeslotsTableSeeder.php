<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TimeslotsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('timeslots')->delete();
        
        \DB::table('timeslots')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'ព្រឹក',
                'start' => '07:00',
                'end' => '11:00',
                'effective_day' => '1,2,3,4,5,6',
                'active' => 1,
                'rest_duration' => '0',
                'created_at' => '2023-11-03 14:44:15',
                'updated_at' => '2023-11-03 14:44:15',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'ល្ងាច',
                'start' => '12:00',
                'end' => '16:00',
                'effective_day' => '1,2,3,4,5,6',
                'active' => 1,
                'rest_duration' => '0',
                'created_at' => '2023-11-03 14:44:15',
                'updated_at' => '2023-11-03 14:44:15',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'យប់',
                'start' => '18:00',
                'end' => '22:00',
                'effective_day' => '1,2,3,4,5,6',
                'active' => 1,
                'rest_duration' => '0',
                'created_at' => '2023-11-03 14:44:15',
                'updated_at' => '2023-11-03 14:44:15',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}