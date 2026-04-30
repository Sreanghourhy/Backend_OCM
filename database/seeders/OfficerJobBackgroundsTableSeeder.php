<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficerJobBackgroundsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('officer_job_backgrounds')->delete();
        
        \DB::table('officer_job_backgrounds')->insert(array (
            0 => 
            array (
                'id' => 1,
                'officer_id' => 1342,
                'officer_job_id' => NULL,
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'sub_organization' => 'អគ្គលេខាធិការដ្ឋានរាជរដ្ឋាភិបាល',
                'position' => 'មន្ត្រី',
                'start' => '2015-08-17',
                'end' => '2001-01-01',
                'pdf' => '',
                'skill_of_position' => 'អភិវឌ្ឍកម្មសុវែរ',
                'sector' => 0,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 00:00:00',
                'deleted_at' => NULL,
                'is_primary' => false,
            ),
            1 => 
            array (
                'id' => 2,
                'officer_id' => 1342,
                'officer_job_id' => NULL,
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'sub_organization' => 'នាយកដ្ឋានឯកសារអេឡិចត្រូវនិច និងព័ត៌មានវិទ្យា',
                'position' => 'អនុប្រធានការិយាល័យ',
                'start' => '2001-01-01',
                'end' => '2023-01-01',
                'pdf' => '',
                'skill_of_position' => 'អភិវឌ្ឍកម្មសុវែរ',
                'sector' => 0,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 00:00:00',
                'deleted_at' => NULL,
                'is_primary' => false,
            ),
            2 => 
            array (
                'id' => 3,
                'officer_id' => 1342,
                'officer_job_id' => NULL,
                'organization' => 'ទីស្ដីការគណៈរដ្ឋមន្ត្រី',
                'sub_organization' => 'អគ្គនាយកដ្ឋានបរិវត្តកម្មឌីជីថល',
                'position' => 'អនុប្រធានការិយាល័យ',
                'start' => '2023-01-01',
                'end' => '2025-05-31',
                'pdf' => '',
                'skill_of_position' => 'អភិវឌ្ឍកម្មសុវែរ',
                'sector' => 0,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => NULL,
                'created_at' => '2025-05-31 00:00:00',
                'updated_at' => '2025-05-31 00:00:00',
                'deleted_at' => NULL,
                'is_primary' => false,
            ),
            3 => 
            array (
                'id' => 6,
                'officer_id' => 3860,
                'officer_job_id' => NULL,
                'organization' => 'ក្រសួង ប្រៃណីយ៍ និងទូរគមនាគមន៍',
                'sub_organization' => 'asd',
                'position' => 'asdasd',
                'start' => '2026-04-08',
                'end' => '2026-04-16',
                'pdf' => '',
                'skill_of_position' => 'asdasd',
                'sector' => 0,
                'created_by' => 1342,
                'updated_by' => 1342,
                'deleted_by' => NULL,
                'created_at' => '2026-04-27 00:00:00',
                'updated_at' => '2026-04-27 00:00:00',
                'deleted_at' => NULL,
                'is_primary' => false,
            ),
        ));
        
        
    }
}