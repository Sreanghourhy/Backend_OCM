<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('provinces')->delete();
        
        \DB::table('provinces')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name_kh' => 'បន្ទាយមានជ័យ',
                'name_en' => 'Banteay Meanchey',
                'code' => '1',
            ),
            1 => 
            array (
                'id' => 2,
                'name_kh' => 'បាត់ដំបង',
                'name_en' => 'Battambang',
                'code' => '2',
            ),
            2 => 
            array (
                'id' => 3,
                'name_kh' => 'កំពង់ចាម',
                'name_en' => 'Kampong Cham',
                'code' => '3',
            ),
            3 => 
            array (
                'id' => 4,
                'name_kh' => 'កំពង់ឆ្នាំង',
                'name_en' => 'Kampong Chhnang',
                'code' => '4',
            ),
            4 => 
            array (
                'id' => 5,
                'name_kh' => 'កំពង់ស្ពឺ',
                'name_en' => 'Kampong Speu',
                'code' => '5',
            ),
            5 => 
            array (
                'id' => 6,
                'name_kh' => 'កំពង់ធំ',
                'name_en' => 'Kampong Thom',
                'code' => '6',
            ),
            6 => 
            array (
                'id' => 7,
                'name_kh' => 'កំពត',
                'name_en' => 'Kampot',
                'code' => '7',
            ),
            7 => 
            array (
                'id' => 8,
                'name_kh' => 'កណ្ដាល',
                'name_en' => 'Kandal',
                'code' => '8',
            ),
            8 => 
            array (
                'id' => 9,
                'name_kh' => 'កោះកុង',
                'name_en' => 'Koh Kong',
                'code' => '9',
            ),
            9 => 
            array (
                'id' => 10,
                'name_kh' => 'ក្រចេះ',
                'name_en' => 'Kratie',
                'code' => '10',
            ),
            10 => 
            array (
                'id' => 11,
                'name_kh' => 'មណ្ឌលគិរី',
                'name_en' => 'Mondul Kiri',
                'code' => '11',
            ),
            11 => 
            array (
                'id' => 12,
                'name_kh' => 'រាជធានីភ្នំពេញ',
                'name_en' => 'Phnom Penh',
                'code' => '12',
            ),
            12 => 
            array (
                'id' => 13,
                'name_kh' => 'ព្រះវិហារ',
                'name_en' => 'Preah Vihear',
                'code' => '13',
            ),
            13 => 
            array (
                'id' => 14,
                'name_kh' => 'ព្រៃវែង',
                'name_en' => 'Prey Veng',
                'code' => '14',
            ),
            14 => 
            array (
                'id' => 15,
                'name_kh' => 'ពោធិ៍សាត់',
                'name_en' => 'Pursat',
                'code' => '15',
            ),
            15 => 
            array (
                'id' => 16,
                'name_kh' => 'រតនគិរី',
                'name_en' => 'Ratanak Kiri',
                'code' => '16',
            ),
            16 => 
            array (
                'id' => 17,
                'name_kh' => 'សៀមរាប',
                'name_en' => 'Siemreap',
                'code' => '17',
            ),
            17 => 
            array (
                'id' => 18,
                'name_kh' => 'ព្រះសីហនុ',
                'name_en' => 'Preah Sihanouk',
                'code' => '18',
            ),
            18 => 
            array (
                'id' => 19,
                'name_kh' => 'ស្ទឹងត្រែង',
                'name_en' => 'Stung Treng',
                'code' => '19',
            ),
            19 => 
            array (
                'id' => 20,
                'name_kh' => 'ស្វាយរៀង',
                'name_en' => 'Svay Rieng',
                'code' => '20',
            ),
            20 => 
            array (
                'id' => 21,
                'name_kh' => 'តាកែវ',
                'name_en' => 'Takeo',
                'code' => '21',
            ),
            21 => 
            array (
                'id' => 22,
                'name_kh' => 'ឧត្ដរមានជ័យ',
                'name_en' => 'Oddar Meanchey',
                'code' => '22',
            ),
            22 => 
            array (
                'id' => 23,
                'name_kh' => 'កែប',
                'name_en' => 'Kep',
                'code' => '23',
            ),
            23 => 
            array (
                'id' => 24,
                'name_kh' => 'ប៉ៃលិន',
                'name_en' => 'Pailin',
                'code' => '24',
            ),
            24 => 
            array (
                'id' => 25,
                'name_kh' => 'ត្បូងឃ្មុំ',
                'name_en' => 'Tboung Khmum',
                'code' => '25',
            ),
        ));
        
        
    }
}