<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PeopleLanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('people_languages')->delete();
        
        \DB::table('people_languages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'people_id' => 1342,
                'name' => 'ខ្មែរ',
                'reading' => 'a',
                'speaking' => 'a',
                'writing' => 'a',
                'pdf' => NULL,
                'created_at' => '2025-05-30 00:00:00',
                'updated_at' => '2025-05-30 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'people_id' => 1342,
                'name' => 'អង់គ្លេស',
                'reading' => 'a',
                'speaking' => 'a',
                'writing' => 'b',
                'pdf' => NULL,
                'created_at' => '2025-05-30 00:00:00',
                'updated_at' => '2025-05-30 00:00:00',
            ),
        ));
        
        
    }
}