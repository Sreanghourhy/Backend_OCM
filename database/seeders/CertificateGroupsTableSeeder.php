<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CertificateGroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('certificate_groups')->delete();
        
        \DB::table('certificate_groups')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'បឋមភូមិ',
                'desp' => 'កម្រិតសិក្សា បឋមភូមិ ថ្នាកទី៩ រយះពេល ៣ ឆ្នាំ',
                'education_level' => 'កម្រិតវប្បធម័ទូទៅ',
                'education_level_name' => 'general_knowledge',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'ទុតិយភូមិ',
                'desp' => 'កម្រិតសិក្សា ទុតិយភូមិ ថ្នាកទី១២ រយះពេល ៣ ឆ្នាំ',
                'education_level' => 'កម្រិតវប្បធម័ទូទៅ',
                'education_level_name' => 'general_knowledge',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'ផ្សេងៗ',
                'desp' => 'កម្រិតសិក្សាផ្សេងៗដែលមាន កម្រិតស្មើ ឬ ប្រហាក់ប្រហែល',
                'education_level' => 'កម្រិតវប្បធម័ទូទៅ',
                'education_level_name' => 'general_knowledge',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'បវិញ្ញាបត្ររង',
                'desp' => 'កម្រិតសិក្សា ថ្នាក់បរិញ្ញាបត្ររង រយះពេល ២ ឆ្នាំ',
                'education_level' => 'កម្រិតបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ',
                'education_level_name' => 'skill',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'បរិញ្ញាបត្រ',
                'desp' => 'កម្រិតសិក្សារ ថ្នាក់បរិញ្ញាបត្រ រយះពេល ៤ ឆ្នាំ',
                'education_level' => 'កម្រិតបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ',
                'education_level_name' => 'skill',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'បរិញ្ញាបត្រជាន់ខ្ពស់',
                'desp' => 'កម្រិតសិក្សារ ថ្នាក់អនុបណ្ឌិត រយះពេល ២ ឆ្នាំ',
                'education_level' => 'កម្រិតបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ',
                'education_level_name' => 'skill',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'បណ្ឌិត',
                'desp' => 'កម្រិតសិក្សារ ថ្នាក់បណ្ឌិត វិទ្យាសាស្ត្រ ២ ឬ ៣ ឆ្នាំ',
                'education_level' => 'កម្រិតបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ',
                'education_level_name' => 'skill',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'ផ្សេងៗ',
                'desp' => 'កម្រិតសិក្សាផ្សេងៗដែលមាន កម្រិតស្មើ ឬ ប្រហាក់ប្រហែល',
                'education_level' => 'កម្រិតបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ',
                'education_level_name' => 'skill',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'វិញ្ញាបណ្ណបត្រ',
                'desp' => 'វគ្គសិក្សារផ្សេង កម្រិតវិញ្ញាបណ្ណបត្រ',
                'education_level' => 'វគ្គបណ្ដុះបណ្ដាលបន្ត',
                'education_level_name' => 'additional',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'សញ្ញាបត្រសាលាភូមិន្ទរដ្ឋបាល',
                'desp' => 'វគ្គសិក្សារផ្សេង កម្រិតសញ្ញាបត្រ',
                'education_level' => 'វគ្គបណ្ដុះបណ្ដាលបន្ត',
                'education_level_name' => 'additional',
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-02-20 10:50:11',
                'updated_at' => '2025-02-20 10:50:11',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}