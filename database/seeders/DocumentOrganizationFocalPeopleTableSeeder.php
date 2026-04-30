<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentOrganizationFocalPeopleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('document_organization_focal_people')->delete();
        
        \DB::table('document_organization_focal_people')->insert(array (
            0 => 
            array (
                'id' => 9,
                'organization_structure_id' => 3,
                'officer_id' => 2484,
                'priority' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'created_at' => '2026-02-04 16:00:11',
                'updated_at' => '2026-02-04 16:00:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 10,
                'organization_structure_id' => 3,
                'officer_id' => 3604,
                'priority' => NULL,
                'created_by' => 1,
                'updated_by' => 1,
                'deleted_by' => 0,
                'created_at' => '2026-02-04 16:00:11',
                'updated_at' => '2026-02-04 16:00:11',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 11,
                'organization_structure_id' => 35,
                'officer_id' => 1339,
                'priority' => NULL,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 12,
                'organization_structure_id' => 35,
                'officer_id' => 1332,
                'priority' => NULL,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 13,
                'organization_structure_id' => 5,
                'officer_id' => 1306,
                'priority' => NULL,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 14,
                'organization_structure_id' => 5,
                'officer_id' => 1331,
                'priority' => NULL,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}