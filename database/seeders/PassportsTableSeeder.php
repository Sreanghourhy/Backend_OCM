<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PassportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('passports')->delete();
        
        \DB::table('passports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'people_id' => 1342,
                'number' => 'N01957929',
                'serial_number' => '',
                'profession' => '',
                'height' => '',
                'distinguishing_mark' => '',
                'emergency_contact_person' => '',
                'address' => '',
                'type' => '0',
                'country_code' => '',
                'nationality' => '',
                'dob' => '2025-05-31',
                'gender' => '',
                'effective_date' => '2021-05-06',
                'expired_date' => '2031-05-06',
                'pob' => '',
                'pdf' => 'xvrNY5N9WCU32poRmyIvVbU6bozpOpPaCGbPe2A9.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 09:57:43',
                'updated_at' => '2025-05-31 09:57:51',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}