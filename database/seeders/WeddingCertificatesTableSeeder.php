<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WeddingCertificatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wedding_certificates')->delete();
        
        \DB::table('wedding_certificates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'people_id' => 1342,
                'wedding_number' => '137',
                'book_number' => '03-2014',
                'year' => '2014-07-10',
                'province_id' => 12,
                'district_id' => 103,
                'commune_id' => 893,
                'spouse_id' => 0,
                'spouse_firstname' => 'រ៉ូសា',
                'spouse_lastname' => 'ចាន់',
                'spouse_enfirstname' => 'Rosa',
                'spouse_enlastname' => 'CHAN',
                'spouse_national' => 'khmer',
                'spouse_nid' => '010739852',
                'spouse_passport' => 'N01466939',
                'spouse_nationality' => 'khmer',
                'spouse_dob' => '1983-02-23',
                'spouse_profession' => 'បុគ្គលិកធនាគារ',
                'spouse_profession_organization' => 'មាយប៊េង - Maybank',
                'spouse_pob' => 'ជំរំរិទ្ធិសែន ប្រទេសថៃ',
                'spouse_address' => 'ផ្ទះ៩០៥ស៊ី ផ្លូវ០១ បុរីតូក្យូស៊ីធី៩៩៩ បុរី១០០ខ្នង ទឹកថ្លា សែនសុខ ភ្នំពេញ',
                'spouse_death' => 0,
                'spouse_father_firstname' => 'ស៊ីផូ',
                'spouse_father_lastname' => 'ចាន់',
                'spouse_father_enfirstname' => 'Sipho',
                'spouse_father_enlastname' => 'CHAN',
                'spouse_father_dob' => '2025-05-31',
                'spouse_father_nationality' => 'khmer',
                'spouse_father_national' => NULL,
                'spouse_father_pob' => '',
                'spouse_father_address' => NULL,
                'spouse_father_profession' => NULL,
                'spouse_father_picture' => NULL,
                'spouse_father_death' => 0,
                'spouse_mother_firstname' => 'សាវន',
                'spouse_mother_lastname' => 'អួន',
                'spouse_mother_enfirstname' => 'Savon',
                'spouse_mother_enlastname' => 'OUN',
                'spouse_mother_dob' => '2025-05-31',
                'spouse_mother_nationality' => 'khmer',
                'spouse_mother_national' => NULL,
                'spouse_mother_pob' => 'ឃំរ៉ូងជ្រៃ ស្រុកថ្មគោក ខេត្តបាត់ដំបង',
                'spouse_mother_address' => NULL,
                'spouse_mother_profession' => NULL,
                'spouse_mother_picture' => NULL,
                'spouse_mother_death' => 0,
                'issued_date' => '2014-07-10',
                'issued_location' => NULL,
                'signed_name' => NULL,
                'pdf' => 'PpphKr8GddM19HN62uHQrziGyZCUzrGtJVNdcA33.pdf',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 09:25:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}