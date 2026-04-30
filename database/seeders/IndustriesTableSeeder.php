<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('industries')->delete();
        
        \DB::table('industries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => '5678ujkifo938iu7yfhgbnjumaksndef',
                'name' => '​សាធារណៈ',
                'tags' => '#public',
                'desp' => NULL,
                'tpid' => NULL,
                'pid' => NULL,
                'cids' => NULL,
                'created_at' => '2026-04-08 00:00:00',
                'updated_at' => '2026-04-08 00:00:00',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'key' => '76t5ghjukiol9i8u7628iklo',
                'name' => 'ឯកជន',
                'tags' => '#private',
                'desp' => NULL,
                'tpid' => NULL,
                'pid' => NULL,
                'cids' => NULL,
                'created_at' => '2026-04-08 00:00:00',
                'updated_at' => '2026-04-08 00:00:00',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'key' => '9876ytghji98765tr4edf',
                'name' => 'អង្គការក្រៅរដ្ឋាភិបាល',
                'tags' => '#ngo',
                'desp' => NULL,
                'tpid' => NULL,
                'pid' => NULL,
                'cids' => NULL,
                'created_at' => '2026-04-08 00:00:00',
                'updated_at' => '2026-04-08 00:00:00',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 6,
            'key' => '09876ytghJKOP)(*&^',
                'name' => 'ខុទ្ទកាល័យ',
                'tags' => '#public#prime_minister_office#deputy_prime_minister_office#senior_minister_office#minister_office#secretariat_of_state_office',
                'desp' => NULL,
                'tpid' => NULL,
                'pid' => NULL,
                'cids' => NULL,
                'created_at' => '2026-04-08 00:00:00',
                'updated_at' => '2026-04-08 00:00:00',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 7,
                'key' => '#$%^&yuhnmkI8U765TRFDGHJ',
                'name' => 'ក្រុមប្រឹក្សា',
                'tags' => '#public#private#consultation_group',
                'desp' => NULL,
                'tpid' => NULL,
                'pid' => NULL,
                'cids' => NULL,
                'created_at' => '2026-04-08 00:00:00',
                'updated_at' => '2026-04-08 00:00:00',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 8,
                'key' => '567*iujkio(*&^%trfdcvbnm',
                    'name' => 'អាជ្ញាធរ',
                    'tags' => '#public#private#authority',
                    'desp' => NULL,
                    'tpid' => NULL,
                    'pid' => NULL,
                    'cids' => NULL,
                    'created_at' => '2026-04-08 00:00:00',
                    'updated_at' => '2026-04-08 00:00:00',
                    'deleted_at' => NULL,
                ),
                6 => 
                array (
                    'id' => 9,
                    'key' => '87^%$678IUkjm<lpO09i*u&ytGFVbh',
                    'name' => 'គណៈកម្មាធិការ',
                    'tags' => '#public#private#council',
                    'desp' => NULL,
                    'tpid' => NULL,
                    'pid' => NULL,
                    'cids' => NULL,
                    'created_at' => '2026-04-08 00:00:00',
                    'updated_at' => '2026-04-08 00:00:00',
                    'deleted_at' => NULL,
                ),
                7 => 
                array (
                    'id' => 10,
                    'key' => '(*&u8iJHgtrfVBJNMk,mnbhg',
                        'name' => 'ក្រសួងស្ថាប័ន',
                        'tags' => '#public#ministry',
                        'desp' => NULL,
                        'tpid' => NULL,
                        'pid' => NULL,
                        'cids' => NULL,
                        'created_at' => '2026-04-08 00:00:00',
                        'updated_at' => '2026-04-08 00:00:00',
                        'deleted_at' => NULL,
                    ),
                    8 => 
                    array (
                        'id' => 11,
                        'key' => '0987U*IKJu7y6%TRFvgbhnjmk,l',
                        'name' => 'អគ្គនាយកដ្ឋាន',
                        'tags' => '#public#general_department',
                        'desp' => NULL,
                        'tpid' => NULL,
                        'pid' => NULL,
                        'cids' => NULL,
                        'created_at' => '2026-04-08 00:00:00',
                        'updated_at' => '2026-04-08 00:00:00',
                        'deleted_at' => NULL,
                    ),
                    9 => 
                    array (
                        'id' => 12,
                        'key' => 'lkjI*U76yT%GBHJNMkloi987',
                        'name' => 'នាយកដ្ឋាន',
                        'tags' => '#public#department',
                        'desp' => NULL,
                        'tpid' => NULL,
                        'pid' => NULL,
                        'cids' => NULL,
                        'created_at' => '2026-04-08 00:00:00',
                        'updated_at' => '2026-04-08 00:00:00',
                        'deleted_at' => NULL,
                    ),
                    10 => 
                    array (
                        'id' => 13,
                        'key' => 'lo9i*&^%TRgHBNgftr56789io',
                        'name' => 'ការិយាល័យ',
                        'tags' => '#public#private#office#division',
                        'desp' => NULL,
                        'tpid' => NULL,
                        'pid' => NULL,
                        'cids' => NULL,
                        'created_at' => '2026-04-08 00:00:00',
                        'updated_at' => '2026-04-08 00:00:00',
                        'deleted_at' => NULL,
                    ),
                    11 => 
                    array (
                        'id' => 14,
                        'key' => 'kljh*&6%TYuijkjhGT5^78(',
                            'name' => 'ក្រុមការងារ',
                            'tags' => '#public#private#teamwork',
                            'desp' => NULL,
                            'tpid' => NULL,
                            'pid' => NULL,
                            'cids' => NULL,
                            'created_at' => '2026-04-08 00:00:00',
                            'updated_at' => '2026-04-08 00:00:00',
                            'deleted_at' => NULL,
                        ),
                    ));
        
        
    }
}