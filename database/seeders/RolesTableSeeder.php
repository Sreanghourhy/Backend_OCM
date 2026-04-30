<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 18,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'internship',
                'khname' => 'បុគ្គលិកស្មគ្រចិត្ត',
                'enname' => 'Intership',
                'sub_role_index' => 15,
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'client',
                'guard_name' => 'api',
                'created_at' => '2023-09-10 06:44:28',
                'updated_at' => '2023-09-10 06:44:28',
                'tag' => 'client_service',
                'key_name' => 'client',
                'sub_role' => NULL,
                'khname' => 'ភ្ញៀវ',
                'enname' => 'Client',
                'sub_role_index' => NULL,
            ),
            2 => 
            array (
                'id' => 1,
                'name' => 'super',
                'guard_name' => 'api',
                'created_at' => '2023-09-10 06:44:28',
                'updated_at' => '2023-09-10 06:44:28',
                'tag' => 'core_service',
                'key_name' => 'super',
                'sub_role' => 'super',
                'khname' => 'រដ្ឋាលជាន់ខ្ពស់',
                'enname' => 'Super Administration',
                'sub_role_index' => NULL,
            ),
            3 => 
            array (
                'id' => 2,
                'name' => 'admin',
                'guard_name' => 'api',
                'created_at' => '2023-09-10 06:44:28',
                'updated_at' => '2023-09-10 06:44:28',
                'tag' => 'core_service',
                'key_name' => 'admin',
                'sub_role' => 'admin',
                'khname' => 'រដ្ឋបាល',
                'enname' => 'Administration',
                'sub_role_index' => NULL,
            ),
            4 => 
            array (
                'id' => 3,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2023-09-10 06:44:28',
                'updated_at' => '2023-09-10 06:44:28',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'department',
                'khname' => 'នាយកដ្ឋាន',
                'enname' => 'Department',
                'sub_role_index' => 9,
            ),
            5 => 
            array (
                'id' => 5,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 09:53:51',
                'updated_at' => '2026-01-28 09:53:51',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'general_department',
                'khname' => 'អគ្គនាយកដ្ឋាន',
                'enname' => 'General Department',
                'sub_role_index' => 7,
            ),
            6 => 
            array (
                'id' => 6,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'secretary_of_state',
                'khname' => 'រដ្ឋលេខាធិការ',
                'enname' => 'Secretary of State',
                'sub_role_index' => 5,
            ),
            7 => 
            array (
                'id' => 7,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'deputy_secretary_of_state',
                'khname' => 'អនុរដ្ឋលេខាធិការ',
                'enname' => 'Deputy Secretary of State',
                'sub_role_index' => 6,
            ),
            8 => 
            array (
                'id' => 8,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'minister',
                'khname' => 'រដ្ឋមន្ត្រី',
                'enname' => 'Minister',
                'sub_role_index' => 4,
            ),
            9 => 
            array (
                'id' => 9,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'senior_minister',
                'khname' => 'ទេសរដ្ឋមន្ត្រី',
                'enname' => 'Senior Minister',
                'sub_role_index' => 3,
            ),
            10 => 
            array (
                'id' => 10,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'deputy_prime_minister',
                'khname' => 'ឧបនាយករដ្ឋមន្ត្រី',
                'enname' => 'Deputy Prime Minister',
                'sub_role_index' => 2,
            ),
            11 => 
            array (
                'id' => 11,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'prime_minister',
                'khname' => 'នាយករដ្ឋមន្ត្រី',
                'enname' => 'Primary Minister',
                'sub_role_index' => 1,
            ),
            12 => 
            array (
                'id' => 12,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'deputy_general_department',
                'khname' => 'អគ្គនាយករង',
                'enname' => 'Deputy General Department',
                'sub_role_index' => 8,
            ),
            13 => 
            array (
                'id' => 13,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'deputy_department',
                'khname' => 'អនុប្រជាននាយកដ្ឋាន',
                'enname' => 'Deputy Department',
                'sub_role_index' => 10,
            ),
            14 => 
            array (
                'id' => 14,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'division',
                'khname' => 'ប្រធានការិយាល័យ',
                'enname' => 'Division',
                'sub_role_index' => 11,
            ),
            15 => 
            array (
                'id' => 15,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'deputy_division',
                'khname' => 'អនុប្រធានការិយាល័យ',
                'enname' => 'Deputy Division',
                'sub_role_index' => 12,
            ),
            16 => 
            array (
                'id' => 16,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'officer',
                'khname' => 'មន្ត្រី',
                'enname' => 'Officer',
                'sub_role_index' => 13,
            ),
            17 => 
            array (
                'id' => 17,
                'name' => 'backend',
                'guard_name' => 'api',
                'created_at' => '2026-01-28 10:02:34',
                'updated_at' => '2026-01-28 10:02:34',
                'tag' => 'core_service',
                'key_name' => 'backend',
                'sub_role' => 'contract_officer',
                'khname' => 'មន្ត្រីកិច្ចសន្យា',
                'enname' => 'Contract Officer',
                'sub_role_index' => 14,
            ),
        ));
        
        
    }
}