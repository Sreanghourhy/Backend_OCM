<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficerRankByCertificatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('officer_rank_by_certificates')->delete();
        
        \DB::table('officer_rank_by_certificates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'officer_id' => 1342,
                'previous_rank_id' => 33,
                'rank_id' => 32,
                'date' => '2009-06-01',
                'organization' => 'សកលវិទ្យាល័យភូមិន្ទភ្នំពេញ',
                'location' => 'សង្កាត់ទឹកថ្លា ខណ្ឌទឺកថ្លា ភ្នំពេញ',
                'certificate' => 'បរិញ្ញាបត្រវិទ្យាសាស្ត្រកុំព្យូទ័រ',
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