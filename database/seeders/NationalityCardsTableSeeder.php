<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NationalityCardsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('nationality_cards')->delete();
        
        \DB::table('nationality_cards')->insert(array (
            0 => 
            array (
                'id' => 1,
                'number' => '010517515',
                'height' => NULL,
                'start' => '2016-03-23',
                'end' => '2026-03-22',
                'desp' => '',
                'serial' => '',
                'pdf' => 'pYD77IZv8oz1ZmcEDhWO3HgQFgB1DKctqZFqECWq.pdf',
                'distinguishing_mark' => 'ប្រជ្រុយចំ ០.៣ ស.ម មុខក្រោមគល់ចិញ្ចើមស្ដាំ',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 09:32:24',
                'updated_at' => '2025-07-31 08:07:50',
                'deleted_at' => '2025-07-31 08:07:50',
            ),
            1 => 
            array (
                'id' => 2,
                'number' => '010517515',
                'height' => NULL,
                'start' => '2016-08-01',
                'end' => '2026-08-01',
                'desp' => '',
                'serial' => '',
                'pdf' => 'hA35ybEHo3pNNUhKOOCTavf9m7H9EukOZ5bU4iTc.pdf',
                'distinguishing_mark' => 'ប្រជ្រុយក្រោមចញ្ចើមខាងឆ្វេង',
                'created_by' => 1342,
                'updated_by' => 1342,
                'deleted_by' => NULL,
                'created_at' => '2025-08-04 15:18:04',
                'updated_at' => '2025-08-04 15:18:21',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}