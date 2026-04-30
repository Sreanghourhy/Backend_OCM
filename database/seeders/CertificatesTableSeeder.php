<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CertificatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('certificates')->delete();
        
        \DB::table('certificates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'field_name' => 'មធ្យមសិក្សាចំណេះទូទៅ',
                'start' => '2001',
                'end' => '2004',
                'location' => 'សង្កាត់ទួលទំពូង១ ខណ្ឌចំការមន រាជធានីភ្នំពេញ',
                'place_name' => 'វិទ្យាល័យទួលទំពូង',
                'certificate_note' => '',
                'certificate_group_id' => 2,
                'people_id' => 1342,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'pdf' => '1Mg0FzUN3N9lZ77pHRZCCDGlABxhN4HEA08sdbyc.pdf',
                'created_at' => '2025-05-31 08:20:41',
                'updated_at' => '2025-05-31 08:28:48',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'field_name' => 'វិទ្យាសាស្ត្រកុំព្យូទ័រ',
                'start' => '2004',
                'end' => '2008',
                'location' => 'សែនសុខ ភ្នំពេញ',
                'place_name' => 'សកលវិទ្យាល័យភូមិន្ទភ្នំពេញ',
                'certificate_note' => '',
                'certificate_group_id' => 5,
                'people_id' => 1342,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'pdf' => 'vxm4YqVBpfGJ1K7kymtMVEX1IejhZVIyK27VyrL6.pdf',
                'created_at' => '2025-05-31 10:02:47',
                'updated_at' => '2025-05-31 10:03:35',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'field_name' => 'ឈ្មោះជំនាញ',
                'start' => '2015-07-01',
                'end' => '2021-07-01',
                'location' => 'ទីតាំងនៃគ្រឹះស្ថានសិក្សា',
                'place_name' => 'ឈ្មោះគ្រឹះស្ថានសិក្សា',
                'certificate_note' => '',
                'certificate_group_id' => 5,
                'people_id' => 1342,
                'created_by' => 1342,
                'updated_by' => 1342,
                'deleted_by' => NULL,
                'pdf' => 'Qk6sMGJTN6EdJWSXBe5WyD0pSW8aMPbarGX6wSDz.pdf',
                'created_at' => '2025-07-29 09:45:13',
                'updated_at' => '2025-07-29 11:01:52',
                'deleted_at' => '2025-07-29 11:01:52',
            ),
        ));
        
        
    }
}