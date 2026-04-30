<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BirthCertificatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('birth_certificates')->delete();
        
        \DB::table('birth_certificates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'people_id' => 1342,
                'birth_number' => '1401',
                'book_number' => '15',
                'year' => '2005-05-24',
                'province_id' => 12,
                'district_id' => 96,
                'commune_id' => 834,
                'firstname' => '',
                'lastname' => '',
                'enfirstname' => '',
                'enlastname' => '',
                'dob' => '2025-05-31',
                'gender' => 'male',
                'nationality' => 'ខ្មែរ',
                'profession' => '',
                'organization' => '',
                'national' => 'ខ្មែរ',
                'pob' => '',
                'issued_date' => '2005-05-24',
                'wedding_certificate_id' => 0,
                'pdf' => 'tMlWrp0smeoXBqBmioCmh6KwZ8jpDY3jGxRqU7oK.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 09:30:29',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'people_id' => 1342,
                'birth_number' => '232',
                'book_number' => '06-2015',
                'year' => '2015-04-24',
                'province_id' => 12,
                'district_id' => 103,
                'commune_id' => 893,
                'firstname' => 'ពុទ្ធិរាជ',
                'lastname' => 'គង់ចាន់',
                'enfirstname' => 'Puthireach',
                'enlastname' => 'KONGCHAN',
                'dob' => '2015-04-01',
                'gender' => 'male',
                'nationality' => 'khmer',
                'profession' => 'សិស្ស',
                'organization' => 'សាលាអន្តរជាតិឌឺគ្រែន',
                'national' => 'khmer',
                'pob' => 'ភូមិទឹកថ្លា សង្កាត់ទឹកថ្លា ខណ្ឌសែនសុខ ភ្នំពេញ',
                'issued_date' => '2015-04-24',
                'wedding_certificate_id' => 1,
                'pdf' => 'H8McECOMK7Kyz8sTQBFmEZFCj5vUwRHUyPniUxHh.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-06-01 00:00:00',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'people_id' => 1342,
                'birth_number' => '288',
                'book_number' => '03-2018',
                'year' => '2018-11-19',
                'province_id' => 12,
                'district_id' => 103,
                'commune_id' => 893,
                'firstname' => 'បេឡាមុទិតា',
                'lastname' => 'គង់ចាន់',
                'enfirstname' => 'Bellamudhita',
                'enlastname' => 'KONGCHAN',
                'dob' => '2018-09-29',
                'gender' => 'female',
                'nationality' => 'khmer',
                'profession' => 'សិស្ស',
                'organization' => 'សាលាអន្តរជាតិឌឺគ្រែន',
                'national' => 'khmer',
                'pob' => 'ភូមិបុរី១០០ខ្នង សង្កាត់ទឹកថ្លា ខណ្ឌសែនសុខ ភ្នំពេញ',
                'issued_date' => '2018-11-19',
                'wedding_certificate_id' => 1,
                'pdf' => 'gwPsKs9lOQn8uuHuEv8t0FZG6TWfCW9Jg83tPefe.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-06-01 00:00:00',
                'updated_at' => '2025-06-01 11:16:57',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}