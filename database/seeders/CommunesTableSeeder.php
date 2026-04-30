<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CommunesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('communes')->delete();
        
        \DB::table('communes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name_kh' => 'បន្ទាយនាង',
                'name_en' => 'Banteay Neang',
                'code' => '10201',
                'province_id' => 1,
                'district_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name_kh' => 'បត់ត្រង់',
                'name_en' => 'Bat Trang',
                'code' => '10202',
                'province_id' => 1,
                'district_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name_kh' => 'ចំណោម',
                'name_en' => 'Chamnaom',
                'code' => '10203',
                'province_id' => 1,
                'district_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name_kh' => 'គោកបល្ល័ង្គ',
                'name_en' => 'Kouk Ballangk',
                'code' => '10204',
                'province_id' => 1,
                'district_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name_kh' => 'គយម៉ែង',
                'name_en' => 'Koy Maeng',
                'code' => '10205',
                'province_id' => 1,
                'district_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name_kh' => 'អូរប្រាសាទ',
                'name_en' => 'Ou Prasat',
                'code' => '10206',
                'province_id' => 1,
                'district_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name_kh' => 'ភ្នំតូច',
                'name_en' => 'Phnum Touch',
                'code' => '10207',
                'province_id' => 1,
                'district_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name_kh' => 'រហាត់ទឹក',
                'name_en' => 'Rohat Tuek',
                'code' => '10208',
                'province_id' => 1,
                'district_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name_kh' => 'ឫស្សីក្រោក',
                'name_en' => 'Ruessei Kraok',
                'code' => '10209',
                'province_id' => 1,
                'district_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sambuor',
                'code' => '10210',
                'province_id' => 1,
                'district_id' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name_kh' => 'សឿ',
                'name_en' => 'Soea',
                'code' => '10211',
                'province_id' => 1,
                'district_id' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'name_kh' => 'ស្រះរាំង',
                'name_en' => 'Srah Reang',
                'code' => '10212',
                'province_id' => 1,
                'district_id' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'name_kh' => 'តាឡំ',
                'name_en' => 'Ta Lam',
                'code' => '10213',
                'province_id' => 1,
                'district_id' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'name_kh' => 'ណាំតៅ',
                'name_en' => 'Nam Tau',
                'code' => '10301',
                'province_id' => 1,
                'district_id' => 2,
            ),
            14 => 
            array (
                'id' => 15,
                'name_kh' => 'ប៉ោយចារ',
                'name_en' => 'Poy Char',
                'code' => '10302',
                'province_id' => 1,
                'district_id' => 2,
            ),
            15 => 
            array (
                'id' => 16,
                'name_kh' => 'ពន្លៃ',
                'name_en' => 'Ponley',
                'code' => '10303',
                'province_id' => 1,
                'district_id' => 2,
            ),
            16 => 
            array (
                'id' => 17,
                'name_kh' => 'ស្ពានស្រែង',
                'name_en' => 'Spean Sraeng',
                'code' => '10304',
                'province_id' => 1,
                'district_id' => 2,
            ),
            17 => 
            array (
                'id' => 18,
                'name_kh' => 'ស្រះជីក',
                'name_en' => 'Srah Chik',
                'code' => '10305',
                'province_id' => 1,
                'district_id' => 2,
            ),
            18 => 
            array (
                'id' => 19,
                'name_kh' => 'ភ្នំដី',
                'name_en' => 'Phnum Dei',
                'code' => '10306',
                'province_id' => 1,
                'district_id' => 2,
            ),
            19 => 
            array (
                'id' => 20,
                'name_kh' => 'ឈ្នួរមានជ័យ',
                'name_en' => 'Chnuor Mean Chey',
                'code' => '10401',
                'province_id' => 1,
                'district_id' => 3,
            ),
            20 => 
            array (
                'id' => 21,
                'name_kh' => 'ជប់វារី',
                'name_en' => 'Chob Vari',
                'code' => '10402',
                'province_id' => 1,
                'district_id' => 3,
            ),
            21 => 
            array (
                'id' => 22,
                'name_kh' => 'ភ្នំលៀប',
                'name_en' => 'Phnum Lieb',
                'code' => '10403',
                'province_id' => 1,
                'district_id' => 3,
            ),
            22 => 
            array (
                'id' => 23,
                'name_kh' => 'ប្រាសាទ',
                'name_en' => 'Prasat',
                'code' => '10404',
                'province_id' => 1,
                'district_id' => 3,
            ),
            23 => 
            array (
                'id' => 24,
                'name_kh' => 'ព្រះនេត្រព្រះ',
                'name_en' => 'Preak Netr Preah',
                'code' => '10405',
                'province_id' => 1,
                'district_id' => 3,
            ),
            24 => 
            array (
                'id' => 25,
                'name_kh' => 'រហាល',
                'name_en' => 'Rohal',
                'code' => '10406',
                'province_id' => 1,
                'district_id' => 3,
            ),
            25 => 
            array (
                'id' => 26,
                'name_kh' => 'ទានកាំ',
                'name_en' => 'Tean Kam',
                'code' => '10407',
                'province_id' => 1,
                'district_id' => 3,
            ),
            26 => 
            array (
                'id' => 27,
                'name_kh' => 'ទឹកជោរ',
                'name_en' => 'Tuek Chour',
                'code' => '10408',
                'province_id' => 1,
                'district_id' => 3,
            ),
            27 => 
            array (
                'id' => 28,
                'name_kh' => 'បុស្បូវ',
                'name_en' => 'Bos Sbov',
                'code' => '10409',
                'province_id' => 1,
                'district_id' => 3,
            ),
            28 => 
            array (
                'id' => 29,
                'name_kh' => 'ចង្ហា',
                'name_en' => 'Changha',
                'code' => '10501',
                'province_id' => 1,
                'district_id' => 4,
            ),
            29 => 
            array (
                'id' => 30,
                'name_kh' => 'កូប',
                'name_en' => 'Koub',
                'code' => '10502',
                'province_id' => 1,
                'district_id' => 4,
            ),
            30 => 
            array (
                'id' => 31,
                'name_kh' => 'គុត្ដសត',
                'name_en' => 'Kuttasat',
                'code' => '10503',
                'province_id' => 1,
                'district_id' => 4,
            ),
            31 => 
            array (
                'id' => 32,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '10505',
                'province_id' => 1,
                'district_id' => 4,
            ),
            32 => 
            array (
                'id' => 33,
                'name_kh' => 'សូភី',
                'name_en' => 'Souphi',
                'code' => '10506',
                'province_id' => 1,
                'district_id' => 4,
            ),
            33 => 
            array (
                'id' => 34,
                'name_kh' => 'សឹង្ហ',
                'name_en' => 'Soengh',
                'code' => '10507',
                'province_id' => 1,
                'district_id' => 4,
            ),
            34 => 
            array (
                'id' => 35,
                'name_kh' => 'អូរបីជាន់',
                'name_en' => 'Ou Beichoan',
                'code' => '10509',
                'province_id' => 1,
                'district_id' => 4,
            ),
            35 => 
            array (
                'id' => 36,
                'name_kh' => 'កំពង់ស្វាយ',
                'name_en' => 'Kampong Svay',
                'code' => '10602',
                'province_id' => 1,
                'district_id' => 5,
            ),
            36 => 
            array (
                'id' => 37,
                'name_kh' => 'កោះពងសត្វ',
                'name_en' => 'Kaoh Pong Satv',
                'code' => '10603',
                'province_id' => 1,
                'district_id' => 5,
            ),
            37 => 
            array (
                'id' => 38,
                'name_kh' => 'ម្កាក់',
                'name_en' => 'Mkak',
                'code' => '10604',
                'province_id' => 1,
                'district_id' => 5,
            ),
            38 => 
            array (
                'id' => 39,
                'name_kh' => 'អូរអំបិល',
                'name_en' => 'Ou Ambel',
                'code' => '10605',
                'province_id' => 1,
                'district_id' => 5,
            ),
            39 => 
            array (
                'id' => 40,
                'name_kh' => 'ភ្នៀត',
                'name_en' => 'Phniet',
                'code' => '10606',
                'province_id' => 1,
                'district_id' => 5,
            ),
            40 => 
            array (
                'id' => 41,
                'name_kh' => 'ព្រះពន្លា',
                'name_en' => 'Preah Ponlea',
                'code' => '10607',
                'province_id' => 1,
                'district_id' => 5,
            ),
            41 => 
            array (
                'id' => 42,
                'name_kh' => 'ទឹកថ្លា',
                'name_en' => 'Tuek Thla',
                'code' => '10608',
                'province_id' => 1,
                'district_id' => 5,
            ),
            42 => 
            array (
                'id' => 43,
                'name_kh' => 'បន្ទាយឆ្មារ',
                'name_en' => 'Banteay Chhmar',
                'code' => '10701',
                'province_id' => 1,
                'district_id' => 6,
            ),
            43 => 
            array (
                'id' => 44,
                'name_kh' => 'គោករមៀត',
                'name_en' => 'Kouk Romiet',
                'code' => '10702',
                'province_id' => 1,
                'district_id' => 6,
            ),
            44 => 
            array (
                'id' => 45,
                'name_kh' => 'ភូមិថ្មី',
                'name_en' => 'Phum Thmei',
                'code' => '10703',
                'province_id' => 1,
                'district_id' => 6,
            ),
            45 => 
            array (
                'id' => 46,
                'name_kh' => 'ថ្មពួក',
                'name_en' => 'Thma Puok',
                'code' => '10704',
                'province_id' => 1,
                'district_id' => 6,
            ),
            46 => 
            array (
                'id' => 47,
                'name_kh' => 'គោកកឋិន',
                'name_en' => 'Kouk Kakthen',
                'code' => '10705',
                'province_id' => 1,
                'district_id' => 6,
            ),
            47 => 
            array (
                'id' => 48,
                'name_kh' => 'គំរូ',
                'name_en' => 'Kumru',
                'code' => '10706',
                'province_id' => 1,
                'district_id' => 6,
            ),
            48 => 
            array (
                'id' => 49,
                'name_kh' => 'ផ្គាំ',
                'name_en' => 'Phkoam',
                'code' => '10801',
                'province_id' => 1,
                'district_id' => 7,
            ),
            49 => 
            array (
                'id' => 50,
                'name_kh' => 'សារង្គ',
                'name_en' => 'Sarongk',
                'code' => '10802',
                'province_id' => 1,
                'district_id' => 7,
            ),
            50 => 
            array (
                'id' => 51,
                'name_kh' => 'ស្លក្រាម',
                'name_en' => 'Sla Kram',
                'code' => '10803',
                'province_id' => 1,
                'district_id' => 7,
            ),
            51 => 
            array (
                'id' => 52,
                'name_kh' => 'ស្វាយចេក',
                'name_en' => 'Svay Chek',
                'code' => '10804',
                'province_id' => 1,
                'district_id' => 7,
            ),
            52 => 
            array (
                'id' => 53,
                'name_kh' => 'តាបែន',
                'name_en' => 'Ta Baen',
                'code' => '10805',
                'province_id' => 1,
                'district_id' => 7,
            ),
            53 => 
            array (
                'id' => 54,
                'name_kh' => 'តាផូ',
                'name_en' => 'Ta Phou',
                'code' => '10806',
                'province_id' => 1,
                'district_id' => 7,
            ),
            54 => 
            array (
                'id' => 55,
                'name_kh' => 'ទ្រាស',
                'name_en' => 'Treas',
                'code' => '10807',
                'province_id' => 1,
                'district_id' => 7,
            ),
            55 => 
            array (
                'id' => 56,
                'name_kh' => 'រលួស',
                'name_en' => 'Roluos',
                'code' => '10808',
                'province_id' => 1,
                'district_id' => 7,
            ),
            56 => 
            array (
                'id' => 57,
                'name_kh' => 'បឹងបេង',
                'name_en' => 'Boeng Beng',
                'code' => '10901',
                'province_id' => 1,
                'district_id' => 8,
            ),
            57 => 
            array (
                'id' => 58,
                'name_kh' => 'ម៉ាឡៃ',
                'name_en' => 'Malai',
                'code' => '10902',
                'province_id' => 1,
                'district_id' => 8,
            ),
            58 => 
            array (
                'id' => 59,
                'name_kh' => 'អូរសំព័រ',
                'name_en' => 'Ou Sampoar',
                'code' => '10903',
                'province_id' => 1,
                'district_id' => 8,
            ),
            59 => 
            array (
                'id' => 60,
                'name_kh' => 'អូរស្រឡៅ',
                'name_en' => 'Ou Sralau',
                'code' => '10904',
                'province_id' => 1,
                'district_id' => 8,
            ),
            60 => 
            array (
                'id' => 61,
                'name_kh' => 'ទួលពង្រ',
                'name_en' => 'Tuol Pongro',
                'code' => '10905',
                'province_id' => 1,
                'district_id' => 8,
            ),
            61 => 
            array (
                'id' => 62,
                'name_kh' => 'តាគង់',
                'name_en' => 'Ta Kong',
                'code' => '10906',
                'province_id' => 1,
                'district_id' => 8,
            ),
            62 => 
            array (
                'id' => 63,
                'name_kh' => 'និមិត្ដ',
                'name_en' => 'Nimitt',
                'code' => '11001',
                'province_id' => 1,
                'district_id' => 9,
            ),
            63 => 
            array (
                'id' => 64,
                'name_kh' => 'ប៉ោយប៉ែត',
                'name_en' => 'Paoy Paet',
                'code' => '11002',
                'province_id' => 1,
                'district_id' => 9,
            ),
            64 => 
            array (
                'id' => 65,
                'name_kh' => 'ផ្សារកណ្តាល',
                'name_en' => 'Phsar Kandal',
                'code' => '11003',
                'province_id' => 1,
                'district_id' => 9,
            ),
            65 => 
            array (
                'id' => 66,
                'name_kh' => 'កន្ទឺ ១',
                'name_en' => 'Kantueu Muoy',
                'code' => '20101',
                'province_id' => 2,
                'district_id' => 10,
            ),
            66 => 
            array (
                'id' => 67,
                'name_kh' => 'កន្ទឺ ២',
                'name_en' => 'Kantueu Pir',
                'code' => '20102',
                'province_id' => 2,
                'district_id' => 10,
            ),
            67 => 
            array (
                'id' => 68,
                'name_kh' => 'បាយដំរាំ',
                'name_en' => 'Bay Damram',
                'code' => '20103',
                'province_id' => 2,
                'district_id' => 10,
            ),
            68 => 
            array (
                'id' => 69,
                'name_kh' => 'ឈើទាល',
                'name_en' => 'Chheu Teal',
                'code' => '20104',
                'province_id' => 2,
                'district_id' => 10,
            ),
            69 => 
            array (
                'id' => 70,
                'name_kh' => 'ចែងមានជ័យ',
                'name_en' => 'Chaeng Mean Chey',
                'code' => '20105',
                'province_id' => 2,
                'district_id' => 10,
            ),
            70 => 
            array (
                'id' => 71,
                'name_kh' => 'ភ្នំសំពៅ',
                'name_en' => 'Phnum Sampov',
                'code' => '20106',
                'province_id' => 2,
                'district_id' => 10,
            ),
            71 => 
            array (
                'id' => 72,
                'name_kh' => 'ស្នឹង',
                'name_en' => 'Snoeng',
                'code' => '20107',
                'province_id' => 2,
                'district_id' => 10,
            ),
            72 => 
            array (
                'id' => 73,
                'name_kh' => 'តាគ្រាម',
                'name_en' => 'Ta Kream',
                'code' => '20108',
                'province_id' => 2,
                'district_id' => 10,
            ),
            73 => 
            array (
                'id' => 74,
                'name_kh' => 'តាពូង',
                'name_en' => 'Ta Pung',
                'code' => '20201',
                'province_id' => 2,
                'district_id' => 11,
            ),
            74 => 
            array (
                'id' => 75,
                'name_kh' => 'តាម៉ឺន',
                'name_en' => 'Ta Meun',
                'code' => '20202',
                'province_id' => 2,
                'district_id' => 11,
            ),
            75 => 
            array (
                'id' => 76,
                'name_kh' => 'អូរតាគី',
                'name_en' => 'Ou Ta Ki',
                'code' => '20203',
                'province_id' => 2,
                'district_id' => 11,
            ),
            76 => 
            array (
                'id' => 77,
                'name_kh' => 'ជ្រៃ',
                'name_en' => 'Chrey',
                'code' => '20204',
                'province_id' => 2,
                'district_id' => 11,
            ),
            77 => 
            array (
                'id' => 78,
                'name_kh' => 'អន្លង់រុន',
                'name_en' => 'Anlong Run',
                'code' => '20205',
                'province_id' => 2,
                'district_id' => 11,
            ),
            78 => 
            array (
                'id' => 79,
                'name_kh' => 'ជ្រោយស្ដៅ',
                'name_en' => 'Chrouy Sdau',
                'code' => '20206',
                'province_id' => 2,
                'district_id' => 11,
            ),
            79 => 
            array (
                'id' => 80,
                'name_kh' => 'បឹងព្រីង',
                'name_en' => 'Boeng Pring',
                'code' => '20207',
                'province_id' => 2,
                'district_id' => 11,
            ),
            80 => 
            array (
                'id' => 81,
                'name_kh' => 'គោកឃ្មុំ',
                'name_en' => 'Kouk Khmum',
                'code' => '20208',
                'province_id' => 2,
                'district_id' => 11,
            ),
            81 => 
            array (
                'id' => 82,
                'name_kh' => 'បន្សាយត្រែង',
                'name_en' => 'Bansay Traeng',
                'code' => '20209',
                'province_id' => 2,
                'district_id' => 11,
            ),
            82 => 
            array (
                'id' => 83,
                'name_kh' => 'រូងជ្រៃ',
                'name_en' => 'Rung Chrey',
                'code' => '20210',
                'province_id' => 2,
                'district_id' => 11,
            ),
            83 => 
            array (
                'id' => 84,
                'name_kh' => 'ទួលតាឯក',
                'name_en' => 'Tuol Ta Ek',
                'code' => '20301',
                'province_id' => 2,
                'district_id' => 12,
            ),
            84 => 
            array (
                'id' => 85,
                'name_kh' => 'ព្រែកព្រះស្ដេច',
                'name_en' => 'Prek Preah Sdach',
                'code' => '20302',
                'province_id' => 2,
                'district_id' => 12,
            ),
            85 => 
            array (
                'id' => 86,
                'name_kh' => 'រតនៈ',
                'name_en' => 'Rottanak',
                'code' => '20303',
                'province_id' => 2,
                'district_id' => 12,
            ),
            86 => 
            array (
                'id' => 87,
                'name_kh' => 'ចំការសំរោង',
                'name_en' => 'Chomkar Somraong',
                'code' => '20304',
                'province_id' => 2,
                'district_id' => 12,
            ),
            87 => 
            array (
                'id' => 88,
                'name_kh' => 'ស្លាកែត',
                'name_en' => 'Sla Ket',
                'code' => '20305',
                'province_id' => 2,
                'district_id' => 12,
            ),
            88 => 
            array (
                'id' => 89,
                'name_kh' => 'ក្ដុលដូនទាវ',
                'name_en' => 'Kdol Doun Teav',
                'code' => '20306',
                'province_id' => 2,
                'district_id' => 12,
            ),
            89 => 
            array (
                'id' => 90,
                'name_kh' => 'អូរម៉ាល់',
                'name_en' => 'OMal',
                'code' => '20307',
                'province_id' => 2,
                'district_id' => 12,
            ),
            90 => 
            array (
                'id' => 91,
                'name_kh' => 'វត្ដគរ',
                'name_en' => 'wat Kor',
                'code' => '20308',
                'province_id' => 2,
                'district_id' => 12,
            ),
            91 => 
            array (
                'id' => 92,
                'name_kh' => 'អូរចារ',
                'name_en' => 'Ou Char',
                'code' => '20309',
                'province_id' => 2,
                'district_id' => 12,
            ),
            92 => 
            array (
                'id' => 93,
                'name_kh' => 'ស្វាយប៉ោ',
                'name_en' => 'Svay Por',
                'code' => '20310',
                'province_id' => 2,
                'district_id' => 12,
            ),
            93 => 
            array (
                'id' => 94,
                'name_kh' => 'បវេល',
                'name_en' => 'Bavel',
                'code' => '20401',
                'province_id' => 2,
                'district_id' => 13,
            ),
            94 => 
            array (
                'id' => 95,
                'name_kh' => 'ខ្នាចរមាស',
                'name_en' => 'Khnach Romeas',
                'code' => '20402',
                'province_id' => 2,
                'district_id' => 13,
            ),
            95 => 
            array (
                'id' => 96,
                'name_kh' => 'ល្វា',
                'name_en' => 'Lvea',
                'code' => '20403',
                'province_id' => 2,
                'district_id' => 13,
            ),
            96 => 
            array (
                'id' => 97,
                'name_kh' => 'ព្រៃខ្ពស់',
                'name_en' => 'Prey Khpos',
                'code' => '20404',
                'province_id' => 2,
                'district_id' => 13,
            ),
            97 => 
            array (
                'id' => 98,
                'name_kh' => 'អំពិលប្រាំដើម',
                'name_en' => 'Ampil Pram Daeum',
                'code' => '20405',
                'province_id' => 2,
                'district_id' => 13,
            ),
            98 => 
            array (
                'id' => 99,
                'name_kh' => 'ក្ដុលតាហែន',
                'name_en' => 'Kdol Ta Haen',
                'code' => '20406',
                'province_id' => 2,
                'district_id' => 13,
            ),
            99 => 
            array (
                'id' => 100,
                'name_kh' => 'ឃ្លាំងមាស',
                'name_en' => 'Khlaeng Meas',
                'code' => '20407',
                'province_id' => 2,
                'district_id' => 13,
            ),
            100 => 
            array (
                'id' => 101,
                'name_kh' => 'បឹងប្រាំ',
                'name_en' => 'Boeung Pram',
                'code' => '20408',
                'province_id' => 2,
                'district_id' => 13,
            ),
            101 => 
            array (
                'id' => 102,
                'name_kh' => 'ព្រែកនរិន្ទ',
                'name_en' => 'Preaek Norint',
                'code' => '20501',
                'province_id' => 2,
                'district_id' => 14,
            ),
            102 => 
            array (
                'id' => 103,
                'name_kh' => 'សំរោងក្នុង',
                'name_en' => 'Samraong Knong',
                'code' => '20502',
                'province_id' => 2,
                'district_id' => 14,
            ),
            103 => 
            array (
                'id' => 104,
                'name_kh' => 'ព្រែកខ្ពប',
                'name_en' => 'Preaek Khpob',
                'code' => '20503',
                'province_id' => 2,
                'district_id' => 14,
            ),
            104 => 
            array (
                'id' => 105,
                'name_kh' => 'ព្រែកហ្លួង',
                'name_en' => 'Preaek Luong',
                'code' => '20504',
                'province_id' => 2,
                'district_id' => 14,
            ),
            105 => 
            array (
                'id' => 106,
                'name_kh' => 'ពាមឯក',
                'name_en' => 'Peam Aek',
                'code' => '20505',
                'province_id' => 2,
                'district_id' => 14,
            ),
            106 => 
            array (
                'id' => 107,
                'name_kh' => 'ព្រៃចាស់',
                'name_en' => 'Prey Chas',
                'code' => '20506',
                'province_id' => 2,
                'district_id' => 14,
            ),
            107 => 
            array (
                'id' => 108,
                'name_kh' => 'កោះជីវាំង',
                'name_en' => 'Kaoh Chiveang',
                'code' => '20507',
                'province_id' => 2,
                'district_id' => 14,
            ),
            108 => 
            array (
                'id' => 109,
                'name_kh' => 'មោង',
                'name_en' => 'Moung',
                'code' => '20601',
                'province_id' => 2,
                'district_id' => 15,
            ),
            109 => 
            array (
                'id' => 110,
                'name_kh' => 'គារ',
                'name_en' => 'Kear',
                'code' => '20602',
                'province_id' => 2,
                'district_id' => 15,
            ),
            110 => 
            array (
                'id' => 111,
                'name_kh' => 'ព្រៃស្វាយ',
                'name_en' => 'Prey Svay',
                'code' => '20603',
                'province_id' => 2,
                'district_id' => 15,
            ),
            111 => 
            array (
                'id' => 112,
                'name_kh' => 'ឫស្សីក្រាំង',
                'name_en' => 'Ruessei Krang',
                'code' => '20604',
                'province_id' => 2,
                'district_id' => 15,
            ),
            112 => 
            array (
                'id' => 113,
                'name_kh' => 'ជ្រៃ',
                'name_en' => 'Chrey',
                'code' => '20605',
                'province_id' => 2,
                'district_id' => 15,
            ),
            113 => 
            array (
                'id' => 114,
                'name_kh' => 'តាលាស់',
                'name_en' => 'Ta Loas',
                'code' => '20606',
                'province_id' => 2,
                'district_id' => 15,
            ),
            114 => 
            array (
                'id' => 115,
                'name_kh' => 'កកោះ',
                'name_en' => 'Kakaoh',
                'code' => '20607',
                'province_id' => 2,
                'district_id' => 15,
            ),
            115 => 
            array (
                'id' => 116,
                'name_kh' => 'ព្រៃតូច',
                'name_en' => 'Prey Touch',
                'code' => '20608',
                'province_id' => 2,
                'district_id' => 15,
            ),
            116 => 
            array (
                'id' => 117,
                'name_kh' => 'របស់មង្គល',
                'name_en' => 'Robas Mongkol',
                'code' => '20609',
                'province_id' => 2,
                'district_id' => 15,
            ),
            117 => 
            array (
                'id' => 118,
                'name_kh' => 'ស្ដៅ',
                'name_en' => 'Sdau',
                'code' => '20701',
                'province_id' => 2,
                'district_id' => 16,
            ),
            118 => 
            array (
                'id' => 119,
                'name_kh' => 'អណ្ដើកហែប',
                'name_en' => 'Andaeuk Haeb',
                'code' => '20702',
                'province_id' => 2,
                'district_id' => 16,
            ),
            119 => 
            array (
                'id' => 120,
                'name_kh' => 'ផ្លូវមាស',
                'name_en' => 'Phlov Meas',
                'code' => '20703',
                'province_id' => 2,
                'district_id' => 16,
            ),
            120 => 
            array (
                'id' => 121,
                'name_kh' => 'ត្រែង',
                'name_en' => 'Traeng',
                'code' => '20704',
                'province_id' => 2,
                'district_id' => 16,
            ),
            121 => 
            array (
                'id' => 122,
                'name_kh' => 'រស្មីសង្ហារ',
                'name_en' => 'Reaksmei Songha',
                'code' => '20705',
                'province_id' => 2,
                'district_id' => 16,
            ),
            122 => 
            array (
                'id' => 123,
                'name_kh' => 'អន្លង់វិល',
                'name_en' => 'Anlong Vil',
                'code' => '20801',
                'province_id' => 2,
                'district_id' => 17,
            ),
            123 => 
            array (
                'id' => 124,
                'name_kh' => 'នរា',
                'name_en' => 'Norea',
                'code' => '20802',
                'province_id' => 2,
                'district_id' => 17,
            ),
            124 => 
            array (
                'id' => 125,
                'name_kh' => 'តាប៉ុន',
                'name_en' => 'Ta Pon',
                'code' => '20803',
                'province_id' => 2,
                'district_id' => 17,
            ),
            125 => 
            array (
                'id' => 126,
                'name_kh' => 'រកា',
                'name_en' => 'Roka',
                'code' => '20804',
                'province_id' => 2,
                'district_id' => 17,
            ),
            126 => 
            array (
                'id' => 127,
                'name_kh' => 'កំពង់ព្រះ',
                'name_en' => 'Kampong Preah',
                'code' => '20805',
                'province_id' => 2,
                'district_id' => 17,
            ),
            127 => 
            array (
                'id' => 128,
                'name_kh' => 'កំពង់ព្រៀង',
                'name_en' => 'Kampong Prieng',
                'code' => '20806',
                'province_id' => 2,
                'district_id' => 17,
            ),
            128 => 
            array (
                'id' => 129,
                'name_kh' => 'រាំងកេសី',
                'name_en' => 'Reang Kesei',
                'code' => '20807',
                'province_id' => 2,
                'district_id' => 17,
            ),
            129 => 
            array (
                'id' => 130,
                'name_kh' => 'អូរដំបង ១',
                'name_en' => 'Ou Dambang Muoy',
                'code' => '20808',
                'province_id' => 2,
                'district_id' => 17,
            ),
            130 => 
            array (
                'id' => 131,
                'name_kh' => 'អូរដំបង ២',
                'name_en' => 'Ou Dambang Pir',
                'code' => '20809',
                'province_id' => 2,
                'district_id' => 17,
            ),
            131 => 
            array (
                'id' => 132,
                'name_kh' => 'វត្ដតាមិម',
                'name_en' => 'Vaot Ta Muem',
                'code' => '20810',
                'province_id' => 2,
                'district_id' => 17,
            ),
            132 => 
            array (
                'id' => 133,
                'name_kh' => 'តាតោក',
                'name_en' => 'Ta Taok',
                'code' => '20901',
                'province_id' => 2,
                'district_id' => 18,
            ),
            133 => 
            array (
                'id' => 134,
                'name_kh' => 'កំពង់ល្ពៅ',
                'name_en' => 'Kampong Lpov',
                'code' => '20902',
                'province_id' => 2,
                'district_id' => 18,
            ),
            134 => 
            array (
                'id' => 135,
                'name_kh' => 'អូរសំរិល',
                'name_en' => 'Ou Samril',
                'code' => '20903',
                'province_id' => 2,
                'district_id' => 18,
            ),
            135 => 
            array (
                'id' => 136,
                'name_kh' => 'ស៊ុង',
                'name_en' => 'Sung',
                'code' => '20904',
                'province_id' => 2,
                'district_id' => 18,
            ),
            136 => 
            array (
                'id' => 137,
                'name_kh' => 'សំឡូត',
                'name_en' => 'Samlout',
                'code' => '20905',
                'province_id' => 2,
                'district_id' => 18,
            ),
            137 => 
            array (
                'id' => 138,
                'name_kh' => 'មានជ័យ',
                'name_en' => 'Mean Chey',
                'code' => '20906',
                'province_id' => 2,
                'district_id' => 18,
            ),
            138 => 
            array (
                'id' => 139,
                'name_kh' => 'តាសាញ',
                'name_en' => 'Ta Sanh',
                'code' => '20907',
                'province_id' => 2,
                'district_id' => 18,
            ),
            139 => 
            array (
                'id' => 140,
                'name_kh' => 'សំពៅលូន',
                'name_en' => 'Sampov Lun',
                'code' => '21001',
                'province_id' => 2,
                'district_id' => 19,
            ),
            140 => 
            array (
                'id' => 141,
                'name_kh' => 'អង្គរបាន',
                'name_en' => 'Angkor Ban',
                'code' => '21002',
                'province_id' => 2,
                'district_id' => 19,
            ),
            141 => 
            array (
                'id' => 142,
                'name_kh' => 'តាស្ដា',
                'name_en' => 'Ta Sda',
                'code' => '21003',
                'province_id' => 2,
                'district_id' => 19,
            ),
            142 => 
            array (
                'id' => 143,
                'name_kh' => 'សន្ដិភាព',
                'name_en' => 'Santepheap',
                'code' => '21004',
                'province_id' => 2,
                'district_id' => 19,
            ),
            143 => 
            array (
                'id' => 144,
                'name_kh' => 'សេរីមានជ័យ',
                'name_en' => 'Serei Mean Chey',
                'code' => '21005',
                'province_id' => 2,
                'district_id' => 19,
            ),
            144 => 
            array (
                'id' => 145,
                'name_kh' => 'ជ្រៃសីមា',
                'name_en' => 'Chrey Seima',
                'code' => '21006',
                'province_id' => 2,
                'district_id' => 19,
            ),
            145 => 
            array (
                'id' => 146,
                'name_kh' => 'ភ្នំព្រឹក',
                'name_en' => 'Phnum Proek',
                'code' => '21101',
                'province_id' => 2,
                'district_id' => 20,
            ),
            146 => 
            array (
                'id' => 147,
                'name_kh' => 'ពេជ្រចិន្ដា',
                'name_en' => 'Pech Chenda',
                'code' => '21102',
                'province_id' => 2,
                'district_id' => 20,
            ),
            147 => 
            array (
                'id' => 148,
                'name_kh' => 'បួរ',
                'name_en' => 'Bour',
                'code' => '21103',
                'province_id' => 2,
                'district_id' => 20,
            ),
            148 => 
            array (
                'id' => 149,
                'name_kh' => 'បារាំងធ្លាក់',
                'name_en' => 'Barang Thleak',
                'code' => '21104',
                'province_id' => 2,
                'district_id' => 20,
            ),
            149 => 
            array (
                'id' => 150,
                'name_kh' => 'អូររំដួល',
                'name_en' => 'Ou Rumduol',
                'code' => '21105',
                'province_id' => 2,
                'district_id' => 20,
            ),
            150 => 
            array (
                'id' => 151,
                'name_kh' => 'កំរៀង',
                'name_en' => 'Kamrieng',
                'code' => '21201',
                'province_id' => 2,
                'district_id' => 21,
            ),
            151 => 
            array (
                'id' => 152,
                'name_kh' => 'បឹងរាំង',
                'name_en' => 'Boeng Reang',
                'code' => '21202',
                'province_id' => 2,
                'district_id' => 21,
            ),
            152 => 
            array (
                'id' => 153,
                'name_kh' => 'អូរដា',
                'name_en' => 'Ou Da',
                'code' => '21203',
                'province_id' => 2,
                'district_id' => 21,
            ),
            153 => 
            array (
                'id' => 154,
                'name_kh' => 'ត្រាង',
                'name_en' => 'Trang',
                'code' => '21204',
                'province_id' => 2,
                'district_id' => 21,
            ),
            154 => 
            array (
                'id' => 155,
                'name_kh' => 'តាសែន',
                'name_en' => 'Ta Saen',
                'code' => '21205',
                'province_id' => 2,
                'district_id' => 21,
            ),
            155 => 
            array (
                'id' => 156,
                'name_kh' => 'តាក្រី',
                'name_en' => 'Ta Krei',
                'code' => '21206',
                'province_id' => 2,
                'district_id' => 21,
            ),
            156 => 
            array (
                'id' => 157,
                'name_kh' => 'ធិបតី',
                'name_en' => 'Thipakdei',
                'code' => '21301',
                'province_id' => 2,
                'district_id' => 22,
            ),
            157 => 
            array (
                'id' => 158,
                'name_kh' => 'គាស់ក្រឡ',
                'name_en' => 'Kaos Krala',
                'code' => '21302',
                'province_id' => 2,
                'district_id' => 22,
            ),
            158 => 
            array (
                'id' => 159,
                'name_kh' => 'ហប់',
                'name_en' => 'Hab',
                'code' => '21303',
                'province_id' => 2,
                'district_id' => 22,
            ),
            159 => 
            array (
                'id' => 160,
                'name_kh' => 'ព្រះផុស',
                'name_en' => 'Preah Phos',
                'code' => '21304',
                'province_id' => 2,
                'district_id' => 22,
            ),
            160 => 
            array (
                'id' => 161,
                'name_kh' => 'ដូនបា',
                'name_en' => 'Doun Ba',
                'code' => '21305',
                'province_id' => 2,
                'district_id' => 22,
            ),
            161 => 
            array (
                'id' => 162,
                'name_kh' => 'ឆ្នាល់មាន់',
                'name_en' => 'Chhnal Moan',
                'code' => '21306',
                'province_id' => 2,
                'district_id' => 22,
            ),
            162 => 
            array (
                'id' => 163,
                'name_kh' => 'ព្រែកជីក',
                'name_en' => 'Preaek Chik',
                'code' => '21401',
                'province_id' => 2,
                'district_id' => 23,
            ),
            163 => 
            array (
                'id' => 164,
                'name_kh' => 'ព្រៃត្រឡាច',
                'name_en' => 'Prey Tralach',
                'code' => '21402',
                'province_id' => 2,
                'district_id' => 23,
            ),
            164 => 
            array (
                'id' => 165,
                'name_kh' => 'មុខរាហ៍',
                'name_en' => 'Mukh Reah',
                'code' => '21403',
                'province_id' => 2,
                'district_id' => 23,
            ),
            165 => 
            array (
                'id' => 166,
                'name_kh' => 'ស្តុកប្រវឹក',
                'name_en' => 'Sdok Pravoek',
                'code' => '21404',
                'province_id' => 2,
                'district_id' => 23,
            ),
            166 => 
            array (
                'id' => 167,
                'name_kh' => 'បាសាក់',
                'name_en' => 'Basak',
                'code' => '21405',
                'province_id' => 2,
                'district_id' => 23,
            ),
            167 => 
            array (
                'id' => 168,
                'name_kh' => 'បាធាយ',
                'name_en' => 'Batheay',
                'code' => '30101',
                'province_id' => 3,
                'district_id' => 24,
            ),
            168 => 
            array (
                'id' => 169,
                'name_kh' => 'ច្បារអំពៅ',
                'name_en' => 'Chbar Ampov',
                'code' => '30102',
                'province_id' => 3,
                'district_id' => 24,
            ),
            169 => 
            array (
                'id' => 170,
                'name_kh' => 'ជាលា',
                'name_en' => 'Chealea',
                'code' => '30103',
                'province_id' => 3,
                'district_id' => 24,
            ),
            170 => 
            array (
                'id' => 171,
                'name_kh' => 'ជើងព្រៃ',
                'name_en' => 'Cheung Prey',
                'code' => '30104',
                'province_id' => 3,
                'district_id' => 24,
            ),
            171 => 
            array (
                'id' => 172,
                'name_kh' => 'មេព្រីង',
                'name_en' => 'Me Pring',
                'code' => '30105',
                'province_id' => 3,
                'district_id' => 24,
            ),
            172 => 
            array (
                'id' => 173,
                'name_kh' => 'ផ្អាវ',
                'name_en' => 'Phav',
                'code' => '30106',
                'province_id' => 3,
                'district_id' => 24,
            ),
            173 => 
            array (
                'id' => 174,
                'name_kh' => 'សំបូរ',
                'name_en' => 'Sambour',
                'code' => '30107',
                'province_id' => 3,
                'district_id' => 24,
            ),
            174 => 
            array (
                'id' => 175,
                'name_kh' => 'សណ្ដែក',
                'name_en' => 'Sandaek',
                'code' => '30108',
                'province_id' => 3,
                'district_id' => 24,
            ),
            175 => 
            array (
                'id' => 176,
                'name_kh' => 'តាំងក្រាំង',
                'name_en' => 'Tang Krang',
                'code' => '30109',
                'province_id' => 3,
                'district_id' => 24,
            ),
            176 => 
            array (
                'id' => 177,
                'name_kh' => 'តាំងក្រសាំង',
                'name_en' => 'Tang Krasang',
                'code' => '30110',
                'province_id' => 3,
                'district_id' => 24,
            ),
            177 => 
            array (
                'id' => 178,
                'name_kh' => 'ត្រប់',
                'name_en' => 'Trab',
                'code' => '30111',
                'province_id' => 3,
                'district_id' => 24,
            ),
            178 => 
            array (
                'id' => 179,
                'name_kh' => 'ទំនប់',
                'name_en' => 'Tumnob',
                'code' => '30112',
                'province_id' => 3,
                'district_id' => 24,
            ),
            179 => 
            array (
                'id' => 180,
                'name_kh' => 'បុសខ្នុរ',
                'name_en' => 'Bos Khnor',
                'code' => '30201',
                'province_id' => 3,
                'district_id' => 25,
            ),
            180 => 
            array (
                'id' => 181,
                'name_kh' => 'ចំការអណ្ដូង',
                'name_en' => 'Chamkar Andoung',
                'code' => '30202',
                'province_id' => 3,
                'district_id' => 25,
            ),
            181 => 
            array (
                'id' => 182,
                'name_kh' => 'ជយោ',
                'name_en' => 'Cheyyou',
                'code' => '30203',
                'province_id' => 3,
                'district_id' => 25,
            ),
            182 => 
            array (
                'id' => 183,
                'name_kh' => 'ល្វាលើ',
                'name_en' => 'Lvea Leu',
                'code' => '30204',
                'province_id' => 3,
                'district_id' => 25,
            ),
            183 => 
            array (
                'id' => 184,
                'name_kh' => 'ស្ពឺ',
                'name_en' => 'Spueu',
                'code' => '30205',
                'province_id' => 3,
                'district_id' => 25,
            ),
            184 => 
            array (
                'id' => 185,
                'name_kh' => 'ស្វាយទាប',
                'name_en' => 'Svay Teab',
                'code' => '30206',
                'province_id' => 3,
                'district_id' => 25,
            ),
            185 => 
            array (
                'id' => 186,
                'name_kh' => 'តាអុង',
                'name_en' => 'Ta Ong',
                'code' => '30207',
                'province_id' => 3,
                'district_id' => 25,
            ),
            186 => 
            array (
                'id' => 187,
                'name_kh' => 'តាប្រុក',
                'name_en' => 'Ta Prok',
                'code' => '30208',
                'province_id' => 3,
                'district_id' => 25,
            ),
            187 => 
            array (
                'id' => 188,
                'name_kh' => 'ខ្នុរដំបង',
                'name_en' => 'Khnor Dambang',
                'code' => '30301',
                'province_id' => 3,
                'district_id' => 26,
            ),
            188 => 
            array (
                'id' => 189,
                'name_kh' => 'គោករវៀង',
                'name_en' => 'Kouk Rovieng',
                'code' => '30302',
                'province_id' => 3,
                'district_id' => 26,
            ),
            189 => 
            array (
                'id' => 190,
                'name_kh' => 'ផ្ដៅជុំ',
                'name_en' => 'Pdau Chum',
                'code' => '30303',
                'province_id' => 3,
                'district_id' => 26,
            ),
            190 => 
            array (
                'id' => 191,
                'name_kh' => 'ព្រៃចារ',
                'name_en' => 'Prey Char',
                'code' => '30304',
                'province_id' => 3,
                'district_id' => 26,
            ),
            191 => 
            array (
                'id' => 192,
                'name_kh' => 'ព្រីងជ្រុំ',
                'name_en' => 'Pring Chrum',
                'code' => '30305',
                'province_id' => 3,
                'district_id' => 26,
            ),
            192 => 
            array (
                'id' => 193,
                'name_kh' => 'សំពងជ័យ',
                'name_en' => 'Sampong Chey',
                'code' => '30306',
                'province_id' => 3,
                'district_id' => 26,
            ),
            193 => 
            array (
                'id' => 194,
                'name_kh' => 'ស្ដើងជ័យ',
                'name_en' => 'Sdaeung Chey',
                'code' => '30307',
                'province_id' => 3,
                'district_id' => 26,
            ),
            194 => 
            array (
                'id' => 195,
                'name_kh' => 'សូទិព្វ',
                'name_en' => 'Soutib',
                'code' => '30308',
                'province_id' => 3,
                'district_id' => 26,
            ),
            195 => 
            array (
                'id' => 196,
                'name_kh' => 'ស្រម៉រ',
                'name_en' => 'Sramar',
                'code' => '30309',
                'province_id' => 3,
                'district_id' => 26,
            ),
            196 => 
            array (
                'id' => 197,
                'name_kh' => 'ត្រពាំងគរ',
                'name_en' => 'Trapeang Kor',
                'code' => '30310',
                'province_id' => 3,
                'district_id' => 26,
            ),
            197 => 
            array (
                'id' => 198,
                'name_kh' => 'បឹងកុក',
                'name_en' => 'Boeng Kok',
                'code' => '30501',
                'province_id' => 3,
                'district_id' => 27,
            ),
            198 => 
            array (
                'id' => 199,
                'name_kh' => 'កំពង់ចាម',
                'name_en' => 'Kampong Cham',
                'code' => '30502',
                'province_id' => 3,
                'district_id' => 27,
            ),
            199 => 
            array (
                'id' => 200,
                'name_kh' => 'សំបួរមាស',
                'name_en' => 'Sambuor Meas',
                'code' => '30503',
                'province_id' => 3,
                'district_id' => 27,
            ),
            200 => 
            array (
                'id' => 201,
                'name_kh' => 'វាលវង់',
                'name_en' => 'Veal Vong',
                'code' => '30504',
                'province_id' => 3,
                'district_id' => 27,
            ),
            201 => 
            array (
                'id' => 202,
                'name_kh' => 'អំពិល',
                'name_en' => 'Ampil',
                'code' => '30601',
                'province_id' => 3,
                'district_id' => 28,
            ),
            202 => 
            array (
                'id' => 203,
                'name_kh' => 'ហាន់ជ័យ',
                'name_en' => 'Hanchey',
                'code' => '30602',
                'province_id' => 3,
                'district_id' => 28,
            ),
            203 => 
            array (
                'id' => 204,
                'name_kh' => 'កៀនជ្រៃ',
                'name_en' => 'Kien Chrey',
                'code' => '30603',
                'province_id' => 3,
                'district_id' => 28,
            ),
            204 => 
            array (
                'id' => 205,
                'name_kh' => 'គគរ',
                'name_en' => 'Kokor',
                'code' => '30604',
                'province_id' => 3,
                'district_id' => 28,
            ),
            205 => 
            array (
                'id' => 206,
                'name_kh' => 'កោះមិត្ដ',
                'name_en' => 'Kaoh Mitt',
                'code' => '30605',
                'province_id' => 3,
                'district_id' => 28,
            ),
            206 => 
            array (
                'id' => 207,
                'name_kh' => 'កោះរកា',
                'name_en' => 'Kaoh Roka',
                'code' => '30606',
                'province_id' => 3,
                'district_id' => 28,
            ),
            207 => 
            array (
                'id' => 208,
                'name_kh' => 'កោះសំរោង',
                'name_en' => 'Kaoh Samraong',
                'code' => '30607',
                'province_id' => 3,
                'district_id' => 28,
            ),
            208 => 
            array (
                'id' => 209,
                'name_kh' => 'កោះទន្ទឹម',
                'name_en' => 'Kaoh Tontuem',
                'code' => '30608',
                'province_id' => 3,
                'district_id' => 28,
            ),
            209 => 
            array (
                'id' => 210,
                'name_kh' => 'ក្រឡា',
                'name_en' => 'Krala',
                'code' => '30609',
                'province_id' => 3,
                'district_id' => 28,
            ),
            210 => 
            array (
                'id' => 211,
                'name_kh' => 'អូរស្វាយ',
                'name_en' => 'Ou Svay',
                'code' => '30610',
                'province_id' => 3,
                'district_id' => 28,
            ),
            211 => 
            array (
                'id' => 212,
                'name_kh' => 'រអាង',
                'name_en' => 'Roang',
                'code' => '30611',
                'province_id' => 3,
                'district_id' => 28,
            ),
            212 => 
            array (
                'id' => 213,
                'name_kh' => 'រំចេក',
                'name_en' => 'Rumchek',
                'code' => '30612',
                'province_id' => 3,
                'district_id' => 28,
            ),
            213 => 
            array (
                'id' => 214,
                'name_kh' => 'ស្រក',
                'name_en' => 'Srak',
                'code' => '30613',
                'province_id' => 3,
                'district_id' => 28,
            ),
            214 => 
            array (
                'id' => 215,
                'name_kh' => 'ទ្រាន',
                'name_en' => 'Trean',
                'code' => '30614',
                'province_id' => 3,
                'district_id' => 28,
            ),
            215 => 
            array (
                'id' => 216,
                'name_kh' => 'វិហារធំ',
                'name_en' => 'Vihear Thum',
                'code' => '30615',
                'province_id' => 3,
                'district_id' => 28,
            ),
            216 => 
            array (
                'id' => 217,
                'name_kh' => 'អង្គរបាន',
                'name_en' => 'Angkor Ban',
                'code' => '30701',
                'province_id' => 3,
                'district_id' => 29,
            ),
            217 => 
            array (
                'id' => 218,
                'name_kh' => 'កងតាណឹង',
                'name_en' => 'Kang Ta Noeng',
                'code' => '30702',
                'province_id' => 3,
                'district_id' => 29,
            ),
            218 => 
            array (
                'id' => 219,
                'name_kh' => 'ខ្ចៅ',
                'name_en' => 'Khchau',
                'code' => '30703',
                'province_id' => 3,
                'district_id' => 29,
            ),
            219 => 
            array (
                'id' => 220,
                'name_kh' => 'ពាមជីកង',
                'name_en' => 'Peam Chi Kang',
                'code' => '30704',
                'province_id' => 3,
                'district_id' => 29,
            ),
            220 => 
            array (
                'id' => 221,
                'name_kh' => 'ព្រែកកុយ',
                'name_en' => 'Preaek Koy',
                'code' => '30705',
                'province_id' => 3,
                'district_id' => 29,
            ),
            221 => 
            array (
                'id' => 222,
                'name_kh' => 'ព្រែកក្របៅ',
                'name_en' => 'Preaek Krabau',
                'code' => '30706',
                'province_id' => 3,
                'district_id' => 29,
            ),
            222 => 
            array (
                'id' => 223,
                'name_kh' => 'រាយប៉ាយ',
                'name_en' => 'Reay Pay',
                'code' => '30707',
                'province_id' => 3,
                'district_id' => 29,
            ),
            223 => 
            array (
                'id' => 224,
                'name_kh' => 'រកាអារ',
                'name_en' => 'Roka Ar',
                'code' => '30708',
                'province_id' => 3,
                'district_id' => 29,
            ),
            224 => 
            array (
                'id' => 225,
                'name_kh' => 'រកាគយ',
                'name_en' => 'Roka Koy',
                'code' => '30709',
                'province_id' => 3,
                'district_id' => 29,
            ),
            225 => 
            array (
                'id' => 226,
                'name_kh' => 'ស្ដៅ',
                'name_en' => 'Sdau',
                'code' => '30710',
                'province_id' => 3,
                'district_id' => 29,
            ),
            226 => 
            array (
                'id' => 227,
                'name_kh' => 'សូរគង',
                'name_en' => 'Sour Kong',
                'code' => '30711',
                'province_id' => 3,
                'district_id' => 29,
            ),
            227 => 
            array (
                'id' => 228,
                'name_kh' => 'កំពង់រាប',
                'name_en' => 'Kampong Reab',
                'code' => '30801',
                'province_id' => 3,
                'district_id' => 30,
            ),
            228 => 
            array (
                'id' => 229,
                'name_kh' => 'កោះសូទិន',
                'name_en' => 'Kaoh Sotin',
                'code' => '30802',
                'province_id' => 3,
                'district_id' => 30,
            ),
            229 => 
            array (
                'id' => 230,
                'name_kh' => 'ល្វេ',
                'name_en' => 'Lve',
                'code' => '30803',
                'province_id' => 3,
                'district_id' => 30,
            ),
            230 => 
            array (
                'id' => 231,
                'name_kh' => 'មហាលាភ',
                'name_en' => 'Moha Leaph',
                'code' => '30804',
                'province_id' => 3,
                'district_id' => 30,
            ),
            231 => 
            array (
                'id' => 232,
                'name_kh' => 'មហាខ្ញូង',
                'name_en' => 'Moha Khnhoung',
                'code' => '30805',
                'province_id' => 3,
                'district_id' => 30,
            ),
            232 => 
            array (
                'id' => 233,
                'name_kh' => 'ពាមប្រធ្នោះ',
                'name_en' => 'Peam Prathnuoh',
                'code' => '30806',
                'province_id' => 3,
                'district_id' => 30,
            ),
            233 => 
            array (
                'id' => 234,
                'name_kh' => 'ពង្រ',
                'name_en' => 'Pongro',
                'code' => '30807',
                'province_id' => 3,
                'district_id' => 30,
            ),
            234 => 
            array (
                'id' => 235,
                'name_kh' => 'ព្រែកតានង់',
                'name_en' => 'Preaek Ta Nong',
                'code' => '30808',
                'province_id' => 3,
                'district_id' => 30,
            ),
            235 => 
            array (
                'id' => 236,
                'name_kh' => 'បារាយណ៍',
                'name_en' => 'Baray',
                'code' => '31301',
                'province_id' => 3,
                'district_id' => 31,
            ),
            236 => 
            array (
                'id' => 237,
                'name_kh' => 'បឹងណាយ',
                'name_en' => 'Boeng Nay',
                'code' => '31302',
                'province_id' => 3,
                'district_id' => 31,
            ),
            237 => 
            array (
                'id' => 238,
                'name_kh' => 'ជ្រៃវៀន',
                'name_en' => 'Chrey Vien',
                'code' => '31303',
                'province_id' => 3,
                'district_id' => 31,
            ),
            238 => 
            array (
                'id' => 239,
                'name_kh' => 'ខ្វិតធំ',
                'name_en' => 'Khvet Thum',
                'code' => '31304',
                'province_id' => 3,
                'district_id' => 31,
            ),
            239 => 
            array (
                'id' => 240,
                'name_kh' => 'គរ',
                'name_en' => 'Kor',
                'code' => '31305',
                'province_id' => 3,
                'district_id' => 31,
            ),
            240 => 
            array (
                'id' => 241,
                'name_kh' => 'ក្រូច',
                'name_en' => 'Krouch',
                'code' => '31306',
                'province_id' => 3,
                'district_id' => 31,
            ),
            241 => 
            array (
                'id' => 242,
                'name_kh' => 'ល្វា',
                'name_en' => 'Lvea',
                'code' => '31307',
                'province_id' => 3,
                'district_id' => 31,
            ),
            242 => 
            array (
                'id' => 243,
                'name_kh' => 'មៀន',
                'name_en' => 'Mien',
                'code' => '31308',
                'province_id' => 3,
                'district_id' => 31,
            ),
            243 => 
            array (
                'id' => 244,
                'name_kh' => 'ព្រៃឈរ',
                'name_en' => 'Prey Chhor',
                'code' => '31309',
                'province_id' => 3,
                'district_id' => 31,
            ),
            244 => 
            array (
                'id' => 245,
                'name_kh' => 'សូរ្យសែន',
                'name_en' => 'Sour Saen',
                'code' => '31310',
                'province_id' => 3,
                'district_id' => 31,
            ),
            245 => 
            array (
                'id' => 246,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '31311',
                'province_id' => 3,
                'district_id' => 31,
            ),
            246 => 
            array (
                'id' => 247,
                'name_kh' => 'ស្រង៉ែ',
                'name_en' => 'Sragnae',
                'code' => '31312',
                'province_id' => 3,
                'district_id' => 31,
            ),
            247 => 
            array (
                'id' => 248,
                'name_kh' => 'ថ្មពូន',
                'name_en' => 'Thma Pun',
                'code' => '31313',
                'province_id' => 3,
                'district_id' => 31,
            ),
            248 => 
            array (
                'id' => 249,
                'name_kh' => 'តុងរ៉ុង',
                'name_en' => 'Tong Rong',
                'code' => '31314',
                'province_id' => 3,
                'district_id' => 31,
            ),
            249 => 
            array (
                'id' => 250,
                'name_kh' => 'ត្រពាំងព្រះ',
                'name_en' => 'Trapeang Preah',
                'code' => '31315',
                'province_id' => 3,
                'district_id' => 31,
            ),
            250 => 
            array (
                'id' => 251,
                'name_kh' => 'បារាយណ៍',
                'name_en' => 'Baray',
                'code' => '31401',
                'province_id' => 3,
                'district_id' => 32,
            ),
            251 => 
            array (
                'id' => 252,
                'name_kh' => 'ជីបាល',
                'name_en' => 'Chi Bal',
                'code' => '31402',
                'province_id' => 3,
                'district_id' => 32,
            ),
            252 => 
            array (
                'id' => 253,
                'name_kh' => 'ខ្នារស',
                'name_en' => 'Khnar Sa',
                'code' => '31403',
                'province_id' => 3,
                'district_id' => 32,
            ),
            253 => 
            array (
                'id' => 254,
                'name_kh' => 'កោះអណ្ដែត',
                'name_en' => 'Kaoh Andaet',
                'code' => '31404',
                'province_id' => 3,
                'district_id' => 32,
            ),
            254 => 
            array (
                'id' => 255,
                'name_kh' => 'មានជ័យ',
                'name_en' => 'Mean Chey',
                'code' => '31405',
                'province_id' => 3,
                'district_id' => 32,
            ),
            255 => 
            array (
                'id' => 256,
                'name_kh' => 'ផ្ទះកណ្ដាល',
                'name_en' => 'Phteah Kandal',
                'code' => '31406',
                'province_id' => 3,
                'district_id' => 32,
            ),
            256 => 
            array (
                'id' => 257,
                'name_kh' => 'ប្រាំយ៉ាម',
                'name_en' => 'Pram Yam',
                'code' => '31407',
                'province_id' => 3,
                'district_id' => 32,
            ),
            257 => 
            array (
                'id' => 258,
                'name_kh' => 'ព្រែកដំបូក',
                'name_en' => 'Preaek Dambouk',
                'code' => '31408',
                'province_id' => 3,
                'district_id' => 32,
            ),
            258 => 
            array (
                'id' => 259,
                'name_kh' => 'ព្រែកពោធិ',
                'name_en' => 'Preaek Pou',
                'code' => '31409',
                'province_id' => 3,
                'district_id' => 32,
            ),
            259 => 
            array (
                'id' => 260,
                'name_kh' => 'ព្រែករំដេង',
                'name_en' => 'Preaek Rumdeng',
                'code' => '31410',
                'province_id' => 3,
                'district_id' => 32,
            ),
            260 => 
            array (
                'id' => 261,
                'name_kh' => 'ឫស្សីស្រុក',
                'name_en' => 'Ruessei Srok',
                'code' => '31411',
                'province_id' => 3,
                'district_id' => 32,
            ),
            261 => 
            array (
                'id' => 262,
                'name_kh' => 'ស្វាយពោធិ',
                'name_en' => 'Svay Pou',
                'code' => '31412',
                'province_id' => 3,
                'district_id' => 32,
            ),
            262 => 
            array (
                'id' => 263,
                'name_kh' => 'ស្វាយខ្សាច់ភ្នំ',
                'name_en' => 'Svay Khsach Phnum',
                'code' => '31413',
                'province_id' => 3,
                'district_id' => 32,
            ),
            263 => 
            array (
                'id' => 264,
                'name_kh' => 'ទងត្រឡាច',
                'name_en' => 'Tong Tralach',
                'code' => '31414',
                'province_id' => 3,
                'district_id' => 32,
            ),
            264 => 
            array (
                'id' => 265,
                'name_kh' => 'អារក្សត្នោត',
                'name_en' => 'Areaks Tnot',
                'code' => '31501',
                'province_id' => 3,
                'district_id' => 33,
            ),
            265 => 
            array (
                'id' => 266,
                'name_kh' => 'ដងក្ដារ',
                'name_en' => 'Dang Kdar',
                'code' => '31503',
                'province_id' => 3,
                'district_id' => 33,
            ),
            266 => 
            array (
                'id' => 267,
                'name_kh' => 'ខ្ពបតាងួន',
                'name_en' => 'Khpob Ta Nguon',
                'code' => '31504',
                'province_id' => 3,
                'district_id' => 33,
            ),
            267 => 
            array (
                'id' => 268,
                'name_kh' => 'មេសរជ្រៃ',
                'name_en' => 'Me Sar Chrey',
                'code' => '31505',
                'province_id' => 3,
                'district_id' => 33,
            ),
            268 => 
            array (
                'id' => 269,
                'name_kh' => 'អូរម្លូ',
                'name_en' => 'Ou Mlu',
                'code' => '31506',
                'province_id' => 3,
                'district_id' => 33,
            ),
            269 => 
            array (
                'id' => 270,
                'name_kh' => 'ពាមកោះស្នា',
                'name_en' => 'Peam Kaoh Snar',
                'code' => '31507',
                'province_id' => 3,
                'district_id' => 33,
            ),
            270 => 
            array (
                'id' => 271,
                'name_kh' => 'ព្រះអណ្ដូង',
                'name_en' => 'Preah Andoung',
                'code' => '31508',
                'province_id' => 3,
                'district_id' => 33,
            ),
            271 => 
            array (
                'id' => 272,
                'name_kh' => 'ព្រែកបាក់',
                'name_en' => 'Preaek Bak',
                'code' => '31509',
                'province_id' => 3,
                'district_id' => 33,
            ),
            272 => 
            array (
                'id' => 273,
                'name_kh' => 'ព្រែកកក់',
                'name_en' => 'Preak Kak',
                'code' => '31510',
                'province_id' => 3,
                'district_id' => 33,
            ),
            273 => 
            array (
                'id' => 274,
                'name_kh' => 'សូភាស',
                'name_en' => 'Soupheas',
                'code' => '31512',
                'province_id' => 3,
                'district_id' => 33,
            ),
            274 => 
            array (
                'id' => 275,
                'name_kh' => 'ទួលព្រះឃ្លាំង',
                'name_en' => 'Tuol Preah Khleang',
                'code' => '31513',
                'province_id' => 3,
                'district_id' => 33,
            ),
            275 => 
            array (
                'id' => 276,
                'name_kh' => 'ទួលសំបួរ',
                'name_en' => 'Tuol Sambuor',
                'code' => '31514',
                'province_id' => 3,
                'district_id' => 33,
            ),
            276 => 
            array (
                'id' => 277,
                'name_kh' => 'អញ្ចាញរូង',
                'name_en' => 'Anhchanh Rung',
                'code' => '40101',
                'province_id' => 4,
                'district_id' => 34,
            ),
            277 => 
            array (
                'id' => 278,
                'name_kh' => 'ឆ្នុកទ្រូ',
                'name_en' => 'Chhnok Tru',
                'code' => '40102',
                'province_id' => 4,
                'district_id' => 34,
            ),
            278 => 
            array (
                'id' => 279,
                'name_kh' => 'ចក',
                'name_en' => 'Chak',
                'code' => '40103',
                'province_id' => 4,
                'district_id' => 34,
            ),
            279 => 
            array (
                'id' => 280,
                'name_kh' => 'ខុនរ៉ង',
                'name_en' => 'Khon Rang',
                'code' => '40104',
                'province_id' => 4,
                'district_id' => 34,
            ),
            280 => 
            array (
                'id' => 281,
                'name_kh' => 'កំពង់ព្រះគគីរ',
                'name_en' => 'Kampong Preah Kokir',
                'code' => '40105',
                'province_id' => 4,
                'district_id' => 34,
            ),
            281 => 
            array (
                'id' => 282,
                'name_kh' => 'មេលំ',
                'name_en' => 'Melum',
                'code' => '40106',
                'province_id' => 4,
                'district_id' => 34,
            ),
            282 => 
            array (
                'id' => 283,
                'name_kh' => 'ផ្សារ',
                'name_en' => 'Phsar',
                'code' => '40107',
                'province_id' => 4,
                'district_id' => 34,
            ),
            283 => 
            array (
                'id' => 284,
                'name_kh' => 'ពេជចង្វារ',
                'name_en' => 'Pech Changvar',
                'code' => '40108',
                'province_id' => 4,
                'district_id' => 34,
            ),
            284 => 
            array (
                'id' => 285,
                'name_kh' => 'ពពេល',
                'name_en' => 'Popel',
                'code' => '40109',
                'province_id' => 4,
                'district_id' => 34,
            ),
            285 => 
            array (
                'id' => 286,
                'name_kh' => 'ពន្លៃ',
                'name_en' => 'Ponley',
                'code' => '40110',
                'province_id' => 4,
                'district_id' => 34,
            ),
            286 => 
            array (
                'id' => 287,
                'name_kh' => 'ត្រពាំងចាន់',
                'name_en' => 'Trapeang Chan',
                'code' => '40111',
                'province_id' => 4,
                'district_id' => 34,
            ),
            287 => 
            array (
                'id' => 288,
                'name_kh' => 'ជលសា',
                'name_en' => 'Chol Sar',
                'code' => '40201',
                'province_id' => 4,
                'district_id' => 35,
            ),
            288 => 
            array (
                'id' => 289,
                'name_kh' => 'កោះថ្កូវ',
                'name_en' => 'Kaoh Thkov',
                'code' => '40202',
                'province_id' => 4,
                'district_id' => 35,
            ),
            289 => 
            array (
                'id' => 290,
                'name_kh' => 'កំពង់អុស',
                'name_en' => 'Kampong Ous',
                'code' => '40203',
                'province_id' => 4,
                'district_id' => 35,
            ),
            290 => 
            array (
                'id' => 291,
                'name_kh' => 'ពាមឆ្កោក',
                'name_en' => 'Peam Chhkaok',
                'code' => '40204',
                'province_id' => 4,
                'district_id' => 35,
            ),
            291 => 
            array (
                'id' => 292,
                'name_kh' => 'ព្រៃគ្រី',
                'name_en' => 'Prey Kri',
                'code' => '40205',
                'province_id' => 4,
                'district_id' => 35,
            ),
            292 => 
            array (
                'id' => 293,
                'name_kh' => 'ផ្សារឆ្នាំង',
                'name_en' => 'Phsar Chhnang',
                'code' => '40301',
                'province_id' => 4,
                'district_id' => 36,
            ),
            293 => 
            array (
                'id' => 294,
                'name_kh' => 'កំពង់ឆ្នាំង',
                'name_en' => 'Kampong Chhnang',
                'code' => '40302',
                'province_id' => 4,
                'district_id' => 36,
            ),
            294 => 
            array (
                'id' => 295,
                'name_kh' => 'ប្អេរ',
                'name_en' => 'Ber',
                'code' => '40303',
                'province_id' => 4,
                'district_id' => 36,
            ),
            295 => 
            array (
                'id' => 296,
                'name_kh' => 'ខ្សាម',
                'name_en' => 'Khsam',
                'code' => '40304',
                'province_id' => 4,
                'district_id' => 36,
            ),
            296 => 
            array (
                'id' => 297,
                'name_kh' => 'ច្រណូក',
                'name_en' => 'Chranouk',
                'code' => '40401',
                'province_id' => 4,
                'district_id' => 37,
            ),
            297 => 
            array (
                'id' => 298,
                'name_kh' => 'ដារ',
                'name_en' => 'Dar',
                'code' => '40402',
                'province_id' => 4,
                'district_id' => 37,
            ),
            298 => 
            array (
                'id' => 299,
                'name_kh' => 'កំពង់ហៅ',
                'name_en' => 'Kampong Hau',
                'code' => '40403',
                'province_id' => 4,
                'district_id' => 37,
            ),
            299 => 
            array (
                'id' => 300,
                'name_kh' => 'ផ្លូវទូក',
                'name_en' => 'Phlov Tuk',
                'code' => '40404',
                'province_id' => 4,
                'district_id' => 37,
            ),
            300 => 
            array (
                'id' => 301,
                'name_kh' => 'ពោធិ៍',
                'name_en' => 'Pou',
                'code' => '40405',
                'province_id' => 4,
                'district_id' => 37,
            ),
            301 => 
            array (
                'id' => 302,
                'name_kh' => 'ប្រឡាយមាស',
                'name_en' => 'Pralay Meas',
                'code' => '40406',
                'province_id' => 4,
                'district_id' => 37,
            ),
            302 => 
            array (
                'id' => 303,
                'name_kh' => 'សំរោងសែន',
                'name_en' => 'Samraong Saen',
                'code' => '40407',
                'province_id' => 4,
                'district_id' => 37,
            ),
            303 => 
            array (
                'id' => 304,
                'name_kh' => 'ស្វាយរំពារ',
                'name_en' => 'Svay Rumpear',
                'code' => '40408',
                'province_id' => 4,
                'district_id' => 37,
            ),
            304 => 
            array (
                'id' => 305,
                'name_kh' => 'ត្រងិល',
                'name_en' => 'Trangel',
                'code' => '40409',
                'province_id' => 4,
                'district_id' => 37,
            ),
            305 => 
            array (
                'id' => 306,
                'name_kh' => 'អំពិលទឹក',
                'name_en' => 'Ampil Tuek',
                'code' => '40501',
                'province_id' => 4,
                'district_id' => 38,
            ),
            306 => 
            array (
                'id' => 307,
                'name_kh' => 'ឈូកស',
                'name_en' => 'Chhuk Sa',
                'code' => '40502',
                'province_id' => 4,
                'district_id' => 38,
            ),
            307 => 
            array (
                'id' => 308,
                'name_kh' => 'ច្រេស',
                'name_en' => 'Chres',
                'code' => '40503',
                'province_id' => 4,
                'district_id' => 38,
            ),
            308 => 
            array (
                'id' => 309,
                'name_kh' => 'កំពង់ត្រឡាច',
                'name_en' => 'Kampong Tralach',
                'code' => '40504',
                'province_id' => 4,
                'district_id' => 38,
            ),
            309 => 
            array (
                'id' => 310,
                'name_kh' => 'លង្វែក',
                'name_en' => 'Longveaek',
                'code' => '40505',
                'province_id' => 4,
                'district_id' => 38,
            ),
            310 => 
            array (
                'id' => 311,
                'name_kh' => 'អូរឫស្សី',
                'name_en' => 'Ou Ruessei',
                'code' => '40506',
                'province_id' => 4,
                'district_id' => 38,
            ),
            311 => 
            array (
                'id' => 312,
                'name_kh' => 'ពានី',
                'name_en' => 'Peani',
                'code' => '40507',
                'province_id' => 4,
                'district_id' => 38,
            ),
            312 => 
            array (
                'id' => 313,
                'name_kh' => 'សែប',
                'name_en' => 'Saeb',
                'code' => '40508',
                'province_id' => 4,
                'district_id' => 38,
            ),
            313 => 
            array (
                'id' => 314,
                'name_kh' => 'តាជេស',
                'name_en' => 'Ta Ches',
                'code' => '40509',
                'province_id' => 4,
                'district_id' => 38,
            ),
            314 => 
            array (
                'id' => 315,
                'name_kh' => 'ថ្មឥដ្ឋ',
                'name_en' => 'Thma Edth',
                'code' => '40510',
                'province_id' => 4,
                'district_id' => 38,
            ),
            315 => 
            array (
                'id' => 316,
                'name_kh' => 'អណ្ដូងស្នាយ',
                'name_en' => 'Andoung Snay',
                'code' => '40601',
                'province_id' => 4,
                'district_id' => 39,
            ),
            316 => 
            array (
                'id' => 317,
                'name_kh' => 'បន្ទាយព្រាល',
                'name_en' => 'Banteay Preal',
                'code' => '40602',
                'province_id' => 4,
                'district_id' => 39,
            ),
            317 => 
            array (
                'id' => 318,
                'name_kh' => 'ជើងគ្រាវ',
                'name_en' => 'Cheung Kreav',
                'code' => '40603',
                'province_id' => 4,
                'district_id' => 39,
            ),
            318 => 
            array (
                'id' => 319,
                'name_kh' => 'ជ្រៃបាក់',
                'name_en' => 'Chrey Bak',
                'code' => '40604',
                'province_id' => 4,
                'district_id' => 39,
            ),
            319 => 
            array (
                'id' => 320,
                'name_kh' => 'គោកបន្ទាយ',
                'name_en' => 'Kouk Banteay',
                'code' => '40605',
                'province_id' => 4,
                'district_id' => 39,
            ),
            320 => 
            array (
                'id' => 321,
                'name_kh' => 'ក្រាំងលាវ',
                'name_en' => 'Krang Leav',
                'code' => '40606',
                'province_id' => 4,
                'district_id' => 39,
            ),
            321 => 
            array (
                'id' => 322,
                'name_kh' => 'ពង្រ',
                'name_en' => 'Pongro',
                'code' => '40607',
                'province_id' => 4,
                'district_id' => 39,
            ),
            322 => 
            array (
                'id' => 323,
                'name_kh' => 'ប្រស្នឹប',
                'name_en' => 'Prasnoeb',
                'code' => '40608',
                'province_id' => 4,
                'district_id' => 39,
            ),
            323 => 
            array (
                'id' => 324,
                'name_kh' => 'ព្រៃមូល',
                'name_en' => 'Prey Mul',
                'code' => '40609',
                'province_id' => 4,
                'district_id' => 39,
            ),
            324 => 
            array (
                'id' => 325,
                'name_kh' => 'រលាប្អៀរ',
                'name_en' => 'Rolea Bier',
                'code' => '40610',
                'province_id' => 4,
                'district_id' => 39,
            ),
            325 => 
            array (
                'id' => 326,
                'name_kh' => 'ស្រែថ្មី',
                'name_en' => 'Srae Thmei',
                'code' => '40611',
                'province_id' => 4,
                'district_id' => 39,
            ),
            326 => 
            array (
                'id' => 327,
                'name_kh' => 'ស្វាយជ្រុំ',
                'name_en' => 'Svay Chrum',
                'code' => '40612',
                'province_id' => 4,
                'district_id' => 39,
            ),
            327 => 
            array (
                'id' => 328,
                'name_kh' => 'ទឹកហូត',
                'name_en' => 'Tuek Hout',
                'code' => '40613',
                'province_id' => 4,
                'district_id' => 39,
            ),
            328 => 
            array (
                'id' => 329,
                'name_kh' => 'ឈានឡើង',
                'name_en' => 'Chhean Laeung',
                'code' => '40701',
                'province_id' => 4,
                'district_id' => 40,
            ),
            329 => 
            array (
                'id' => 330,
                'name_kh' => 'ខ្នារឆ្មារ',
                'name_en' => 'Khnar Chhmar',
                'code' => '40702',
                'province_id' => 4,
                'district_id' => 40,
            ),
            330 => 
            array (
                'id' => 331,
                'name_kh' => 'ក្រាំងល្វា',
                'name_en' => 'Krang Lvea',
                'code' => '40703',
                'province_id' => 4,
                'district_id' => 40,
            ),
            331 => 
            array (
                'id' => 332,
                'name_kh' => 'ពាម',
                'name_en' => 'Peam',
                'code' => '40704',
                'province_id' => 4,
                'district_id' => 40,
            ),
            332 => 
            array (
                'id' => 333,
                'name_kh' => 'សេដ្ឋី',
                'name_en' => 'Sedthei',
                'code' => '40705',
                'province_id' => 4,
                'district_id' => 40,
            ),
            333 => 
            array (
                'id' => 334,
                'name_kh' => 'ស្វាយ',
                'name_en' => 'Svay',
                'code' => '40706',
                'province_id' => 4,
                'district_id' => 40,
            ),
            334 => 
            array (
                'id' => 335,
                'name_kh' => 'ស្វាយជុក',
                'name_en' => 'Svay Chuk',
                'code' => '40707',
                'province_id' => 4,
                'district_id' => 40,
            ),
            335 => 
            array (
                'id' => 336,
                'name_kh' => 'ត្បែងខ្ពស់',
                'name_en' => 'Tbaeng Khpos',
                'code' => '40708',
                'province_id' => 4,
                'district_id' => 40,
            ),
            336 => 
            array (
                'id' => 337,
                'name_kh' => 'ធ្លកវៀន',
                'name_en' => 'Thlok Vien',
                'code' => '40709',
                'province_id' => 4,
                'district_id' => 40,
            ),
            337 => 
            array (
                'id' => 338,
                'name_kh' => 'អភិវឌ្ឍន៍',
                'name_en' => 'Akphivoadth',
                'code' => '40801',
                'province_id' => 4,
                'district_id' => 41,
            ),
            338 => 
            array (
                'id' => 339,
                'name_kh' => 'ជៀប',
                'name_en' => 'Chieb',
                'code' => '40802',
                'province_id' => 4,
                'district_id' => 41,
            ),
            339 => 
            array (
                'id' => 340,
                'name_kh' => 'ចោងម៉ោង',
                'name_en' => 'Chaong Maong',
                'code' => '40803',
                'province_id' => 4,
                'district_id' => 41,
            ),
            340 => 
            array (
                'id' => 341,
                'name_kh' => 'ក្បាលទឹក',
                'name_en' => 'Kbal Tuek',
                'code' => '40804',
                'province_id' => 4,
                'district_id' => 41,
            ),
            341 => 
            array (
                'id' => 342,
                'name_kh' => 'ខ្លុងពពក',
                'name_en' => 'Khlong Popok',
                'code' => '40805',
                'province_id' => 4,
                'district_id' => 41,
            ),
            342 => 
            array (
                'id' => 343,
                'name_kh' => 'ក្រាំងស្គារ',
                'name_en' => 'Krang Skear',
                'code' => '40806',
                'province_id' => 4,
                'district_id' => 41,
            ),
            343 => 
            array (
                'id' => 344,
                'name_kh' => 'តាំងក្រសាំង',
                'name_en' => 'Tang Krasang',
                'code' => '40807',
                'province_id' => 4,
                'district_id' => 41,
            ),
            344 => 
            array (
                'id' => 345,
                'name_kh' => 'ទួលខ្ពស់',
                'name_en' => 'Tuol Khpos',
                'code' => '40808',
                'province_id' => 4,
                'district_id' => 41,
            ),
            345 => 
            array (
                'id' => 346,
                'name_kh' => 'ក្តុលសែនជ័យ',
                'name_en' => 'Kdol Saen Chey',
                'code' => '40809',
                'province_id' => 4,
                'district_id' => 41,
            ),
            346 => 
            array (
                'id' => 347,
                'name_kh' => 'បរសេដ្ឋ',
                'name_en' => 'Basedth',
                'code' => '50101',
                'province_id' => 5,
                'district_id' => 42,
            ),
            347 => 
            array (
                'id' => 348,
                'name_kh' => 'កាត់ភ្លុក',
                'name_en' => 'Kat Phluk',
                'code' => '50102',
                'province_id' => 5,
                'district_id' => 42,
            ),
            348 => 
            array (
                'id' => 349,
                'name_kh' => 'និទាន',
                'name_en' => 'Nitean',
                'code' => '50103',
                'province_id' => 5,
                'district_id' => 42,
            ),
            349 => 
            array (
                'id' => 350,
                'name_kh' => 'ភក្ដី',
                'name_en' => 'Pheakdei',
                'code' => '50104',
                'province_id' => 5,
                'district_id' => 42,
            ),
            350 => 
            array (
                'id' => 351,
                'name_kh' => 'ភារីមានជ័យ',
                'name_en' => 'Pheari Mean Chey',
                'code' => '50105',
                'province_id' => 5,
                'district_id' => 42,
            ),
            351 => 
            array (
                'id' => 352,
                'name_kh' => 'ផុង',
                'name_en' => 'Phong',
                'code' => '50106',
                'province_id' => 5,
                'district_id' => 42,
            ),
            352 => 
            array (
                'id' => 353,
                'name_kh' => 'ពោធិអង្ក្រង',
                'name_en' => 'Pou Angkrang',
                'code' => '50107',
                'province_id' => 5,
                'district_id' => 42,
            ),
            353 => 
            array (
                'id' => 354,
                'name_kh' => 'ពោធិ៍ចំរើន',
                'name_en' => 'Pou Chamraeun',
                'code' => '50108',
                'province_id' => 5,
                'district_id' => 42,
            ),
            354 => 
            array (
                'id' => 355,
                'name_kh' => 'ពោធិ៍ម្រាល',
                'name_en' => 'Pou Mreal',
                'code' => '50109',
                'province_id' => 5,
                'district_id' => 42,
            ),
            355 => 
            array (
                'id' => 356,
                'name_kh' => 'ស្វាយចចិប',
                'name_en' => 'Svay Chacheb',
                'code' => '50110',
                'province_id' => 5,
                'district_id' => 42,
            ),
            356 => 
            array (
                'id' => 357,
                'name_kh' => 'ទួលអំពិល',
                'name_en' => 'Tuol Ampil',
                'code' => '50111',
                'province_id' => 5,
                'district_id' => 42,
            ),
            357 => 
            array (
                'id' => 358,
                'name_kh' => 'ទួលសាលា',
                'name_en' => 'Tuol Sala',
                'code' => '50112',
                'province_id' => 5,
                'district_id' => 42,
            ),
            358 => 
            array (
                'id' => 359,
                'name_kh' => 'កក់',
                'name_en' => 'Kak',
                'code' => '50113',
                'province_id' => 5,
                'district_id' => 42,
            ),
            359 => 
            array (
                'id' => 360,
                'name_kh' => 'ស្វាយរំពារ',
                'name_en' => 'Svay Rumpear',
                'code' => '50114',
                'province_id' => 5,
                'district_id' => 42,
            ),
            360 => 
            array (
                'id' => 361,
                'name_kh' => 'ព្រះខែ',
                'name_en' => 'Preah Khae',
                'code' => '50115',
                'province_id' => 5,
                'district_id' => 42,
            ),
            361 => 
            array (
                'id' => 362,
                'name_kh' => 'ច្បារមន',
                'name_en' => 'Chbar Mon',
                'code' => '50201',
                'province_id' => 5,
                'district_id' => 43,
            ),
            362 => 
            array (
                'id' => 363,
                'name_kh' => 'កណ្ដោលដុំ',
                'name_en' => 'Kandaol Dom',
                'code' => '50202',
                'province_id' => 5,
                'district_id' => 43,
            ),
            363 => 
            array (
                'id' => 364,
                'name_kh' => 'រការធំ',
                'name_en' => 'Rokar Thum',
                'code' => '50203',
                'province_id' => 5,
                'district_id' => 43,
            ),
            364 => 
            array (
                'id' => 365,
                'name_kh' => 'សុព័រទេព',
                'name_en' => 'Sopoar Tep',
                'code' => '50204',
                'province_id' => 5,
                'district_id' => 43,
            ),
            365 => 
            array (
                'id' => 366,
                'name_kh' => 'ស្វាយក្រវ៉ាន់',
                'name_en' => 'Svay Kravan',
                'code' => '50205',
                'province_id' => 5,
                'district_id' => 43,
            ),
            366 => 
            array (
                'id' => 367,
                'name_kh' => 'អង្គពពេល',
                'name_en' => 'Angk Popel',
                'code' => '50301',
                'province_id' => 5,
                'district_id' => 44,
            ),
            367 => 
            array (
                'id' => 368,
                'name_kh' => 'ជង្រុក',
                'name_en' => 'Chongruk',
                'code' => '50302',
                'province_id' => 5,
                'district_id' => 44,
            ),
            368 => 
            array (
                'id' => 369,
                'name_kh' => 'មហាឫស្សី',
                'name_en' => 'Moha Ruessei',
                'code' => '50303',
                'province_id' => 5,
                'district_id' => 44,
            ),
            369 => 
            array (
                'id' => 370,
                'name_kh' => 'ពេជ្រមុនី',
                'name_en' => 'Pechr Muni',
                'code' => '50304',
                'province_id' => 5,
                'district_id' => 44,
            ),
            370 => 
            array (
                'id' => 371,
                'name_kh' => 'ព្រះនិព្វាន',
                'name_en' => 'Preah Nipean',
                'code' => '50305',
                'province_id' => 5,
                'district_id' => 44,
            ),
            371 => 
            array (
                'id' => 372,
                'name_kh' => 'ព្រៃញាតិ',
                'name_en' => 'Prey Nheat',
                'code' => '50306',
                'province_id' => 5,
                'district_id' => 44,
            ),
            372 => 
            array (
                'id' => 373,
                'name_kh' => 'ព្រៃវិហារ',
                'name_en' => 'Prey Vihear',
                'code' => '50307',
                'province_id' => 5,
                'district_id' => 44,
            ),
            373 => 
            array (
                'id' => 374,
                'name_kh' => 'រកាកោះ',
                'name_en' => 'Roka Kaoh',
                'code' => '50308',
                'province_id' => 5,
                'district_id' => 44,
            ),
            374 => 
            array (
                'id' => 375,
                'name_kh' => 'ស្ដុក',
                'name_en' => 'Sdok',
                'code' => '50309',
                'province_id' => 5,
                'district_id' => 44,
            ),
            375 => 
            array (
                'id' => 376,
                'name_kh' => 'ស្នំក្រពើ',
                'name_en' => 'Snam Krapeu',
                'code' => '50310',
                'province_id' => 5,
                'district_id' => 44,
            ),
            376 => 
            array (
                'id' => 377,
                'name_kh' => 'ស្រង់',
                'name_en' => 'Srang',
                'code' => '50311',
                'province_id' => 5,
                'district_id' => 44,
            ),
            377 => 
            array (
                'id' => 378,
                'name_kh' => 'ទឹកល្អក់',
                'name_en' => 'Tuek Lak',
                'code' => '50312',
                'province_id' => 5,
                'district_id' => 44,
            ),
            378 => 
            array (
                'id' => 379,
                'name_kh' => 'វាល',
                'name_en' => 'Veal',
                'code' => '50313',
                'province_id' => 5,
                'district_id' => 44,
            ),
            379 => 
            array (
                'id' => 380,
                'name_kh' => 'ហោងសំណំ',
                'name_en' => 'Haong Samnam',
                'code' => '50401',
                'province_id' => 5,
                'district_id' => 45,
            ),
            380 => 
            array (
                'id' => 381,
                'name_kh' => 'រស្មីសាមគ្គី',
                'name_en' => 'Reaksmei Sameakki',
                'code' => '50402',
                'province_id' => 5,
                'district_id' => 45,
            ),
            381 => 
            array (
                'id' => 382,
                'name_kh' => 'ត្រពាំងជោ',
                'name_en' => 'Trapeang Chour',
                'code' => '50403',
                'province_id' => 5,
                'district_id' => 45,
            ),
            382 => 
            array (
                'id' => 383,
                'name_kh' => 'សង្កែសាទប',
                'name_en' => 'Sangkae Satob',
                'code' => '50404',
                'province_id' => 5,
                'district_id' => 45,
            ),
            383 => 
            array (
                'id' => 384,
                'name_kh' => 'តាសាល',
                'name_en' => 'Ta Sal',
                'code' => '50405',
                'province_id' => 5,
                'district_id' => 45,
            ),
            384 => 
            array (
                'id' => 385,
                'name_kh' => 'ចាន់សែន',
                'name_en' => 'Chan Saen',
                'code' => '50501',
                'province_id' => 5,
                'district_id' => 46,
            ),
            385 => 
            array (
                'id' => 386,
                'name_kh' => 'ជើងរាស់',
                'name_en' => 'Cheung Roas',
                'code' => '50502',
                'province_id' => 5,
                'district_id' => 46,
            ),
            386 => 
            array (
                'id' => 387,
                'name_kh' => 'ជំពូព្រឹក្ស',
                'name_en' => 'Chumpu Proeks',
                'code' => '50503',
                'province_id' => 5,
                'district_id' => 46,
            ),
            387 => 
            array (
                'id' => 388,
                'name_kh' => 'ក្សេមក្សាន្ដ',
                'name_en' => 'Khsem Khsant',
                'code' => '50504',
                'province_id' => 5,
                'district_id' => 46,
            ),
            388 => 
            array (
                'id' => 389,
                'name_kh' => 'ក្រាំងចេក',
                'name_en' => 'Krang Chek',
                'code' => '50505',
                'province_id' => 5,
                'district_id' => 46,
            ),
            389 => 
            array (
                'id' => 390,
                'name_kh' => 'មានជ័យ',
                'name_en' => 'Mean Chey',
                'code' => '50506',
                'province_id' => 5,
                'district_id' => 46,
            ),
            390 => 
            array (
                'id' => 391,
                'name_kh' => 'ព្រះស្រែ',
                'name_en' => 'Preah Srae',
                'code' => '50507',
                'province_id' => 5,
                'district_id' => 46,
            ),
            391 => 
            array (
                'id' => 392,
                'name_kh' => 'ព្រៃក្រសាំង',
                'name_en' => 'Prey Krasang',
                'code' => '50508',
                'province_id' => 5,
                'district_id' => 46,
            ),
            392 => 
            array (
                'id' => 393,
                'name_kh' => 'ត្រាចទង',
                'name_en' => 'Trach Tong',
                'code' => '50509',
                'province_id' => 5,
                'district_id' => 46,
            ),
            393 => 
            array (
                'id' => 394,
                'name_kh' => 'វាលពង់',
                'name_en' => 'Veal Pong',
                'code' => '50510',
                'province_id' => 5,
                'district_id' => 46,
            ),
            394 => 
            array (
                'id' => 395,
                'name_kh' => 'វាំងចាស់',
                'name_en' => 'Veang Chas',
                'code' => '50511',
                'province_id' => 5,
                'district_id' => 46,
            ),
            395 => 
            array (
                'id' => 396,
                'name_kh' => 'យុទ្ធសាមគ្គី',
                'name_en' => 'Yutth Sameakki',
                'code' => '50512',
                'province_id' => 5,
                'district_id' => 46,
            ),
            396 => 
            array (
                'id' => 397,
                'name_kh' => 'ដំណាក់រាំង',
                'name_en' => 'Damnak Reang',
                'code' => '50513',
                'province_id' => 5,
                'district_id' => 46,
            ),
            397 => 
            array (
                'id' => 398,
                'name_kh' => 'ពាំងល្វា',
                'name_en' => 'Peang Lvea',
                'code' => '50514',
                'province_id' => 5,
                'district_id' => 46,
            ),
            398 => 
            array (
                'id' => 399,
                'name_kh' => 'ភ្នំតូច',
                'name_en' => 'Phnom Touch',
                'code' => '50515',
                'province_id' => 5,
                'district_id' => 46,
            ),
            399 => 
            array (
                'id' => 400,
                'name_kh' => 'ចំបក់',
                'name_en' => 'Chambak',
                'code' => '50601',
                'province_id' => 5,
                'district_id' => 47,
            ),
            400 => 
            array (
                'id' => 401,
                'name_kh' => 'ជាំសង្កែ',
                'name_en' => 'Choam Sangkae',
                'code' => '50602',
                'province_id' => 5,
                'district_id' => 47,
            ),
            401 => 
            array (
                'id' => 402,
                'name_kh' => 'ដំបូករូង',
                'name_en' => 'Dambouk Rung',
                'code' => '50603',
                'province_id' => 5,
                'district_id' => 47,
            ),
            402 => 
            array (
                'id' => 403,
                'name_kh' => 'គិរីវន្ដ',
                'name_en' => 'Kiri Voan',
                'code' => '50604',
                'province_id' => 5,
                'district_id' => 47,
            ),
            403 => 
            array (
                'id' => 404,
                'name_kh' => 'ក្រាំងដីវ៉ាយ',
                'name_en' => 'Krang Dei Vay',
                'code' => '50605',
                'province_id' => 5,
                'district_id' => 47,
            ),
            404 => 
            array (
                'id' => 405,
                'name_kh' => 'មហាសាំង',
                'name_en' => 'Moha Sang',
                'code' => '50606',
                'province_id' => 5,
                'district_id' => 47,
            ),
            405 => 
            array (
                'id' => 406,
                'name_kh' => 'អូរ',
                'name_en' => 'Ou',
                'code' => '50607',
                'province_id' => 5,
                'district_id' => 47,
            ),
            406 => 
            array (
                'id' => 407,
                'name_kh' => 'ព្រៃរំដួល',
                'name_en' => 'Prey Rumduol',
                'code' => '50608',
                'province_id' => 5,
                'district_id' => 47,
            ),
            407 => 
            array (
                'id' => 408,
                'name_kh' => 'ព្រៃក្មេង',
                'name_en' => 'Prey Kmeng',
                'code' => '50609',
                'province_id' => 5,
                'district_id' => 47,
            ),
            408 => 
            array (
                'id' => 409,
                'name_kh' => 'តាំងសំរោង',
                'name_en' => 'Tang Samraong',
                'code' => '50610',
                'province_id' => 5,
                'district_id' => 47,
            ),
            409 => 
            array (
                'id' => 410,
                'name_kh' => 'តាំងស្យា',
                'name_en' => 'Tang Sya',
                'code' => '50611',
                'province_id' => 5,
                'district_id' => 47,
            ),
            410 => 
            array (
                'id' => 411,
                'name_kh' => 'ត្រែងត្រយឹង',
                'name_en' => 'Traeng Trayueng',
                'code' => '50613',
                'province_id' => 5,
                'district_id' => 47,
            ),
            411 => 
            array (
                'id' => 412,
                'name_kh' => 'រលាំងចក',
                'name_en' => 'Roleang Chak',
                'code' => '50701',
                'province_id' => 5,
                'district_id' => 48,
            ),
            412 => 
            array (
                'id' => 413,
                'name_kh' => 'កាហែង',
                'name_en' => 'Kahaeng',
                'code' => '50702',
                'province_id' => 5,
                'district_id' => 48,
            ),
            413 => 
            array (
                'id' => 414,
                'name_kh' => 'ខ្ទុំក្រាំង',
                'name_en' => 'Khtum Krang',
                'code' => '50703',
                'province_id' => 5,
                'district_id' => 48,
            ),
            414 => 
            array (
                'id' => 415,
                'name_kh' => 'ក្រាំងអំពិល',
                'name_en' => 'Krang Ampil',
                'code' => '50704',
                'province_id' => 5,
                'district_id' => 48,
            ),
            415 => 
            array (
                'id' => 416,
                'name_kh' => 'ព្នាយ',
                'name_en' => 'Pneay',
                'code' => '50705',
                'province_id' => 5,
                'district_id' => 48,
            ),
            416 => 
            array (
                'id' => 417,
                'name_kh' => 'រលាំងគ្រើល',
                'name_en' => 'Roleang Kreul',
                'code' => '50706',
                'province_id' => 5,
                'district_id' => 48,
            ),
            417 => 
            array (
                'id' => 418,
                'name_kh' => 'សំរោងទង',
                'name_en' => 'Samrong Tong',
                'code' => '50707',
                'province_id' => 5,
                'district_id' => 48,
            ),
            418 => 
            array (
                'id' => 419,
                'name_kh' => 'សំបូរ',
                'name_en' => 'Sambour',
                'code' => '50708',
                'province_id' => 5,
                'district_id' => 48,
            ),
            419 => 
            array (
                'id' => 420,
                'name_kh' => 'សែងដី',
                'name_en' => 'Saen Dei',
                'code' => '50709',
                'province_id' => 5,
                'district_id' => 48,
            ),
            420 => 
            array (
                'id' => 421,
                'name_kh' => 'ស្គុះ',
                'name_en' => 'Skuh',
                'code' => '50710',
                'province_id' => 5,
                'district_id' => 48,
            ),
            421 => 
            array (
                'id' => 422,
                'name_kh' => 'តាំងក្រូច',
                'name_en' => 'Tang Krouch',
                'code' => '50711',
                'province_id' => 5,
                'district_id' => 48,
            ),
            422 => 
            array (
                'id' => 423,
                'name_kh' => 'ធម្មតាអរ',
                'name_en' => 'Thummoda Ar',
                'code' => '50712',
                'province_id' => 5,
                'district_id' => 48,
            ),
            423 => 
            array (
                'id' => 424,
                'name_kh' => 'ត្រពាំងគង',
                'name_en' => 'Trapeang Kong',
                'code' => '50713',
                'province_id' => 5,
                'district_id' => 48,
            ),
            424 => 
            array (
                'id' => 425,
                'name_kh' => 'ទំព័រមាស',
                'name_en' => 'Tumpoar Meas',
                'code' => '50714',
                'province_id' => 5,
                'district_id' => 48,
            ),
            425 => 
            array (
                'id' => 426,
                'name_kh' => 'វល្លិសរ',
                'name_en' => 'Voa Sar',
                'code' => '50715',
                'province_id' => 5,
                'district_id' => 48,
            ),
            426 => 
            array (
                'id' => 427,
                'name_kh' => 'អមលាំង',
                'name_en' => 'Amleang',
                'code' => '50801',
                'province_id' => 5,
                'district_id' => 49,
            ),
            427 => 
            array (
                'id' => 428,
                'name_kh' => 'មនោរម្យ',
                'name_en' => 'Monourom',
                'code' => '50802',
                'province_id' => 5,
                'district_id' => 49,
            ),
            428 => 
            array (
                'id' => 429,
                'name_kh' => 'ប្រាំបីមុម',
                'name_en' => 'Prambei Mum',
                'code' => '50804',
                'province_id' => 5,
                'district_id' => 49,
            ),
            429 => 
            array (
                'id' => 430,
                'name_kh' => 'រុងរឿង',
                'name_en' => 'Rung Roeang',
                'code' => '50805',
                'province_id' => 5,
                'district_id' => 49,
            ),
            430 => 
            array (
                'id' => 431,
                'name_kh' => 'ទ័ពមាន',
                'name_en' => 'Toap Mean',
                'code' => '50806',
                'province_id' => 5,
                'district_id' => 49,
            ),
            431 => 
            array (
                'id' => 432,
                'name_kh' => 'វាលពន់',
                'name_en' => 'Veal Pon',
                'code' => '50807',
                'province_id' => 5,
                'district_id' => 49,
            ),
            432 => 
            array (
                'id' => 433,
                'name_kh' => 'យាអង្គ',
                'name_en' => 'Yea Angk',
                'code' => '50808',
                'province_id' => 5,
                'district_id' => 49,
            ),
            433 => 
            array (
                'id' => 434,
                'name_kh' => 'បាក់ស្នា',
                'name_en' => 'Bak Sna',
                'code' => '60101',
                'province_id' => 6,
                'district_id' => 50,
            ),
            434 => 
            array (
                'id' => 435,
                'name_kh' => 'បល្ល័ង្គ',
                'name_en' => 'Ballangk',
                'code' => '60102',
                'province_id' => 6,
                'district_id' => 50,
            ),
            435 => 
            array (
                'id' => 436,
                'name_kh' => 'បារាយណ៍',
                'name_en' => 'Baray',
                'code' => '60103',
                'province_id' => 6,
                'district_id' => 50,
            ),
            436 => 
            array (
                'id' => 437,
                'name_kh' => 'បឹង',
                'name_en' => 'Boeng',
                'code' => '60104',
                'province_id' => 6,
                'district_id' => 50,
            ),
            437 => 
            array (
                'id' => 438,
                'name_kh' => 'ចើងដើង',
                'name_en' => 'Chaeung Daeung',
                'code' => '60105',
                'province_id' => 6,
                'district_id' => 50,
            ),
            438 => 
            array (
                'id' => 439,
                'name_kh' => 'ឈូកខ្សាច់',
                'name_en' => 'Chhuk Khsach',
                'code' => '60107',
                'province_id' => 6,
                'district_id' => 50,
            ),
            439 => 
            array (
                'id' => 440,
                'name_kh' => 'ចុងដូង',
                'name_en' => 'Chong Doung',
                'code' => '60108',
                'province_id' => 6,
                'district_id' => 50,
            ),
            440 => 
            array (
                'id' => 441,
                'name_kh' => 'គគីធំ',
                'name_en' => 'Kokir Thum',
                'code' => '60110',
                'province_id' => 6,
                'district_id' => 50,
            ),
            441 => 
            array (
                'id' => 442,
                'name_kh' => 'ក្រវ៉ា',
                'name_en' => 'Krava',
                'code' => '60111',
                'province_id' => 6,
                'district_id' => 50,
            ),
            442 => 
            array (
                'id' => 443,
                'name_kh' => 'ត្នោតជុំ',
                'name_en' => 'Tnaot Chum',
                'code' => '60117',
                'province_id' => 6,
                'district_id' => 50,
            ),
            443 => 
            array (
                'id' => 444,
                'name_kh' => 'ជ័យ',
                'name_en' => 'Chey',
                'code' => '60201',
                'province_id' => 6,
                'district_id' => 51,
            ),
            444 => 
            array (
                'id' => 445,
                'name_kh' => 'ដំរីស្លាប់',
                'name_en' => 'Damrei Slab',
                'code' => '60202',
                'province_id' => 6,
                'district_id' => 51,
            ),
            445 => 
            array (
                'id' => 446,
                'name_kh' => 'កំពង់គោ',
                'name_en' => 'Kampong Kou',
                'code' => '60203',
                'province_id' => 6,
                'district_id' => 51,
            ),
            446 => 
            array (
                'id' => 447,
                'name_kh' => 'កំពង់ស្វាយ',
                'name_en' => 'Kampong Svay',
                'code' => '60204',
                'province_id' => 6,
                'district_id' => 51,
            ),
            447 => 
            array (
                'id' => 448,
                'name_kh' => 'នីពេជ',
                'name_en' => 'Nipech',
                'code' => '60205',
                'province_id' => 6,
                'district_id' => 51,
            ),
            448 => 
            array (
                'id' => 449,
                'name_kh' => 'ផាត់សណ្ដាយ',
                'name_en' => 'Phat Sanday',
                'code' => '60206',
                'province_id' => 6,
                'district_id' => 51,
            ),
            449 => 
            array (
                'id' => 450,
                'name_kh' => 'សាន់គ',
                'name_en' => 'San Kor',
                'code' => '60207',
                'province_id' => 6,
                'district_id' => 51,
            ),
            450 => 
            array (
                'id' => 451,
                'name_kh' => 'ត្បែង',
                'name_en' => 'Tbaeng',
                'code' => '60208',
                'province_id' => 6,
                'district_id' => 51,
            ),
            451 => 
            array (
                'id' => 452,
                'name_kh' => 'ត្រពាំងឫស្សី',
                'name_en' => 'Trapeang Ruessei',
                'code' => '60209',
                'province_id' => 6,
                'district_id' => 51,
            ),
            452 => 
            array (
                'id' => 453,
                'name_kh' => 'ក្ដីដូង',
                'name_en' => 'Kdei Doung',
                'code' => '60210',
                'province_id' => 6,
                'district_id' => 51,
            ),
            453 => 
            array (
                'id' => 454,
                'name_kh' => 'ព្រៃគុយ',
                'name_en' => 'Prey Kuy',
                'code' => '60211',
                'province_id' => 6,
                'district_id' => 51,
            ),
            454 => 
            array (
                'id' => 455,
                'name_kh' => 'ដំរីជាន់ខ្លា',
                'name_en' => 'Damrei Choan Khla',
                'code' => '60301',
                'province_id' => 6,
                'district_id' => 52,
            ),
            455 => 
            array (
                'id' => 456,
                'name_kh' => 'កំពង់ធំ',
                'name_en' => 'Kampong Thum',
                'code' => '60302',
                'province_id' => 6,
                'district_id' => 52,
            ),
            456 => 
            array (
                'id' => 457,
                'name_kh' => 'កំពង់រទេះ',
                'name_en' => 'Kampong Roteh',
                'code' => '60303',
                'province_id' => 6,
                'district_id' => 52,
            ),
            457 => 
            array (
                'id' => 458,
                'name_kh' => 'អូរកន្ធរ',
                'name_en' => 'Ou Kanthor',
                'code' => '60304',
                'province_id' => 6,
                'district_id' => 52,
            ),
            458 => 
            array (
                'id' => 459,
                'name_kh' => 'កំពង់ក្របៅ',
                'name_en' => 'Kampong Krabau',
                'code' => '60306',
                'province_id' => 6,
                'district_id' => 52,
            ),
            459 => 
            array (
                'id' => 460,
                'name_kh' => 'ព្រៃតាហ៊ូ',
                'name_en' => 'Prey Ta Hu',
                'code' => '60308',
                'province_id' => 6,
                'district_id' => 52,
            ),
            460 => 
            array (
                'id' => 461,
                'name_kh' => 'អាចារ្យលាក់',
                'name_en' => 'Achar Leak',
                'code' => '60309',
                'province_id' => 6,
                'district_id' => 52,
            ),
            461 => 
            array (
                'id' => 462,
                'name_kh' => 'ស្រយ៉ូវ',
                'name_en' => 'Srayov',
                'code' => '60310',
                'province_id' => 6,
                'district_id' => 52,
            ),
            462 => 
            array (
                'id' => 463,
                'name_kh' => 'ដូង',
                'name_en' => 'Doung',
                'code' => '60401',
                'province_id' => 6,
                'district_id' => 53,
            ),
            463 => 
            array (
                'id' => 464,
                'name_kh' => 'ក្រយា',
                'name_en' => 'Kraya',
                'code' => '60402',
                'province_id' => 6,
                'district_id' => 53,
            ),
            464 => 
            array (
                'id' => 465,
                'name_kh' => 'ផាន់ញើម',
                'name_en' => 'Phan Nheum',
                'code' => '60403',
                'province_id' => 6,
                'district_id' => 53,
            ),
            465 => 
            array (
                'id' => 466,
                'name_kh' => 'សាគ្រាម',
                'name_en' => 'Sakream',
                'code' => '60404',
                'province_id' => 6,
                'district_id' => 53,
            ),
            466 => 
            array (
                'id' => 467,
                'name_kh' => 'សាលាវិស័យ',
                'name_en' => 'Sala Visai',
                'code' => '60405',
                'province_id' => 6,
                'district_id' => 53,
            ),
            467 => 
            array (
                'id' => 468,
                'name_kh' => 'សាមគ្គី',
                'name_en' => 'Sameakki',
                'code' => '60406',
                'province_id' => 6,
                'district_id' => 53,
            ),
            468 => 
            array (
                'id' => 469,
                'name_kh' => 'ទួលគ្រើល',
                'name_en' => 'Tuol Kreul',
                'code' => '60407',
                'province_id' => 6,
                'district_id' => 53,
            ),
            469 => 
            array (
                'id' => 470,
                'name_kh' => 'ឈូក',
                'name_en' => 'Chhuk',
                'code' => '60501',
                'province_id' => 6,
                'district_id' => 54,
            ),
            470 => 
            array (
                'id' => 471,
                'name_kh' => 'គោល',
                'name_en' => 'Koul',
                'code' => '60502',
                'province_id' => 6,
                'district_id' => 54,
            ),
            471 => 
            array (
                'id' => 472,
                'name_kh' => 'សំបូរណ៍',
                'name_en' => 'Sambour',
                'code' => '60503',
                'province_id' => 6,
                'district_id' => 54,
            ),
            472 => 
            array (
                'id' => 473,
                'name_kh' => 'ស្រើង',
                'name_en' => 'Sraeung',
                'code' => '60504',
                'province_id' => 6,
                'district_id' => 54,
            ),
            473 => 
            array (
                'id' => 474,
                'name_kh' => 'តាំងក្រសៅ',
                'name_en' => 'Tang Krasau',
                'code' => '60505',
                'province_id' => 6,
                'district_id' => 54,
            ),
            474 => 
            array (
                'id' => 475,
                'name_kh' => 'ឈើទាល',
                'name_en' => 'Chheu Teal',
                'code' => '60601',
                'province_id' => 6,
                'district_id' => 55,
            ),
            475 => 
            array (
                'id' => 476,
                'name_kh' => 'ដងកាំបិត',
                'name_en' => 'Dang Kambet',
                'code' => '60602',
                'province_id' => 6,
                'district_id' => 55,
            ),
            476 => 
            array (
                'id' => 477,
                'name_kh' => 'ក្លែង',
                'name_en' => 'Klaeng',
                'code' => '60603',
                'province_id' => 6,
                'district_id' => 55,
            ),
            477 => 
            array (
                'id' => 478,
                'name_kh' => 'មានរិទ្ធ',
                'name_en' => 'Mean Rith',
                'code' => '60604',
                'province_id' => 6,
                'district_id' => 55,
            ),
            478 => 
            array (
                'id' => 479,
                'name_kh' => 'មានជ័យ',
                'name_en' => 'Mean Chey',
                'code' => '60605',
                'province_id' => 6,
                'district_id' => 55,
            ),
            479 => 
            array (
                'id' => 480,
                'name_kh' => 'ងន',
                'name_en' => 'Ngan',
                'code' => '60606',
                'province_id' => 6,
                'district_id' => 55,
            ),
            480 => 
            array (
                'id' => 481,
                'name_kh' => 'សណ្ដាន់',
                'name_en' => 'Sandan',
                'code' => '60607',
                'province_id' => 6,
                'district_id' => 55,
            ),
            481 => 
            array (
                'id' => 482,
                'name_kh' => 'សុចិត្រ',
                'name_en' => 'Sochet',
                'code' => '60608',
                'province_id' => 6,
                'district_id' => 55,
            ),
            482 => 
            array (
                'id' => 483,
                'name_kh' => 'ទំរីង',
                'name_en' => 'Tum Ring',
                'code' => '60609',
                'province_id' => 6,
                'district_id' => 55,
            ),
            483 => 
            array (
                'id' => 484,
                'name_kh' => 'បឹងល្វា',
                'name_en' => 'Boeng Lvea',
                'code' => '60701',
                'province_id' => 6,
                'district_id' => 56,
            ),
            484 => 
            array (
                'id' => 485,
                'name_kh' => 'ជ្រាប់',
                'name_en' => 'Chroab',
                'code' => '60702',
                'province_id' => 6,
                'district_id' => 56,
            ),
            485 => 
            array (
                'id' => 486,
                'name_kh' => 'កំពង់ថ្ម',
                'name_en' => 'Kampong Thma',
                'code' => '60703',
                'province_id' => 6,
                'district_id' => 56,
            ),
            486 => 
            array (
                'id' => 487,
                'name_kh' => 'កកោះ',
                'name_en' => 'Kakaoh',
                'code' => '60704',
                'province_id' => 6,
                'district_id' => 56,
            ),
            487 => 
            array (
                'id' => 488,
                'name_kh' => 'ក្រយា',
                'name_en' => 'Kraya',
                'code' => '60705',
                'province_id' => 6,
                'district_id' => 56,
            ),
            488 => 
            array (
                'id' => 489,
                'name_kh' => 'ព្នៅ',
                'name_en' => 'Pnov',
                'code' => '60706',
                'province_id' => 6,
                'district_id' => 56,
            ),
            489 => 
            array (
                'id' => 490,
                'name_kh' => 'ប្រាសាទ',
                'name_en' => 'Prasat',
                'code' => '60707',
                'province_id' => 6,
                'district_id' => 56,
            ),
            490 => 
            array (
                'id' => 491,
                'name_kh' => 'តាំងក្រសាំង',
                'name_en' => 'Tang Krasang',
                'code' => '60708',
                'province_id' => 6,
                'district_id' => 56,
            ),
            491 => 
            array (
                'id' => 492,
                'name_kh' => 'ទីពោ',
                'name_en' => 'Ti Pou',
                'code' => '60709',
                'province_id' => 6,
                'district_id' => 56,
            ),
            492 => 
            array (
                'id' => 493,
                'name_kh' => 'ត្បូងក្រពើ',
                'name_en' => 'Tboung Krapeu',
                'code' => '60710',
                'province_id' => 6,
                'district_id' => 56,
            ),
            493 => 
            array (
                'id' => 494,
                'name_kh' => 'បន្ទាយស្ទោង',
                'name_en' => 'Banteay Stoung',
                'code' => '60801',
                'province_id' => 6,
                'district_id' => 57,
            ),
            494 => 
            array (
                'id' => 495,
                'name_kh' => 'ចំណាក្រោម',
                'name_en' => 'Chamna Kraom',
                'code' => '60802',
                'province_id' => 6,
                'district_id' => 57,
            ),
            495 => 
            array (
                'id' => 496,
                'name_kh' => 'ចំណាលើ',
                'name_en' => 'Chamna Leu',
                'code' => '60803',
                'province_id' => 6,
                'district_id' => 57,
            ),
            496 => 
            array (
                'id' => 497,
                'name_kh' => 'កំពង់ចិនជើង',
                'name_en' => 'Kampong Chen Cheung',
                'code' => '60804',
                'province_id' => 6,
                'district_id' => 57,
            ),
            497 => 
            array (
                'id' => 498,
                'name_kh' => 'កំពង់ចិនត្បូង',
                'name_en' => 'Kampong Chen Tboung',
                'code' => '60805',
                'province_id' => 6,
                'district_id' => 57,
            ),
            498 => 
            array (
                'id' => 499,
                'name_kh' => 'ម្សាក្រង',
                'name_en' => 'Msa Krang',
                'code' => '60806',
                'province_id' => 6,
                'district_id' => 57,
            ),
            499 => 
            array (
                'id' => 500,
                'name_kh' => 'ពាមបាង',
                'name_en' => 'Peam Bang',
                'code' => '60807',
                'province_id' => 6,
                'district_id' => 57,
            ),
        ));
        \DB::table('communes')->insert(array (
            0 => 
            array (
                'id' => 501,
                'name_kh' => 'ពពក',
                'name_en' => 'Popok',
                'code' => '60808',
                'province_id' => 6,
                'district_id' => 57,
            ),
            1 => 
            array (
                'id' => 502,
                'name_kh' => 'ប្រឡាយ',
                'name_en' => 'Pralay',
                'code' => '60809',
                'province_id' => 6,
                'district_id' => 57,
            ),
            2 => 
            array (
                'id' => 503,
                'name_kh' => 'ព្រះដំរី',
                'name_en' => 'Preah Damrei',
                'code' => '60810',
                'province_id' => 6,
                'district_id' => 57,
            ),
            3 => 
            array (
                'id' => 504,
                'name_kh' => 'រុងរឿង',
                'name_en' => 'Rung Roeang',
                'code' => '60811',
                'province_id' => 6,
                'district_id' => 57,
            ),
            4 => 
            array (
                'id' => 505,
                'name_kh' => 'សំព្រោជ',
                'name_en' => 'Samprouch',
                'code' => '60812',
                'province_id' => 6,
                'district_id' => 57,
            ),
            5 => 
            array (
                'id' => 506,
                'name_kh' => 'ទ្រា',
                'name_en' => 'Trea',
                'code' => '60813',
                'province_id' => 6,
                'district_id' => 57,
            ),
            6 => 
            array (
                'id' => 507,
                'name_kh' => 'ពង្រ',
                'name_en' => 'Pongro',
                'code' => '60901',
                'province_id' => 6,
                'district_id' => 58,
            ),
            7 => 
            array (
                'id' => 508,
                'name_kh' => 'ច្រនាង',
                'name_en' => 'Chraneang',
                'code' => '60902',
                'province_id' => 6,
                'district_id' => 58,
            ),
            8 => 
            array (
                'id' => 509,
                'name_kh' => 'ជ្រលង',
                'name_en' => 'Chrolong',
                'code' => '60903',
                'province_id' => 6,
                'district_id' => 58,
            ),
            9 => 
            array (
                'id' => 510,
                'name_kh' => 'ទ្រៀល',
                'name_en' => 'Triel',
                'code' => '60904',
                'province_id' => 6,
                'district_id' => 58,
            ),
            10 => 
            array (
                'id' => 511,
                'name_kh' => 'សូយោង',
                'name_en' => 'Sou Young',
                'code' => '60905',
                'province_id' => 6,
                'district_id' => 58,
            ),
            11 => 
            array (
                'id' => 512,
                'name_kh' => 'ស្រឡៅ',
                'name_en' => 'Sralau',
                'code' => '60906',
                'province_id' => 6,
                'district_id' => 58,
            ),
            12 => 
            array (
                'id' => 513,
                'name_kh' => 'ស្វាយភ្លើង',
                'name_en' => 'Svay Phleung',
                'code' => '60907',
                'province_id' => 6,
                'district_id' => 58,
            ),
            13 => 
            array (
                'id' => 514,
                'name_kh' => 'អណ្ដូងពោធិ៍',
                'name_en' => 'Andoung Pou',
                'code' => '60908',
                'province_id' => 6,
                'district_id' => 58,
            ),
            14 => 
            array (
                'id' => 515,
                'name_kh' => 'អង្គភ្នំតូច',
                'name_en' => 'Angk Phnum Touch',
                'code' => '70101',
                'province_id' => 7,
                'district_id' => 59,
            ),
            15 => 
            array (
                'id' => 516,
                'name_kh' => 'អង្គរជ័យ',
                'name_en' => 'Ankor Chey',
                'code' => '70102',
                'province_id' => 7,
                'district_id' => 59,
            ),
            16 => 
            array (
                'id' => 517,
                'name_kh' => 'ចំប៉ី',
                'name_en' => 'Champei',
                'code' => '70103',
                'province_id' => 7,
                'district_id' => 59,
            ),
            17 => 
            array (
                'id' => 518,
                'name_kh' => 'ដំបូកខ្ពស់',
                'name_en' => 'Dambouk Khpos',
                'code' => '70104',
                'province_id' => 7,
                'district_id' => 59,
            ),
            18 => 
            array (
                'id' => 519,
                'name_kh' => 'ដានគោម',
                'name_en' => 'Dan Koum',
                'code' => '70105',
                'province_id' => 7,
                'district_id' => 59,
            ),
            19 => 
            array (
                'id' => 520,
                'name_kh' => 'ដើមដូង',
                'name_en' => 'Daeum Doung',
                'code' => '70106',
                'province_id' => 7,
                'district_id' => 59,
            ),
            20 => 
            array (
                'id' => 521,
                'name_kh' => 'ម្រោម',
                'name_en' => 'Mroum',
                'code' => '70107',
                'province_id' => 7,
                'district_id' => 59,
            ),
            21 => 
            array (
                'id' => 522,
                'name_kh' => 'ភ្នំកុង',
                'name_en' => 'Phnum Kong',
                'code' => '70108',
                'province_id' => 7,
                'district_id' => 59,
            ),
            22 => 
            array (
                'id' => 523,
                'name_kh' => 'ប្រភ្នំ',
                'name_en' => 'Praphnum',
                'code' => '70109',
                'province_id' => 7,
                'district_id' => 59,
            ),
            23 => 
            array (
                'id' => 524,
                'name_kh' => 'សំឡាញ',
                'name_en' => 'Samlanh',
                'code' => '70110',
                'province_id' => 7,
                'district_id' => 59,
            ),
            24 => 
            array (
                'id' => 525,
                'name_kh' => 'តានី',
                'name_en' => 'Tani',
                'code' => '70111',
                'province_id' => 7,
                'district_id' => 59,
            ),
            25 => 
            array (
                'id' => 526,
                'name_kh' => 'បន្ទាយមាសខាងកើត',
                'name_en' => 'Banteay Meas Khang Kaeut',
                'code' => '70201',
                'province_id' => 7,
                'district_id' => 60,
            ),
            26 => 
            array (
                'id' => 527,
                'name_kh' => 'បន្ទាយមាសខាងលិច',
                'name_en' => 'Banteay Meas Khang lech',
                'code' => '70202',
                'province_id' => 7,
                'district_id' => 60,
            ),
            27 => 
            array (
                'id' => 528,
                'name_kh' => 'ព្រៃទន្លេ',
                'name_en' => 'Prey Tonle',
                'code' => '70203',
                'province_id' => 7,
                'district_id' => 60,
            ),
            28 => 
            array (
                'id' => 529,
                'name_kh' => 'សំរោងក្រោម',
                'name_en' => 'Samraong Kraom',
                'code' => '70204',
                'province_id' => 7,
                'district_id' => 60,
            ),
            29 => 
            array (
                'id' => 530,
                'name_kh' => 'សំរោងលើ',
                'name_en' => 'Samraong Leu',
                'code' => '70205',
                'province_id' => 7,
                'district_id' => 60,
            ),
            30 => 
            array (
                'id' => 531,
                'name_kh' => 'ស្ដេចគង់ខាងជើង',
                'name_en' => 'Sdach Kong Khang Cheung',
                'code' => '70206',
                'province_id' => 7,
                'district_id' => 60,
            ),
            31 => 
            array (
                'id' => 532,
                'name_kh' => 'ស្ដេចគង់ខាងលិច',
                'name_en' => 'Sdach Kong Khang lech',
                'code' => '70207',
                'province_id' => 7,
                'district_id' => 60,
            ),
            32 => 
            array (
                'id' => 533,
                'name_kh' => 'ស្ដេចគង់ខាងត្បូង',
                'name_en' => 'Sdach Kong Khang Tboung',
                'code' => '70208',
                'province_id' => 7,
                'district_id' => 60,
            ),
            33 => 
            array (
                'id' => 534,
                'name_kh' => 'ត្នោតចុងស្រង់',
                'name_en' => 'Tnoat Chong Srang',
                'code' => '70209',
                'province_id' => 7,
                'district_id' => 60,
            ),
            34 => 
            array (
                'id' => 535,
                'name_kh' => 'ត្រពាំងសាលាខាងកើត',
                'name_en' => 'Trapeang Sala Khang Kaeut',
                'code' => '70210',
                'province_id' => 7,
                'district_id' => 60,
            ),
            35 => 
            array (
                'id' => 536,
                'name_kh' => 'ត្រពាំងសាលាខាងលិច',
                'name_en' => 'Trapeang Sala Khang Lech',
                'code' => '70211',
                'province_id' => 7,
                'district_id' => 60,
            ),
            36 => 
            array (
                'id' => 537,
                'name_kh' => 'ទូកមាសខាងកើត',
                'name_en' => 'Tuk Meas Khang Kaeut',
                'code' => '70212',
                'province_id' => 7,
                'district_id' => 60,
            ),
            37 => 
            array (
                'id' => 538,
                'name_kh' => 'ទូកមាសខាងលិច',
                'name_en' => 'Tuk Meas Khang Lech',
                'code' => '70213',
                'province_id' => 7,
                'district_id' => 60,
            ),
            38 => 
            array (
                'id' => 539,
                'name_kh' => 'វត្ដអង្គខាងជើង',
                'name_en' => 'Voat Angk Khang Cheung',
                'code' => '70214',
                'province_id' => 7,
                'district_id' => 60,
            ),
            39 => 
            array (
                'id' => 540,
                'name_kh' => 'វត្ដអង្គខាងត្បូង',
                'name_en' => 'Voat Angk Khang Tboung',
                'code' => '70215',
                'province_id' => 7,
                'district_id' => 60,
            ),
            40 => 
            array (
                'id' => 541,
                'name_kh' => 'បានៀវ',
                'name_en' => 'Baniev',
                'code' => '70301',
                'province_id' => 7,
                'district_id' => 61,
            ),
            41 => 
            array (
                'id' => 542,
                'name_kh' => 'តាកែន',
                'name_en' => 'Takaen',
                'code' => '70302',
                'province_id' => 7,
                'district_id' => 61,
            ),
            42 => 
            array (
                'id' => 543,
                'name_kh' => 'បឹងនិមល',
                'name_en' => 'Boeng Nimol',
                'code' => '70303',
                'province_id' => 7,
                'district_id' => 61,
            ),
            43 => 
            array (
                'id' => 544,
                'name_kh' => 'ឈូក',
                'name_en' => 'Chhuk',
                'code' => '70304',
                'province_id' => 7,
                'district_id' => 61,
            ),
            44 => 
            array (
                'id' => 545,
                'name_kh' => 'ដូនយ៉យ',
                'name_en' => 'Doun Yay',
                'code' => '70305',
                'province_id' => 7,
                'district_id' => 61,
            ),
            45 => 
            array (
                'id' => 546,
                'name_kh' => 'ក្រាំងស្បូវ',
                'name_en' => 'Krang Sbov',
                'code' => '70306',
                'province_id' => 7,
                'district_id' => 61,
            ),
            46 => 
            array (
                'id' => 547,
                'name_kh' => 'ក្រាំងស្នាយ',
                'name_en' => 'Krang Snay',
                'code' => '70307',
                'province_id' => 7,
                'district_id' => 61,
            ),
            47 => 
            array (
                'id' => 548,
                'name_kh' => 'ល្បើក',
                'name_en' => 'Lbaeuk',
                'code' => '70308',
                'province_id' => 7,
                'district_id' => 61,
            ),
            48 => 
            array (
                'id' => 549,
                'name_kh' => 'ត្រពាំងភ្លាំង',
                'name_en' => 'Trapeang Phleang',
                'code' => '70309',
                'province_id' => 7,
                'district_id' => 61,
            ),
            49 => 
            array (
                'id' => 550,
                'name_kh' => 'មានជ័យ',
                'name_en' => 'Mean Chey',
                'code' => '70310',
                'province_id' => 7,
                'district_id' => 61,
            ),
            50 => 
            array (
                'id' => 551,
                'name_kh' => 'នារាយណ៍',
                'name_en' => 'Neareay',
                'code' => '70311',
                'province_id' => 7,
                'district_id' => 61,
            ),
            51 => 
            array (
                'id' => 552,
                'name_kh' => 'សត្វពង',
                'name_en' => 'Satv Pong',
                'code' => '70312',
                'province_id' => 7,
                'district_id' => 61,
            ),
            52 => 
            array (
                'id' => 553,
                'name_kh' => 'ត្រពាំងបី',
                'name_en' => 'Trapeang Bei',
                'code' => '70313',
                'province_id' => 7,
                'district_id' => 61,
            ),
            53 => 
            array (
                'id' => 554,
                'name_kh' => 'ត្រមែង',
                'name_en' => 'Tramaeng',
                'code' => '70314',
                'province_id' => 7,
                'district_id' => 61,
            ),
            54 => 
            array (
                'id' => 555,
                'name_kh' => 'តេជោអភិវឌ្ឍន៍',
                'name_en' => 'Dechou Akphivoadth',
                'code' => '70315',
                'province_id' => 7,
                'district_id' => 61,
            ),
            55 => 
            array (
                'id' => 556,
                'name_kh' => 'ច្រេស',
                'name_en' => 'Chres',
                'code' => '70401',
                'province_id' => 7,
                'district_id' => 62,
            ),
            56 => 
            array (
                'id' => 557,
                'name_kh' => 'ជំពូវន្ដ',
                'name_en' => 'Chumpu Voan',
                'code' => '70402',
                'province_id' => 7,
                'district_id' => 62,
            ),
            57 => 
            array (
                'id' => 558,
                'name_kh' => 'ស្នាយអញ្ជិត',
                'name_en' => 'Snay Anhchit',
                'code' => '70403',
                'province_id' => 7,
                'district_id' => 62,
            ),
            58 => 
            array (
                'id' => 559,
                'name_kh' => 'ស្រែចែង',
                'name_en' => 'Srae Chaeng',
                'code' => '70404',
                'province_id' => 7,
                'district_id' => 62,
            ),
            59 => 
            array (
                'id' => 560,
                'name_kh' => 'ស្រែក្នុង',
                'name_en' => 'Srae Knong',
                'code' => '70405',
                'province_id' => 7,
                'district_id' => 62,
            ),
            60 => 
            array (
                'id' => 561,
                'name_kh' => 'ស្រែសំរោង',
                'name_en' => 'Srae Samraong',
                'code' => '70406',
                'province_id' => 7,
                'district_id' => 62,
            ),
            61 => 
            array (
                'id' => 562,
                'name_kh' => 'ត្រពាំងរាំង',
                'name_en' => 'Trapeang Reang',
                'code' => '70407',
                'province_id' => 7,
                'district_id' => 62,
            ),
            62 => 
            array (
                'id' => 563,
                'name_kh' => 'ដំណាក់សុក្រំ',
                'name_en' => 'Damnak Sokram',
                'code' => '70501',
                'province_id' => 7,
                'district_id' => 63,
            ),
            63 => 
            array (
                'id' => 564,
                'name_kh' => 'ដងទង់',
                'name_en' => 'Dang Tong',
                'code' => '70502',
                'province_id' => 7,
                'district_id' => 63,
            ),
            64 => 
            array (
                'id' => 565,
                'name_kh' => 'ឃ្ជាយខាងជើង',
                'name_en' => 'Khcheay Khang Cheung',
                'code' => '70503',
                'province_id' => 7,
                'district_id' => 63,
            ),
            65 => 
            array (
                'id' => 566,
                'name_kh' => 'ខ្ជាយខាងត្បូង',
                'name_en' => 'Khcheay Khang Tboung',
                'code' => '70504',
                'province_id' => 7,
                'district_id' => 63,
            ),
            66 => 
            array (
                'id' => 567,
                'name_kh' => 'មានរិទ្ធិ',
                'name_en' => 'Mean Ritth',
                'code' => '70505',
                'province_id' => 7,
                'district_id' => 63,
            ),
            67 => 
            array (
                'id' => 568,
                'name_kh' => 'ស្រែជាខាងជើង',
                'name_en' => 'Srae Chea Khang Cheung',
                'code' => '70506',
                'province_id' => 7,
                'district_id' => 63,
            ),
            68 => 
            array (
                'id' => 569,
                'name_kh' => 'ស្រែជាខាងត្បូង',
                'name_en' => 'Srae Chea Khang Tboung',
                'code' => '70507',
                'province_id' => 7,
                'district_id' => 63,
            ),
            69 => 
            array (
                'id' => 570,
                'name_kh' => 'ទទុង',
                'name_en' => 'Totung',
                'code' => '70508',
                'province_id' => 7,
                'district_id' => 63,
            ),
            70 => 
            array (
                'id' => 571,
                'name_kh' => 'អង្គ រមាស',
                'name_en' => 'Angk  Romeas',
                'code' => '70509',
                'province_id' => 7,
                'district_id' => 63,
            ),
            71 => 
            array (
                'id' => 572,
                'name_kh' => 'ល្អាង',
                'name_en' => 'Lang',
                'code' => '70510',
                'province_id' => 7,
                'district_id' => 63,
            ),
            72 => 
            array (
                'id' => 573,
                'name_kh' => 'បឹងសាលាខាងជើង',
                'name_en' => 'Boeng Sala Khang Cheung',
                'code' => '70601',
                'province_id' => 7,
                'district_id' => 64,
            ),
            73 => 
            array (
                'id' => 574,
                'name_kh' => 'បឹងសាលាខាងត្បូង',
                'name_en' => 'Boeng Sala Khang Tboung',
                'code' => '70602',
                'province_id' => 7,
                'district_id' => 64,
            ),
            74 => 
            array (
                'id' => 575,
                'name_kh' => 'ដំណាក់កន្ទួតខាងជើង',
                'name_en' => 'Damnak Kantuot Khang Cheung',
                'code' => '70603',
                'province_id' => 7,
                'district_id' => 64,
            ),
            75 => 
            array (
                'id' => 576,
                'name_kh' => 'ដំណាក់កន្ទួតខាងត្បូង',
                'name_en' => 'Damnak Kantuot Khang Tboung',
                'code' => '70604',
                'province_id' => 7,
                'district_id' => 64,
            ),
            76 => 
            array (
                'id' => 577,
                'name_kh' => 'កំពង់ត្រាចខាងកើត',
                'name_en' => 'Kampong Trach Khang Kaeut',
                'code' => '70605',
                'province_id' => 7,
                'district_id' => 64,
            ),
            77 => 
            array (
                'id' => 578,
                'name_kh' => 'កំពង់ត្រាចខាងលិច',
                'name_en' => 'Kampong Trach Khang Lech',
                'code' => '70606',
                'province_id' => 7,
                'district_id' => 64,
            ),
            78 => 
            array (
                'id' => 579,
                'name_kh' => 'ប្រាសាទភ្នំខ្យង',
                'name_en' => 'Prasat Phnom Khyang',
                'code' => '70607',
                'province_id' => 7,
                'district_id' => 64,
            ),
            79 => 
            array (
                'id' => 580,
                'name_kh' => 'ភ្នំប្រាសាទ',
                'name_en' => 'Phnom Prasat',
                'code' => '70608',
                'province_id' => 7,
                'district_id' => 64,
            ),
            80 => 
            array (
                'id' => 581,
                'name_kh' => 'អង្គសុរភី',
                'name_en' => 'Ang Sophy',
                'code' => '70609',
                'province_id' => 7,
                'district_id' => 64,
            ),
            81 => 
            array (
                'id' => 582,
                'name_kh' => 'ព្រែកក្រឹស',
                'name_en' => 'Preaek Kroes',
                'code' => '70612',
                'province_id' => 7,
                'district_id' => 64,
            ),
            82 => 
            array (
                'id' => 583,
                'name_kh' => 'ឫស្សីស្រុកខាងកើត',
                'name_en' => 'Ruessei Srok Khang Kaeut',
                'code' => '70613',
                'province_id' => 7,
                'district_id' => 64,
            ),
            83 => 
            array (
                'id' => 584,
                'name_kh' => 'ឫស្សីស្រុកខាងលិច',
                'name_en' => 'Ruessei Srok Khang Lech',
                'code' => '70614',
                'province_id' => 7,
                'district_id' => 64,
            ),
            84 => 
            array (
                'id' => 585,
                'name_kh' => 'ស្វាយទងខាងជើង',
                'name_en' => 'Svay Tong Khang Cheung',
                'code' => '70615',
                'province_id' => 7,
                'district_id' => 64,
            ),
            85 => 
            array (
                'id' => 586,
                'name_kh' => 'ស្វាយទងខាងត្បូង',
                'name_en' => 'Svay Tong Khang Tboung',
                'code' => '70616',
                'province_id' => 7,
                'district_id' => 64,
            ),
            86 => 
            array (
                'id' => 587,
                'name_kh' => 'បឹងទូក',
                'name_en' => 'Boeng Tuk',
                'code' => '70701',
                'province_id' => 7,
                'district_id' => 65,
            ),
            87 => 
            array (
                'id' => 588,
                'name_kh' => 'ជុំគ្រៀល',
                'name_en' => 'Chum Kriel',
                'code' => '70702',
                'province_id' => 7,
                'district_id' => 65,
            ),
            88 => 
            array (
                'id' => 589,
                'name_kh' => 'កំពង់ក្រែង',
                'name_en' => 'Kampong Kraeng',
                'code' => '70703',
                'province_id' => 7,
                'district_id' => 65,
            ),
            89 => 
            array (
                'id' => 590,
                'name_kh' => 'កំពង់សំរោង',
                'name_en' => 'Kampong Samraong',
                'code' => '70704',
                'province_id' => 7,
                'district_id' => 65,
            ),
            90 => 
            array (
                'id' => 591,
                'name_kh' => 'កណ្ដោល',
                'name_en' => 'Kandaol',
                'code' => '70705',
                'province_id' => 7,
                'district_id' => 65,
            ),
            91 => 
            array (
                'id' => 592,
                'name_kh' => 'កោះតូច',
                'name_en' => 'Kaoh Touch',
                'code' => '70707',
                'province_id' => 7,
                'district_id' => 65,
            ),
            92 => 
            array (
                'id' => 593,
                'name_kh' => 'កូនសត្វ',
                'name_en' => 'Koun Satv',
                'code' => '70708',
                'province_id' => 7,
                'district_id' => 65,
            ),
            93 => 
            array (
                'id' => 594,
                'name_kh' => 'ម៉ាក់ប្រាង្គ',
                'name_en' => 'Makprang',
                'code' => '70709',
                'province_id' => 7,
                'district_id' => 65,
            ),
            94 => 
            array (
                'id' => 595,
                'name_kh' => 'ព្រែកត្នោត',
                'name_en' => 'Preaek Tnoat',
                'code' => '70711',
                'province_id' => 7,
                'district_id' => 65,
            ),
            95 => 
            array (
                'id' => 596,
                'name_kh' => 'ព្រៃឃ្មុំ',
                'name_en' => 'Prey Khmum',
                'code' => '70712',
                'province_id' => 7,
                'district_id' => 65,
            ),
            96 => 
            array (
                'id' => 597,
                'name_kh' => 'ព្រៃថ្នង',
                'name_en' => 'Prey Thnang',
                'code' => '70713',
                'province_id' => 7,
                'district_id' => 65,
            ),
            97 => 
            array (
                'id' => 598,
                'name_kh' => 'ស្ទឹងកែវ',
                'name_en' => 'Stueng Kaev',
                'code' => '70715',
                'province_id' => 7,
                'district_id' => 65,
            ),
            98 => 
            array (
                'id' => 599,
                'name_kh' => 'ថ្មី',
                'name_en' => 'Thmei',
                'code' => '70716',
                'province_id' => 7,
                'district_id' => 65,
            ),
            99 => 
            array (
                'id' => 600,
                'name_kh' => 'ត្រពាំងព្រីង',
                'name_en' => 'Trapeang Pring',
                'code' => '70717',
                'province_id' => 7,
                'district_id' => 65,
            ),
            100 => 
            array (
                'id' => 601,
                'name_kh' => 'ត្រពាំងសង្កែ',
                'name_en' => 'Trapeang Sangkae',
                'code' => '70718',
                'province_id' => 7,
                'district_id' => 65,
            ),
            101 => 
            array (
                'id' => 602,
                'name_kh' => 'ត្រពាំងធំ',
                'name_en' => 'Trapeang Thum',
                'code' => '70719',
                'province_id' => 7,
                'district_id' => 65,
            ),
            102 => 
            array (
                'id' => 603,
                'name_kh' => 'កំពង់កណ្ដាល',
                'name_en' => 'Kampong Kandal',
                'code' => '70801',
                'province_id' => 7,
                'district_id' => 66,
            ),
            103 => 
            array (
                'id' => 604,
                'name_kh' => 'ក្រាំងអំពិល',
                'name_en' => 'Krang Ampil',
                'code' => '70802',
                'province_id' => 7,
                'district_id' => 66,
            ),
            104 => 
            array (
                'id' => 605,
                'name_kh' => 'កំពង់បាយ',
                'name_en' => 'Kampong Bay',
                'code' => '70803',
                'province_id' => 7,
                'district_id' => 66,
            ),
            105 => 
            array (
                'id' => 606,
                'name_kh' => 'អណ្ដូងខ្មែរ',
                'name_en' => 'Andoung Khmer',
                'code' => '70804',
                'province_id' => 7,
                'district_id' => 66,
            ),
            106 => 
            array (
                'id' => 607,
                'name_kh' => 'ត្រើយកោះ',
                'name_en' => 'Traeuy Kaoh',
                'code' => '70805',
                'province_id' => 7,
                'district_id' => 66,
            ),
            107 => 
            array (
                'id' => 608,
                'name_kh' => 'អំពៅព្រៃ',
                'name_en' => 'Ampov Prey',
                'code' => '80101',
                'province_id' => 8,
                'district_id' => 67,
            ),
            108 => 
            array (
                'id' => 609,
                'name_kh' => 'អន្លង់រមៀត',
                'name_en' => 'Anlong Romiet',
                'code' => '80102',
                'province_id' => 8,
                'district_id' => 67,
            ),
            109 => 
            array (
                'id' => 610,
                'name_kh' => 'បារគូ',
                'name_en' => 'Barku',
                'code' => '80103',
                'province_id' => 8,
                'district_id' => 67,
            ),
            110 => 
            array (
                'id' => 611,
                'name_kh' => 'បឹងខ្យាង',
                'name_en' => 'Boeng Khyang',
                'code' => '80104',
                'province_id' => 8,
                'district_id' => 67,
            ),
            111 => 
            array (
                'id' => 612,
                'name_kh' => 'ជើងកើប',
                'name_en' => 'Cheung Kaeub',
                'code' => '80105',
                'province_id' => 8,
                'district_id' => 67,
            ),
            112 => 
            array (
                'id' => 613,
                'name_kh' => 'ដើមឫស',
                'name_en' => 'Daeum Rues',
                'code' => '80106',
                'province_id' => 8,
                'district_id' => 67,
            ),
            113 => 
            array (
                'id' => 614,
                'name_kh' => 'កណ្ដោក',
                'name_en' => 'Kandaok',
                'code' => '80107',
                'province_id' => 8,
                'district_id' => 67,
            ),
            114 => 
            array (
                'id' => 615,
                'name_kh' => 'ថ្មី',
                'name_en' => 'Thmei',
                'code' => '80108',
                'province_id' => 8,
                'district_id' => 67,
            ),
            115 => 
            array (
                'id' => 616,
                'name_kh' => 'គោកត្រប់',
                'name_en' => 'Kouk Trab',
                'code' => '80109',
                'province_id' => 8,
                'district_id' => 67,
            ),
            116 => 
            array (
                'id' => 617,
                'name_kh' => 'ព្រះពុទ្ធ',
                'name_en' => 'Preah Putth',
                'code' => '80113',
                'province_id' => 8,
                'district_id' => 67,
            ),
            117 => 
            array (
                'id' => 618,
                'name_kh' => 'ព្រែករកា',
                'name_en' => 'Preaek Roka',
                'code' => '80115',
                'province_id' => 8,
                'district_id' => 67,
            ),
            118 => 
            array (
                'id' => 619,
                'name_kh' => 'ព្រែកស្លែង',
                'name_en' => 'Preaek Slaeng',
                'code' => '80116',
                'province_id' => 8,
                'district_id' => 67,
            ),
            119 => 
            array (
                'id' => 620,
                'name_kh' => 'រកា',
                'name_en' => 'Roka',
                'code' => '80117',
                'province_id' => 8,
                'district_id' => 67,
            ),
            120 => 
            array (
                'id' => 621,
                'name_kh' => 'រលាំងកែន',
                'name_en' => 'Roleang Kaen',
                'code' => '80118',
                'province_id' => 8,
                'district_id' => 67,
            ),
            121 => 
            array (
                'id' => 622,
                'name_kh' => 'សៀមរាប',
                'name_en' => 'Siem Reab',
                'code' => '80122',
                'province_id' => 8,
                'district_id' => 67,
            ),
            122 => 
            array (
                'id' => 623,
                'name_kh' => 'ត្បែង',
                'name_en' => 'Tbaeng',
                'code' => '80125',
                'province_id' => 8,
                'district_id' => 67,
            ),
            123 => 
            array (
                'id' => 624,
                'name_kh' => 'ត្រពាំងវែង',
                'name_en' => 'Trapeang Veaeng',
                'code' => '80127',
                'province_id' => 8,
                'district_id' => 67,
            ),
            124 => 
            array (
                'id' => 625,
                'name_kh' => 'ទ្រា',
                'name_en' => 'Trea',
                'code' => '80128',
                'province_id' => 8,
                'district_id' => 67,
            ),
            125 => 
            array (
                'id' => 626,
                'name_kh' => 'បន្ទាយដែក',
                'name_en' => 'Banteay Daek',
                'code' => '80201',
                'province_id' => 8,
                'district_id' => 68,
            ),
            126 => 
            array (
                'id' => 627,
                'name_kh' => 'ឈើទាល',
                'name_en' => 'Chheu Teal',
                'code' => '80202',
                'province_id' => 8,
                'district_id' => 68,
            ),
            127 => 
            array (
                'id' => 628,
                'name_kh' => 'ដីឥដ្ឋ',
                'name_en' => 'Dei Edth',
                'code' => '80203',
                'province_id' => 8,
                'district_id' => 68,
            ),
            128 => 
            array (
                'id' => 629,
                'name_kh' => 'កំពង់ស្វាយ',
                'name_en' => 'Kampong Svay',
                'code' => '80204',
                'province_id' => 8,
                'district_id' => 68,
            ),
            129 => 
            array (
                'id' => 630,
                'name_kh' => 'គគីរ',
                'name_en' => 'Kokir',
                'code' => '80206',
                'province_id' => 8,
                'district_id' => 68,
            ),
            130 => 
            array (
                'id' => 631,
                'name_kh' => 'គគីរធំ',
                'name_en' => 'Kokir Thum',
                'code' => '80207',
                'province_id' => 8,
                'district_id' => 68,
            ),
            131 => 
            array (
                'id' => 632,
                'name_kh' => 'ភូមិធំ',
                'name_en' => 'Phum Thum',
                'code' => '80208',
                'province_id' => 8,
                'district_id' => 68,
            ),
            132 => 
            array (
                'id' => 633,
                'name_kh' => 'សំរោងធំ',
                'name_en' => 'Samraong Thum',
                'code' => '80211',
                'province_id' => 8,
                'district_id' => 68,
            ),
            133 => 
            array (
                'id' => 634,
                'name_kh' => 'បាក់ដាវ',
                'name_en' => 'Bak Dav',
                'code' => '80301',
                'province_id' => 8,
                'district_id' => 69,
            ),
            134 => 
            array (
                'id' => 635,
                'name_kh' => 'ជ័យធំ',
                'name_en' => 'Chey Thum',
                'code' => '80302',
                'province_id' => 8,
                'district_id' => 69,
            ),
            135 => 
            array (
                'id' => 636,
                'name_kh' => 'កំពង់ចំលង',
                'name_en' => 'Kampong Chamlang',
                'code' => '80303',
                'province_id' => 8,
                'district_id' => 69,
            ),
            136 => 
            array (
                'id' => 637,
                'name_kh' => 'កោះចូរ៉ាម',
                'name_en' => 'Kaoh Chouram',
                'code' => '80304',
                'province_id' => 8,
                'district_id' => 69,
            ),
            137 => 
            array (
                'id' => 638,
                'name_kh' => 'កោះឧកញ៉ាតី',
                'name_en' => 'Kaoh Oknha Tei',
                'code' => '80305',
                'province_id' => 8,
                'district_id' => 69,
            ),
            138 => 
            array (
                'id' => 639,
                'name_kh' => 'ព្រះប្រសប់',
                'name_en' => 'Preah Prasab',
                'code' => '80306',
                'province_id' => 8,
                'district_id' => 69,
            ),
            139 => 
            array (
                'id' => 640,
                'name_kh' => 'ព្រែកអំពិល',
                'name_en' => 'Preaek Ampil',
                'code' => '80307',
                'province_id' => 8,
                'district_id' => 69,
            ),
            140 => 
            array (
                'id' => 641,
                'name_kh' => 'ព្រែកលួង',
                'name_en' => 'Preaek Luong',
                'code' => '80308',
                'province_id' => 8,
                'district_id' => 69,
            ),
            141 => 
            array (
                'id' => 642,
                'name_kh' => 'ព្រែកតាកូវ',
                'name_en' => 'Preaek Ta kov',
                'code' => '80309',
                'province_id' => 8,
                'district_id' => 69,
            ),
            142 => 
            array (
                'id' => 643,
                'name_kh' => 'ព្រែកតាមាក់',
                'name_en' => 'Preaek Ta Meak',
                'code' => '80310',
                'province_id' => 8,
                'district_id' => 69,
            ),
            143 => 
            array (
                'id' => 644,
                'name_kh' => 'ពុកឫស្សី',
                'name_en' => 'Puk Ruessei',
                'code' => '80311',
                'province_id' => 8,
                'district_id' => 69,
            ),
            144 => 
            array (
                'id' => 645,
                'name_kh' => 'រកាជន្លឹង',
                'name_en' => 'Roka Chonlueng',
                'code' => '80312',
                'province_id' => 8,
                'district_id' => 69,
            ),
            145 => 
            array (
                'id' => 646,
                'name_kh' => 'សន្លុង',
                'name_en' => 'Sanlung',
                'code' => '80313',
                'province_id' => 8,
                'district_id' => 69,
            ),
            146 => 
            array (
                'id' => 647,
                'name_kh' => 'ស៊ីធរ',
                'name_en' => 'Sithor',
                'code' => '80314',
                'province_id' => 8,
                'district_id' => 69,
            ),
            147 => 
            array (
                'id' => 648,
                'name_kh' => 'ស្វាយជ្រំ',
                'name_en' => 'Svay Chrum',
                'code' => '80315',
                'province_id' => 8,
                'district_id' => 69,
            ),
            148 => 
            array (
                'id' => 649,
                'name_kh' => 'ស្វាយរមៀត',
                'name_en' => 'Svay Romiet',
                'code' => '80316',
                'province_id' => 8,
                'district_id' => 69,
            ),
            149 => 
            array (
                'id' => 650,
                'name_kh' => 'តាឯក',
                'name_en' => 'Ta Aek',
                'code' => '80317',
                'province_id' => 8,
                'district_id' => 69,
            ),
            150 => 
            array (
                'id' => 651,
                'name_kh' => 'វិហារសួគ៌',
                'name_en' => 'Vihear Suork',
                'code' => '80318',
                'province_id' => 8,
                'district_id' => 69,
            ),
            151 => 
            array (
                'id' => 652,
                'name_kh' => 'ឈើខ្មៅ',
                'name_en' => 'Chheu Kmau',
                'code' => '80401',
                'province_id' => 8,
                'district_id' => 70,
            ),
            152 => 
            array (
                'id' => 653,
                'name_kh' => 'ជ្រោយតាកែវ',
                'name_en' => 'Chrouy Ta Kaev',
                'code' => '80402',
                'province_id' => 8,
                'district_id' => 70,
            ),
            153 => 
            array (
                'id' => 654,
                'name_kh' => 'កំពង់កុង',
                'name_en' => 'Kampong Kong',
                'code' => '80403',
                'province_id' => 8,
                'district_id' => 70,
            ),
            154 => 
            array (
                'id' => 655,
                'name_kh' => 'កោះធំ ‹ក›',
                'name_en' => 'Kaoh Thum Ka',
                'code' => '80404',
                'province_id' => 8,
                'district_id' => 70,
            ),
            155 => 
            array (
                'id' => 656,
                'name_kh' => 'កោះធំ ‹ខ›',
                'name_en' => 'Kaoh Thum Kha',
                'code' => '80405',
                'province_id' => 8,
                'district_id' => 70,
            ),
            156 => 
            array (
                'id' => 657,
                'name_kh' => 'លើកដែក',
                'name_en' => 'Leuk Daek',
                'code' => '80407',
                'province_id' => 8,
                'district_id' => 70,
            ),
            157 => 
            array (
                'id' => 658,
                'name_kh' => 'ពោធិ៍បាន',
                'name_en' => 'Pouthi Ban',
                'code' => '80408',
                'province_id' => 8,
                'district_id' => 70,
            ),
            158 => 
            array (
                'id' => 659,
                'name_kh' => 'ព្រែកជ្រៃ',
                'name_en' => 'Prea​ek Chrey',
                'code' => '80409',
                'province_id' => 8,
                'district_id' => 70,
            ),
            159 => 
            array (
                'id' => 660,
                'name_kh' => 'ព្រែកស្ដី',
                'name_en' => 'Preaek Sdei',
                'code' => '80410',
                'province_id' => 8,
                'district_id' => 70,
            ),
            160 => 
            array (
                'id' => 661,
                'name_kh' => 'ព្រែកថ្មី',
                'name_en' => 'Preaek Thmei',
                'code' => '80411',
                'province_id' => 8,
                'district_id' => 70,
            ),
            161 => 
            array (
                'id' => 662,
                'name_kh' => 'សំពៅពូន',
                'name_en' => 'Sampeou Poun',
                'code' => '80412',
                'province_id' => 8,
                'district_id' => 70,
            ),
            162 => 
            array (
                'id' => 663,
                'name_kh' => 'កំពង់ភ្នំ',
                'name_en' => 'Kampong Phnum',
                'code' => '80501',
                'province_id' => 8,
                'district_id' => 71,
            ),
            163 => 
            array (
                'id' => 664,
                'name_kh' => 'ក្អមសំណរ',
                'name_en' => 'Kam Samnar',
                'code' => '80502',
                'province_id' => 8,
                'district_id' => 71,
            ),
            164 => 
            array (
                'id' => 665,
                'name_kh' => 'ខ្ពបអាទាវ',
                'name_en' => 'Khpob Ateav',
                'code' => '80503',
                'province_id' => 8,
                'district_id' => 71,
            ),
            165 => 
            array (
                'id' => 666,
                'name_kh' => 'ពាមរាំង',
                'name_en' => 'Peam Reang',
                'code' => '80504',
                'province_id' => 8,
                'district_id' => 71,
            ),
            166 => 
            array (
                'id' => 667,
                'name_kh' => 'ព្រែកដាច់',
                'name_en' => 'Preaek Dach',
                'code' => '80505',
                'province_id' => 8,
                'district_id' => 71,
            ),
            167 => 
            array (
                'id' => 668,
                'name_kh' => 'ព្រែកទន្លាប់',
                'name_en' => 'Preaek Tonloab',
                'code' => '80506',
                'province_id' => 8,
                'district_id' => 71,
            ),
            168 => 
            array (
                'id' => 669,
                'name_kh' => 'សណ្ដារ',
                'name_en' => 'Sandar',
                'code' => '80507',
                'province_id' => 8,
                'district_id' => 71,
            ),
            169 => 
            array (
                'id' => 670,
                'name_kh' => 'អរិយក្សត្រ',
                'name_en' => 'Akreiy Ksatr',
                'code' => '80601',
                'province_id' => 8,
                'district_id' => 72,
            ),
            170 => 
            array (
                'id' => 671,
                'name_kh' => 'បារុង',
                'name_en' => 'Barong',
                'code' => '80602',
                'province_id' => 8,
                'district_id' => 72,
            ),
            171 => 
            array (
                'id' => 672,
                'name_kh' => 'បឹងគ្រំ',
                'name_en' => 'Boeng Krum',
                'code' => '80603',
                'province_id' => 8,
                'district_id' => 72,
            ),
            172 => 
            array (
                'id' => 673,
                'name_kh' => 'កោះកែវ',
                'name_en' => 'Kaoh Kaev',
                'code' => '80604',
                'province_id' => 8,
                'district_id' => 72,
            ),
            173 => 
            array (
                'id' => 674,
                'name_kh' => 'កោះរះ',
                'name_en' => 'Kaoh Reah',
                'code' => '80605',
                'province_id' => 8,
                'district_id' => 72,
            ),
            174 => 
            array (
                'id' => 675,
                'name_kh' => 'ល្វាសរ',
                'name_en' => 'Lvea Sar',
                'code' => '80606',
                'province_id' => 8,
                'district_id' => 72,
            ),
            175 => 
            array (
                'id' => 676,
                'name_kh' => 'ពាមឧកញ៉ាអុង',
                'name_en' => 'Peam Oknha Ong',
                'code' => '80607',
                'province_id' => 8,
                'district_id' => 72,
            ),
            176 => 
            array (
                'id' => 677,
                'name_kh' => 'ភូមិធំ',
                'name_en' => 'Phum Thum',
                'code' => '80608',
                'province_id' => 8,
                'district_id' => 72,
            ),
            177 => 
            array (
                'id' => 678,
                'name_kh' => 'ព្រែកក្មេង',
                'name_en' => 'Preaek Kmeng',
                'code' => '80609',
                'province_id' => 8,
                'district_id' => 72,
            ),
            178 => 
            array (
                'id' => 679,
                'name_kh' => 'ព្រែករៃ',
                'name_en' => 'Preaek Rey',
                'code' => '80610',
                'province_id' => 8,
                'district_id' => 72,
            ),
            179 => 
            array (
                'id' => 680,
                'name_kh' => 'ព្រែកឫស្សី',
                'name_en' => 'Preaek Ruessei',
                'code' => '80611',
                'province_id' => 8,
                'district_id' => 72,
            ),
            180 => 
            array (
                'id' => 681,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sambuor',
                'code' => '80612',
                'province_id' => 8,
                'district_id' => 72,
            ),
            181 => 
            array (
                'id' => 682,
                'name_kh' => 'សារិកាកែវ',
                'name_en' => 'Sarikakaev',
                'code' => '80613',
                'province_id' => 8,
                'district_id' => 72,
            ),
            182 => 
            array (
                'id' => 683,
                'name_kh' => 'ថ្មគរ',
                'name_en' => 'Thma Kor',
                'code' => '80614',
                'province_id' => 8,
                'district_id' => 72,
            ),
            183 => 
            array (
                'id' => 684,
                'name_kh' => 'ទឹកឃ្លាំង',
                'name_en' => 'Tuek Khleang',
                'code' => '80615',
                'province_id' => 8,
                'district_id' => 72,
            ),
            184 => 
            array (
                'id' => 685,
                'name_kh' => 'ព្រែកអញ្ចាញ',
                'name_en' => 'Preaek Anhchanh',
                'code' => '80703',
                'province_id' => 8,
                'district_id' => 73,
            ),
            185 => 
            array (
                'id' => 686,
                'name_kh' => 'ព្រែកដំបង',
                'name_en' => 'Preaek Dambang',
                'code' => '80704',
                'province_id' => 8,
                'district_id' => 73,
            ),
            186 => 
            array (
                'id' => 687,
                'name_kh' => 'រកាកោង ទី ១',
                'name_en' => 'Roka Kong Ti Muoy',
                'code' => '80707',
                'province_id' => 8,
                'district_id' => 73,
            ),
            187 => 
            array (
                'id' => 688,
                'name_kh' => 'រកាកោង ទី ២',
                'name_en' => 'Roka Kong Ti Pir',
                'code' => '80708',
                'province_id' => 8,
                'district_id' => 73,
            ),
            188 => 
            array (
                'id' => 689,
                'name_kh' => 'ឫស្សីជ្រោយ',
                'name_en' => 'Ruessei Chrouy',
                'code' => '80709',
                'province_id' => 8,
                'district_id' => 73,
            ),
            189 => 
            array (
                'id' => 690,
                'name_kh' => 'សំបួរមាស',
                'name_en' => 'Sambuor Meas',
                'code' => '80710',
                'province_id' => 8,
                'district_id' => 73,
            ),
            190 => 
            array (
                'id' => 691,
                'name_kh' => 'ស្វាយអំពារ',
                'name_en' => 'Svay Ampear',
                'code' => '80711',
                'province_id' => 8,
                'district_id' => 73,
            ),
            191 => 
            array (
                'id' => 692,
                'name_kh' => 'បែកចាន',
                'name_en' => 'Baek Chan',
                'code' => '80801',
                'province_id' => 8,
                'district_id' => 74,
            ),
            192 => 
            array (
                'id' => 693,
                'name_kh' => 'ឆក់ឈើនាង',
                'name_en' => 'Chhak Chheu Neang',
                'code' => '80803',
                'province_id' => 8,
                'district_id' => 74,
            ),
            193 => 
            array (
                'id' => 694,
                'name_kh' => 'ដំណាក់អំពិល',
                'name_en' => 'Damnak Ampil',
                'code' => '80804',
                'province_id' => 8,
                'district_id' => 74,
            ),
            194 => 
            array (
                'id' => 695,
                'name_kh' => 'ក្រាំងម្កាក់',
                'name_en' => 'Krang Mkak',
                'code' => '80807',
                'province_id' => 8,
                'district_id' => 74,
            ),
            195 => 
            array (
                'id' => 696,
                'name_kh' => 'លំហាច',
                'name_en' => 'Lumhach',
                'code' => '80808',
                'province_id' => 8,
                'district_id' => 74,
            ),
            196 => 
            array (
                'id' => 697,
                'name_kh' => 'ម្កាក់',
                'name_en' => 'Mkak',
                'code' => '80809',
                'province_id' => 8,
                'district_id' => 74,
            ),
            197 => 
            array (
                'id' => 698,
                'name_kh' => 'ពើក',
                'name_en' => 'Peuk',
                'code' => '80811',
                'province_id' => 8,
                'district_id' => 74,
            ),
            198 => 
            array (
                'id' => 699,
                'name_kh' => 'ព្រៃពួច',
                'name_en' => 'Prey Puoch',
                'code' => '80813',
                'province_id' => 8,
                'district_id' => 74,
            ),
            199 => 
            array (
                'id' => 700,
                'name_kh' => 'សំរោងលើ',
                'name_en' => 'Samraong Leu',
                'code' => '80814',
                'province_id' => 8,
                'district_id' => 74,
            ),
            200 => 
            array (
                'id' => 701,
                'name_kh' => 'ទួលព្រេជ',
                'name_en' => 'Tuol Prech',
                'code' => '80816',
                'province_id' => 8,
                'district_id' => 74,
            ),
            201 => 
            array (
                'id' => 702,
                'name_kh' => 'ឈ្វាំង',
                'name_en' => 'Chhveang',
                'code' => '80901',
                'province_id' => 8,
                'district_id' => 75,
            ),
            202 => 
            array (
                'id' => 703,
                'name_kh' => 'ជ្រៃលាស់',
                'name_en' => 'Chrey Loas',
                'code' => '80902',
                'province_id' => 8,
                'district_id' => 75,
            ),
            203 => 
            array (
                'id' => 704,
                'name_kh' => 'កំពង់ហ្លួង',
                'name_en' => 'Kampong Luong',
                'code' => '80903',
                'province_id' => 8,
                'district_id' => 75,
            ),
            204 => 
            array (
                'id' => 705,
                'name_kh' => 'កំពង់អុស',
                'name_en' => 'Kampong Os',
                'code' => '80904',
                'province_id' => 8,
                'district_id' => 75,
            ),
            205 => 
            array (
                'id' => 706,
                'name_kh' => 'កោះចិន',
                'name_en' => 'Kaoh Chen',
                'code' => '80905',
                'province_id' => 8,
                'district_id' => 75,
            ),
            206 => 
            array (
                'id' => 707,
                'name_kh' => 'ភ្នំបាត',
                'name_en' => 'Phnum Bat',
                'code' => '80906',
                'province_id' => 8,
                'district_id' => 75,
            ),
            207 => 
            array (
                'id' => 708,
                'name_kh' => 'ពញាឮ',
                'name_en' => 'Ponhea Lueu',
                'code' => '80907',
                'province_id' => 8,
                'district_id' => 75,
            ),
            208 => 
            array (
                'id' => 709,
                'name_kh' => 'ព្រែកតាទែន',
                'name_en' => 'Preaek Ta Teaen',
                'code' => '80910',
                'province_id' => 8,
                'district_id' => 75,
            ),
            209 => 
            array (
                'id' => 710,
                'name_kh' => 'ផ្សារដែក',
                'name_en' => 'Phsar Daek',
                'code' => '80911',
                'province_id' => 8,
                'district_id' => 75,
            ),
            210 => 
            array (
                'id' => 711,
                'name_kh' => 'ទំនប់ធំ',
                'name_en' => 'Tumnob Thum',
                'code' => '80913',
                'province_id' => 8,
                'district_id' => 75,
            ),
            211 => 
            array (
                'id' => 712,
                'name_kh' => 'វិហារហ្លួង',
                'name_en' => 'Vihear Luong',
                'code' => '80914',
                'province_id' => 8,
                'district_id' => 75,
            ),
            212 => 
            array (
                'id' => 713,
                'name_kh' => 'ខ្ពប',
                'name_en' => 'Khpob',
                'code' => '81001',
                'province_id' => 8,
                'district_id' => 76,
            ),
            213 => 
            array (
                'id' => 714,
                'name_kh' => 'កោះខែល',
                'name_en' => 'Kaoh Khael',
                'code' => '81003',
                'province_id' => 8,
                'district_id' => 76,
            ),
            214 => 
            array (
                'id' => 715,
                'name_kh' => 'កោះខ្សាច់ទន្លា',
                'name_en' => 'Kaoh Khsach Tonlea',
                'code' => '81004',
                'province_id' => 8,
                'district_id' => 76,
            ),
            215 => 
            array (
                'id' => 716,
                'name_kh' => 'ក្រាំងយ៉ូវ',
                'name_en' => 'Krang Yov',
                'code' => '81005',
                'province_id' => 8,
                'district_id' => 76,
            ),
            216 => 
            array (
                'id' => 717,
                'name_kh' => 'ប្រាសាទ',
                'name_en' => 'Prasat',
                'code' => '81006',
                'province_id' => 8,
                'district_id' => 76,
            ),
            217 => 
            array (
                'id' => 718,
                'name_kh' => 'ព្រែកអំបិល',
                'name_en' => 'Preaek Ambel',
                'code' => '81007',
                'province_id' => 8,
                'district_id' => 76,
            ),
            218 => 
            array (
                'id' => 719,
                'name_kh' => 'ព្រែកគយ',
                'name_en' => 'Preaek Koy',
                'code' => '81008',
                'province_id' => 8,
                'district_id' => 76,
            ),
            219 => 
            array (
                'id' => 720,
                'name_kh' => 'ស្អាងភ្នំ',
                'name_en' => 'Sang Phnum',
                'code' => '81010',
                'province_id' => 8,
                'district_id' => 76,
            ),
            220 => 
            array (
                'id' => 721,
                'name_kh' => 'ស្វាយប្រទាល',
                'name_en' => 'Svay Prateal',
                'code' => '81012',
                'province_id' => 8,
                'district_id' => 76,
            ),
            221 => 
            array (
                'id' => 722,
                'name_kh' => 'តាលន់',
                'name_en' => 'Ta Lon',
                'code' => '81014',
                'province_id' => 8,
                'district_id' => 76,
            ),
            222 => 
            array (
                'id' => 723,
                'name_kh' => 'ត្រើយស្លា',
                'name_en' => 'Traeuy Sla',
                'code' => '81015',
                'province_id' => 8,
                'district_id' => 76,
            ),
            223 => 
            array (
                'id' => 724,
                'name_kh' => 'ទឹកវិល',
                'name_en' => 'Tuek Vil',
                'code' => '81016',
                'province_id' => 8,
                'district_id' => 76,
            ),
            224 => 
            array (
                'id' => 725,
                'name_kh' => 'តាក្ដុល',
                'name_en' => 'Ta Kdol',
                'code' => '81101',
                'province_id' => 8,
                'district_id' => 77,
            ),
            225 => 
            array (
                'id' => 726,
                'name_kh' => 'ព្រែកឫស្សី',
                'name_en' => 'Prek Ruessey',
                'code' => '81102',
                'province_id' => 8,
                'district_id' => 77,
            ),
            226 => 
            array (
                'id' => 727,
                'name_kh' => 'ដើមមៀន',
                'name_en' => 'Doeum Mien',
                'code' => '81103',
                'province_id' => 8,
                'district_id' => 77,
            ),
            227 => 
            array (
                'id' => 728,
                'name_kh' => 'តាខ្មៅ',
                'name_en' => 'Ta Khmao',
                'code' => '81104',
                'province_id' => 8,
                'district_id' => 77,
            ),
            228 => 
            array (
                'id' => 729,
                'name_kh' => 'ព្រែកហូរ',
                'name_en' => 'Prek Ho',
                'code' => '81105',
                'province_id' => 8,
                'district_id' => 77,
            ),
            229 => 
            array (
                'id' => 730,
                'name_kh' => 'កំពង់សំណាញ់',
                'name_en' => 'Kampong Samnanh',
                'code' => '81106',
                'province_id' => 8,
                'district_id' => 77,
            ),
            230 => 
            array (
                'id' => 731,
                'name_kh' => 'ស្វាយរលំ',
                'name_en' => 'Svay Rolum',
                'code' => '81107',
                'province_id' => 8,
                'district_id' => 77,
            ),
            231 => 
            array (
                'id' => 732,
                'name_kh' => 'កោះអន្លង់ចិន',
                'name_en' => 'Kaoh Anlong Chen',
                'code' => '81108',
                'province_id' => 8,
                'district_id' => 77,
            ),
            232 => 
            array (
                'id' => 733,
                'name_kh' => 'សិត្បូ',
                'name_en' => 'Setbou',
                'code' => '81109',
                'province_id' => 8,
                'district_id' => 77,
            ),
            233 => 
            array (
                'id' => 734,
                'name_kh' => 'រកាខ្ពស់',
                'name_en' => 'Roka Khpos',
                'code' => '81110',
                'province_id' => 8,
                'district_id' => 77,
            ),
            234 => 
            array (
                'id' => 735,
                'name_kh' => 'អណ្ដូងទឹក',
                'name_en' => 'Andoung Tuek',
                'code' => '90101',
                'province_id' => 9,
                'district_id' => 78,
            ),
            235 => 
            array (
                'id' => 736,
                'name_kh' => 'កណ្ដោល',
                'name_en' => 'Kandaol',
                'code' => '90102',
                'province_id' => 9,
                'district_id' => 78,
            ),
            236 => 
            array (
                'id' => 737,
                'name_kh' => 'តានូន',
                'name_en' => 'Ta Noun',
                'code' => '90103',
                'province_id' => 9,
                'district_id' => 78,
            ),
            237 => 
            array (
                'id' => 738,
                'name_kh' => 'ថ្មស',
                'name_en' => 'Thma Sa',
                'code' => '90104',
                'province_id' => 9,
                'district_id' => 78,
            ),
            238 => 
            array (
                'id' => 739,
                'name_kh' => 'កោះស្ដេច',
                'name_en' => 'Kaoh Sdach',
                'code' => '90201',
                'province_id' => 9,
                'district_id' => 79,
            ),
            239 => 
            array (
                'id' => 740,
                'name_kh' => 'ភ្ញីមាស',
                'name_en' => 'Phnhi Meas',
                'code' => '90202',
                'province_id' => 9,
                'district_id' => 79,
            ),
            240 => 
            array (
                'id' => 741,
                'name_kh' => 'ព្រែកខ្សាច់',
                'name_en' => 'Preaek Khsach',
                'code' => '90203',
                'province_id' => 9,
                'district_id' => 79,
            ),
            241 => 
            array (
                'id' => 742,
                'name_kh' => 'ជ្រោយប្រស់',
                'name_en' => 'Chrouy Pras',
                'code' => '90301',
                'province_id' => 9,
                'district_id' => 80,
            ),
            242 => 
            array (
                'id' => 743,
                'name_kh' => 'កោះកាពិ',
                'name_en' => 'Kaoh Kapi',
                'code' => '90302',
                'province_id' => 9,
                'district_id' => 80,
            ),
            243 => 
            array (
                'id' => 744,
                'name_kh' => 'តាតៃក្រោម',
                'name_en' => 'Ta Tai Kraom',
                'code' => '90303',
                'province_id' => 9,
                'district_id' => 80,
            ),
            244 => 
            array (
                'id' => 745,
                'name_kh' => 'ត្រពាំងរូង',
                'name_en' => 'Trapeang Rung',
                'code' => '90304',
                'province_id' => 9,
                'district_id' => 80,
            ),
            245 => 
            array (
                'id' => 746,
                'name_kh' => 'ស្មាច់មានជ័យ',
                'name_en' => 'Smach Mean Chey',
                'code' => '90401',
                'province_id' => 9,
                'district_id' => 81,
            ),
            246 => 
            array (
                'id' => 747,
                'name_kh' => 'ដងទង់',
                'name_en' => 'Dang Tong',
                'code' => '90402',
                'province_id' => 9,
                'district_id' => 81,
            ),
            247 => 
            array (
                'id' => 748,
                'name_kh' => 'ស្ទឹងវែង',
                'name_en' => 'Stueng Veaeng',
                'code' => '90403',
                'province_id' => 9,
                'district_id' => 81,
            ),
            248 => 
            array (
                'id' => 749,
                'name_kh' => 'ប៉ាក់ខ្លង',
                'name_en' => 'Pak Khlang',
                'code' => '90501',
                'province_id' => 9,
                'district_id' => 82,
            ),
            249 => 
            array (
                'id' => 750,
                'name_kh' => 'ពាមក្រសោប',
                'name_en' => 'Peam Krasaob',
                'code' => '90502',
                'province_id' => 9,
                'district_id' => 82,
            ),
            250 => 
            array (
                'id' => 751,
                'name_kh' => 'ទួលគគីរ',
                'name_en' => 'Tuol Kokir',
                'code' => '90503',
                'province_id' => 9,
                'district_id' => 82,
            ),
            251 => 
            array (
                'id' => 752,
                'name_kh' => 'បឹងព្រាវ',
                'name_en' => 'Boeng Preav',
                'code' => '90601',
                'province_id' => 9,
                'district_id' => 83,
            ),
            252 => 
            array (
                'id' => 753,
                'name_kh' => 'ជី ខ ក្រោម',
                'name_en' => 'Chi Kha Kraom',
                'code' => '90602',
                'province_id' => 9,
                'district_id' => 83,
            ),
            253 => 
            array (
                'id' => 754,
                'name_kh' => 'ជី ខ លើ',
                'name_en' => 'Chi kha Leu',
                'code' => '90603',
                'province_id' => 9,
                'district_id' => 83,
            ),
            254 => 
            array (
                'id' => 755,
                'name_kh' => 'ជ្រោយស្វាយ',
                'name_en' => 'Chrouy Svay',
                'code' => '90604',
                'province_id' => 9,
                'district_id' => 83,
            ),
            255 => 
            array (
                'id' => 756,
                'name_kh' => 'ដងពែង',
                'name_en' => 'Dang Peaeng',
                'code' => '90605',
                'province_id' => 9,
                'district_id' => 83,
            ),
            256 => 
            array (
                'id' => 757,
                'name_kh' => 'ស្រែអំបិល',
                'name_en' => 'Srae Ambel',
                'code' => '90606',
                'province_id' => 9,
                'district_id' => 83,
            ),
            257 => 
            array (
                'id' => 758,
                'name_kh' => 'តាទៃលើ',
                'name_en' => 'Ta Tey Leu',
                'code' => '90701',
                'province_id' => 9,
                'district_id' => 84,
            ),
            258 => 
            array (
                'id' => 759,
                'name_kh' => 'ប្រឡាយ',
                'name_en' => 'Pralay',
                'code' => '90702',
                'province_id' => 9,
                'district_id' => 84,
            ),
            259 => 
            array (
                'id' => 760,
                'name_kh' => 'ជំនាប់',
                'name_en' => 'Chumnoab',
                'code' => '90703',
                'province_id' => 9,
                'district_id' => 84,
            ),
            260 => 
            array (
                'id' => 761,
                'name_kh' => 'ឫស្សីជ្រុំ',
                'name_en' => 'Ruessei Chrum',
                'code' => '90704',
                'province_id' => 9,
                'district_id' => 84,
            ),
            261 => 
            array (
                'id' => 762,
                'name_kh' => 'ជីផាត',
                'name_en' => 'Chi Phat',
                'code' => '90705',
                'province_id' => 9,
                'district_id' => 84,
            ),
            262 => 
            array (
                'id' => 763,
                'name_kh' => 'ថ្មដូនពៅ',
                'name_en' => 'Thma Doun Pov',
                'code' => '90706',
                'province_id' => 9,
                'district_id' => 84,
            ),
            263 => 
            array (
                'id' => 764,
                'name_kh' => 'ឆ្លូង',
                'name_en' => 'Chhloung',
                'code' => '100101',
                'province_id' => 10,
                'district_id' => 85,
            ),
            264 => 
            array (
                'id' => 765,
                'name_kh' => 'ដំរីផុង',
                'name_en' => 'Damrei Phong',
                'code' => '100102',
                'province_id' => 10,
                'district_id' => 85,
            ),
            265 => 
            array (
                'id' => 766,
                'name_kh' => 'ហាន់ជ័យ',
                'name_en' => 'Han Chey',
                'code' => '100103',
                'province_id' => 10,
                'district_id' => 85,
            ),
            266 => 
            array (
                'id' => 767,
                'name_kh' => 'កំពង់ដំរី',
                'name_en' => 'Kampong Damrei',
                'code' => '100104',
                'province_id' => 10,
                'district_id' => 85,
            ),
            267 => 
            array (
                'id' => 768,
                'name_kh' => 'កញ្ជរ',
                'name_en' => 'Kanhchor',
                'code' => '100105',
                'province_id' => 10,
                'district_id' => 85,
            ),
            268 => 
            array (
                'id' => 769,
                'name_kh' => 'ខ្សាច់អណ្ដែត',
                'name_en' => 'Khsach Andeth',
                'code' => '100106',
                'province_id' => 10,
                'district_id' => 85,
            ),
            269 => 
            array (
                'id' => 770,
                'name_kh' => 'ពង្រ',
                'name_en' => 'Pongro',
                'code' => '100107',
                'province_id' => 10,
                'district_id' => 85,
            ),
            270 => 
            array (
                'id' => 771,
                'name_kh' => 'ព្រែកសាម៉ាន់',
                'name_en' => 'Preaek Saman',
                'code' => '100108',
                'province_id' => 10,
                'district_id' => 85,
            ),
            271 => 
            array (
                'id' => 772,
                'name_kh' => 'កោះទ្រង់',
                'name_en' => 'Kaoh Trong',
                'code' => '100207',
                'province_id' => 10,
                'district_id' => 86,
            ),
            272 => 
            array (
                'id' => 773,
                'name_kh' => 'ក្រគរ',
                'name_en' => 'Krakor',
                'code' => '100208',
                'province_id' => 10,
                'district_id' => 86,
            ),
            273 => 
            array (
                'id' => 774,
                'name_kh' => 'ក្រចេះ',
                'name_en' => 'Kracheh',
                'code' => '100209',
                'province_id' => 10,
                'district_id' => 86,
            ),
            274 => 
            array (
                'id' => 775,
                'name_kh' => 'អូរឫស្សី',
                'name_en' => 'Ou Ruessei',
                'code' => '100210',
                'province_id' => 10,
                'district_id' => 86,
            ),
            275 => 
            array (
                'id' => 776,
                'name_kh' => 'រកាកណ្ដាល',
                'name_en' => 'Roka Kandal',
                'code' => '100211',
                'province_id' => 10,
                'district_id' => 86,
            ),
            276 => 
            array (
                'id' => 777,
                'name_kh' => 'ចំបក់',
                'name_en' => 'Chambâk',
                'code' => '100301',
                'province_id' => 10,
                'district_id' => 87,
            ),
            277 => 
            array (
                'id' => 778,
                'name_kh' => 'ជ្រោយបន្ទាយ',
                'name_en' => 'Chrouy Banteay',
                'code' => '100302',
                'province_id' => 10,
                'district_id' => 87,
            ),
            278 => 
            array (
                'id' => 779,
                'name_kh' => 'កំពង់គរ',
                'name_en' => 'Kampong Kor',
                'code' => '100303',
                'province_id' => 10,
                'district_id' => 87,
            ),
            279 => 
            array (
                'id' => 780,
                'name_kh' => 'កោះតាស៊ុយ',
                'name_en' => 'Koh Ta Suy',
                'code' => '100304',
                'province_id' => 10,
                'district_id' => 87,
            ),
            280 => 
            array (
                'id' => 781,
                'name_kh' => 'ព្រែកប្រសព្វ',
                'name_en' => 'Preaek Prasab',
                'code' => '100305',
                'province_id' => 10,
                'district_id' => 87,
            ),
            281 => 
            array (
                'id' => 782,
                'name_kh' => 'ឫស្សីកែវ',
                'name_en' => 'Russey Keo',
                'code' => '100306',
                'province_id' => 10,
                'district_id' => 87,
            ),
            282 => 
            array (
                'id' => 783,
                'name_kh' => 'សោប',
                'name_en' => 'Saob',
                'code' => '100307',
                'province_id' => 10,
                'district_id' => 87,
            ),
            283 => 
            array (
                'id' => 784,
                'name_kh' => 'តាម៉ៅ',
                'name_en' => 'Ta Mao',
                'code' => '100308',
                'province_id' => 10,
                'district_id' => 87,
            ),
            284 => 
            array (
                'id' => 785,
                'name_kh' => 'បឹងចារ',
                'name_en' => 'Boeng Char',
                'code' => '100401',
                'province_id' => 10,
                'district_id' => 88,
            ),
            285 => 
            array (
                'id' => 786,
                'name_kh' => 'កំពង់ចាម',
                'name_en' => 'Kampong Cham',
                'code' => '100402',
                'province_id' => 10,
                'district_id' => 88,
            ),
            286 => 
            array (
                'id' => 787,
                'name_kh' => 'ក្បាលដំរី',
                'name_en' => 'Kbal Damrei',
                'code' => '100403',
                'province_id' => 10,
                'district_id' => 88,
            ),
            287 => 
            array (
                'id' => 788,
                'name_kh' => 'កោះខ្ញែរ',
                'name_en' => 'Kaoh Khnhaer',
                'code' => '100404',
                'province_id' => 10,
                'district_id' => 88,
            ),
            288 => 
            array (
                'id' => 789,
                'name_kh' => 'អូរគ្រៀង',
                'name_en' => 'Ou Krieng',
                'code' => '100405',
                'province_id' => 10,
                'district_id' => 88,
            ),
            289 => 
            array (
                'id' => 790,
                'name_kh' => 'រលួសមានជ័យ',
                'name_en' => 'Roluos Mean Chey',
                'code' => '100406',
                'province_id' => 10,
                'district_id' => 88,
            ),
            290 => 
            array (
                'id' => 791,
                'name_kh' => 'សំបូរ',
                'name_en' => 'Sambour',
                'code' => '100407',
                'province_id' => 10,
                'district_id' => 88,
            ),
            291 => 
            array (
                'id' => 792,
                'name_kh' => 'សណ្ដាន់',
                'name_en' => 'Sandan',
                'code' => '100408',
                'province_id' => 10,
                'district_id' => 88,
            ),
            292 => 
            array (
                'id' => 793,
                'name_kh' => 'ស្រែជិះ',
                'name_en' => 'Srae Chis',
                'code' => '100409',
                'province_id' => 10,
                'district_id' => 88,
            ),
            293 => 
            array (
                'id' => 794,
                'name_kh' => 'វឌ្ឍនៈ',
                'name_en' => 'Voadthonak',
                'code' => '100410',
                'province_id' => 10,
                'district_id' => 88,
            ),
            294 => 
            array (
                'id' => 795,
                'name_kh' => 'ឃ្សឹម',
                'name_en' => 'Khsuem',
                'code' => '100501',
                'province_id' => 10,
                'district_id' => 89,
            ),
            295 => 
            array (
                'id' => 796,
                'name_kh' => 'ពីរធ្នូ',
                'name_en' => 'Pir Thnu',
                'code' => '100502',
                'province_id' => 10,
                'district_id' => 89,
            ),
            296 => 
            array (
                'id' => 797,
                'name_kh' => 'ស្នួល',
                'name_en' => 'Snuol',
                'code' => '100503',
                'province_id' => 10,
                'district_id' => 89,
            ),
            297 => 
            array (
                'id' => 798,
                'name_kh' => 'ស្រែចារ',
                'name_en' => 'Srae Char',
                'code' => '100504',
                'province_id' => 10,
                'district_id' => 89,
            ),
            298 => 
            array (
                'id' => 799,
                'name_kh' => 'ស្វាយជ្រះ',
                'name_en' => 'Svay Chreah',
                'code' => '100505',
                'province_id' => 10,
                'district_id' => 89,
            ),
            299 => 
            array (
                'id' => 800,
                'name_kh' => 'គ្រញូងសែនជ័យ',
                'name_en' => 'Kronhoung Saen Chey',
                'code' => '100506',
                'province_id' => 10,
                'district_id' => 89,
            ),
            300 => 
            array (
                'id' => 801,
                'name_kh' => 'បុសលាវ',
                'name_en' => 'Bos Leav',
                'code' => '100601',
                'province_id' => 10,
                'district_id' => 90,
            ),
            301 => 
            array (
                'id' => 802,
                'name_kh' => 'ចង្ក្រង់',
                'name_en' => 'Changkrang',
                'code' => '100602',
                'province_id' => 10,
                'district_id' => 90,
            ),
            302 => 
            array (
                'id' => 803,
                'name_kh' => 'ដារ',
                'name_en' => 'Dar',
                'code' => '100603',
                'province_id' => 10,
                'district_id' => 90,
            ),
            303 => 
            array (
                'id' => 804,
                'name_kh' => 'កន្ទួត',
                'name_en' => 'Kantuot',
                'code' => '100604',
                'province_id' => 10,
                'district_id' => 90,
            ),
            304 => 
            array (
                'id' => 805,
                'name_kh' => 'គោលាប់',
                'name_en' => 'Kou Loab',
                'code' => '100605',
                'province_id' => 10,
                'district_id' => 90,
            ),
            305 => 
            array (
                'id' => 806,
                'name_kh' => 'កោះច្រែង',
                'name_en' => 'Kaoh Chraeng',
                'code' => '100606',
                'province_id' => 10,
                'district_id' => 90,
            ),
            306 => 
            array (
                'id' => 807,
                'name_kh' => 'សំបុក',
                'name_en' => 'Sambok',
                'code' => '100607',
                'province_id' => 10,
                'district_id' => 90,
            ),
            307 => 
            array (
                'id' => 808,
                'name_kh' => 'ថ្មអណ្ដើក',
                'name_en' => 'Thma Andaeuk',
                'code' => '100608',
                'province_id' => 10,
                'district_id' => 90,
            ),
            308 => 
            array (
                'id' => 809,
                'name_kh' => 'ថ្មគ្រែ',
                'name_en' => 'Thma Kreae',
                'code' => '100609',
                'province_id' => 10,
                'district_id' => 90,
            ),
            309 => 
            array (
                'id' => 810,
                'name_kh' => 'ថ្មី',
                'name_en' => 'Thmei',
                'code' => '100610',
                'province_id' => 10,
                'district_id' => 90,
            ),
            310 => 
            array (
                'id' => 811,
                'name_kh' => 'ចុងផ្លាស់',
                'name_en' => 'Chong Phlah',
                'code' => '110101',
                'province_id' => 11,
                'district_id' => 91,
            ),
            311 => 
            array (
                'id' => 812,
                'name_kh' => 'មេម៉ង់',
                'name_en' => 'Memang',
                'code' => '110102',
                'province_id' => 11,
                'district_id' => 91,
            ),
            312 => 
            array (
                'id' => 813,
                'name_kh' => 'ស្រែឈូក',
                'name_en' => 'Srae Chhuk',
                'code' => '110103',
                'province_id' => 11,
                'district_id' => 91,
            ),
            313 => 
            array (
                'id' => 814,
                'name_kh' => 'ស្រែខ្ទុម',
                'name_en' => 'Srae Khtum',
                'code' => '110104',
                'province_id' => 11,
                'district_id' => 91,
            ),
            314 => 
            array (
                'id' => 815,
                'name_kh' => 'ស្រែព្រះ',
                'name_en' => 'Srae Preah',
                'code' => '110105',
                'province_id' => 11,
                'district_id' => 91,
            ),
            315 => 
            array (
                'id' => 816,
                'name_kh' => 'ណងឃីលិក',
                'name_en' => 'Nang Khi Lik',
                'code' => '110201',
                'province_id' => 11,
                'district_id' => 92,
            ),
            316 => 
            array (
                'id' => 817,
                'name_kh' => 'អ បួនលើ',
                'name_en' => 'A Buon Leu',
                'code' => '110202',
                'province_id' => 11,
                'district_id' => 92,
            ),
            317 => 
            array (
                'id' => 818,
                'name_kh' => 'រយ៉',
                'name_en' => 'Roya',
                'code' => '110203',
                'province_id' => 11,
                'district_id' => 92,
            ),
            318 => 
            array (
                'id' => 819,
                'name_kh' => 'សុខសាន្ដ',
                'name_en' => 'Sokh Sant',
                'code' => '110204',
                'province_id' => 11,
                'district_id' => 92,
            ),
            319 => 
            array (
                'id' => 820,
                'name_kh' => 'ស្រែហ៊ុយ',
                'name_en' => 'Srae Huy',
                'code' => '110205',
                'province_id' => 11,
                'district_id' => 92,
            ),
            320 => 
            array (
                'id' => 821,
                'name_kh' => 'ស្រែសង្គម',
                'name_en' => 'Srae Sangkum',
                'code' => '110206',
                'province_id' => 11,
                'district_id' => 92,
            ),
            321 => 
            array (
                'id' => 822,
                'name_kh' => 'ដាក់ដាំ',
                'name_en' => 'Dak Dam',
                'code' => '110301',
                'province_id' => 11,
                'district_id' => 93,
            ),
            322 => 
            array (
                'id' => 823,
                'name_kh' => 'សែនមនោរម្យ',
                'name_en' => 'Saen Monourom',
                'code' => '110302',
                'province_id' => 11,
                'district_id' => 93,
            ),
            323 => 
            array (
                'id' => 824,
                'name_kh' => 'ក្រង់តេះ',
                'name_en' => 'Krang Teh',
                'code' => '110401',
                'province_id' => 11,
                'district_id' => 94,
            ),
            324 => 
            array (
                'id' => 825,
                'name_kh' => 'ពូជ្រៃ',
                'name_en' => 'Pu Chrey',
                'code' => '110402',
                'province_id' => 11,
                'district_id' => 94,
            ),
            325 => 
            array (
                'id' => 826,
                'name_kh' => 'ស្រែអំពូម',
                'name_en' => 'Srae Ampum',
                'code' => '110403',
                'province_id' => 11,
                'district_id' => 94,
            ),
            326 => 
            array (
                'id' => 827,
                'name_kh' => 'ប៊ូស្រា',
                'name_en' => 'Bu Sra',
                'code' => '110404',
                'province_id' => 11,
                'district_id' => 94,
            ),
            327 => 
            array (
                'id' => 828,
                'name_kh' => 'មនោរម្យ',
                'name_en' => 'Monourom',
                'code' => '110501',
                'province_id' => 11,
                'district_id' => 95,
            ),
            328 => 
            array (
                'id' => 829,
                'name_kh' => 'សុខដុម',
                'name_en' => 'Sokh Dom',
                'code' => '110502',
                'province_id' => 11,
                'district_id' => 95,
            ),
            329 => 
            array (
                'id' => 830,
                'name_kh' => 'ស្ពានមានជ័យ',
                'name_en' => 'Spean Mean Chey',
                'code' => '110503',
                'province_id' => 11,
                'district_id' => 95,
            ),
            330 => 
            array (
                'id' => 831,
                'name_kh' => 'រមនា',
                'name_en' => 'Romonea',
                'code' => '110504',
                'province_id' => 11,
                'district_id' => 95,
            ),
            331 => 
            array (
                'id' => 832,
                'name_kh' => 'ទន្លេបាសាក់',
                'name_en' => 'Tonle Basak',
                'code' => '120101',
                'province_id' => 12,
                'district_id' => 96,
            ),
            332 => 
            array (
                'id' => 833,
                'name_kh' => 'ទួលទំពូងទី ២',
                'name_en' => 'Tuol Tumpung Ti Pir',
                'code' => '120109',
                'province_id' => 12,
                'district_id' => 96,
            ),
            333 => 
            array (
                'id' => 834,
                'name_kh' => 'ទួលទំពូងទី ១',
                'name_en' => 'Tuol Tumpung Ti Muoy',
                'code' => '120110',
                'province_id' => 12,
                'district_id' => 96,
            ),
            334 => 
            array (
                'id' => 835,
                'name_kh' => 'បឹងត្របែក',
                'name_en' => 'Boeng Trabaek',
                'code' => '120111',
                'province_id' => 12,
                'district_id' => 96,
            ),
            335 => 
            array (
                'id' => 836,
                'name_kh' => 'ផ្សារដើមថ្កូវ',
                'name_en' => 'Phsar Daeum Thkov',
                'code' => '120112',
                'province_id' => 12,
                'district_id' => 96,
            ),
            336 => 
            array (
                'id' => 837,
                'name_kh' => 'ផ្សារថ្មីទី ១',
                'name_en' => 'Phsar Thmei Ti Muoy',
                'code' => '120201',
                'province_id' => 12,
                'district_id' => 97,
            ),
            337 => 
            array (
                'id' => 838,
                'name_kh' => 'ផ្សារថ្មីទី ២',
                'name_en' => 'Phsar Thmei Ti Pir',
                'code' => '120202',
                'province_id' => 12,
                'district_id' => 97,
            ),
            338 => 
            array (
                'id' => 839,
                'name_kh' => 'ផ្សារថ្មីទី ៣',
                'name_en' => 'Phsar Thmei Ti Bei',
                'code' => '120203',
                'province_id' => 12,
                'district_id' => 97,
            ),
            339 => 
            array (
                'id' => 840,
                'name_kh' => 'បឹងរាំង',
                'name_en' => 'Boeng Reang',
                'code' => '120204',
                'province_id' => 12,
                'district_id' => 97,
            ),
            340 => 
            array (
                'id' => 841,
                'name_kh' => 'ផ្សារកណ្ដាលទី១',
                'name_en' => 'Phsar Kandal Ti Muoy',
                'code' => '120205',
                'province_id' => 12,
                'district_id' => 97,
            ),
            341 => 
            array (
                'id' => 842,
                'name_kh' => 'ផ្សារកណ្ដាលទី២',
                'name_en' => 'Phsar Kandal Ti Pir',
                'code' => '120206',
                'province_id' => 12,
                'district_id' => 97,
            ),
            342 => 
            array (
                'id' => 843,
                'name_kh' => 'ចតុមុខ',
                'name_en' => 'Chakto Mukh',
                'code' => '120207',
                'province_id' => 12,
                'district_id' => 97,
            ),
            343 => 
            array (
                'id' => 844,
                'name_kh' => 'ជ័យជំនះ',
                'name_en' => 'Chey Chummeah',
                'code' => '120208',
                'province_id' => 12,
                'district_id' => 97,
            ),
            344 => 
            array (
                'id' => 845,
                'name_kh' => 'ផ្សារចាស់',
                'name_en' => 'Phsar Chas',
                'code' => '120209',
                'province_id' => 12,
                'district_id' => 97,
            ),
            345 => 
            array (
                'id' => 846,
                'name_kh' => 'ស្រះចក',
                'name_en' => 'Srah Chak',
                'code' => '120210',
                'province_id' => 12,
                'district_id' => 97,
            ),
            346 => 
            array (
                'id' => 847,
                'name_kh' => 'វត្ដភ្នំ',
                'name_en' => 'Voat Phnum',
                'code' => '120211',
                'province_id' => 12,
                'district_id' => 97,
            ),
            347 => 
            array (
                'id' => 848,
                'name_kh' => 'អូរឫស្សីទី ១',
                'name_en' => 'Ou Ruessei Ti Muoy',
                'code' => '120301',
                'province_id' => 12,
                'district_id' => 98,
            ),
            348 => 
            array (
                'id' => 849,
                'name_kh' => 'អូរឫស្សីទី ២',
                'name_en' => 'Ou Ruessei Ti Pir',
                'code' => '120302',
                'province_id' => 12,
                'district_id' => 98,
            ),
            349 => 
            array (
                'id' => 850,
                'name_kh' => 'អូរឫស្សីទី ៣',
                'name_en' => 'Ou Ruessei Ti Bei',
                'code' => '120303',
                'province_id' => 12,
                'district_id' => 98,
            ),
            350 => 
            array (
                'id' => 851,
                'name_kh' => 'អូរឫស្សីទី ៤',
                'name_en' => 'Ou Ruessei Ti Buon',
                'code' => '120304',
                'province_id' => 12,
                'district_id' => 98,
            ),
            351 => 
            array (
                'id' => 852,
                'name_kh' => 'មនោរម្យ',
                'name_en' => 'Monourom',
                'code' => '120305',
                'province_id' => 12,
                'district_id' => 98,
            ),
            352 => 
            array (
                'id' => 853,
                'name_kh' => 'មិត្ដភាព',
                'name_en' => 'Mittapheap',
                'code' => '120306',
                'province_id' => 12,
                'district_id' => 98,
            ),
            353 => 
            array (
                'id' => 854,
                'name_kh' => 'វាលវង់',
                'name_en' => 'Veal Vong',
                'code' => '120307',
                'province_id' => 12,
                'district_id' => 98,
            ),
            354 => 
            array (
                'id' => 855,
                'name_kh' => 'បឹងព្រលឹត',
                'name_en' => 'Boeng Proluet',
                'code' => '120308',
                'province_id' => 12,
                'district_id' => 98,
            ),
            355 => 
            array (
                'id' => 856,
                'name_kh' => 'ផ្សារដេប៉ូទី ១',
                'name_en' => 'Phsar Depou Ti Muoy',
                'code' => '120401',
                'province_id' => 12,
                'district_id' => 99,
            ),
            356 => 
            array (
                'id' => 857,
                'name_kh' => 'ផ្សារដេប៉ូទី ២',
                'name_en' => 'Phsar Depou Ti Pir',
                'code' => '120402',
                'province_id' => 12,
                'district_id' => 99,
            ),
            357 => 
            array (
                'id' => 858,
                'name_kh' => 'ផ្សារដេប៉ូទី ៣',
                'name_en' => 'Phsar Depou Ti Bei',
                'code' => '120403',
                'province_id' => 12,
                'district_id' => 99,
            ),
            358 => 
            array (
                'id' => 859,
                'name_kh' => 'ទឹកល្អក់ទី ១',
                'name_en' => 'Tuek Lak Ti Muoy',
                'code' => '120404',
                'province_id' => 12,
                'district_id' => 99,
            ),
            359 => 
            array (
                'id' => 860,
                'name_kh' => 'ទឹកល្អក់ទី ២',
                'name_en' => 'Tuek Lak Ti Pir',
                'code' => '120405',
                'province_id' => 12,
                'district_id' => 99,
            ),
            360 => 
            array (
                'id' => 861,
                'name_kh' => 'ទឹកល្អក់ទី ៣',
                'name_en' => 'Tuek Lak Ti Bei',
                'code' => '120406',
                'province_id' => 12,
                'district_id' => 99,
            ),
            361 => 
            array (
                'id' => 862,
                'name_kh' => 'បឹងកក់ទី ១',
                'name_en' => 'Boeng Kak Ti Muoy',
                'code' => '120407',
                'province_id' => 12,
                'district_id' => 99,
            ),
            362 => 
            array (
                'id' => 863,
                'name_kh' => 'បឹងកក់ទី ២',
                'name_en' => 'Boeng Kak Ti Pir',
                'code' => '120408',
                'province_id' => 12,
                'district_id' => 99,
            ),
            363 => 
            array (
                'id' => 864,
                'name_kh' => 'ផ្សារដើមគរ',
                'name_en' => 'Phsar Daeum Kor',
                'code' => '120409',
                'province_id' => 12,
                'district_id' => 99,
            ),
            364 => 
            array (
                'id' => 865,
                'name_kh' => 'បឹងសាឡាង',
                'name_en' => 'Boeng Salang',
                'code' => '120410',
                'province_id' => 12,
                'district_id' => 99,
            ),
            365 => 
            array (
                'id' => 866,
                'name_kh' => 'ដង្កោ',
                'name_en' => 'Dangkao',
                'code' => '120501',
                'province_id' => 12,
                'district_id' => 100,
            ),
            366 => 
            array (
                'id' => 867,
                'name_kh' => 'ពងទឹក',
                'name_en' => 'Pong Tuek',
                'code' => '120507',
                'province_id' => 12,
                'district_id' => 100,
            ),
            367 => 
            array (
                'id' => 868,
                'name_kh' => 'ព្រៃវែង',
                'name_en' => 'Prey Veaeng',
                'code' => '120508',
                'province_id' => 12,
                'district_id' => 100,
            ),
            368 => 
            array (
                'id' => 869,
                'name_kh' => 'ព្រៃស',
                'name_en' => 'Prey Sa',
                'code' => '120510',
                'province_id' => 12,
                'district_id' => 100,
            ),
            369 => 
            array (
                'id' => 870,
                'name_kh' => 'ក្រាំងពង្រ',
                'name_en' => 'Krang Pongro',
                'code' => '120512',
                'province_id' => 12,
                'district_id' => 100,
            ),
            370 => 
            array (
                'id' => 871,
                'name_kh' => 'សាក់សំពៅ',
                'name_en' => 'Sak Sampov',
                'code' => '120514',
                'province_id' => 12,
                'district_id' => 100,
            ),
            371 => 
            array (
                'id' => 872,
                'name_kh' => 'ជើងឯក',
                'name_en' => 'Cheung Aek',
                'code' => '120515',
                'province_id' => 12,
                'district_id' => 100,
            ),
            372 => 
            array (
                'id' => 873,
                'name_kh' => 'គងនយ',
                'name_en' => 'Kong Noy',
                'code' => '120516',
                'province_id' => 12,
                'district_id' => 100,
            ),
            373 => 
            array (
                'id' => 874,
                'name_kh' => 'ព្រែកកំពឹស',
                'name_en' => 'Preaek Kampues',
                'code' => '120517',
                'province_id' => 12,
                'district_id' => 100,
            ),
            374 => 
            array (
                'id' => 875,
                'name_kh' => 'រលួស',
                'name_en' => 'Roluos',
                'code' => '120518',
                'province_id' => 12,
                'district_id' => 100,
            ),
            375 => 
            array (
                'id' => 876,
                'name_kh' => 'ស្ពានថ្ម',
                'name_en' => 'Spean Thma',
                'code' => '120519',
                'province_id' => 12,
                'district_id' => 100,
            ),
            376 => 
            array (
                'id' => 877,
                'name_kh' => 'ទៀន',
                'name_en' => 'Tien',
                'code' => '120520',
                'province_id' => 12,
                'district_id' => 100,
            ),
            377 => 
            array (
                'id' => 878,
                'name_kh' => 'ចាក់អង្រែលើ',
                'name_en' => 'Chak Angrae Leu',
                'code' => '120606',
                'province_id' => 12,
                'district_id' => 101,
            ),
            378 => 
            array (
                'id' => 879,
                'name_kh' => 'ចាក់អង្រែក្រោម',
                'name_en' => 'Chak Angrae Kraom',
                'code' => '120607',
                'province_id' => 12,
                'district_id' => 101,
            ),
            379 => 
            array (
                'id' => 880,
                'name_kh' => 'ស្ទឹងមានជ័យទី១',
                'name_en' => 'Stueng Mean chey 1',
                'code' => '120608',
                'province_id' => 12,
                'district_id' => 101,
            ),
            380 => 
            array (
                'id' => 881,
                'name_kh' => 'ស្ទឹងមានជ័យទី២',
                'name_en' => 'Stueng Mean chey 2',
                'code' => '120609',
                'province_id' => 12,
                'district_id' => 101,
            ),
            381 => 
            array (
                'id' => 882,
                'name_kh' => 'ស្ទឹងមានជ័យទី៣',
                'name_en' => 'Stueng Mean chey 3',
                'code' => '120610',
                'province_id' => 12,
                'district_id' => 101,
            ),
            382 => 
            array (
                'id' => 883,
                'name_kh' => 'បឹងទំពុនទី១',
                'name_en' => 'Boeng Tumpun 1',
                'code' => '120611',
                'province_id' => 12,
                'district_id' => 101,
            ),
            383 => 
            array (
                'id' => 884,
                'name_kh' => 'បឹងទំពុនទី២',
                'name_en' => 'Boeng Tumpun 2',
                'code' => '120612',
                'province_id' => 12,
                'district_id' => 101,
            ),
            384 => 
            array (
                'id' => 885,
                'name_kh' => 'ស្វាយប៉ាក',
                'name_en' => 'Svay Pak',
                'code' => '120703',
                'province_id' => 12,
                'district_id' => 102,
            ),
            385 => 
            array (
                'id' => 886,
                'name_kh' => 'គីឡូម៉ែត្រលេខ៦',
                'name_en' => 'Kilomaetr Lekh Prammuoy',
                'code' => '120704',
                'province_id' => 12,
                'district_id' => 102,
            ),
            386 => 
            array (
                'id' => 887,
                'name_kh' => 'ឫស្សីកែវ',
                'name_en' => 'Ruessei Kaev',
                'code' => '120706',
                'province_id' => 12,
                'district_id' => 102,
            ),
            387 => 
            array (
                'id' => 888,
                'name_kh' => 'ច្រាំងចំរេះទី ១',
                'name_en' => 'Chrang Chamreh Ti Muoy',
                'code' => '120711',
                'province_id' => 12,
                'district_id' => 102,
            ),
            388 => 
            array (
                'id' => 889,
                'name_kh' => 'ច្រាំងចំរេះទី ២',
                'name_en' => 'Chrang Chamreh Ti Pir',
                'code' => '120712',
                'province_id' => 12,
                'district_id' => 102,
            ),
            389 => 
            array (
                'id' => 890,
                'name_kh' => 'ទួលសង្កែទី១',
                'name_en' => 'Tuol Sangkae 1',
                'code' => '120713',
                'province_id' => 12,
                'district_id' => 102,
            ),
            390 => 
            array (
                'id' => 891,
                'name_kh' => 'ទួលសង្កែទី២',
                'name_en' => 'Tuol Sangkae 2',
                'code' => '120714',
                'province_id' => 12,
                'district_id' => 102,
            ),
            391 => 
            array (
                'id' => 892,
                'name_kh' => 'ភ្នំពេញថ្មី',
                'name_en' => 'Phnom Penh Thmei',
                'code' => '120801',
                'province_id' => 12,
                'district_id' => 103,
            ),
            392 => 
            array (
                'id' => 893,
                'name_kh' => 'ទឹកថ្លា',
                'name_en' => 'Tuek Thla',
                'code' => '120802',
                'province_id' => 12,
                'district_id' => 103,
            ),
            393 => 
            array (
                'id' => 894,
                'name_kh' => 'ឃ្មួញ',
                'name_en' => 'Khmuonh',
                'code' => '120803',
                'province_id' => 12,
                'district_id' => 103,
            ),
            394 => 
            array (
                'id' => 895,
                'name_kh' => 'ក្រាំងធ្នង់',
                'name_en' => 'Krang Thnong',
                'code' => '120807',
                'province_id' => 12,
                'district_id' => 103,
            ),
            395 => 
            array (
                'id' => 896,
                'name_kh' => 'អូរបែកក្អម',
                'name_en' => 'Ou Baek Kam',
                'code' => '120808',
                'province_id' => 12,
                'district_id' => 103,
            ),
            396 => 
            array (
                'id' => 897,
                'name_kh' => 'គោកឃ្លាង',
                'name_en' => 'Kouk Khleang',
                'code' => '120809',
                'province_id' => 12,
                'district_id' => 103,
            ),
            397 => 
            array (
                'id' => 898,
                'name_kh' => 'ត្រពាំងក្រសាំង',
                'name_en' => 'Trapeang Krasang',
                'code' => '120901',
                'province_id' => 12,
                'district_id' => 104,
            ),
            398 => 
            array (
                'id' => 899,
                'name_kh' => 'សំរោងក្រោម',
                'name_en' => 'Samraong Kraom',
                'code' => '120906',
                'province_id' => 12,
                'district_id' => 104,
            ),
            399 => 
            array (
                'id' => 900,
                'name_kh' => 'ចោមចៅទី១',
                'name_en' => 'Chaom Chau 1',
                'code' => '120914',
                'province_id' => 12,
                'district_id' => 104,
            ),
            400 => 
            array (
                'id' => 901,
                'name_kh' => 'ចោមចៅទី២',
                'name_en' => 'Chaom Chau 2',
                'code' => '120915',
                'province_id' => 12,
                'district_id' => 104,
            ),
            401 => 
            array (
                'id' => 902,
                'name_kh' => 'ចោមចៅទី៣',
                'name_en' => 'Chaom Chau 3',
                'code' => '120916',
                'province_id' => 12,
                'district_id' => 104,
            ),
            402 => 
            array (
                'id' => 903,
                'name_kh' => 'កាកាបទី១',
                'name_en' => 'Kakab 1',
                'code' => '120917',
                'province_id' => 12,
                'district_id' => 104,
            ),
            403 => 
            array (
                'id' => 904,
                'name_kh' => 'កាកាបទី២',
                'name_en' => 'Kakab 2',
                'code' => '120918',
                'province_id' => 12,
                'district_id' => 104,
            ),
            404 => 
            array (
                'id' => 905,
                'name_kh' => 'ជ្រោយចង្វារ',
                'name_en' => 'Chrouy Changvar',
                'code' => '121001',
                'province_id' => 12,
                'district_id' => 105,
            ),
            405 => 
            array (
                'id' => 906,
                'name_kh' => 'ព្រែកលៀប',
                'name_en' => 'Preaek Lieb',
                'code' => '121002',
                'province_id' => 12,
                'district_id' => 105,
            ),
            406 => 
            array (
                'id' => 907,
                'name_kh' => 'ព្រែកតាសេក',
                'name_en' => 'Preaek Ta Sek',
                'code' => '121003',
                'province_id' => 12,
                'district_id' => 105,
            ),
            407 => 
            array (
                'id' => 908,
                'name_kh' => 'កោះដាច់',
                'name_en' => 'Kaoh Dach',
                'code' => '121004',
                'province_id' => 12,
                'district_id' => 105,
            ),
            408 => 
            array (
                'id' => 909,
                'name_kh' => 'បាក់ខែង',
                'name_en' => 'Bak Kaeng',
                'code' => '121005',
                'province_id' => 12,
                'district_id' => 105,
            ),
            409 => 
            array (
                'id' => 910,
                'name_kh' => 'ព្រែកព្នៅ',
                'name_en' => 'Preaek Phnov',
                'code' => '121101',
                'province_id' => 12,
                'district_id' => 106,
            ),
            410 => 
            array (
                'id' => 911,
                'name_kh' => 'ពញាពន់',
                'name_en' => 'Ponhea Pon',
                'code' => '121102',
                'province_id' => 12,
                'district_id' => 106,
            ),
            411 => 
            array (
                'id' => 912,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '121103',
                'province_id' => 12,
                'district_id' => 106,
            ),
            412 => 
            array (
                'id' => 913,
                'name_kh' => 'គោករកា',
                'name_en' => 'Kouk Roka',
                'code' => '121104',
                'province_id' => 12,
                'district_id' => 106,
            ),
            413 => 
            array (
                'id' => 914,
                'name_kh' => 'ពន្សាំង',
                'name_en' => 'Ponsang',
                'code' => '121105',
                'province_id' => 12,
                'district_id' => 106,
            ),
            414 => 
            array (
                'id' => 915,
                'name_kh' => 'ច្បារអំពៅទី ១',
                'name_en' => 'Chhbar Ampov Ti Muoy',
                'code' => '121201',
                'province_id' => 12,
                'district_id' => 107,
            ),
            415 => 
            array (
                'id' => 916,
                'name_kh' => 'ច្បារអំពៅទី ២',
                'name_en' => 'Chbar Ampov Ti Pir',
                'code' => '121202',
                'province_id' => 12,
                'district_id' => 107,
            ),
            416 => 
            array (
                'id' => 917,
                'name_kh' => 'និរោធ',
                'name_en' => 'Nirouth',
                'code' => '121203',
                'province_id' => 12,
                'district_id' => 107,
            ),
            417 => 
            array (
                'id' => 918,
                'name_kh' => 'ព្រែកប្រា',
                'name_en' => 'Preaek Pra',
                'code' => '121204',
                'province_id' => 12,
                'district_id' => 107,
            ),
            418 => 
            array (
                'id' => 919,
                'name_kh' => 'វាលស្បូវ',
                'name_en' => 'Veal Sbov',
                'code' => '121205',
                'province_id' => 12,
                'district_id' => 107,
            ),
            419 => 
            array (
                'id' => 920,
                'name_kh' => 'ព្រែកឯង',
                'name_en' => 'Preaek Aeng',
                'code' => '121206',
                'province_id' => 12,
                'district_id' => 107,
            ),
            420 => 
            array (
                'id' => 921,
                'name_kh' => 'ក្បាលកោះ',
                'name_en' => 'Kbal Kaoh',
                'code' => '121207',
                'province_id' => 12,
                'district_id' => 107,
            ),
            421 => 
            array (
                'id' => 922,
                'name_kh' => 'ព្រែកថ្មី',
                'name_en' => 'Preaek Thmei',
                'code' => '121208',
                'province_id' => 12,
                'district_id' => 107,
            ),
            422 => 
            array (
                'id' => 923,
                'name_kh' => 'បឹងកេងកងទី ១',
                'name_en' => 'Boeng Keng Kang Ti Muoy',
                'code' => '121301',
                'province_id' => 12,
                'district_id' => 108,
            ),
            423 => 
            array (
                'id' => 924,
                'name_kh' => 'បឹងកេងកងទី ២',
                'name_en' => 'Boeng Keng Kang Ti Pir',
                'code' => '121302',
                'province_id' => 12,
                'district_id' => 108,
            ),
            424 => 
            array (
                'id' => 925,
                'name_kh' => 'បឹងកេងកងទី ៣',
                'name_en' => 'Boeng Keng Kang Ti Bei',
                'code' => '121303',
                'province_id' => 12,
                'district_id' => 108,
            ),
            425 => 
            array (
                'id' => 926,
                'name_kh' => 'អូឡាំពិក',
                'name_en' => 'Olympic',
                'code' => '121304',
                'province_id' => 12,
                'district_id' => 108,
            ),
            426 => 
            array (
                'id' => 927,
                'name_kh' => 'ទំនប់ទឹក',
                'name_en' => 'Tumnob Tuek',
                'code' => '121305',
                'province_id' => 12,
                'district_id' => 108,
            ),
            427 => 
            array (
                'id' => 928,
                'name_kh' => 'ទួលស្វាយព្រៃទី ១',
                'name_en' => 'Tuol Svay Prey Ti Muoy',
                'code' => '121306',
                'province_id' => 12,
                'district_id' => 108,
            ),
            428 => 
            array (
                'id' => 929,
                'name_kh' => 'ទួលស្វាយព្រៃទី ២',
                'name_en' => 'Tuol Svay Prey Ti Pir',
                'code' => '121307',
                'province_id' => 12,
                'district_id' => 108,
            ),
            429 => 
            array (
                'id' => 930,
                'name_kh' => 'កំបូល',
                'name_en' => 'Kamboul',
                'code' => '121401',
                'province_id' => 12,
                'district_id' => 109,
            ),
            430 => 
            array (
                'id' => 931,
                'name_kh' => 'កន្ទោក',
                'name_en' => 'Kantaok',
                'code' => '121402',
                'province_id' => 12,
                'district_id' => 109,
            ),
            431 => 
            array (
                'id' => 932,
                'name_kh' => 'ឪឡោក',
                'name_en' => 'Ovlaok',
                'code' => '121403',
                'province_id' => 12,
                'district_id' => 109,
            ),
            432 => 
            array (
                'id' => 933,
                'name_kh' => 'ស្នោរ',
                'name_en' => 'Snaor',
                'code' => '121404',
                'province_id' => 12,
                'district_id' => 109,
            ),
            433 => 
            array (
                'id' => 934,
                'name_kh' => 'ភ្លើងឆេះរទេះ',
                'name_en' => 'Phleung Chheh Roteh',
                'code' => '121405',
                'province_id' => 12,
                'district_id' => 109,
            ),
            434 => 
            array (
                'id' => 935,
                'name_kh' => 'បឹងធំ',
                'name_en' => 'Boeng Thum',
                'code' => '121406',
                'province_id' => 12,
                'district_id' => 109,
            ),
            435 => 
            array (
                'id' => 936,
                'name_kh' => 'ប្រទះឡាង',
                'name_en' => 'Prateah Lang',
                'code' => '121407',
                'province_id' => 12,
                'district_id' => 109,
            ),
            436 => 
            array (
                'id' => 937,
                'name_kh' => 'ស្អាង',
                'name_en' => 'Sang',
                'code' => '130101',
                'province_id' => 13,
                'district_id' => 110,
            ),
            437 => 
            array (
                'id' => 938,
                'name_kh' => 'តស៊ូ',
                'name_en' => 'Tasu',
                'code' => '130102',
                'province_id' => 13,
                'district_id' => 110,
            ),
            438 => 
            array (
                'id' => 939,
                'name_kh' => 'ខ្យង',
                'name_en' => 'Khyang',
                'code' => '130103',
                'province_id' => 13,
                'district_id' => 110,
            ),
            439 => 
            array (
                'id' => 940,
                'name_kh' => 'ច្រាច់',
                'name_en' => 'Chrach',
                'code' => '130104',
                'province_id' => 13,
                'district_id' => 110,
            ),
            440 => 
            array (
                'id' => 941,
                'name_kh' => 'ធ្មា',
                'name_en' => 'Thmea',
                'code' => '130105',
                'province_id' => 13,
                'district_id' => 110,
            ),
            441 => 
            array (
                'id' => 942,
                'name_kh' => 'ពុទ្រា',
                'name_en' => 'Putrea',
                'code' => '130106',
                'province_id' => 13,
                'district_id' => 110,
            ),
            442 => 
            array (
                'id' => 943,
                'name_kh' => 'ឆែបមួយ',
                'name_en' => 'Chhaeb Muoy',
                'code' => '130201',
                'province_id' => 13,
                'district_id' => 111,
            ),
            443 => 
            array (
                'id' => 944,
                'name_kh' => 'ឆែបពីរ',
                'name_en' => 'Chhaeb Pir',
                'code' => '130202',
                'province_id' => 13,
                'district_id' => 111,
            ),
            444 => 
            array (
                'id' => 945,
                'name_kh' => 'សង្កែមួយ',
                'name_en' => 'Sangkae Muoy',
                'code' => '130203',
                'province_id' => 13,
                'district_id' => 111,
            ),
            445 => 
            array (
                'id' => 946,
                'name_kh' => 'សង្កែពីរ',
                'name_en' => 'Sangkae Pir',
                'code' => '130204',
                'province_id' => 13,
                'district_id' => 111,
            ),
            446 => 
            array (
                'id' => 947,
                'name_kh' => 'ម្លូព្រៃមួយ',
                'name_en' => 'Mlu Prey Muoy',
                'code' => '130205',
                'province_id' => 13,
                'district_id' => 111,
            ),
            447 => 
            array (
                'id' => 948,
                'name_kh' => 'ម្លូព្រៃពីរ',
                'name_en' => 'Mlu Prey Pir',
                'code' => '130206',
                'province_id' => 13,
                'district_id' => 111,
            ),
            448 => 
            array (
                'id' => 949,
                'name_kh' => 'កំពង់ស្រឡៅមួយ',
                'name_en' => 'Kampong Sralau Muoy',
                'code' => '130207',
                'province_id' => 13,
                'district_id' => 111,
            ),
            449 => 
            array (
                'id' => 950,
                'name_kh' => 'កំពង់ស្រឡៅពីរ',
                'name_en' => 'Kampong Sralau Pir',
                'code' => '130208',
                'province_id' => 13,
                'district_id' => 111,
            ),
            450 => 
            array (
                'id' => 951,
                'name_kh' => 'ជាំក្សាន្ដ',
                'name_en' => 'Choam Ksant',
                'code' => '130301',
                'province_id' => 13,
                'district_id' => 112,
            ),
            451 => 
            array (
                'id' => 952,
                'name_kh' => 'ទឹកក្រហម',
                'name_en' => 'Tuek Kraham',
                'code' => '130302',
                'province_id' => 13,
                'district_id' => 112,
            ),
            452 => 
            array (
                'id' => 953,
                'name_kh' => 'ព្រីងធំ',
                'name_en' => 'Pring Thum',
                'code' => '130303',
                'province_id' => 13,
                'district_id' => 112,
            ),
            453 => 
            array (
                'id' => 954,
                'name_kh' => 'រំដោះស្រែ',
                'name_en' => 'Rumdaoh Srae',
                'code' => '130304',
                'province_id' => 13,
                'district_id' => 112,
            ),
            454 => 
            array (
                'id' => 955,
                'name_kh' => 'យាង',
                'name_en' => 'Yeang',
                'code' => '130305',
                'province_id' => 13,
                'district_id' => 112,
            ),
            455 => 
            array (
                'id' => 956,
                'name_kh' => 'កន្ទួត',
                'name_en' => 'Kantuot',
                'code' => '130306',
                'province_id' => 13,
                'district_id' => 112,
            ),
            456 => 
            array (
                'id' => 957,
                'name_kh' => 'ស្រអែម',
                'name_en' => 'Sror Aem',
                'code' => '130307',
                'province_id' => 13,
                'district_id' => 112,
            ),
            457 => 
            array (
                'id' => 958,
                'name_kh' => 'មរកត',
                'name_en' => 'Morokot',
                'code' => '130308',
                'province_id' => 13,
                'district_id' => 112,
            ),
            458 => 
            array (
                'id' => 959,
                'name_kh' => 'គូលែនត្បូង',
                'name_en' => 'Kuleaen Tboung',
                'code' => '130401',
                'province_id' => 13,
                'district_id' => 113,
            ),
            459 => 
            array (
                'id' => 960,
                'name_kh' => 'គូលែនជើង',
                'name_en' => 'Kuleaen Cheung',
                'code' => '130402',
                'province_id' => 13,
                'district_id' => 113,
            ),
            460 => 
            array (
                'id' => 961,
                'name_kh' => 'ថ្មី',
                'name_en' => 'Thmei',
                'code' => '130403',
                'province_id' => 13,
                'district_id' => 113,
            ),
            461 => 
            array (
                'id' => 962,
                'name_kh' => 'ភ្នំពេញ',
                'name_en' => 'Phnum Penh',
                'code' => '130404',
                'province_id' => 13,
                'district_id' => 113,
            ),
            462 => 
            array (
                'id' => 963,
                'name_kh' => 'ភ្នំត្បែងពីរ',
                'name_en' => 'Phnum Tbaeng Pir',
                'code' => '130405',
                'province_id' => 13,
                'district_id' => 113,
            ),
            463 => 
            array (
                'id' => 964,
                'name_kh' => 'ស្រយង់',
                'name_en' => 'Srayang',
                'code' => '130406',
                'province_id' => 13,
                'district_id' => 113,
            ),
            464 => 
            array (
                'id' => 965,
                'name_kh' => 'របៀប',
                'name_en' => 'Robieb',
                'code' => '130501',
                'province_id' => 13,
                'district_id' => 114,
            ),
            465 => 
            array (
                'id' => 966,
                'name_kh' => 'រស្មី',
                'name_en' => 'Reaksmei',
                'code' => '130502',
                'province_id' => 13,
                'district_id' => 114,
            ),
            466 => 
            array (
                'id' => 967,
                'name_kh' => 'រហ័ស',
                'name_en' => 'Rohas',
                'code' => '130503',
                'province_id' => 13,
                'district_id' => 114,
            ),
            467 => 
            array (
                'id' => 968,
                'name_kh' => 'រុងរឿង',
                'name_en' => 'Rung Roeang',
                'code' => '130504',
                'province_id' => 13,
                'district_id' => 114,
            ),
            468 => 
            array (
                'id' => 969,
                'name_kh' => 'រីករាយ',
                'name_en' => 'Rik Reay',
                'code' => '130505',
                'province_id' => 13,
                'district_id' => 114,
            ),
            469 => 
            array (
                'id' => 970,
                'name_kh' => 'រួសរាន់',
                'name_en' => 'Ruos Roan',
                'code' => '130506',
                'province_id' => 13,
                'district_id' => 114,
            ),
            470 => 
            array (
                'id' => 971,
                'name_kh' => 'រតនៈ',
                'name_en' => 'Rotanak',
                'code' => '130507',
                'province_id' => 13,
                'district_id' => 114,
            ),
            471 => 
            array (
                'id' => 972,
                'name_kh' => 'រៀបរយ',
                'name_en' => 'Rieb Roy',
                'code' => '130508',
                'province_id' => 13,
                'district_id' => 114,
            ),
            472 => 
            array (
                'id' => 973,
                'name_kh' => 'រក្សា',
                'name_en' => 'Reaksa',
                'code' => '130509',
                'province_id' => 13,
                'district_id' => 114,
            ),
            473 => 
            array (
                'id' => 974,
                'name_kh' => 'រំដោះ',
                'name_en' => 'Rumdaoh',
                'code' => '130510',
                'province_id' => 13,
                'district_id' => 114,
            ),
            474 => 
            array (
                'id' => 975,
                'name_kh' => 'រមទម',
                'name_en' => 'Romtum',
                'code' => '130511',
                'province_id' => 13,
                'district_id' => 114,
            ),
            475 => 
            array (
                'id' => 976,
                'name_kh' => 'រមណីយ',
                'name_en' => 'Romoneiy',
                'code' => '130512',
                'province_id' => 13,
                'district_id' => 114,
            ),
            476 => 
            array (
                'id' => 977,
                'name_kh' => 'ចំរើន',
                'name_en' => 'Chamraeun',
                'code' => '130601',
                'province_id' => 13,
                'district_id' => 115,
            ),
            477 => 
            array (
                'id' => 978,
                'name_kh' => 'រអាង',
                'name_en' => 'Roang',
                'code' => '130602',
                'province_id' => 13,
                'district_id' => 115,
            ),
            478 => 
            array (
                'id' => 979,
                'name_kh' => 'ភ្នំត្បែងមួយ',
                'name_en' => 'Phnum Tbaeng Muoy',
                'code' => '130603',
                'province_id' => 13,
                'district_id' => 115,
            ),
            479 => 
            array (
                'id' => 980,
                'name_kh' => 'ស្ដៅ',
                'name_en' => 'Sdau',
                'code' => '130604',
                'province_id' => 13,
                'district_id' => 115,
            ),
            480 => 
            array (
                'id' => 981,
                'name_kh' => 'រណសិរ្ស',
                'name_en' => 'Ronak Ser',
                'code' => '130605',
                'province_id' => 13,
                'district_id' => 115,
            ),
            481 => 
            array (
                'id' => 982,
                'name_kh' => 'ឈានមុខ',
                'name_en' => 'Chhean Mukh',
                'code' => '130703',
                'province_id' => 13,
                'district_id' => 116,
            ),
            482 => 
            array (
                'id' => 983,
                'name_kh' => 'ពោធិ៍',
                'name_en' => 'Pou',
                'code' => '130704',
                'province_id' => 13,
                'district_id' => 116,
            ),
            483 => 
            array (
                'id' => 984,
                'name_kh' => 'ប្រមេរុ',
                'name_en' => 'Prame',
                'code' => '130705',
                'province_id' => 13,
                'district_id' => 116,
            ),
            484 => 
            array (
                'id' => 985,
                'name_kh' => 'ព្រះឃ្លាំង',
                'name_en' => 'Preah Khleang',
                'code' => '130706',
                'province_id' => 13,
                'district_id' => 116,
            ),
            485 => 
            array (
                'id' => 986,
                'name_kh' => 'កំពង់ប្រណាក',
                'name_en' => 'Kampong Pranak',
                'code' => '130801',
                'province_id' => 13,
                'district_id' => 117,
            ),
            486 => 
            array (
                'id' => 987,
                'name_kh' => 'ប៉ាលហាល',
                'name_en' => 'Pal Hal',
                'code' => '130802',
                'province_id' => 13,
                'district_id' => 117,
            ),
            487 => 
            array (
                'id' => 988,
                'name_kh' => 'បឹងព្រះ',
                'name_en' => 'Boeng Preah',
                'code' => '140101',
                'province_id' => 14,
                'district_id' => 118,
            ),
            488 => 
            array (
                'id' => 989,
                'name_kh' => 'ជើងភ្នំ',
                'name_en' => 'Cheung Phnum',
                'code' => '140102',
                'province_id' => 14,
                'district_id' => 118,
            ),
            489 => 
            array (
                'id' => 990,
                'name_kh' => 'ឈើកាច់',
                'name_en' => 'Chheu Kach',
                'code' => '140103',
                'province_id' => 14,
                'district_id' => 118,
            ),
            490 => 
            array (
                'id' => 991,
                'name_kh' => 'រក្សជ័យ',
                'name_en' => 'Reaks Chey',
                'code' => '140104',
                'province_id' => 14,
                'district_id' => 118,
            ),
            491 => 
            array (
                'id' => 992,
                'name_kh' => 'រោងដំរី',
                'name_en' => 'Roung Damrei',
                'code' => '140105',
                'province_id' => 14,
                'district_id' => 118,
            ),
            492 => 
            array (
                'id' => 993,
                'name_kh' => 'ស្ដៅកោង',
                'name_en' => 'Sdau Kaong',
                'code' => '140106',
                'province_id' => 14,
                'district_id' => 118,
            ),
            493 => 
            array (
                'id' => 994,
                'name_kh' => 'ស្ពឺ  ក',
                'name_en' => 'Spueu Ka',
                'code' => '140107',
                'province_id' => 14,
                'district_id' => 118,
            ),
            494 => 
            array (
                'id' => 995,
                'name_kh' => 'ស្ពឺ  ខ',
                'name_en' => 'Spueu Kha',
                'code' => '140108',
                'province_id' => 14,
                'district_id' => 118,
            ),
            495 => 
            array (
                'id' => 996,
                'name_kh' => 'ធាយ',
                'name_en' => 'Theay',
                'code' => '140109',
                'province_id' => 14,
                'district_id' => 118,
            ),
            496 => 
            array (
                'id' => 997,
                'name_kh' => 'ជាច',
                'name_en' => 'Cheach',
                'code' => '140201',
                'province_id' => 14,
                'district_id' => 119,
            ),
            497 => 
            array (
                'id' => 998,
                'name_kh' => 'ដូនកឹង',
                'name_en' => 'Doun Koeng',
                'code' => '140202',
                'province_id' => 14,
                'district_id' => 119,
            ),
            498 => 
            array (
                'id' => 999,
                'name_kh' => 'ក្រញូង',
                'name_en' => 'Kranhung',
                'code' => '140203',
                'province_id' => 14,
                'district_id' => 119,
            ),
            499 => 
            array (
                'id' => 1000,
                'name_kh' => 'ក្របៅ',
                'name_en' => 'Krabau',
                'code' => '140204',
                'province_id' => 14,
                'district_id' => 119,
            ),
        ));
        \DB::table('communes')->insert(array (
            0 => 
            array (
                'id' => 1001,
                'name_kh' => 'ស៊ាងឃ្វាង',
                'name_en' => 'Seang Khveang',
                'code' => '140205',
                'province_id' => 14,
                'district_id' => 119,
            ),
            1 => 
            array (
                'id' => 1002,
                'name_kh' => 'ស្មោងខាងជើង',
                'name_en' => 'Smaong Khang Cheung',
                'code' => '140206',
                'province_id' => 14,
                'district_id' => 119,
            ),
            2 => 
            array (
                'id' => 1003,
                'name_kh' => 'ស្មោងខាងត្បូង',
                'name_en' => 'Smaong Khang Tboung',
                'code' => '140207',
                'province_id' => 14,
                'district_id' => 119,
            ),
            3 => 
            array (
                'id' => 1004,
                'name_kh' => 'ត្របែក',
                'name_en' => 'Trabaek',
                'code' => '140208',
                'province_id' => 14,
                'district_id' => 119,
            ),
            4 => 
            array (
                'id' => 1005,
                'name_kh' => 'អន្សោង',
                'name_en' => 'Ansaong',
                'code' => '140301',
                'province_id' => 14,
                'district_id' => 120,
            ),
            5 => 
            array (
                'id' => 1006,
                'name_kh' => 'ចាម',
                'name_en' => 'Cham',
                'code' => '140302',
                'province_id' => 14,
                'district_id' => 120,
            ),
            6 => 
            array (
                'id' => 1007,
                'name_kh' => 'ជាងដែក',
                'name_en' => 'Cheang Daek',
                'code' => '140303',
                'province_id' => 14,
                'district_id' => 120,
            ),
            7 => 
            array (
                'id' => 1008,
                'name_kh' => 'ជ្រៃ',
                'name_en' => 'Chrey',
                'code' => '140304',
                'province_id' => 14,
                'district_id' => 120,
            ),
            8 => 
            array (
                'id' => 1009,
                'name_kh' => 'កន្សោមអក',
                'name_en' => 'Kansoam Ak',
                'code' => '140305',
                'province_id' => 14,
                'district_id' => 120,
            ),
            9 => 
            array (
                'id' => 1010,
                'name_kh' => 'គោខ្ចក',
                'name_en' => 'Kou Khchak',
                'code' => '140306',
                'province_id' => 14,
                'district_id' => 120,
            ),
            10 => 
            array (
                'id' => 1011,
                'name_kh' => 'កំពង់ត្របែក',
                'name_en' => 'Kampong Trabaek',
                'code' => '140307',
                'province_id' => 14,
                'district_id' => 120,
            ),
            11 => 
            array (
                'id' => 1012,
                'name_kh' => 'ពាមមន្ទារ',
                'name_en' => 'Peam Montear',
                'code' => '140308',
                'province_id' => 14,
                'district_id' => 120,
            ),
            12 => 
            array (
                'id' => 1013,
                'name_kh' => 'ប្រាសាទ',
                'name_en' => 'Prasat',
                'code' => '140309',
                'province_id' => 14,
                'district_id' => 120,
            ),
            13 => 
            array (
                'id' => 1014,
                'name_kh' => 'ប្រធាតុ',
                'name_en' => 'Pratheat',
                'code' => '140310',
                'province_id' => 14,
                'district_id' => 120,
            ),
            14 => 
            array (
                'id' => 1015,
                'name_kh' => 'ព្រៃឈរ',
                'name_en' => 'Prey Chhor',
                'code' => '140311',
                'province_id' => 14,
                'district_id' => 120,
            ),
            15 => 
            array (
                'id' => 1016,
                'name_kh' => 'ព្រៃពោន',
                'name_en' => 'Prey Poun',
                'code' => '140312',
                'province_id' => 14,
                'district_id' => 120,
            ),
            16 => 
            array (
                'id' => 1017,
                'name_kh' => 'ថ្កូវ',
                'name_en' => 'Thkov',
                'code' => '140313',
                'province_id' => 14,
                'district_id' => 120,
            ),
            17 => 
            array (
                'id' => 1018,
                'name_kh' => 'ចុងអំពិល',
                'name_en' => 'Chong Ampil',
                'code' => '140401',
                'province_id' => 14,
                'district_id' => 121,
            ),
            18 => 
            array (
                'id' => 1019,
                'name_kh' => 'កញ្ជ្រៀច',
                'name_en' => 'Kanhchriech',
                'code' => '140402',
                'province_id' => 14,
                'district_id' => 121,
            ),
            19 => 
            array (
                'id' => 1020,
                'name_kh' => 'ក្ដឿងរាយ',
                'name_en' => 'Kdoeang Reay',
                'code' => '140403',
                'province_id' => 14,
                'district_id' => 121,
            ),
            20 => 
            array (
                'id' => 1021,
                'name_kh' => 'គោកគង់កើត',
                'name_en' => 'Kouk Kong Kaeut',
                'code' => '140404',
                'province_id' => 14,
                'district_id' => 121,
            ),
            21 => 
            array (
                'id' => 1022,
                'name_kh' => 'គោកគង់លិច',
                'name_en' => 'Kouk Kong Lech',
                'code' => '140405',
                'province_id' => 14,
                'district_id' => 121,
            ),
            22 => 
            array (
                'id' => 1023,
                'name_kh' => 'ព្រាល',
                'name_en' => 'Preal',
                'code' => '140406',
                'province_id' => 14,
                'district_id' => 121,
            ),
            23 => 
            array (
                'id' => 1024,
                'name_kh' => 'ថ្មពូន',
                'name_en' => 'Thma Pun',
                'code' => '140407',
                'province_id' => 14,
                'district_id' => 121,
            ),
            24 => 
            array (
                'id' => 1025,
                'name_kh' => 'ត្នោត',
                'name_en' => 'Tnaot',
                'code' => '140408',
                'province_id' => 14,
                'district_id' => 121,
            ),
            25 => 
            array (
                'id' => 1026,
                'name_kh' => 'អង្គរសរ',
                'name_en' => 'Angkor Sar',
                'code' => '140501',
                'province_id' => 14,
                'district_id' => 122,
            ),
            26 => 
            array (
                'id' => 1027,
                'name_kh' => 'ច្រេស',
                'name_en' => 'Chres',
                'code' => '140502',
                'province_id' => 14,
                'district_id' => 122,
            ),
            27 => 
            array (
                'id' => 1028,
                'name_kh' => 'ជីផុច',
                'name_en' => 'Chi Phoch',
                'code' => '140503',
                'province_id' => 14,
                'district_id' => 122,
            ),
            28 => 
            array (
                'id' => 1029,
                'name_kh' => 'ព្រៃឃ្នេស',
                'name_en' => 'Prey Khnes',
                'code' => '140504',
                'province_id' => 14,
                'district_id' => 122,
            ),
            29 => 
            array (
                'id' => 1030,
                'name_kh' => 'ព្រៃរំដេង',
                'name_en' => 'Prey Rumdeng',
                'code' => '140505',
                'province_id' => 14,
                'district_id' => 122,
            ),
            30 => 
            array (
                'id' => 1031,
                'name_kh' => 'ព្រៃទទឹង',
                'name_en' => 'Prey Totueng',
                'code' => '140506',
                'province_id' => 14,
                'district_id' => 122,
            ),
            31 => 
            array (
                'id' => 1032,
                'name_kh' => 'ស្វាយជ្រុំ',
                'name_en' => 'Svay Chrum',
                'code' => '140507',
                'province_id' => 14,
                'district_id' => 122,
            ),
            32 => 
            array (
                'id' => 1033,
                'name_kh' => 'ត្រពាំងស្រែ',
                'name_en' => 'Trapeang Srae',
                'code' => '140508',
                'province_id' => 14,
                'district_id' => 122,
            ),
            33 => 
            array (
                'id' => 1034,
                'name_kh' => 'អង្គរអង្គ',
                'name_en' => 'Angkor Angk',
                'code' => '140601',
                'province_id' => 14,
                'district_id' => 123,
            ),
            34 => 
            array (
                'id' => 1035,
                'name_kh' => 'កំពង់ប្រាសាទ',
                'name_en' => 'Kampong Prasat',
                'code' => '140602',
                'province_id' => 14,
                'district_id' => 123,
            ),
            35 => 
            array (
                'id' => 1036,
                'name_kh' => 'កោះចេក',
                'name_en' => 'Kaoh Chek',
                'code' => '140603',
                'province_id' => 14,
                'district_id' => 123,
            ),
            36 => 
            array (
                'id' => 1037,
                'name_kh' => 'កោះរកា',
                'name_en' => 'Kaoh Roka',
                'code' => '140604',
                'province_id' => 14,
                'district_id' => 123,
            ),
            37 => 
            array (
                'id' => 1038,
                'name_kh' => 'កោះសំពៅ',
                'name_en' => 'Kaoh Sampov',
                'code' => '140605',
                'province_id' => 14,
                'district_id' => 123,
            ),
            38 => 
            array (
                'id' => 1039,
                'name_kh' => 'ក្រាំងតាយ៉ង',
                'name_en' => 'Krang Ta Yang',
                'code' => '140606',
                'province_id' => 14,
                'district_id' => 123,
            ),
            39 => 
            array (
                'id' => 1040,
                'name_kh' => 'ព្រែកក្របៅ',
                'name_en' => 'Preaek Krabau',
                'code' => '140607',
                'province_id' => 14,
                'district_id' => 123,
            ),
            40 => 
            array (
                'id' => 1041,
                'name_kh' => 'ព្រែកសំបួរ',
                'name_en' => 'Preaek Sambuor',
                'code' => '140608',
                'province_id' => 14,
                'district_id' => 123,
            ),
            41 => 
            array (
                'id' => 1042,
                'name_kh' => 'ឫស្សីស្រុក',
                'name_en' => 'Ruessei Srok',
                'code' => '140609',
                'province_id' => 14,
                'district_id' => 123,
            ),
            42 => 
            array (
                'id' => 1043,
                'name_kh' => 'ស្វាយភ្លោះ',
                'name_en' => 'Svay Phluoh',
                'code' => '140610',
                'province_id' => 14,
                'district_id' => 123,
            ),
            43 => 
            array (
                'id' => 1044,
                'name_kh' => 'បាបោង',
                'name_en' => 'Ba Baong',
                'code' => '140701',
                'province_id' => 14,
                'district_id' => 124,
            ),
            44 => 
            array (
                'id' => 1045,
                'name_kh' => 'បន្លិចប្រាសាទ',
                'name_en' => 'Banlich Prasat',
                'code' => '140702',
                'province_id' => 14,
                'district_id' => 124,
            ),
            45 => 
            array (
                'id' => 1046,
                'name_kh' => 'អ្នកលឿង',
                'name_en' => 'Neak Loeang',
                'code' => '140703',
                'province_id' => 14,
                'district_id' => 124,
            ),
            46 => 
            array (
                'id' => 1047,
                'name_kh' => 'ពាមមានជ័យ',
                'name_en' => 'Peam Mean Chey',
                'code' => '140704',
                'province_id' => 14,
                'district_id' => 124,
            ),
            47 => 
            array (
                'id' => 1048,
                'name_kh' => 'ពាមរក៍',
                'name_en' => 'Peam Ro',
                'code' => '140705',
                'province_id' => 14,
                'district_id' => 124,
            ),
            48 => 
            array (
                'id' => 1049,
                'name_kh' => 'ព្រែកខ្សាយ ក',
                'name_en' => 'Preaek Khsay Ka',
                'code' => '140706',
                'province_id' => 14,
                'district_id' => 124,
            ),
            49 => 
            array (
                'id' => 1050,
                'name_kh' => 'ព្រែកខ្សាយ ខ',
                'name_en' => 'Preaek Khsay Kha',
                'code' => '140707',
                'province_id' => 14,
                'district_id' => 124,
            ),
            50 => 
            array (
                'id' => 1051,
                'name_kh' => 'ព្រៃកណ្ដៀង',
                'name_en' => 'Prey Kandieng',
                'code' => '140708',
                'province_id' => 14,
                'district_id' => 124,
            ),
            51 => 
            array (
                'id' => 1052,
                'name_kh' => 'កំពង់ពពិល',
                'name_en' => 'Kampong Popil',
                'code' => '140801',
                'province_id' => 14,
                'district_id' => 125,
            ),
            52 => 
            array (
                'id' => 1053,
                'name_kh' => 'កញ្ចំ',
                'name_en' => 'Kanhcham',
                'code' => '140802',
                'province_id' => 14,
                'district_id' => 125,
            ),
            53 => 
            array (
                'id' => 1054,
                'name_kh' => 'កំពង់ប្រាំង',
                'name_en' => 'Kampong Prang',
                'code' => '140803',
                'province_id' => 14,
                'district_id' => 125,
            ),
            54 => 
            array (
                'id' => 1055,
                'name_kh' => 'មេសរប្រចាន់',
                'name_en' => 'Mesar Prachan',
                'code' => '140805',
                'province_id' => 14,
                'district_id' => 125,
            ),
            55 => 
            array (
                'id' => 1056,
                'name_kh' => 'ព្រៃព្នៅ',
                'name_en' => 'Prey Pnov',
                'code' => '140807',
                'province_id' => 14,
                'district_id' => 125,
            ),
            56 => 
            array (
                'id' => 1057,
                'name_kh' => 'ព្រៃស្នៀត',
                'name_en' => 'Prey Sniet',
                'code' => '140808',
                'province_id' => 14,
                'district_id' => 125,
            ),
            57 => 
            array (
                'id' => 1058,
                'name_kh' => 'ព្រៃស្រឡិត',
                'name_en' => 'Prey Sralet',
                'code' => '140809',
                'province_id' => 14,
                'district_id' => 125,
            ),
            58 => 
            array (
                'id' => 1059,
                'name_kh' => 'រាប',
                'name_en' => 'Reab',
                'code' => '140810',
                'province_id' => 14,
                'district_id' => 125,
            ),
            59 => 
            array (
                'id' => 1060,
                'name_kh' => 'រកា',
                'name_en' => 'Roka',
                'code' => '140811',
                'province_id' => 14,
                'district_id' => 125,
            ),
            60 => 
            array (
                'id' => 1061,
                'name_kh' => 'អង្គររាជ្យ',
                'name_en' => 'Angkor Reach',
                'code' => '140901',
                'province_id' => 14,
                'district_id' => 126,
            ),
            61 => 
            array (
                'id' => 1062,
                'name_kh' => 'បន្ទាយចក្រី',
                'name_en' => 'Banteay Chakrei',
                'code' => '140902',
                'province_id' => 14,
                'district_id' => 126,
            ),
            62 => 
            array (
                'id' => 1063,
                'name_kh' => 'បឹងដោល',
                'name_en' => 'Boeng Daol',
                'code' => '140903',
                'province_id' => 14,
                'district_id' => 126,
            ),
            63 => 
            array (
                'id' => 1064,
                'name_kh' => 'ជៃកំពក',
                'name_en' => 'Chey Kampok',
                'code' => '140904',
                'province_id' => 14,
                'district_id' => 126,
            ),
            64 => 
            array (
                'id' => 1065,
                'name_kh' => 'កំពង់សឹង',
                'name_en' => 'Kampong Soeng',
                'code' => '140905',
                'province_id' => 14,
                'district_id' => 126,
            ),
            65 => 
            array (
                'id' => 1066,
                'name_kh' => 'ក្រាំងស្វាយ',
                'name_en' => 'Krang Svay',
                'code' => '140906',
                'province_id' => 14,
                'district_id' => 126,
            ),
            66 => 
            array (
                'id' => 1067,
                'name_kh' => 'ល្វា',
                'name_en' => 'Lvea',
                'code' => '140907',
                'province_id' => 14,
                'district_id' => 126,
            ),
            67 => 
            array (
                'id' => 1068,
                'name_kh' => 'ព្រះស្ដេច',
                'name_en' => 'Preah Sdach',
                'code' => '140908',
                'province_id' => 14,
                'district_id' => 126,
            ),
            68 => 
            array (
                'id' => 1069,
                'name_kh' => 'រាធរ',
                'name_en' => 'Reathor',
                'code' => '140909',
                'province_id' => 14,
                'district_id' => 126,
            ),
            69 => 
            array (
                'id' => 1070,
                'name_kh' => 'រំចេក',
                'name_en' => 'Rumchek',
                'code' => '140910',
                'province_id' => 14,
                'district_id' => 126,
            ),
            70 => 
            array (
                'id' => 1071,
                'name_kh' => 'សេនារាជឧត្ដម',
                'name_en' => 'Sena Reach Otdam',
                'code' => '140911',
                'province_id' => 14,
                'district_id' => 126,
            ),
            71 => 
            array (
                'id' => 1072,
                'name_kh' => 'បារាយណ៍',
                'name_en' => 'Baray',
                'code' => '141001',
                'province_id' => 14,
                'district_id' => 127,
            ),
            72 => 
            array (
                'id' => 1073,
                'name_kh' => 'ជើងទឹក',
                'name_en' => 'Cheung Tuek',
                'code' => '141002',
                'province_id' => 14,
                'district_id' => 127,
            ),
            73 => 
            array (
                'id' => 1074,
                'name_kh' => 'កំពង់លាវ',
                'name_en' => 'Kampong Leav',
                'code' => '141003',
                'province_id' => 14,
                'district_id' => 127,
            ),
            74 => 
            array (
                'id' => 1075,
                'name_kh' => 'តាកោ',
                'name_en' => 'Ta Kao',
                'code' => '141004',
                'province_id' => 14,
                'district_id' => 127,
            ),
            75 => 
            array (
                'id' => 1076,
                'name_kh' => 'ពោធិ៍រៀង',
                'name_en' => 'Pou Rieng',
                'code' => '141101',
                'province_id' => 14,
                'district_id' => 128,
            ),
            76 => 
            array (
                'id' => 1077,
                'name_kh' => 'ព្រែកអន្ទះ',
                'name_en' => 'Preaek Anteah',
                'code' => '141102',
                'province_id' => 14,
                'district_id' => 128,
            ),
            77 => 
            array (
                'id' => 1078,
                'name_kh' => 'ព្រែកជ្រៃ',
                'name_en' => 'Preaek Chrey',
                'code' => '141103',
                'province_id' => 14,
                'district_id' => 128,
            ),
            78 => 
            array (
                'id' => 1079,
                'name_kh' => 'ព្រៃកន្លោង',
                'name_en' => 'Prey Kanlaong',
                'code' => '141104',
                'province_id' => 14,
                'district_id' => 128,
            ),
            79 => 
            array (
                'id' => 1080,
                'name_kh' => 'កំពង់ឫស្សី',
                'name_en' => 'Kampong Ruessei',
                'code' => '141106',
                'province_id' => 14,
                'district_id' => 128,
            ),
            80 => 
            array (
                'id' => 1081,
                'name_kh' => 'ព្រែកតាសរ',
                'name_en' => 'Preaek Ta Sar',
                'code' => '141107',
                'province_id' => 14,
                'district_id' => 128,
            ),
            81 => 
            array (
                'id' => 1082,
                'name_kh' => 'អំពិលក្រៅ',
                'name_en' => 'Ampil Krau',
                'code' => '141201',
                'province_id' => 14,
                'district_id' => 129,
            ),
            82 => 
            array (
                'id' => 1083,
                'name_kh' => 'ជ្រៃឃ្មុំ',
                'name_en' => 'Chrey Khmum',
                'code' => '141202',
                'province_id' => 14,
                'district_id' => 129,
            ),
            83 => 
            array (
                'id' => 1084,
                'name_kh' => 'ល្វេ',
                'name_en' => 'Lve',
                'code' => '141203',
                'province_id' => 14,
                'district_id' => 129,
            ),
            84 => 
            array (
                'id' => 1085,
                'name_kh' => 'ព្នៅទី ១',
                'name_en' => 'Pnov Ti Muoy',
                'code' => '141204',
                'province_id' => 14,
                'district_id' => 129,
            ),
            85 => 
            array (
                'id' => 1086,
                'name_kh' => 'ព្នៅទី ២',
                'name_en' => 'Pnov Ti Pir',
                'code' => '141205',
                'province_id' => 14,
                'district_id' => 129,
            ),
            86 => 
            array (
                'id' => 1087,
                'name_kh' => 'ពោធិ៍ទី',
                'name_en' => 'Pou Ti',
                'code' => '141206',
                'province_id' => 14,
                'district_id' => 129,
            ),
            87 => 
            array (
                'id' => 1088,
                'name_kh' => 'ព្រែកចង្ក្រាន',
                'name_en' => 'Preaek Changkran',
                'code' => '141207',
                'province_id' => 14,
                'district_id' => 129,
            ),
            88 => 
            array (
                'id' => 1089,
                'name_kh' => 'ព្រៃដើមថ្នឹង',
                'name_en' => 'Prey Daeum Thnoeng',
                'code' => '141208',
                'province_id' => 14,
                'district_id' => 129,
            ),
            89 => 
            array (
                'id' => 1090,
                'name_kh' => 'ព្រៃទឹង',
                'name_en' => 'Prey Tueng',
                'code' => '141209',
                'province_id' => 14,
                'district_id' => 129,
            ),
            90 => 
            array (
                'id' => 1091,
                'name_kh' => 'រំលេច',
                'name_en' => 'Rumlech',
                'code' => '141210',
                'province_id' => 14,
                'district_id' => 129,
            ),
            91 => 
            array (
                'id' => 1092,
                'name_kh' => 'ឫស្សីសាញ់',
                'name_en' => 'Ruessei Sanh',
                'code' => '141211',
                'province_id' => 14,
                'district_id' => 129,
            ),
            92 => 
            array (
                'id' => 1093,
                'name_kh' => 'អង្គរទ្រេត',
                'name_en' => 'Angkor Tret',
                'code' => '141301',
                'province_id' => 14,
                'district_id' => 130,
            ),
            93 => 
            array (
                'id' => 1094,
                'name_kh' => 'ជាខ្លាង',
                'name_en' => 'Chea Khlang',
                'code' => '141302',
                'province_id' => 14,
                'district_id' => 130,
            ),
            94 => 
            array (
                'id' => 1095,
                'name_kh' => 'ជ្រៃ',
                'name_en' => 'Chrey',
                'code' => '141303',
                'province_id' => 14,
                'district_id' => 130,
            ),
            95 => 
            array (
                'id' => 1096,
                'name_kh' => 'ដំរីពួន',
                'name_en' => 'Damrei Puon',
                'code' => '141304',
                'province_id' => 14,
                'district_id' => 130,
            ),
            96 => 
            array (
                'id' => 1097,
                'name_kh' => 'មេបុណ្យ',
                'name_en' => 'Me Bon',
                'code' => '141305',
                'province_id' => 14,
                'district_id' => 130,
            ),
            97 => 
            array (
                'id' => 1098,
                'name_kh' => 'ពានរោង',
                'name_en' => 'Pean Roung',
                'code' => '141306',
                'province_id' => 14,
                'district_id' => 130,
            ),
            98 => 
            array (
                'id' => 1099,
                'name_kh' => 'ពពឺស',
                'name_en' => 'Popueus',
                'code' => '141307',
                'province_id' => 14,
                'district_id' => 130,
            ),
            99 => 
            array (
                'id' => 1100,
                'name_kh' => 'ព្រៃខ្លា',
                'name_en' => 'Prey Khla',
                'code' => '141308',
                'province_id' => 14,
                'district_id' => 130,
            ),
            100 => 
            array (
                'id' => 1101,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '141309',
                'province_id' => 14,
                'district_id' => 130,
            ),
            101 => 
            array (
                'id' => 1102,
                'name_kh' => 'ស្វាយអន្ទរ',
                'name_en' => 'Svay Antor',
                'code' => '141310',
                'province_id' => 14,
                'district_id' => 130,
            ),
            102 => 
            array (
                'id' => 1103,
                'name_kh' => 'ទឹកថ្លា',
                'name_en' => 'Tuek Thla',
                'code' => '141311',
                'province_id' => 14,
                'district_id' => 130,
            ),
            103 => 
            array (
                'id' => 1104,
                'name_kh' => 'បឹងបត់កណ្ដាល',
                'name_en' => 'Boeng Bat Kandaol',
                'code' => '150101',
                'province_id' => 15,
                'district_id' => 131,
            ),
            104 => 
            array (
                'id' => 1105,
                'name_kh' => 'បឹងខ្នារ',
                'name_en' => 'Boeng Khnar',
                'code' => '150102',
                'province_id' => 15,
                'district_id' => 131,
            ),
            105 => 
            array (
                'id' => 1106,
                'name_kh' => 'ខ្នារទទឹង',
                'name_en' => 'Khnar Totueng',
                'code' => '150103',
                'province_id' => 15,
                'district_id' => 131,
            ),
            106 => 
            array (
                'id' => 1107,
                'name_kh' => 'មេទឹក',
                'name_en' => 'Me Tuek',
                'code' => '150104',
                'province_id' => 15,
                'district_id' => 131,
            ),
            107 => 
            array (
                'id' => 1108,
                'name_kh' => 'អូរតាប៉ោង',
                'name_en' => 'Ou Ta Paong',
                'code' => '150105',
                'province_id' => 15,
                'district_id' => 131,
            ),
            108 => 
            array (
                'id' => 1109,
                'name_kh' => 'រំលេច',
                'name_en' => 'Rumlech',
                'code' => '150106',
                'province_id' => 15,
                'district_id' => 131,
            ),
            109 => 
            array (
                'id' => 1110,
                'name_kh' => 'ស្នាមព្រះ',
                'name_en' => 'Snam Preah',
                'code' => '150107',
                'province_id' => 15,
                'district_id' => 131,
            ),
            110 => 
            array (
                'id' => 1111,
                'name_kh' => 'ស្វាយដូនកែវ',
                'name_en' => 'Svay Doun Kaev',
                'code' => '150108',
                'province_id' => 15,
                'district_id' => 131,
            ),
            111 => 
            array (
                'id' => 1112,
                'name_kh' => 'ត្រពាំងជង',
                'name_en' => 'Trapeang chorng',
                'code' => '150110',
                'province_id' => 15,
                'district_id' => 131,
            ),
            112 => 
            array (
                'id' => 1113,
                'name_kh' => 'អន្លង់វិល',
                'name_en' => 'Anlong Vil',
                'code' => '150201',
                'province_id' => 15,
                'district_id' => 132,
            ),
            113 => 
            array (
                'id' => 1114,
                'name_kh' => 'កណ្ដៀង',
                'name_en' => 'Kandieng',
                'code' => '150203',
                'province_id' => 15,
                'district_id' => 132,
            ),
            114 => 
            array (
                'id' => 1115,
                'name_kh' => 'កញ្ជរ',
                'name_en' => 'Kanhchor',
                'code' => '150204',
                'province_id' => 15,
                'district_id' => 132,
            ),
            115 => 
            array (
                'id' => 1116,
                'name_kh' => 'រាំងទិល',
                'name_en' => 'Reang Til',
                'code' => '150205',
                'province_id' => 15,
                'district_id' => 132,
            ),
            116 => 
            array (
                'id' => 1117,
                'name_kh' => 'ស្រែស្ដុក',
                'name_en' => 'Srae Sdok',
                'code' => '150206',
                'province_id' => 15,
                'district_id' => 132,
            ),
            117 => 
            array (
                'id' => 1118,
                'name_kh' => 'ស្វាយលួង',
                'name_en' => 'Svay Luong',
                'code' => '150207',
                'province_id' => 15,
                'district_id' => 132,
            ),
            118 => 
            array (
                'id' => 1119,
                'name_kh' => 'ស្យា',
                'name_en' => 'Sya',
                'code' => '150208',
                'province_id' => 15,
                'district_id' => 132,
            ),
            119 => 
            array (
                'id' => 1120,
                'name_kh' => 'វាល',
                'name_en' => 'Veal',
                'code' => '150209',
                'province_id' => 15,
                'district_id' => 132,
            ),
            120 => 
            array (
                'id' => 1121,
                'name_kh' => 'កោះជុំ',
                'name_en' => 'Kaoh Chum',
                'code' => '150210',
                'province_id' => 15,
                'district_id' => 132,
            ),
            121 => 
            array (
                'id' => 1122,
                'name_kh' => 'អន្លង់ត្នោត',
                'name_en' => 'Anlong Tnaot',
                'code' => '150301',
                'province_id' => 15,
                'district_id' => 133,
            ),
            122 => 
            array (
                'id' => 1123,
                'name_kh' => 'អន្សាចំបក់',
                'name_en' => 'Ansa Chambak',
                'code' => '150302',
                'province_id' => 15,
                'district_id' => 133,
            ),
            123 => 
            array (
                'id' => 1124,
                'name_kh' => 'បឹងកន្ទួត',
                'name_en' => 'Boeng Kantuot',
                'code' => '150303',
                'province_id' => 15,
                'district_id' => 133,
            ),
            124 => 
            array (
                'id' => 1125,
                'name_kh' => 'ឈើតុំ',
                'name_en' => 'Chheu Tom',
                'code' => '150304',
                'province_id' => 15,
                'district_id' => 133,
            ),
            125 => 
            array (
                'id' => 1126,
                'name_kh' => 'កំពង់លួង',
                'name_en' => 'Kampong Luong',
                'code' => '150305',
                'province_id' => 15,
                'district_id' => 133,
            ),
            126 => 
            array (
                'id' => 1127,
                'name_kh' => 'កំពង់ពោធិ៍',
                'name_en' => 'Kampong Pou',
                'code' => '150306',
                'province_id' => 15,
                'district_id' => 133,
            ),
            127 => 
            array (
                'id' => 1128,
                'name_kh' => 'ក្បាលត្រាច',
                'name_en' => 'Kbal Trach',
                'code' => '150307',
                'province_id' => 15,
                'district_id' => 133,
            ),
            128 => 
            array (
                'id' => 1129,
                'name_kh' => 'អូរសណ្ដាន់',
                'name_en' => 'Ou Sandan',
                'code' => '150308',
                'province_id' => 15,
                'district_id' => 133,
            ),
            129 => 
            array (
                'id' => 1130,
                'name_kh' => 'ស្នាអន្សា',
                'name_en' => 'Sna Ansa',
                'code' => '150309',
                'province_id' => 15,
                'district_id' => 133,
            ),
            130 => 
            array (
                'id' => 1131,
                'name_kh' => 'ស្វាយស',
                'name_en' => 'Svay Sa',
                'code' => '150310',
                'province_id' => 15,
                'district_id' => 133,
            ),
            131 => 
            array (
                'id' => 1132,
                'name_kh' => 'ត្នោតជុំ',
                'name_en' => 'Tnaot Chum',
                'code' => '150311',
                'province_id' => 15,
                'district_id' => 133,
            ),
            132 => 
            array (
                'id' => 1133,
                'name_kh' => 'បាក់ចិញ្ចៀន',
                'name_en' => 'Bak Chenhchien',
                'code' => '150401',
                'province_id' => 15,
                'district_id' => 134,
            ),
            133 => 
            array (
                'id' => 1134,
                'name_kh' => 'លាច',
                'name_en' => 'Leach',
                'code' => '150402',
                'province_id' => 15,
                'district_id' => 134,
            ),
            134 => 
            array (
                'id' => 1135,
                'name_kh' => 'ព្រងិល',
                'name_en' => 'Prongil',
                'code' => '150404',
                'province_id' => 15,
                'district_id' => 134,
            ),
            135 => 
            array (
                'id' => 1136,
                'name_kh' => 'រកាត',
                'name_en' => 'Rokat',
                'code' => '150405',
                'province_id' => 15,
                'district_id' => 134,
            ),
            136 => 
            array (
                'id' => 1137,
                'name_kh' => 'សន្ទ្រែ',
                'name_en' => 'Santreae',
                'code' => '150406',
                'province_id' => 15,
                'district_id' => 134,
            ),
            137 => 
            array (
                'id' => 1138,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '150407',
                'province_id' => 15,
                'district_id' => 134,
            ),
            138 => 
            array (
                'id' => 1139,
                'name_kh' => 'ចំរើនផល',
                'name_en' => 'Chamraeun Phal',
                'code' => '150501',
                'province_id' => 15,
                'district_id' => 135,
            ),
            139 => 
            array (
                'id' => 1140,
                'name_kh' => 'លលកស',
                'name_en' => 'Lolok Sa',
                'code' => '150503',
                'province_id' => 15,
                'district_id' => 135,
            ),
            140 => 
            array (
                'id' => 1141,
                'name_kh' => 'ផ្ទះព្រៃ',
                'name_en' => 'Phteah Prey',
                'code' => '150504',
                'province_id' => 15,
                'district_id' => 135,
            ),
            141 => 
            array (
                'id' => 1142,
                'name_kh' => 'ព្រៃញី',
                'name_en' => 'Prey Nhi',
                'code' => '150505',
                'province_id' => 15,
                'district_id' => 135,
            ),
            142 => 
            array (
                'id' => 1143,
                'name_kh' => 'រលាប',
                'name_en' => 'Roleab',
                'code' => '150506',
                'province_id' => 15,
                'district_id' => 135,
            ),
            143 => 
            array (
                'id' => 1144,
                'name_kh' => 'ស្វាយអាត់',
                'name_en' => 'Svay At',
                'code' => '150507',
                'province_id' => 15,
                'district_id' => 135,
            ),
            144 => 
            array (
                'id' => 1145,
                'name_kh' => 'បន្ទាយដី',
                'name_en' => 'Banteay Dei',
                'code' => '150508',
                'province_id' => 15,
                'district_id' => 135,
            ),
            145 => 
            array (
                'id' => 1146,
                'name_kh' => 'អូរសោម',
                'name_en' => 'Ou Saom',
                'code' => '150601',
                'province_id' => 15,
                'district_id' => 136,
            ),
            146 => 
            array (
                'id' => 1147,
                'name_kh' => 'ក្រពើពីរ',
                'name_en' => 'Krapeu Pir',
                'code' => '150602',
                'province_id' => 15,
                'district_id' => 136,
            ),
            147 => 
            array (
                'id' => 1148,
                'name_kh' => 'អន្លង់រាប',
                'name_en' => 'Anlong Reab',
                'code' => '150603',
                'province_id' => 15,
                'district_id' => 136,
            ),
            148 => 
            array (
                'id' => 1149,
                'name_kh' => 'ប្រម៉ោយ',
                'name_en' => 'Pramaoy',
                'code' => '150604',
                'province_id' => 15,
                'district_id' => 136,
            ),
            149 => 
            array (
                'id' => 1150,
                'name_kh' => 'ថ្មដា',
                'name_en' => 'Thma Da',
                'code' => '150605',
                'province_id' => 15,
                'district_id' => 136,
            ),
            150 => 
            array (
                'id' => 1151,
                'name_kh' => 'តាលោ',
                'name_en' => 'Ta Lou',
                'code' => '150701',
                'province_id' => 15,
                'district_id' => 137,
            ),
            151 => 
            array (
                'id' => 1152,
                'name_kh' => 'ផ្ទះរុង',
                'name_en' => 'Phteah Rung',
                'code' => '150702',
                'province_id' => 15,
                'district_id' => 137,
            ),
            152 => 
            array (
                'id' => 1153,
                'name_kh' => 'ម៉ាលិក',
                'name_en' => 'Malik',
                'code' => '160101',
                'province_id' => 16,
                'district_id' => 138,
            ),
            153 => 
            array (
                'id' => 1154,
                'name_kh' => 'ញ៉ាង',
                'name_en' => 'Nhang',
                'code' => '160103',
                'province_id' => 16,
                'district_id' => 138,
            ),
            154 => 
            array (
                'id' => 1155,
                'name_kh' => 'តាឡាវ',
                'name_en' => 'Ta Lav',
                'code' => '160104',
                'province_id' => 16,
                'district_id' => 138,
            ),
            155 => 
            array (
                'id' => 1156,
                'name_kh' => 'កាចាញ',
                'name_en' => 'Kachanh',
                'code' => '160201',
                'province_id' => 16,
                'district_id' => 139,
            ),
            156 => 
            array (
                'id' => 1157,
                'name_kh' => 'ឡាបានសៀក',
                'name_en' => 'Labansiek',
                'code' => '160202',
                'province_id' => 16,
                'district_id' => 139,
            ),
            157 => 
            array (
                'id' => 1158,
                'name_kh' => 'យក្ខឡោម',
                'name_en' => 'Yeak Laom',
                'code' => '160203',
                'province_id' => 16,
                'district_id' => 139,
            ),
            158 => 
            array (
                'id' => 1159,
                'name_kh' => 'បឹងកន្សែង',
                'name_en' => 'Boeng Kansaeng',
                'code' => '160204',
                'province_id' => 16,
                'district_id' => 139,
            ),
            159 => 
            array (
                'id' => 1160,
                'name_kh' => 'កក់',
                'name_en' => 'Kak',
                'code' => '160301',
                'province_id' => 16,
                'district_id' => 140,
            ),
            160 => 
            array (
                'id' => 1161,
                'name_kh' => 'កិះចុង',
                'name_en' => 'Keh Chong',
                'code' => '160302',
                'province_id' => 16,
                'district_id' => 140,
            ),
            161 => 
            array (
                'id' => 1162,
                'name_kh' => 'ឡាមីញ',
                'name_en' => 'La Minh',
                'code' => '160303',
                'province_id' => 16,
                'district_id' => 140,
            ),
            162 => 
            array (
                'id' => 1163,
                'name_kh' => 'លុងឃុង',
                'name_en' => 'Lung Khung',
                'code' => '160304',
                'province_id' => 16,
                'district_id' => 140,
            ),
            163 => 
            array (
                'id' => 1164,
                'name_kh' => 'ស៊ើង',
                'name_en' => 'Saeung',
                'code' => '160305',
                'province_id' => 16,
                'district_id' => 140,
            ),
            164 => 
            array (
                'id' => 1165,
                'name_kh' => 'ទីងចាក់',
                'name_en' => 'Ting Chak',
                'code' => '160306',
                'province_id' => 16,
                'district_id' => 140,
            ),
            165 => 
            array (
                'id' => 1166,
                'name_kh' => 'សិរីមង្គល',
                'name_en' => 'Serei Mongkol',
                'code' => '160401',
                'province_id' => 16,
                'district_id' => 141,
            ),
            166 => 
            array (
                'id' => 1167,
                'name_kh' => 'ស្រែអង្គ្រង',
                'name_en' => 'Srae Angkrorng',
                'code' => '160402',
                'province_id' => 16,
                'district_id' => 141,
            ),
            167 => 
            array (
                'id' => 1168,
                'name_kh' => 'តាអង',
                'name_en' => 'Ta Ang',
                'code' => '160403',
                'province_id' => 16,
                'district_id' => 141,
            ),
            168 => 
            array (
                'id' => 1169,
                'name_kh' => 'តឺន',
                'name_en' => 'Teun',
                'code' => '160404',
                'province_id' => 16,
                'district_id' => 141,
            ),
            169 => 
            array (
                'id' => 1170,
                'name_kh' => 'ត្រពាំងច្រេស',
                'name_en' => 'Trapeang Chres',
                'code' => '160405',
                'province_id' => 16,
                'district_id' => 141,
            ),
            170 => 
            array (
                'id' => 1171,
                'name_kh' => 'ត្រពាំងក្រហម',
                'name_en' => 'Trapeang Kraham',
                'code' => '160406',
                'province_id' => 16,
                'district_id' => 141,
            ),
            171 => 
            array (
                'id' => 1172,
                'name_kh' => 'ជ័យឧត្ដម',
                'name_en' => 'Chey Otdam',
                'code' => '160501',
                'province_id' => 16,
                'district_id' => 142,
            ),
            172 => 
            array (
                'id' => 1173,
                'name_kh' => 'កាឡែង',
                'name_en' => 'Ka Laeng',
                'code' => '160502',
                'province_id' => 16,
                'district_id' => 142,
            ),
            173 => 
            array (
                'id' => 1174,
                'name_kh' => 'ល្បាំង១',
                'name_en' => 'Lbang Muoy',
                'code' => '160503',
                'province_id' => 16,
                'district_id' => 142,
            ),
            174 => 
            array (
                'id' => 1175,
                'name_kh' => 'ល្បាំង២',
                'name_en' => 'Lbang Pir',
                'code' => '160504',
                'province_id' => 16,
                'district_id' => 142,
            ),
            175 => 
            array (
                'id' => 1176,
                'name_kh' => 'បាតាង',
                'name_en' => 'Ba Tang',
                'code' => '160505',
                'province_id' => 16,
                'district_id' => 142,
            ),
            176 => 
            array (
                'id' => 1177,
                'name_kh' => 'សេដា',
                'name_en' => 'Seda',
                'code' => '160506',
                'province_id' => 16,
                'district_id' => 142,
            ),
            177 => 
            array (
                'id' => 1178,
                'name_kh' => 'ចាអ៊ុង',
                'name_en' => 'Cha Ung',
                'code' => '160601',
                'province_id' => 16,
                'district_id' => 143,
            ),
            178 => 
            array (
                'id' => 1179,
                'name_kh' => 'ប៉ូយ',
                'name_en' => 'Pouy',
                'code' => '160602',
                'province_id' => 16,
                'district_id' => 143,
            ),
            179 => 
            array (
                'id' => 1180,
                'name_kh' => 'ឯកភាព',
                'name_en' => 'Aekakpheap',
                'code' => '160603',
                'province_id' => 16,
                'district_id' => 143,
            ),
            180 => 
            array (
                'id' => 1181,
                'name_kh' => 'កាឡៃ',
                'name_en' => 'Kalai',
                'code' => '160604',
                'province_id' => 16,
                'district_id' => 143,
            ),
            181 => 
            array (
                'id' => 1182,
                'name_kh' => 'អូរជុំ',
                'name_en' => 'Ou Chum',
                'code' => '160605',
                'province_id' => 16,
                'district_id' => 143,
            ),
            182 => 
            array (
                'id' => 1183,
                'name_kh' => 'សាមគ្គី',
                'name_en' => 'Sameakki',
                'code' => '160606',
                'province_id' => 16,
                'district_id' => 143,
            ),
            183 => 
            array (
                'id' => 1184,
                'name_kh' => 'ល្អក់',
                'name_en' => 'Lak',
                'code' => '160607',
                'province_id' => 16,
                'district_id' => 143,
            ),
            184 => 
            array (
                'id' => 1185,
                'name_kh' => 'បរខាំ',
                'name_en' => 'Bar Kham',
                'code' => '160701',
                'province_id' => 16,
                'district_id' => 144,
            ),
            185 => 
            array (
                'id' => 1186,
                'name_kh' => 'លំជ័រ',
                'name_en' => 'Lum Choar',
                'code' => '160702',
                'province_id' => 16,
                'district_id' => 144,
            ),
            186 => 
            array (
                'id' => 1187,
                'name_kh' => 'ប៉ក់ញ៉ៃ',
                'name_en' => 'Pak Nhai',
                'code' => '160703',
                'province_id' => 16,
                'district_id' => 144,
            ),
            187 => 
            array (
                'id' => 1188,
                'name_kh' => 'ប៉ាតេ',
                'name_en' => 'Pa Te',
                'code' => '160704',
                'province_id' => 16,
                'district_id' => 144,
            ),
            188 => 
            array (
                'id' => 1189,
                'name_kh' => 'សេសាន',
                'name_en' => 'Sesan',
                'code' => '160705',
                'province_id' => 16,
                'district_id' => 144,
            ),
            189 => 
            array (
                'id' => 1190,
                'name_kh' => 'សោមធំ',
                'name_en' => 'Saom Thum',
                'code' => '160706',
                'province_id' => 16,
                'district_id' => 144,
            ),
            190 => 
            array (
                'id' => 1191,
                'name_kh' => 'យ៉ាទុង',
                'name_en' => 'Ya Tung',
                'code' => '160707',
                'province_id' => 16,
                'district_id' => 144,
            ),
            191 => 
            array (
                'id' => 1192,
                'name_kh' => 'តាវែងលើ',
                'name_en' => 'Ta Veaeng Leu',
                'code' => '160801',
                'province_id' => 16,
                'district_id' => 145,
            ),
            192 => 
            array (
                'id' => 1193,
                'name_kh' => 'តាវែងក្រោម',
                'name_en' => 'Ta Veaeng Kraom',
                'code' => '160802',
                'province_id' => 16,
                'district_id' => 145,
            ),
            193 => 
            array (
                'id' => 1194,
                'name_kh' => 'ប៉ុង',
                'name_en' => 'Pong',
                'code' => '160901',
                'province_id' => 16,
                'district_id' => 146,
            ),
            194 => 
            array (
                'id' => 1195,
                'name_kh' => 'ហាត់ប៉ក់',
                'name_en' => 'Hat Pak',
                'code' => '160903',
                'province_id' => 16,
                'district_id' => 146,
            ),
            195 => 
            array (
                'id' => 1196,
                'name_kh' => 'កាចូន',
                'name_en' => 'Ka Choun',
                'code' => '160904',
                'province_id' => 16,
                'district_id' => 146,
            ),
            196 => 
            array (
                'id' => 1197,
                'name_kh' => 'កោះប៉ង់',
                'name_en' => 'Kaoh Pang',
                'code' => '160905',
                'province_id' => 16,
                'district_id' => 146,
            ),
            197 => 
            array (
                'id' => 1198,
                'name_kh' => 'កោះពាក្យ',
                'name_en' => 'Kaoh Peak',
                'code' => '160906',
                'province_id' => 16,
                'district_id' => 146,
            ),
            198 => 
            array (
                'id' => 1199,
                'name_kh' => 'កុកឡាក់',
                'name_en' => 'Kok Lak',
                'code' => '160907',
                'province_id' => 16,
                'district_id' => 146,
            ),
            199 => 
            array (
                'id' => 1200,
                'name_kh' => 'ប៉ាកាឡាន់',
                'name_en' => 'Pa Kalan',
                'code' => '160908',
                'province_id' => 16,
                'district_id' => 146,
            ),
            200 => 
            array (
                'id' => 1201,
                'name_kh' => 'ភ្នំកុក',
                'name_en' => 'Phnum Kok',
                'code' => '160909',
                'province_id' => 16,
                'district_id' => 146,
            ),
            201 => 
            array (
                'id' => 1202,
                'name_kh' => 'វើនសៃ',
                'name_en' => 'Veun Sai',
                'code' => '160910',
                'province_id' => 16,
                'district_id' => 146,
            ),
            202 => 
            array (
                'id' => 1203,
                'name_kh' => 'ចារឈូក',
                'name_en' => 'Char Chhuk',
                'code' => '170101',
                'province_id' => 17,
                'district_id' => 147,
            ),
            203 => 
            array (
                'id' => 1204,
                'name_kh' => 'ដូនពេង',
                'name_en' => 'Doun Peng',
                'code' => '170102',
                'province_id' => 17,
                'district_id' => 147,
            ),
            204 => 
            array (
                'id' => 1205,
                'name_kh' => 'គោកដូង',
                'name_en' => 'Kouk Doung',
                'code' => '170103',
                'province_id' => 17,
                'district_id' => 147,
            ),
            205 => 
            array (
                'id' => 1206,
                'name_kh' => 'គោល',
                'name_en' => 'Koul',
                'code' => '170104',
                'province_id' => 17,
                'district_id' => 147,
            ),
            206 => 
            array (
                'id' => 1207,
                'name_kh' => 'នគរភាស',
                'name_en' => 'Nokor Pheas',
                'code' => '170105',
                'province_id' => 17,
                'district_id' => 147,
            ),
            207 => 
            array (
                'id' => 1208,
                'name_kh' => 'ស្រែខ្វាវ',
                'name_en' => 'Srae Khvav',
                'code' => '170106',
                'province_id' => 17,
                'district_id' => 147,
            ),
            208 => 
            array (
                'id' => 1209,
                'name_kh' => 'តាសោម',
                'name_en' => 'Ta Saom',
                'code' => '170107',
                'province_id' => 17,
                'district_id' => 147,
            ),
            209 => 
            array (
                'id' => 1210,
                'name_kh' => 'ជប់តាត្រាវ',
                'name_en' => 'Chob Ta Trav',
                'code' => '170201',
                'province_id' => 17,
                'district_id' => 148,
            ),
            210 => 
            array (
                'id' => 1211,
                'name_kh' => 'លាងដៃ',
                'name_en' => 'Leang Dai',
                'code' => '170202',
                'province_id' => 17,
                'district_id' => 148,
            ),
            211 => 
            array (
                'id' => 1212,
                'name_kh' => 'ពាក់ស្នែង',
                'name_en' => 'Peak Snaeng',
                'code' => '170203',
                'province_id' => 17,
                'district_id' => 148,
            ),
            212 => 
            array (
                'id' => 1213,
                'name_kh' => 'ស្វាយចេក',
                'name_en' => 'Svay Chek',
                'code' => '170204',
                'province_id' => 17,
                'district_id' => 148,
            ),
            213 => 
            array (
                'id' => 1214,
                'name_kh' => 'ខ្នារសណ្ដាយ',
                'name_en' => 'Khnar Sanday',
                'code' => '170301',
                'province_id' => 17,
                'district_id' => 149,
            ),
            214 => 
            array (
                'id' => 1215,
                'name_kh' => 'ឃុនរាម',
                'name_en' => 'Khun Ream',
                'code' => '170302',
                'province_id' => 17,
                'district_id' => 149,
            ),
            215 => 
            array (
                'id' => 1216,
                'name_kh' => 'ព្រះដាក់',
                'name_en' => 'Preah Dak',
                'code' => '170303',
                'province_id' => 17,
                'district_id' => 149,
            ),
            216 => 
            array (
                'id' => 1217,
                'name_kh' => 'រំចេក',
                'name_en' => 'Rumchek',
                'code' => '170304',
                'province_id' => 17,
                'district_id' => 149,
            ),
            217 => 
            array (
                'id' => 1218,
                'name_kh' => 'រុនតាឯក',
                'name_en' => 'Run Ta Aek',
                'code' => '170305',
                'province_id' => 17,
                'district_id' => 149,
            ),
            218 => 
            array (
                'id' => 1219,
                'name_kh' => 'ត្បែង',
                'name_en' => 'Tbaeng',
                'code' => '170306',
                'province_id' => 17,
                'district_id' => 149,
            ),
            219 => 
            array (
                'id' => 1220,
                'name_kh' => 'អន្លង់សំណរ',
                'name_en' => 'Anlong Samnar',
                'code' => '170401',
                'province_id' => 17,
                'district_id' => 150,
            ),
            220 => 
            array (
                'id' => 1221,
                'name_kh' => 'ជីក្រែង',
                'name_en' => 'Chi Kraeng',
                'code' => '170402',
                'province_id' => 17,
                'district_id' => 150,
            ),
            221 => 
            array (
                'id' => 1222,
                'name_kh' => 'កំពង់ក្ដី',
                'name_en' => 'Kampong Kdei',
                'code' => '170403',
                'province_id' => 17,
                'district_id' => 150,
            ),
            222 => 
            array (
                'id' => 1223,
                'name_kh' => 'ខ្វាវ',
                'name_en' => 'Khvav',
                'code' => '170404',
                'province_id' => 17,
                'district_id' => 150,
            ),
            223 => 
            array (
                'id' => 1224,
                'name_kh' => 'គោកធ្លកក្រោម',
                'name_en' => 'Kouk Thlok Kraom',
                'code' => '170405',
                'province_id' => 17,
                'district_id' => 150,
            ),
            224 => 
            array (
                'id' => 1225,
                'name_kh' => 'គោកធ្លកលើ',
                'name_en' => 'Kouk Thlok Leu',
                'code' => '170406',
                'province_id' => 17,
                'district_id' => 150,
            ),
            225 => 
            array (
                'id' => 1226,
                'name_kh' => 'ល្វែងឫស្សី',
                'name_en' => 'Lveaeng Ruessei',
                'code' => '170407',
                'province_id' => 17,
                'district_id' => 150,
            ),
            226 => 
            array (
                'id' => 1227,
                'name_kh' => 'ពង្រក្រោម',
                'name_en' => 'Pongro Kraom',
                'code' => '170408',
                'province_id' => 17,
                'district_id' => 150,
            ),
            227 => 
            array (
                'id' => 1228,
                'name_kh' => 'ពង្រលើ',
                'name_en' => 'Pongro Leu',
                'code' => '170409',
                'province_id' => 17,
                'district_id' => 150,
            ),
            228 => 
            array (
                'id' => 1229,
                'name_kh' => 'ឫស្សីលក',
                'name_en' => 'Ruessei Lok',
                'code' => '170410',
                'province_id' => 17,
                'district_id' => 150,
            ),
            229 => 
            array (
                'id' => 1230,
                'name_kh' => 'សង្វើយ',
                'name_en' => 'Sangvaeuy',
                'code' => '170411',
                'province_id' => 17,
                'district_id' => 150,
            ),
            230 => 
            array (
                'id' => 1231,
                'name_kh' => 'ស្ពានត្នោត',
                'name_en' => 'Spean Tnaot',
                'code' => '170412',
                'province_id' => 17,
                'district_id' => 150,
            ),
            231 => 
            array (
                'id' => 1232,
                'name_kh' => 'ចន្លាសដៃ',
                'name_en' => 'Chanleas Dai',
                'code' => '170601',
                'province_id' => 17,
                'district_id' => 151,
            ),
            232 => 
            array (
                'id' => 1233,
                'name_kh' => 'កំពង់ថ្កូវ',
                'name_en' => 'Kampong Thkov',
                'code' => '170602',
                'province_id' => 17,
                'district_id' => 151,
            ),
            233 => 
            array (
                'id' => 1234,
                'name_kh' => 'ក្រឡាញ់',
                'name_en' => 'Kralanh',
                'code' => '170603',
                'province_id' => 17,
                'district_id' => 151,
            ),
            234 => 
            array (
                'id' => 1235,
                'name_kh' => 'ក្រូចគរ',
                'name_en' => 'Krouch Kor',
                'code' => '170604',
                'province_id' => 17,
                'district_id' => 151,
            ),
            235 => 
            array (
                'id' => 1236,
                'name_kh' => 'រោងគោ',
                'name_en' => 'Roung Kou',
                'code' => '170605',
                'province_id' => 17,
                'district_id' => 151,
            ),
            236 => 
            array (
                'id' => 1237,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sambuor',
                'code' => '170606',
                'province_id' => 17,
                'district_id' => 151,
            ),
            237 => 
            array (
                'id' => 1238,
                'name_kh' => 'សែនសុខ',
                'name_en' => 'Saen Sokh',
                'code' => '170607',
                'province_id' => 17,
                'district_id' => 151,
            ),
            238 => 
            array (
                'id' => 1239,
                'name_kh' => 'ស្នួល',
                'name_en' => 'Snuol',
                'code' => '170608',
                'province_id' => 17,
                'district_id' => 151,
            ),
            239 => 
            array (
                'id' => 1240,
                'name_kh' => 'ស្រណាល',
                'name_en' => 'Sranal',
                'code' => '170609',
                'province_id' => 17,
                'district_id' => 151,
            ),
            240 => 
            array (
                'id' => 1241,
                'name_kh' => 'តាអាន',
                'name_en' => 'Ta An',
                'code' => '170610',
                'province_id' => 17,
                'district_id' => 151,
            ),
            241 => 
            array (
                'id' => 1242,
                'name_kh' => 'សសរស្ដម្ភ',
                'name_en' => 'Sasar Sdam',
                'code' => '170701',
                'province_id' => 17,
                'district_id' => 152,
            ),
            242 => 
            array (
                'id' => 1243,
                'name_kh' => 'ដូនកែវ',
                'name_en' => 'Doun Kaev',
                'code' => '170702',
                'province_id' => 17,
                'district_id' => 152,
            ),
            243 => 
            array (
                'id' => 1244,
                'name_kh' => 'ក្ដីរុន',
                'name_en' => 'Kdei Run',
                'code' => '170703',
                'province_id' => 17,
                'district_id' => 152,
            ),
            244 => 
            array (
                'id' => 1245,
                'name_kh' => 'កែវពណ៌',
                'name_en' => 'Kaev Poar',
                'code' => '170704',
                'province_id' => 17,
                'district_id' => 152,
            ),
            245 => 
            array (
                'id' => 1246,
                'name_kh' => 'ខ្នាត',
                'name_en' => 'Khnat',
                'code' => '170705',
                'province_id' => 17,
                'district_id' => 152,
            ),
            246 => 
            array (
                'id' => 1247,
                'name_kh' => 'ល្វា',
                'name_en' => 'Lvea',
                'code' => '170707',
                'province_id' => 17,
                'district_id' => 152,
            ),
            247 => 
            array (
                'id' => 1248,
                'name_kh' => 'មុខប៉ែន',
                'name_en' => 'Mukh Paen',
                'code' => '170708',
                'province_id' => 17,
                'district_id' => 152,
            ),
            248 => 
            array (
                'id' => 1249,
                'name_kh' => 'ពោធិ៍ទ្រាយ',
                'name_en' => 'Pou Treay',
                'code' => '170709',
                'province_id' => 17,
                'district_id' => 152,
            ),
            249 => 
            array (
                'id' => 1250,
                'name_kh' => 'ពួក',
                'name_en' => 'Puok',
                'code' => '170710',
                'province_id' => 17,
                'district_id' => 152,
            ),
            250 => 
            array (
                'id' => 1251,
                'name_kh' => 'ព្រៃជ្រូក',
                'name_en' => 'Prey Chruk',
                'code' => '170711',
                'province_id' => 17,
                'district_id' => 152,
            ),
            251 => 
            array (
                'id' => 1252,
                'name_kh' => 'រើល',
                'name_en' => 'Reul',
                'code' => '170712',
                'province_id' => 17,
                'district_id' => 152,
            ),
            252 => 
            array (
                'id' => 1253,
                'name_kh' => 'សំរោងយា',
                'name_en' => 'Samraong Yea',
                'code' => '170713',
                'province_id' => 17,
                'district_id' => 152,
            ),
            253 => 
            array (
                'id' => 1254,
                'name_kh' => 'ត្រីញ័រ',
                'name_en' => 'Trei Nhoar',
                'code' => '170715',
                'province_id' => 17,
                'district_id' => 152,
            ),
            254 => 
            array (
                'id' => 1255,
                'name_kh' => 'យាង',
                'name_en' => 'Yeang',
                'code' => '170716',
                'province_id' => 17,
                'district_id' => 152,
            ),
            255 => 
            array (
                'id' => 1256,
                'name_kh' => 'បាគង',
                'name_en' => 'Bakong',
                'code' => '170902',
                'province_id' => 17,
                'district_id' => 153,
            ),
            256 => 
            array (
                'id' => 1257,
                'name_kh' => 'បល្ល័ង្ក',
                'name_en' => 'Ballangk',
                'code' => '170903',
                'province_id' => 17,
                'district_id' => 153,
            ),
            257 => 
            array (
                'id' => 1258,
                'name_kh' => 'កំពង់ភ្លុក',
                'name_en' => 'Kampong Phluk',
                'code' => '170904',
                'province_id' => 17,
                'district_id' => 153,
            ),
            258 => 
            array (
                'id' => 1259,
                'name_kh' => 'កន្ទ្រាំង',
                'name_en' => 'Kantreang',
                'code' => '170905',
                'province_id' => 17,
                'district_id' => 153,
            ),
            259 => 
            array (
                'id' => 1260,
                'name_kh' => 'កណ្ដែក',
                'name_en' => 'Kandaek',
                'code' => '170906',
                'province_id' => 17,
                'district_id' => 153,
            ),
            260 => 
            array (
                'id' => 1261,
                'name_kh' => 'មានជ័យ',
                'name_en' => 'Mean Chey',
                'code' => '170907',
                'province_id' => 17,
                'district_id' => 153,
            ),
            261 => 
            array (
                'id' => 1262,
                'name_kh' => 'រលួស',
                'name_en' => 'Roluos',
                'code' => '170908',
                'province_id' => 17,
                'district_id' => 153,
            ),
            262 => 
            array (
                'id' => 1263,
                'name_kh' => 'ត្រពាំងធំ',
                'name_en' => 'Trapeang Thum',
                'code' => '170909',
                'province_id' => 17,
                'district_id' => 153,
            ),
            263 => 
            array (
                'id' => 1264,
                'name_kh' => 'អំពិល',
                'name_en' => 'Ampil',
                'code' => '170910',
                'province_id' => 17,
                'district_id' => 153,
            ),
            264 => 
            array (
                'id' => 1265,
                'name_kh' => 'ស្លក្រាម',
                'name_en' => 'Sla Kram',
                'code' => '171001',
                'province_id' => 17,
                'district_id' => 154,
            ),
            265 => 
            array (
                'id' => 1266,
                'name_kh' => 'ស្វាយដង្គំ',
                'name_en' => 'Svay Dankum',
                'code' => '171002',
                'province_id' => 17,
                'district_id' => 154,
            ),
            266 => 
            array (
                'id' => 1267,
                'name_kh' => 'គោកចក',
                'name_en' => 'Kok Chak',
                'code' => '171003',
                'province_id' => 17,
                'district_id' => 154,
            ),
            267 => 
            array (
                'id' => 1268,
                'name_kh' => 'សាលាកំរើក',
                'name_en' => 'Sala Kamreuk',
                'code' => '171004',
                'province_id' => 17,
                'district_id' => 154,
            ),
            268 => 
            array (
                'id' => 1269,
                'name_kh' => 'នគរធំ',
                'name_en' => 'Nokor Thum',
                'code' => '171005',
                'province_id' => 17,
                'district_id' => 154,
            ),
            269 => 
            array (
                'id' => 1270,
                'name_kh' => 'ជ្រាវ',
                'name_en' => 'Chreav',
                'code' => '171006',
                'province_id' => 17,
                'district_id' => 154,
            ),
            270 => 
            array (
                'id' => 1271,
                'name_kh' => 'ចុងឃ្នៀស',
                'name_en' => 'Chong Khnies',
                'code' => '171007',
                'province_id' => 17,
                'district_id' => 154,
            ),
            271 => 
            array (
                'id' => 1272,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sngkat Sambuor',
                'code' => '171008',
                'province_id' => 17,
                'district_id' => 154,
            ),
            272 => 
            array (
                'id' => 1273,
                'name_kh' => 'សៀមរាប',
                'name_en' => 'Siem Reab',
                'code' => '171009',
                'province_id' => 17,
                'district_id' => 154,
            ),
            273 => 
            array (
                'id' => 1274,
                'name_kh' => 'ស្រង៉ែ',
                'name_en' => 'Srangae',
                'code' => '171010',
                'province_id' => 17,
                'district_id' => 154,
            ),
            274 => 
            array (
                'id' => 1275,
                'name_kh' => 'ក្របីរៀល',
                'name_en' => 'Krabei Riel',
                'code' => '171012',
                'province_id' => 17,
                'district_id' => 154,
            ),
            275 => 
            array (
                'id' => 1276,
                'name_kh' => 'ទឹកវិល',
                'name_en' => 'Tuek Vil',
                'code' => '171013',
                'province_id' => 17,
                'district_id' => 154,
            ),
            276 => 
            array (
                'id' => 1277,
                'name_kh' => 'ចាន់ស',
                'name_en' => 'Chan Sa',
                'code' => '171101',
                'province_id' => 17,
                'district_id' => 155,
            ),
            277 => 
            array (
                'id' => 1278,
                'name_kh' => 'ដំដែក',
                'name_en' => 'Dam Daek',
                'code' => '171102',
                'province_id' => 17,
                'district_id' => 155,
            ),
            278 => 
            array (
                'id' => 1279,
                'name_kh' => 'ដានរុន',
                'name_en' => 'Dan Run',
                'code' => '171103',
                'province_id' => 17,
                'district_id' => 155,
            ),
            279 => 
            array (
                'id' => 1280,
                'name_kh' => 'កំពង់ឃ្លាំង',
                'name_en' => 'Kampong Khleang',
                'code' => '171104',
                'province_id' => 17,
                'district_id' => 155,
            ),
            280 => 
            array (
                'id' => 1281,
                'name_kh' => 'កៀនសង្កែ',
                'name_en' => 'Kien Sangkae',
                'code' => '171105',
                'province_id' => 17,
                'district_id' => 155,
            ),
            281 => 
            array (
                'id' => 1282,
                'name_kh' => 'ខ្ចាស់',
                'name_en' => 'Khchas',
                'code' => '171106',
                'province_id' => 17,
                'district_id' => 155,
            ),
            282 => 
            array (
                'id' => 1283,
                'name_kh' => 'ខ្នារពោធិ៍',
                'name_en' => 'Khnar Pou',
                'code' => '171107',
                'province_id' => 17,
                'district_id' => 155,
            ),
            283 => 
            array (
                'id' => 1284,
                'name_kh' => 'ពពេល',
                'name_en' => 'Popel',
                'code' => '171108',
                'province_id' => 17,
                'district_id' => 155,
            ),
            284 => 
            array (
                'id' => 1285,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '171109',
                'province_id' => 17,
                'district_id' => 155,
            ),
            285 => 
            array (
                'id' => 1286,
                'name_kh' => 'តាយ៉ែក',
                'name_en' => 'Ta Yaek',
                'code' => '171110',
                'province_id' => 17,
                'district_id' => 155,
            ),
            286 => 
            array (
                'id' => 1287,
                'name_kh' => 'ជ្រោយនាងងួន',
                'name_en' => 'Chrouy Neang Nguon',
                'code' => '171201',
                'province_id' => 17,
                'district_id' => 156,
            ),
            287 => 
            array (
                'id' => 1288,
                'name_kh' => 'ក្លាំងហាយ',
                'name_en' => 'Klang Hay',
                'code' => '171202',
                'province_id' => 17,
                'district_id' => 156,
            ),
            288 => 
            array (
                'id' => 1289,
                'name_kh' => 'ត្រាំសសរ',
                'name_en' => 'Tram Sasar',
                'code' => '171203',
                'province_id' => 17,
                'district_id' => 156,
            ),
            289 => 
            array (
                'id' => 1290,
                'name_kh' => 'មោង',
                'name_en' => 'Moung',
                'code' => '171204',
                'province_id' => 17,
                'district_id' => 156,
            ),
            290 => 
            array (
                'id' => 1291,
                'name_kh' => 'ប្រីយ៍',
                'name_en' => 'Prei',
                'code' => '171205',
                'province_id' => 17,
                'district_id' => 156,
            ),
            291 => 
            array (
                'id' => 1292,
                'name_kh' => 'ស្លែងស្ពាន',
                'name_en' => 'Slaeng Spean',
                'code' => '171206',
                'province_id' => 17,
                'district_id' => 156,
            ),
            292 => 
            array (
                'id' => 1293,
                'name_kh' => 'បឹងមាលា',
                'name_en' => 'Boeng Mealea',
                'code' => '171301',
                'province_id' => 17,
                'district_id' => 157,
            ),
            293 => 
            array (
                'id' => 1294,
                'name_kh' => 'កន្ទួត',
                'name_en' => 'Kantuot',
                'code' => '171302',
                'province_id' => 17,
                'district_id' => 157,
            ),
            294 => 
            array (
                'id' => 1295,
                'name_kh' => 'ខ្នងភ្នំ',
                'name_en' => 'Khnang Phnum',
                'code' => '171303',
                'province_id' => 17,
                'district_id' => 157,
            ),
            295 => 
            array (
                'id' => 1296,
                'name_kh' => 'ស្វាយលើ',
                'name_en' => 'Svay Leu',
                'code' => '171304',
                'province_id' => 17,
                'district_id' => 157,
            ),
            296 => 
            array (
                'id' => 1297,
                'name_kh' => 'តាសៀម',
                'name_en' => 'Ta Siem',
                'code' => '171305',
                'province_id' => 17,
                'district_id' => 157,
            ),
            297 => 
            array (
                'id' => 1298,
                'name_kh' => 'ប្រាសាទ',
                'name_en' => 'Prasat',
                'code' => '171401',
                'province_id' => 17,
                'district_id' => 158,
            ),
            298 => 
            array (
                'id' => 1299,
                'name_kh' => 'ល្វាក្រាំង',
                'name_en' => 'Lvea Krang',
                'code' => '171402',
                'province_id' => 17,
                'district_id' => 158,
            ),
            299 => 
            array (
                'id' => 1300,
                'name_kh' => 'ស្រែណូយ',
                'name_en' => 'Srae Nouy',
                'code' => '171403',
                'province_id' => 17,
                'district_id' => 158,
            ),
            300 => 
            array (
                'id' => 1301,
                'name_kh' => 'ស្វាយ ស',
                'name_en' => 'Svay Sa',
                'code' => '171404',
                'province_id' => 17,
                'district_id' => 158,
            ),
            301 => 
            array (
                'id' => 1302,
                'name_kh' => 'វ៉ារិន',
                'name_en' => 'Varin',
                'code' => '171405',
                'province_id' => 17,
                'district_id' => 158,
            ),
            302 => 
            array (
                'id' => 1303,
                'name_kh' => 'លេខ១',
                'name_en' => 'lek Muoy',
                'code' => '180101',
                'province_id' => 18,
                'district_id' => 159,
            ),
            303 => 
            array (
                'id' => 1304,
                'name_kh' => '២',
                'name_en' => 'Pir',
                'code' => '180102',
                'province_id' => 18,
                'district_id' => 159,
            ),
            304 => 
            array (
                'id' => 1305,
                'name_kh' => '៣',
                'name_en' => 'Bei',
                'code' => '180103',
                'province_id' => 18,
                'district_id' => 159,
            ),
            305 => 
            array (
                'id' => 1306,
                'name_kh' => '៤',
                'name_en' => 'Buon',
                'code' => '180104',
                'province_id' => 18,
                'district_id' => 159,
            ),
            306 => 
            array (
                'id' => 1307,
                'name_kh' => 'អណ្ដូងថ្ម',
                'name_en' => 'Andoung Thma',
                'code' => '180201',
                'province_id' => 18,
                'district_id' => 160,
            ),
            307 => 
            array (
                'id' => 1308,
                'name_kh' => 'បឹងតាព្រហ្ម',
                'name_en' => 'Boeng Ta Prum',
                'code' => '180202',
                'province_id' => 18,
                'district_id' => 160,
            ),
            308 => 
            array (
                'id' => 1309,
                'name_kh' => 'បិតត្រាង',
                'name_en' => 'Bet Trang',
                'code' => '180203',
                'province_id' => 18,
                'district_id' => 160,
            ),
            309 => 
            array (
                'id' => 1310,
                'name_kh' => 'ជើងគោ',
                'name_en' => 'Cheung Kou',
                'code' => '180204',
                'province_id' => 18,
                'district_id' => 160,
            ),
            310 => 
            array (
                'id' => 1311,
                'name_kh' => 'អូរជ្រៅ',
                'name_en' => 'Ou Chrov',
                'code' => '180205',
                'province_id' => 18,
                'district_id' => 160,
            ),
            311 => 
            array (
                'id' => 1312,
                'name_kh' => 'អូរឧកញ៉ាហេង',
                'name_en' => 'Ou Oknha Heng',
                'code' => '180206',
                'province_id' => 18,
                'district_id' => 160,
            ),
            312 => 
            array (
                'id' => 1313,
                'name_kh' => 'ព្រៃនប់',
                'name_en' => 'Prey Nob',
                'code' => '180207',
                'province_id' => 18,
                'district_id' => 160,
            ),
            313 => 
            array (
                'id' => 1314,
                'name_kh' => 'រាម',
                'name_en' => 'Ream',
                'code' => '180208',
                'province_id' => 18,
                'district_id' => 160,
            ),
            314 => 
            array (
                'id' => 1315,
                'name_kh' => 'សាមគ្គី',
                'name_en' => 'Sameakki',
                'code' => '180209',
                'province_id' => 18,
                'district_id' => 160,
            ),
            315 => 
            array (
                'id' => 1316,
                'name_kh' => 'សំរុង',
                'name_en' => 'Samrong',
                'code' => '180210',
                'province_id' => 18,
                'district_id' => 160,
            ),
            316 => 
            array (
                'id' => 1317,
                'name_kh' => 'ទឹកល្អក់',
                'name_en' => 'Tuek Lak',
                'code' => '180211',
                'province_id' => 18,
                'district_id' => 160,
            ),
            317 => 
            array (
                'id' => 1318,
                'name_kh' => 'ទឹកថ្លា',
                'name_en' => 'Tuek Thla',
                'code' => '180212',
                'province_id' => 18,
                'district_id' => 160,
            ),
            318 => 
            array (
                'id' => 1319,
                'name_kh' => 'ទួលទទឹង',
                'name_en' => 'Tuol Totueng',
                'code' => '180213',
                'province_id' => 18,
                'district_id' => 160,
            ),
            319 => 
            array (
                'id' => 1320,
                'name_kh' => 'វាលរេញ',
                'name_en' => 'Veal Renh',
                'code' => '180214',
                'province_id' => 18,
                'district_id' => 160,
            ),
            320 => 
            array (
                'id' => 1321,
                'name_kh' => 'តានៃ',
                'name_en' => 'Ta Ney',
                'code' => '180215',
                'province_id' => 18,
                'district_id' => 160,
            ),
            321 => 
            array (
                'id' => 1322,
                'name_kh' => 'កំពេញ',
                'name_en' => 'Kampenh',
                'code' => '180301',
                'province_id' => 18,
                'district_id' => 161,
            ),
            322 => 
            array (
                'id' => 1323,
                'name_kh' => 'អូរត្រេះ',
                'name_en' => 'Ou Treh',
                'code' => '180302',
                'province_id' => 18,
                'district_id' => 161,
            ),
            323 => 
            array (
                'id' => 1324,
                'name_kh' => 'ទំនប់រលក',
                'name_en' => 'Tumnob Rolok',
                'code' => '180303',
                'province_id' => 18,
                'district_id' => 161,
            ),
            324 => 
            array (
                'id' => 1325,
                'name_kh' => 'កែវផុស',
                'name_en' => 'Kaev Phos',
                'code' => '180304',
                'province_id' => 18,
                'district_id' => 161,
            ),
            325 => 
            array (
                'id' => 1326,
                'name_kh' => 'ចំការហ្លួង',
                'name_en' => 'Chamkar Luong',
                'code' => '180401',
                'province_id' => 18,
                'district_id' => 162,
            ),
            326 => 
            array (
                'id' => 1327,
                'name_kh' => 'កំពង់សីលា',
                'name_en' => 'Kampong Seila',
                'code' => '180402',
                'province_id' => 18,
                'district_id' => 162,
            ),
            327 => 
            array (
                'id' => 1328,
                'name_kh' => 'អូរបាក់រទេះ',
                'name_en' => 'Ou Bak Roteh',
                'code' => '180403',
                'province_id' => 18,
                'district_id' => 162,
            ),
            328 => 
            array (
                'id' => 1329,
                'name_kh' => 'ស្ទឹងឆាយ',
                'name_en' => 'Stueng Chhay',
                'code' => '180404',
                'province_id' => 18,
                'district_id' => 162,
            ),
            329 => 
            array (
                'id' => 1330,
                'name_kh' => 'កោះរ៉ុង',
                'name_en' => 'Kaoh Rung',
                'code' => '180501',
                'province_id' => 18,
                'district_id' => 163,
            ),
            330 => 
            array (
                'id' => 1331,
                'name_kh' => 'កោះរ៉ុងសន្លឹម',
                'name_en' => 'Koah Rung Sonlem',
                'code' => '180502',
                'province_id' => 18,
                'district_id' => 163,
            ),
            331 => 
            array (
                'id' => 1332,
                'name_kh' => 'កំភុន',
                'name_en' => 'Kamphun',
                'code' => '190101',
                'province_id' => 19,
                'district_id' => 164,
            ),
            332 => 
            array (
                'id' => 1333,
                'name_kh' => 'ក្បាលរមាស',
                'name_en' => 'Kbal Romeas',
                'code' => '190102',
                'province_id' => 19,
                'district_id' => 164,
            ),
            333 => 
            array (
                'id' => 1334,
                'name_kh' => 'ភ្លុក',
                'name_en' => 'Phluk',
                'code' => '190103',
                'province_id' => 19,
                'district_id' => 164,
            ),
            334 => 
            array (
                'id' => 1335,
                'name_kh' => 'សាមឃួយ',
                'name_en' => 'Samkhuoy',
                'code' => '190104',
                'province_id' => 19,
                'district_id' => 164,
            ),
            335 => 
            array (
                'id' => 1336,
                'name_kh' => 'ស្ដៅ',
                'name_en' => 'Sdau',
                'code' => '190105',
                'province_id' => 19,
                'district_id' => 164,
            ),
            336 => 
            array (
                'id' => 1337,
                'name_kh' => 'ស្រែគរ',
                'name_en' => 'Srae Kor',
                'code' => '190106',
                'province_id' => 19,
                'district_id' => 164,
            ),
            337 => 
            array (
                'id' => 1338,
                'name_kh' => 'តាឡាត',
                'name_en' => 'Ta Lat',
                'code' => '190107',
                'province_id' => 19,
                'district_id' => 164,
            ),
            338 => 
            array (
                'id' => 1339,
                'name_kh' => 'កោះព្រះ',
                'name_en' => 'Kaoh Preah',
                'code' => '190201',
                'province_id' => 19,
                'district_id' => 165,
            ),
            339 => 
            array (
                'id' => 1340,
                'name_kh' => 'កោះសំពាយ',
                'name_en' => 'Kaoh Sampeay',
                'code' => '190202',
                'province_id' => 19,
                'district_id' => 165,
            ),
            340 => 
            array (
                'id' => 1341,
                'name_kh' => 'កោះស្រឡាយ',
                'name_en' => 'Kaoh Sralay',
                'code' => '190203',
                'province_id' => 19,
                'district_id' => 165,
            ),
            341 => 
            array (
                'id' => 1342,
                'name_kh' => 'អូរម្រះ',
                'name_en' => 'Ou Mreah',
                'code' => '190204',
                'province_id' => 19,
                'district_id' => 165,
            ),
            342 => 
            array (
                'id' => 1343,
                'name_kh' => 'អូរឫស្សីកណ្ដាល',
                'name_en' => 'Ou Ruessei Kandal',
                'code' => '190205',
                'province_id' => 19,
                'district_id' => 165,
            ),
            343 => 
            array (
                'id' => 1344,
                'name_kh' => 'សៀមបូក',
                'name_en' => 'Siem Bouk',
                'code' => '190206',
                'province_id' => 19,
                'district_id' => 165,
            ),
            344 => 
            array (
                'id' => 1345,
                'name_kh' => 'ស្រែក្រសាំង',
                'name_en' => 'Srae Krasang',
                'code' => '190207',
                'province_id' => 19,
                'district_id' => 165,
            ),
            345 => 
            array (
                'id' => 1346,
                'name_kh' => 'ព្រែកមាស',
                'name_en' => 'Preaek Meas',
                'code' => '190301',
                'province_id' => 19,
                'district_id' => 166,
            ),
            346 => 
            array (
                'id' => 1347,
                'name_kh' => 'សេកុង',
                'name_en' => 'Sekong',
                'code' => '190302',
                'province_id' => 19,
                'district_id' => 166,
            ),
            347 => 
            array (
                'id' => 1348,
                'name_kh' => 'សន្ដិភាព',
                'name_en' => 'Santepheap',
                'code' => '190303',
                'province_id' => 19,
                'district_id' => 166,
            ),
            348 => 
            array (
                'id' => 1349,
                'name_kh' => 'ស្រែសំបូរ',
                'name_en' => 'Srae Sambour',
                'code' => '190304',
                'province_id' => 19,
                'district_id' => 166,
            ),
            349 => 
            array (
                'id' => 1350,
                'name_kh' => 'ថ្មកែវ',
                'name_en' => 'Tma Kaev',
                'code' => '190305',
                'province_id' => 19,
                'district_id' => 166,
            ),
            350 => 
            array (
                'id' => 1351,
                'name_kh' => 'ស្ទឹងត្រែង',
                'name_en' => 'Stueng Traeng',
                'code' => '190401',
                'province_id' => 19,
                'district_id' => 167,
            ),
            351 => 
            array (
                'id' => 1352,
                'name_kh' => 'ស្រះឫស្សី',
                'name_en' => 'Srah Ruessei',
                'code' => '190402',
                'province_id' => 19,
                'district_id' => 167,
            ),
            352 => 
            array (
                'id' => 1353,
                'name_kh' => 'ព្រះបាទ',
                'name_en' => 'Preah Bat',
                'code' => '190403',
                'province_id' => 19,
                'district_id' => 167,
            ),
            353 => 
            array (
                'id' => 1354,
                'name_kh' => 'សាមគ្គី',
                'name_en' => 'Sameakki',
                'code' => '190404',
                'province_id' => 19,
                'district_id' => 167,
            ),
            354 => 
            array (
                'id' => 1355,
                'name_kh' => 'អន្លង់ភេ',
                'name_en' => 'Anlong Phe',
                'code' => '190501',
                'province_id' => 19,
                'district_id' => 168,
            ),
            355 => 
            array (
                'id' => 1356,
                'name_kh' => 'ចំការលើ',
                'name_en' => 'Chamkar Leu',
                'code' => '190502',
                'province_id' => 19,
                'district_id' => 168,
            ),
            356 => 
            array (
                'id' => 1357,
                'name_kh' => 'កាំងចាម',
                'name_en' => 'Kang Cham',
                'code' => '190503',
                'province_id' => 19,
                'district_id' => 168,
            ),
            357 => 
            array (
                'id' => 1358,
                'name_kh' => 'អន្លង់ជ្រៃ',
                'name_en' => 'Anlong Chrey',
                'code' => '190505',
                'province_id' => 19,
                'district_id' => 168,
            ),
            358 => 
            array (
                'id' => 1359,
                'name_kh' => 'អូររ៉ៃ',
                'name_en' => 'Ou Rai',
                'code' => '190506',
                'province_id' => 19,
                'district_id' => 168,
            ),
            359 => 
            array (
                'id' => 1360,
                'name_kh' => 'សំអាង',
                'name_en' => 'Sam Ang',
                'code' => '190509',
                'province_id' => 19,
                'district_id' => 168,
            ),
            360 => 
            array (
                'id' => 1361,
                'name_kh' => 'ស្រែឫស្សី',
                'name_en' => 'Srae Ruessei',
                'code' => '190510',
                'province_id' => 19,
                'district_id' => 168,
            ),
            361 => 
            array (
                'id' => 1362,
                'name_kh' => 'ថាឡាបរិវ៉ាត់',
                'name_en' => 'Thala Barivat',
                'code' => '190511',
                'province_id' => 19,
                'district_id' => 168,
            ),
            362 => 
            array (
                'id' => 1363,
                'name_kh' => 'អូរស្វាយ',
                'name_en' => 'Ou Svay',
                'code' => '190601',
                'province_id' => 19,
                'district_id' => 169,
            ),
            363 => 
            array (
                'id' => 1364,
                'name_kh' => 'កោះស្នែង',
                'name_en' => 'Kaoh Snaeng',
                'code' => '190602',
                'province_id' => 19,
                'district_id' => 169,
            ),
            364 => 
            array (
                'id' => 1365,
                'name_kh' => 'ព្រះរំកិល',
                'name_en' => 'Preah Rumkel',
                'code' => '190603',
                'province_id' => 19,
                'district_id' => 169,
            ),
            365 => 
            array (
                'id' => 1366,
                'name_kh' => 'ចន្ទ្រា',
                'name_en' => 'Chantrea',
                'code' => '200103',
                'province_id' => 20,
                'district_id' => 170,
            ),
            366 => 
            array (
                'id' => 1367,
                'name_kh' => 'ច្រេស',
                'name_en' => 'Chres',
                'code' => '200104',
                'province_id' => 20,
                'district_id' => 170,
            ),
            367 => 
            array (
                'id' => 1368,
                'name_kh' => 'មេ សរថ្ងក',
                'name_en' => 'Me Sar Thngak',
                'code' => '200105',
                'province_id' => 20,
                'district_id' => 170,
            ),
            368 => 
            array (
                'id' => 1369,
                'name_kh' => 'ព្រៃគគីរ',
                'name_en' => 'Prey Kokir',
                'code' => '200108',
                'province_id' => 20,
                'district_id' => 170,
            ),
            369 => 
            array (
                'id' => 1370,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '200109',
                'province_id' => 20,
                'district_id' => 170,
            ),
            370 => 
            array (
                'id' => 1371,
                'name_kh' => 'ទួលស្ដី',
                'name_en' => 'Tuol Sdei',
                'code' => '200110',
                'province_id' => 20,
                'district_id' => 170,
            ),
            371 => 
            array (
                'id' => 1466,
                'name_kh' => 'ត្រពាំងសាប',
                'name_en' => 'Trapeang Sab',
                'code' => '210215',
                'province_id' => 21,
                'district_id' => 179,
            ),
            372 => 
            array (
                'id' => 1372,
                'name_kh' => 'បន្ទាយក្រាំង',
                'name_en' => 'Banteay Krang',
                'code' => '200201',
                'province_id' => 20,
                'district_id' => 171,
            ),
            373 => 
            array (
                'id' => 1373,
                'name_kh' => 'ញរ',
                'name_en' => 'Nhor',
                'code' => '200202',
                'province_id' => 20,
                'district_id' => 171,
            ),
            374 => 
            array (
                'id' => 1374,
                'name_kh' => 'ខ្សែត្រ',
                'name_en' => 'Khsaetr',
                'code' => '200203',
                'province_id' => 20,
                'district_id' => 171,
            ),
            375 => 
            array (
                'id' => 1375,
                'name_kh' => 'ព្រះពន្លា',
                'name_en' => 'Preah Ponlea',
                'code' => '200204',
                'province_id' => 20,
                'district_id' => 171,
            ),
            376 => 
            array (
                'id' => 1376,
                'name_kh' => 'ព្រៃធំ',
                'name_en' => 'Prey Thum',
                'code' => '200205',
                'province_id' => 20,
                'district_id' => 171,
            ),
            377 => 
            array (
                'id' => 1377,
                'name_kh' => 'រាជមន្ទីរ',
                'name_en' => 'Reach Montir',
                'code' => '200206',
                'province_id' => 20,
                'district_id' => 171,
            ),
            378 => 
            array (
                'id' => 1378,
                'name_kh' => 'សំឡី',
                'name_en' => 'Samlei',
                'code' => '200207',
                'province_id' => 20,
                'district_id' => 171,
            ),
            379 => 
            array (
                'id' => 1379,
                'name_kh' => 'សំយ៉ោង',
                'name_en' => 'Samyaong',
                'code' => '200208',
                'province_id' => 20,
                'district_id' => 171,
            ),
            380 => 
            array (
                'id' => 1380,
                'name_kh' => 'ស្វាយតាយាន',
                'name_en' => 'Svay Ta Yean',
                'code' => '200209',
                'province_id' => 20,
                'district_id' => 171,
            ),
            381 => 
            array (
                'id' => 1381,
                'name_kh' => 'ថ្មី',
                'name_en' => 'Thmei',
                'code' => '200211',
                'province_id' => 20,
                'district_id' => 171,
            ),
            382 => 
            array (
                'id' => 1382,
                'name_kh' => 'ត្នោត',
                'name_en' => 'Tnaot',
                'code' => '200212',
                'province_id' => 20,
                'district_id' => 171,
            ),
            383 => 
            array (
                'id' => 1383,
                'name_kh' => 'បុសមន',
                'name_en' => 'Bos Mon',
                'code' => '200301',
                'province_id' => 20,
                'district_id' => 172,
            ),
            384 => 
            array (
                'id' => 1384,
                'name_kh' => 'ធ្មា',
                'name_en' => 'Thmea',
                'code' => '200302',
                'province_id' => 20,
                'district_id' => 172,
            ),
            385 => 
            array (
                'id' => 1385,
                'name_kh' => 'កំពង់ចក',
                'name_en' => 'Kampong Chak',
                'code' => '200303',
                'province_id' => 20,
                'district_id' => 172,
            ),
            386 => 
            array (
                'id' => 1386,
                'name_kh' => 'ជ្រុងពពេល',
                'name_en' => 'Chrung Popel',
                'code' => '200304',
                'province_id' => 20,
                'district_id' => 172,
            ),
            387 => 
            array (
                'id' => 1387,
                'name_kh' => 'កំពង់អំពិល',
                'name_en' => 'Kampong Ampil',
                'code' => '200305',
                'province_id' => 20,
                'district_id' => 172,
            ),
            388 => 
            array (
                'id' => 1388,
                'name_kh' => 'ម៉ឺនជ័យ',
                'name_en' => 'Meun Chey',
                'code' => '200306',
                'province_id' => 20,
                'district_id' => 172,
            ),
            389 => 
            array (
                'id' => 1389,
                'name_kh' => 'ពងទឹក',
                'name_en' => 'Pong Tuek',
                'code' => '200307',
                'province_id' => 20,
                'district_id' => 172,
            ),
            390 => 
            array (
                'id' => 1390,
                'name_kh' => 'សង្កែ',
                'name_en' => 'Sangkae',
                'code' => '200308',
                'province_id' => 20,
                'district_id' => 172,
            ),
            391 => 
            array (
                'id' => 1391,
                'name_kh' => 'ស្វាយចេក',
                'name_en' => 'Svay Chek',
                'code' => '200309',
                'province_id' => 20,
                'district_id' => 172,
            ),
            392 => 
            array (
                'id' => 1392,
                'name_kh' => 'ថ្នាធ្នង់',
                'name_en' => 'Thna Thnong',
                'code' => '200310',
                'province_id' => 20,
                'district_id' => 172,
            ),
            393 => 
            array (
                'id' => 1393,
                'name_kh' => 'អំពិល',
                'name_en' => 'Ampil',
                'code' => '200401',
                'province_id' => 20,
                'district_id' => 173,
            ),
            394 => 
            array (
                'id' => 1394,
                'name_kh' => 'អណ្ដូងពោធិ៍',
                'name_en' => 'Andoung Pou',
                'code' => '200402',
                'province_id' => 20,
                'district_id' => 173,
            ),
            395 => 
            array (
                'id' => 1395,
                'name_kh' => 'អណ្ដូងត្របែក',
                'name_en' => 'Andoung Trabaek',
                'code' => '200403',
                'province_id' => 20,
                'district_id' => 173,
            ),
            396 => 
            array (
                'id' => 1396,
                'name_kh' => 'អង្គប្រស្រែ',
                'name_en' => 'Angk Prasrae',
                'code' => '200404',
                'province_id' => 20,
                'district_id' => 173,
            ),
            397 => 
            array (
                'id' => 1397,
                'name_kh' => 'ចន្ដ្រី',
                'name_en' => 'Chantrei',
                'code' => '200405',
                'province_id' => 20,
                'district_id' => 173,
            ),
            398 => 
            array (
                'id' => 1398,
                'name_kh' => 'ជ្រៃធំ',
                'name_en' => 'Chrey Thum',
                'code' => '200406',
                'province_id' => 20,
                'district_id' => 173,
            ),
            399 => 
            array (
                'id' => 1399,
                'name_kh' => 'ដូង',
                'name_en' => 'Doung',
                'code' => '200407',
                'province_id' => 20,
                'district_id' => 173,
            ),
            400 => 
            array (
                'id' => 1400,
                'name_kh' => 'កំពង់ត្រាច',
                'name_en' => 'Kampong Trach',
                'code' => '200408',
                'province_id' => 20,
                'district_id' => 173,
            ),
            401 => 
            array (
                'id' => 1401,
                'name_kh' => 'គគីរ',
                'name_en' => 'Kokir',
                'code' => '200409',
                'province_id' => 20,
                'district_id' => 173,
            ),
            402 => 
            array (
                'id' => 1402,
                'name_kh' => 'ក្រសាំង',
                'name_en' => 'Krasang',
                'code' => '200410',
                'province_id' => 20,
                'district_id' => 173,
            ),
            403 => 
            array (
                'id' => 1403,
                'name_kh' => 'មុខដា',
                'name_en' => 'Mukh Da',
                'code' => '200411',
                'province_id' => 20,
                'district_id' => 173,
            ),
            404 => 
            array (
                'id' => 1404,
                'name_kh' => 'ម្រាម',
                'name_en' => 'Mream',
                'code' => '200412',
                'province_id' => 20,
                'district_id' => 173,
            ),
            405 => 
            array (
                'id' => 1405,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sambuor',
                'code' => '200413',
                'province_id' => 20,
                'district_id' => 173,
            ),
            406 => 
            array (
                'id' => 1406,
                'name_kh' => 'សម្បត្ដិមានជ័យ',
                'name_en' => 'Sambatt Mean Chey',
                'code' => '200414',
                'province_id' => 20,
                'district_id' => 173,
            ),
            407 => 
            array (
                'id' => 1407,
                'name_kh' => 'ត្រពាំងស្ដៅ',
                'name_en' => 'Trapeang Sdau',
                'code' => '200415',
                'province_id' => 20,
                'district_id' => 173,
            ),
            408 => 
            array (
                'id' => 1408,
                'name_kh' => 'ត្រស់',
                'name_en' => 'Tras',
                'code' => '200416',
                'province_id' => 20,
                'district_id' => 173,
            ),
            409 => 
            array (
                'id' => 1409,
                'name_kh' => 'អង្គតាសូ',
                'name_en' => 'Angk Ta Sou',
                'code' => '200501',
                'province_id' => 20,
                'district_id' => 174,
            ),
            410 => 
            array (
                'id' => 1410,
                'name_kh' => 'បាសាក់',
                'name_en' => 'Basak',
                'code' => '200502',
                'province_id' => 20,
                'district_id' => 174,
            ),
            411 => 
            array (
                'id' => 1411,
                'name_kh' => 'ចំបក់',
                'name_en' => 'Chambak',
                'code' => '200503',
                'province_id' => 20,
                'district_id' => 174,
            ),
            412 => 
            array (
                'id' => 1412,
                'name_kh' => 'កំពង់ចំឡង',
                'name_en' => 'Kampong Chamlang',
                'code' => '200504',
                'province_id' => 20,
                'district_id' => 174,
            ),
            413 => 
            array (
                'id' => 1413,
                'name_kh' => 'តាសួស',
                'name_en' => 'Ta Suos',
                'code' => '200505',
                'province_id' => 20,
                'district_id' => 174,
            ),
            414 => 
            array (
                'id' => 1414,
                'name_kh' => 'ឈើទាល',
                'name_en' => 'Chheu Teal',
                'code' => '200507',
                'province_id' => 20,
                'district_id' => 174,
            ),
            415 => 
            array (
                'id' => 1415,
                'name_kh' => 'ដូនស',
                'name_en' => 'Doun Sa',
                'code' => '200508',
                'province_id' => 20,
                'district_id' => 174,
            ),
            416 => 
            array (
                'id' => 1416,
                'name_kh' => 'គោកព្រីង',
                'name_en' => 'Kouk Pring',
                'code' => '200509',
                'province_id' => 20,
                'district_id' => 174,
            ),
            417 => 
            array (
                'id' => 1417,
                'name_kh' => 'ក្រោលគោ',
                'name_en' => 'Kraol Kou',
                'code' => '200510',
                'province_id' => 20,
                'district_id' => 174,
            ),
            418 => 
            array (
                'id' => 1418,
                'name_kh' => 'គ្រួស',
                'name_en' => 'Kruos',
                'code' => '200511',
                'province_id' => 20,
                'district_id' => 174,
            ),
            419 => 
            array (
                'id' => 1419,
                'name_kh' => 'ពោធិរាជ',
                'name_en' => 'Pouthi Reach',
                'code' => '200512',
                'province_id' => 20,
                'district_id' => 174,
            ),
            420 => 
            array (
                'id' => 1420,
                'name_kh' => 'ស្វាយអង្គ',
                'name_en' => 'Svay Angk',
                'code' => '200513',
                'province_id' => 20,
                'district_id' => 174,
            ),
            421 => 
            array (
                'id' => 1421,
                'name_kh' => 'ស្វាយជ្រំ',
                'name_en' => 'Svay Chrum',
                'code' => '200514',
                'province_id' => 20,
                'district_id' => 174,
            ),
            422 => 
            array (
                'id' => 1422,
                'name_kh' => 'ស្វាយធំ',
                'name_en' => 'Svay Thum',
                'code' => '200515',
                'province_id' => 20,
                'district_id' => 174,
            ),
            423 => 
            array (
                'id' => 1423,
                'name_kh' => 'ស្វាយយា',
                'name_en' => 'Svay Yea',
                'code' => '200516',
                'province_id' => 20,
                'district_id' => 174,
            ),
            424 => 
            array (
                'id' => 1424,
                'name_kh' => 'ធ្លក',
                'name_en' => 'Thlok',
                'code' => '200517',
                'province_id' => 20,
                'district_id' => 174,
            ),
            425 => 
            array (
                'id' => 1425,
                'name_kh' => 'ស្វាយរៀង',
                'name_en' => 'Svay Rieng',
                'code' => '200601',
                'province_id' => 20,
                'district_id' => 175,
            ),
            426 => 
            array (
                'id' => 1426,
                'name_kh' => 'ព្រៃឆ្លាក់',
                'name_en' => 'Prey Chhlak',
                'code' => '200602',
                'province_id' => 20,
                'district_id' => 175,
            ),
            427 => 
            array (
                'id' => 1427,
                'name_kh' => 'គយត្របែក',
                'name_en' => 'Koy Trabaek',
                'code' => '200603',
                'province_id' => 20,
                'district_id' => 175,
            ),
            428 => 
            array (
                'id' => 1428,
                'name_kh' => 'ពោធិ៍តាហោ',
                'name_en' => 'Pou Ta Hao',
                'code' => '200604',
                'province_id' => 20,
                'district_id' => 175,
            ),
            429 => 
            array (
                'id' => 1429,
                'name_kh' => 'ចេក',
                'name_en' => 'Chek',
                'code' => '200605',
                'province_id' => 20,
                'district_id' => 175,
            ),
            430 => 
            array (
                'id' => 1430,
                'name_kh' => 'ស្វាយតឿ',
                'name_en' => 'Svay Toea',
                'code' => '200606',
                'province_id' => 20,
                'district_id' => 175,
            ),
            431 => 
            array (
                'id' => 1431,
                'name_kh' => 'សង្ឃរ័',
                'name_en' => 'Sangkhoar',
                'code' => '200607',
                'province_id' => 20,
                'district_id' => 175,
            ),
            432 => 
            array (
                'id' => 1432,
                'name_kh' => 'គគីសោម',
                'name_en' => 'Koki Saom',
                'code' => '200702',
                'province_id' => 20,
                'district_id' => 176,
            ),
            433 => 
            array (
                'id' => 1433,
                'name_kh' => 'កណ្ដៀងរាយ',
                'name_en' => 'Kandieng Reay',
                'code' => '200703',
                'province_id' => 20,
                'district_id' => 176,
            ),
            434 => 
            array (
                'id' => 1434,
                'name_kh' => 'មនោរម្យ',
                'name_en' => 'Monourom',
                'code' => '200704',
                'province_id' => 20,
                'district_id' => 176,
            ),
            435 => 
            array (
                'id' => 1435,
                'name_kh' => 'ពពែត',
                'name_en' => 'Popeaet',
                'code' => '200705',
                'province_id' => 20,
                'district_id' => 176,
            ),
            436 => 
            array (
                'id' => 1436,
                'name_kh' => 'ព្រៃតាអី',
                'name_en' => 'Prey Ta Ei',
                'code' => '200706',
                'province_id' => 20,
                'district_id' => 176,
            ),
            437 => 
            array (
                'id' => 1437,
                'name_kh' => 'ប្រសូត្រ',
                'name_en' => 'Prasoutr',
                'code' => '200707',
                'province_id' => 20,
                'district_id' => 176,
            ),
            438 => 
            array (
                'id' => 1438,
                'name_kh' => 'រមាំងថ្កោល',
                'name_en' => 'Romeang Thkaol',
                'code' => '200708',
                'province_id' => 20,
                'district_id' => 176,
            ),
            439 => 
            array (
                'id' => 1439,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sambuor',
                'code' => '200709',
                'province_id' => 20,
                'district_id' => 176,
            ),
            440 => 
            array (
                'id' => 1440,
                'name_kh' => 'ស្វាយរំពារ',
                'name_en' => 'Svay Rumpear',
                'code' => '200711',
                'province_id' => 20,
                'district_id' => 176,
            ),
            441 => 
            array (
                'id' => 1441,
                'name_kh' => 'បាទី',
                'name_en' => 'Bati',
                'code' => '200801',
                'province_id' => 20,
                'district_id' => 177,
            ),
            442 => 
            array (
                'id' => 1442,
                'name_kh' => 'បាវិត',
                'name_en' => 'Bavet',
                'code' => '200802',
                'province_id' => 20,
                'district_id' => 177,
            ),
            443 => 
            array (
                'id' => 1443,
                'name_kh' => 'ច្រកម្ទេស',
                'name_en' => 'Chrak Mtes',
                'code' => '200803',
                'province_id' => 20,
                'district_id' => 177,
            ),
            444 => 
            array (
                'id' => 1444,
                'name_kh' => 'ប្រាសាទ',
                'name_en' => 'Prasat',
                'code' => '200804',
                'province_id' => 20,
                'district_id' => 177,
            ),
            445 => 
            array (
                'id' => 1445,
                'name_kh' => 'ព្រៃអង្គុញ',
                'name_en' => 'Prey Angkunh',
                'code' => '200805',
                'province_id' => 20,
                'district_id' => 177,
            ),
            446 => 
            array (
                'id' => 1446,
                'name_kh' => 'អង្គរបូរី',
                'name_en' => 'Angkor Borei',
                'code' => '210101',
                'province_id' => 21,
                'district_id' => 178,
            ),
            447 => 
            array (
                'id' => 1447,
                'name_kh' => 'បាស្រែ',
                'name_en' => 'Ba Srae',
                'code' => '210102',
                'province_id' => 21,
                'district_id' => 178,
            ),
            448 => 
            array (
                'id' => 1448,
                'name_kh' => 'គោកធ្លក',
                'name_en' => 'Kouk Thlok',
                'code' => '210103',
                'province_id' => 21,
                'district_id' => 178,
            ),
            449 => 
            array (
                'id' => 1449,
                'name_kh' => 'ពន្លៃ',
                'name_en' => 'Ponley',
                'code' => '210104',
                'province_id' => 21,
                'district_id' => 178,
            ),
            450 => 
            array (
                'id' => 1450,
                'name_kh' => 'ព្រែកផ្ទោល',
                'name_en' => 'Preaek Phtoul',
                'code' => '210105',
                'province_id' => 21,
                'district_id' => 178,
            ),
            451 => 
            array (
                'id' => 1451,
                'name_kh' => 'ព្រៃផ្គាំ',
                'name_en' => 'Prey Phkoam',
                'code' => '210106',
                'province_id' => 21,
                'district_id' => 178,
            ),
            452 => 
            array (
                'id' => 1452,
                'name_kh' => 'ចំបក់',
                'name_en' => 'Chambak',
                'code' => '210201',
                'province_id' => 21,
                'district_id' => 179,
            ),
            453 => 
            array (
                'id' => 1453,
                'name_kh' => 'ចំប៉ី',
                'name_en' => 'Champei',
                'code' => '210202',
                'province_id' => 21,
                'district_id' => 179,
            ),
            454 => 
            array (
                'id' => 1454,
                'name_kh' => 'ដូង',
                'name_en' => 'Doung',
                'code' => '210203',
                'province_id' => 21,
                'district_id' => 179,
            ),
            455 => 
            array (
                'id' => 1455,
                'name_kh' => 'កណ្ដឹង',
                'name_en' => 'Kandoeng',
                'code' => '210204',
                'province_id' => 21,
                'district_id' => 179,
            ),
            456 => 
            array (
                'id' => 1456,
                'name_kh' => 'កុមាររាជា',
                'name_en' => 'Komar Reachea',
                'code' => '210205',
                'province_id' => 21,
                'district_id' => 179,
            ),
            457 => 
            array (
                'id' => 1457,
                'name_kh' => 'ក្រាំងលាវ',
                'name_en' => 'Krang Leav',
                'code' => '210206',
                'province_id' => 21,
                'district_id' => 179,
            ),
            458 => 
            array (
                'id' => 1458,
                'name_kh' => 'ក្រាំងធ្នង់',
                'name_en' => 'Krang Thnong',
                'code' => '210207',
                'province_id' => 21,
                'district_id' => 179,
            ),
            459 => 
            array (
                'id' => 1459,
                'name_kh' => 'លំពង់',
                'name_en' => 'Lumpong',
                'code' => '210208',
                'province_id' => 21,
                'district_id' => 179,
            ),
            460 => 
            array (
                'id' => 1460,
                'name_kh' => 'ពារាម',
                'name_en' => 'Pea Ream',
                'code' => '210209',
                'province_id' => 21,
                'district_id' => 179,
            ),
            461 => 
            array (
                'id' => 1461,
                'name_kh' => 'ពត់សរ',
                'name_en' => 'Pot Sar',
                'code' => '210210',
                'province_id' => 21,
                'district_id' => 179,
            ),
            462 => 
            array (
                'id' => 1462,
                'name_kh' => 'សូរភី',
                'name_en' => 'Sour Phi',
                'code' => '210211',
                'province_id' => 21,
                'district_id' => 179,
            ),
            463 => 
            array (
                'id' => 1463,
                'name_kh' => 'តាំងដូង',
                'name_en' => 'Tang Doung',
                'code' => '210212',
                'province_id' => 21,
                'district_id' => 179,
            ),
            464 => 
            array (
                'id' => 1464,
                'name_kh' => 'ត្នោត',
                'name_en' => 'Tnaot',
                'code' => '210213',
                'province_id' => 21,
                'district_id' => 179,
            ),
            465 => 
            array (
                'id' => 1465,
                'name_kh' => 'ត្រពាំងក្រសាំង',
                'name_en' => 'Trapeang Krasang',
                'code' => '210214',
                'province_id' => 21,
                'district_id' => 179,
            ),
            466 => 
            array (
                'id' => 1467,
                'name_kh' => 'បូរីជលសារ',
                'name_en' => 'Borei Cholsar',
                'code' => '210301',
                'province_id' => 21,
                'district_id' => 180,
            ),
            467 => 
            array (
                'id' => 1468,
                'name_kh' => 'ជ័យជោគ',
                'name_en' => 'Chey Chouk',
                'code' => '210302',
                'province_id' => 21,
                'district_id' => 180,
            ),
            468 => 
            array (
                'id' => 1469,
                'name_kh' => 'ដូងខ្ពស់',
                'name_en' => 'Doung Khpos',
                'code' => '210303',
                'province_id' => 21,
                'district_id' => 180,
            ),
            469 => 
            array (
                'id' => 1470,
                'name_kh' => 'កំពង់ក្រសាំង',
                'name_en' => 'Kampong Krasang',
                'code' => '210304',
                'province_id' => 21,
                'district_id' => 180,
            ),
            470 => 
            array (
                'id' => 1471,
                'name_kh' => 'គោកពោធិ៍',
                'name_en' => 'Kouk Pou',
                'code' => '210305',
                'province_id' => 21,
                'district_id' => 180,
            ),
            471 => 
            array (
                'id' => 1472,
                'name_kh' => 'អង្គប្រាសាទ',
                'name_en' => 'Angk Prasat',
                'code' => '210401',
                'province_id' => 21,
                'district_id' => 181,
            ),
            472 => 
            array (
                'id' => 1473,
                'name_kh' => 'ព្រះបាទជាន់ជុំ',
                'name_en' => 'Preah Bat Choan Chum',
                'code' => '210402',
                'province_id' => 21,
                'district_id' => 181,
            ),
            473 => 
            array (
                'id' => 1474,
                'name_kh' => 'កំណប់',
                'name_en' => 'Kamnab',
                'code' => '210403',
                'province_id' => 21,
                'district_id' => 181,
            ),
            474 => 
            array (
                'id' => 1475,
                'name_kh' => 'កំពែង',
                'name_en' => 'Kampeaeng',
                'code' => '210404',
                'province_id' => 21,
                'district_id' => 181,
            ),
            475 => 
            array (
                'id' => 1476,
                'name_kh' => 'គីរីចុងកោះ',
                'name_en' => 'Kiri Chong Kaoh',
                'code' => '210405',
                'province_id' => 21,
                'district_id' => 181,
            ),
            476 => 
            array (
                'id' => 1477,
                'name_kh' => 'គោកព្រេច',
                'name_en' => 'Kouk Prech',
                'code' => '210406',
                'province_id' => 21,
                'district_id' => 181,
            ),
            477 => 
            array (
                'id' => 1478,
                'name_kh' => 'ភ្នំដិន',
                'name_en' => 'Phnum Den',
                'code' => '210407',
                'province_id' => 21,
                'district_id' => 181,
            ),
            478 => 
            array (
                'id' => 1479,
                'name_kh' => 'ព្រៃអំពក',
                'name_en' => 'Prey Ampok',
                'code' => '210408',
                'province_id' => 21,
                'district_id' => 181,
            ),
            479 => 
            array (
                'id' => 1480,
                'name_kh' => 'ព្រៃរំដេង',
                'name_en' => 'Prey Rumdeng',
                'code' => '210409',
                'province_id' => 21,
                'district_id' => 181,
            ),
            480 => 
            array (
                'id' => 1481,
                'name_kh' => 'រាមអណ្ដើក',
                'name_en' => 'Ream Andaeuk',
                'code' => '210410',
                'province_id' => 21,
                'district_id' => 181,
            ),
            481 => 
            array (
                'id' => 1482,
                'name_kh' => 'សោម',
                'name_en' => 'Saom',
                'code' => '210411',
                'province_id' => 21,
                'district_id' => 181,
            ),
            482 => 
            array (
                'id' => 1483,
                'name_kh' => 'តាអូរ',
                'name_en' => 'Ta Ou',
                'code' => '210412',
                'province_id' => 21,
                'district_id' => 181,
            ),
            483 => 
            array (
                'id' => 1484,
                'name_kh' => 'ក្រពុំឈូក',
                'name_en' => 'Krapum Chhuk',
                'code' => '210501',
                'province_id' => 21,
                'district_id' => 182,
            ),
            484 => 
            array (
                'id' => 1485,
                'name_kh' => 'ពេជសារ',
                'name_en' => 'Pech Sar',
                'code' => '210502',
                'province_id' => 21,
                'district_id' => 182,
            ),
            485 => 
            array (
                'id' => 1486,
                'name_kh' => 'ព្រៃខ្លា',
                'name_en' => 'Prey Khla',
                'code' => '210503',
                'province_id' => 21,
                'district_id' => 182,
            ),
            486 => 
            array (
                'id' => 1487,
                'name_kh' => 'ព្រៃយុថ្កា',
                'name_en' => 'Prey Yuthka',
                'code' => '210504',
                'province_id' => 21,
                'district_id' => 182,
            ),
            487 => 
            array (
                'id' => 1488,
                'name_kh' => 'រមេញ',
                'name_en' => 'Romenh',
                'code' => '210505',
                'province_id' => 21,
                'district_id' => 182,
            ),
            488 => 
            array (
                'id' => 1489,
                'name_kh' => 'ធ្លាប្រជុំ',
                'name_en' => 'Thlea Prachum',
                'code' => '210506',
                'province_id' => 21,
                'district_id' => 182,
            ),
            489 => 
            array (
                'id' => 1490,
                'name_kh' => 'អង្កាញ់',
                'name_en' => 'Angkanh',
                'code' => '210601',
                'province_id' => 21,
                'district_id' => 183,
            ),
            490 => 
            array (
                'id' => 1491,
                'name_kh' => 'បានកាម',
                'name_en' => 'Ban Kam',
                'code' => '210602',
                'province_id' => 21,
                'district_id' => 183,
            ),
            491 => 
            array (
                'id' => 1492,
                'name_kh' => 'ចំប៉ា',
                'name_en' => 'Champa',
                'code' => '210603',
                'province_id' => 21,
                'district_id' => 183,
            ),
            492 => 
            array (
                'id' => 1493,
                'name_kh' => 'ចារ',
                'name_en' => 'Char',
                'code' => '210604',
                'province_id' => 21,
                'district_id' => 183,
            ),
            493 => 
            array (
                'id' => 1494,
                'name_kh' => 'កំពែង',
                'name_en' => 'Kampeaeng',
                'code' => '210605',
                'province_id' => 21,
                'district_id' => 183,
            ),
            494 => 
            array (
                'id' => 1495,
                'name_kh' => 'កំពង់រាប',
                'name_en' => 'Kampong Reab',
                'code' => '210606',
                'province_id' => 21,
                'district_id' => 183,
            ),
            495 => 
            array (
                'id' => 1496,
                'name_kh' => 'ក្ដាញ់',
                'name_en' => 'Kdanh',
                'code' => '210607',
                'province_id' => 21,
                'district_id' => 183,
            ),
            496 => 
            array (
                'id' => 1497,
                'name_kh' => 'ពោធិ៍រំចាក',
                'name_en' => 'Pou Rumchak',
                'code' => '210608',
                'province_id' => 21,
                'district_id' => 183,
            ),
            497 => 
            array (
                'id' => 1498,
                'name_kh' => 'ព្រៃកប្បាស',
                'name_en' => 'Prey Kabbas',
                'code' => '210609',
                'province_id' => 21,
                'district_id' => 183,
            ),
            498 => 
            array (
                'id' => 1499,
                'name_kh' => 'ព្រៃល្វា',
                'name_en' => 'Prey Lvea',
                'code' => '210610',
                'province_id' => 21,
                'district_id' => 183,
            ),
            499 => 
            array (
                'id' => 1500,
                'name_kh' => 'ព្រៃផ្ដៅ',
                'name_en' => 'Prey Phdau',
                'code' => '210611',
                'province_id' => 21,
                'district_id' => 183,
            ),
        ));
        \DB::table('communes')->insert(array (
            0 => 
            array (
                'id' => 1501,
                'name_kh' => 'ស្នោ',
                'name_en' => 'Snao',
                'code' => '210612',
                'province_id' => 21,
                'district_id' => 183,
            ),
            1 => 
            array (
                'id' => 1502,
                'name_kh' => 'តាំងយ៉ាប',
                'name_en' => 'Tang Yab',
                'code' => '210613',
                'province_id' => 21,
                'district_id' => 183,
            ),
            2 => 
            array (
                'id' => 1503,
                'name_kh' => 'បឹងត្រាញ់ខាងជើង',
                'name_en' => 'Boeng Tranh Khang Cheung',
                'code' => '210701',
                'province_id' => 21,
                'district_id' => 184,
            ),
            3 => 
            array (
                'id' => 1504,
                'name_kh' => 'បឹងត្រាញ់ខាងត្បូង',
                'name_en' => 'Boeng Tranh Khang Tboung',
                'code' => '210702',
                'province_id' => 21,
                'district_id' => 184,
            ),
            4 => 
            array (
                'id' => 1505,
                'name_kh' => 'ជើងគួន',
                'name_en' => 'Cheung Kuon',
                'code' => '210703',
                'province_id' => 21,
                'district_id' => 184,
            ),
            5 => 
            array (
                'id' => 1506,
                'name_kh' => 'ជំរះពេន',
                'name_en' => 'Chumreah Pen',
                'code' => '210704',
                'province_id' => 21,
                'district_id' => 184,
            ),
            6 => 
            array (
                'id' => 1507,
                'name_kh' => 'ខ្វាវ',
                'name_en' => 'Khvav',
                'code' => '210705',
                'province_id' => 21,
                'district_id' => 184,
            ),
            7 => 
            array (
                'id' => 1508,
                'name_kh' => 'លំចង់',
                'name_en' => 'Lumchang',
                'code' => '210706',
                'province_id' => 21,
                'district_id' => 184,
            ),
            8 => 
            array (
                'id' => 1509,
                'name_kh' => 'រវៀង',
                'name_en' => 'Rovieng',
                'code' => '210707',
                'province_id' => 21,
                'district_id' => 184,
            ),
            9 => 
            array (
                'id' => 1510,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '210708',
                'province_id' => 21,
                'district_id' => 184,
            ),
            10 => 
            array (
                'id' => 1511,
                'name_kh' => 'សឹង្ហ',
                'name_en' => 'Soengh',
                'code' => '210709',
                'province_id' => 21,
                'district_id' => 184,
            ),
            11 => 
            array (
                'id' => 1512,
                'name_kh' => 'ស្លា',
                'name_en' => 'Sla',
                'code' => '210710',
                'province_id' => 21,
                'district_id' => 184,
            ),
            12 => 
            array (
                'id' => 1513,
                'name_kh' => 'ទ្រា',
                'name_en' => 'Trea',
                'code' => '210711',
                'province_id' => 21,
                'district_id' => 184,
            ),
            13 => 
            array (
                'id' => 1514,
                'name_kh' => 'បារាយណ៍',
                'name_en' => 'Baray',
                'code' => '210801',
                'province_id' => 21,
                'district_id' => 185,
            ),
            14 => 
            array (
                'id' => 1515,
                'name_kh' => 'រកាក្នុង',
                'name_en' => 'Roka Knong',
                'code' => '210802',
                'province_id' => 21,
                'district_id' => 185,
            ),
            15 => 
            array (
                'id' => 1516,
                'name_kh' => 'រកាក្រៅ',
                'name_en' => 'Roka Krau',
                'code' => '210803',
                'province_id' => 21,
                'district_id' => 185,
            ),
            16 => 
            array (
                'id' => 1517,
                'name_kh' => 'អង្គតាសោម',
                'name_en' => 'Angk Ta Saom',
                'code' => '210901',
                'province_id' => 21,
                'district_id' => 186,
            ),
            17 => 
            array (
                'id' => 1518,
                'name_kh' => 'ជាងទង',
                'name_en' => 'Cheang Tong',
                'code' => '210902',
                'province_id' => 21,
                'district_id' => 186,
            ),
            18 => 
            array (
                'id' => 1519,
                'name_kh' => 'គុស',
                'name_en' => 'Kus',
                'code' => '210903',
                'province_id' => 21,
                'district_id' => 186,
            ),
            19 => 
            array (
                'id' => 1520,
                'name_kh' => 'លាយបូរ',
                'name_en' => 'Leay Bour',
                'code' => '210904',
                'province_id' => 21,
                'district_id' => 186,
            ),
            20 => 
            array (
                'id' => 1521,
                'name_kh' => 'ញ៉ែងញ៉ង',
                'name_en' => 'Nhaeng Nhang',
                'code' => '210905',
                'province_id' => 21,
                'district_id' => 186,
            ),
            21 => 
            array (
                'id' => 1522,
                'name_kh' => 'អូរសារាយ',
                'name_en' => 'Ou Saray',
                'code' => '210906',
                'province_id' => 21,
                'district_id' => 186,
            ),
            22 => 
            array (
                'id' => 1523,
                'name_kh' => 'ត្រពាំងក្រញូង',
                'name_en' => 'Trapeang Kranhoung',
                'code' => '210907',
                'province_id' => 21,
                'district_id' => 186,
            ),
            23 => 
            array (
                'id' => 1524,
                'name_kh' => 'ឧត្ដមសុរិយា',
                'name_en' => 'Otdam Soriya',
                'code' => '210908',
                'province_id' => 21,
                'district_id' => 186,
            ),
            24 => 
            array (
                'id' => 1525,
                'name_kh' => 'ពពេល',
                'name_en' => 'Popel',
                'code' => '210909',
                'province_id' => 21,
                'district_id' => 186,
            ),
            25 => 
            array (
                'id' => 1526,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '210910',
                'province_id' => 21,
                'district_id' => 186,
            ),
            26 => 
            array (
                'id' => 1527,
                'name_kh' => 'ស្រែរនោង',
                'name_en' => 'Srae Ronoung',
                'code' => '210911',
                'province_id' => 21,
                'district_id' => 186,
            ),
            27 => 
            array (
                'id' => 1528,
                'name_kh' => 'តាភេម',
                'name_en' => 'Ta Phem',
                'code' => '210912',
                'province_id' => 21,
                'district_id' => 186,
            ),
            28 => 
            array (
                'id' => 1529,
                'name_kh' => 'ត្រាំកក់',
                'name_en' => 'Tram Kak',
                'code' => '210913',
                'province_id' => 21,
                'district_id' => 186,
            ),
            29 => 
            array (
                'id' => 1530,
                'name_kh' => 'ត្រពាំងធំខាងជើង',
                'name_en' => 'Trapeang Thum Khang Cheung',
                'code' => '210914',
                'province_id' => 21,
                'district_id' => 186,
            ),
            30 => 
            array (
                'id' => 1531,
                'name_kh' => 'ត្រពាំងធំខាងត្បូង',
                'name_en' => 'Trapeang Thum Khang Tboung',
                'code' => '210915',
                'province_id' => 21,
                'district_id' => 186,
            ),
            31 => 
            array (
                'id' => 1532,
                'name_kh' => 'អង្កាញ់',
                'name_en' => 'Angkanh',
                'code' => '211001',
                'province_id' => 21,
                'district_id' => 187,
            ),
            32 => 
            array (
                'id' => 1533,
                'name_kh' => 'អង្គខ្នុរ',
                'name_en' => 'Angk Khnor',
                'code' => '211002',
                'province_id' => 21,
                'district_id' => 187,
            ),
            33 => 
            array (
                'id' => 1534,
                'name_kh' => 'ជីខ្មា',
                'name_en' => 'Chi Khma',
                'code' => '211003',
                'province_id' => 21,
                'district_id' => 187,
            ),
            34 => 
            array (
                'id' => 1535,
                'name_kh' => 'ខ្វាវ',
                'name_en' => 'Khvav',
                'code' => '211004',
                'province_id' => 21,
                'district_id' => 187,
            ),
            35 => 
            array (
                'id' => 1536,
                'name_kh' => 'ប្រាំបីមុំ',
                'name_en' => 'Prambei Mum',
                'code' => '211005',
                'province_id' => 21,
                'district_id' => 187,
            ),
            36 => 
            array (
                'id' => 1537,
                'name_kh' => 'អង្គកែវ',
                'name_en' => 'Angk Kaev',
                'code' => '211006',
                'province_id' => 21,
                'district_id' => 187,
            ),
            37 => 
            array (
                'id' => 1538,
                'name_kh' => 'ព្រៃស្លឹក',
                'name_en' => 'Prey Sloek',
                'code' => '211007',
                'province_id' => 21,
                'district_id' => 187,
            ),
            38 => 
            array (
                'id' => 1539,
                'name_kh' => 'រនាម',
                'name_en' => 'Roneam',
                'code' => '211008',
                'province_id' => 21,
                'district_id' => 187,
            ),
            39 => 
            array (
                'id' => 1540,
                'name_kh' => 'សំបួរ',
                'name_en' => 'Sambuor',
                'code' => '211009',
                'province_id' => 21,
                'district_id' => 187,
            ),
            40 => 
            array (
                'id' => 1541,
                'name_kh' => 'សន្លុង',
                'name_en' => 'Sanlung',
                'code' => '211010',
                'province_id' => 21,
                'district_id' => 187,
            ),
            41 => 
            array (
                'id' => 1542,
                'name_kh' => 'ស្មោង',
                'name_en' => 'Smaong',
                'code' => '211011',
                'province_id' => 21,
                'district_id' => 187,
            ),
            42 => 
            array (
                'id' => 1543,
                'name_kh' => 'ស្រង៉ែ',
                'name_en' => 'Srangae',
                'code' => '211012',
                'province_id' => 21,
                'district_id' => 187,
            ),
            43 => 
            array (
                'id' => 1544,
                'name_kh' => 'ធ្លក',
                'name_en' => 'Thlok',
                'code' => '211013',
                'province_id' => 21,
                'district_id' => 187,
            ),
            44 => 
            array (
                'id' => 1545,
                'name_kh' => 'ត្រឡាច',
                'name_en' => 'Tralach',
                'code' => '211014',
                'province_id' => 21,
                'district_id' => 187,
            ),
            45 => 
            array (
                'id' => 1546,
                'name_kh' => 'អន្លង់វែង',
                'name_en' => 'Anlong Veaeng',
                'code' => '220101',
                'province_id' => 22,
                'district_id' => 188,
            ),
            46 => 
            array (
                'id' => 1547,
                'name_kh' => 'ត្រពាំងតាវ',
                'name_en' => 'Trapeang Tav',
                'code' => '220103',
                'province_id' => 22,
                'district_id' => 188,
            ),
            47 => 
            array (
                'id' => 1548,
                'name_kh' => 'ត្រពាំងប្រីយ៍',
                'name_en' => 'Trapeang Prei',
                'code' => '220104',
                'province_id' => 22,
                'district_id' => 188,
            ),
            48 => 
            array (
                'id' => 1549,
                'name_kh' => 'ថ្លាត',
                'name_en' => 'Thlat',
                'code' => '220105',
                'province_id' => 22,
                'district_id' => 188,
            ),
            49 => 
            array (
                'id' => 1550,
                'name_kh' => 'លំទង',
                'name_en' => 'Lumtong',
                'code' => '220106',
                'province_id' => 22,
                'district_id' => 188,
            ),
            50 => 
            array (
                'id' => 1551,
                'name_kh' => 'អំពិល',
                'name_en' => 'Ampil',
                'code' => '220201',
                'province_id' => 22,
                'district_id' => 189,
            ),
            51 => 
            array (
                'id' => 1552,
                'name_kh' => 'បេង',
                'name_en' => 'Beng',
                'code' => '220202',
                'province_id' => 22,
                'district_id' => 189,
            ),
            52 => 
            array (
                'id' => 1553,
                'name_kh' => 'គោកខ្ពស់',
                'name_en' => 'Kouk Khpos',
                'code' => '220203',
                'province_id' => 22,
                'district_id' => 189,
            ),
            53 => 
            array (
                'id' => 1554,
                'name_kh' => 'គោកមន',
                'name_en' => 'Kouk Mon',
                'code' => '220204',
                'province_id' => 22,
                'district_id' => 189,
            ),
            54 => 
            array (
                'id' => 1555,
                'name_kh' => 'ជើងទៀន',
                'name_en' => 'Cheung Tien',
                'code' => '220301',
                'province_id' => 22,
                'district_id' => 190,
            ),
            55 => 
            array (
                'id' => 1556,
                'name_kh' => 'ចុងកាល់',
                'name_en' => 'Chong Kal',
                'code' => '220302',
                'province_id' => 22,
                'district_id' => 190,
            ),
            56 => 
            array (
                'id' => 1557,
                'name_kh' => 'ក្រសាំង',
                'name_en' => 'Krasang',
                'code' => '220303',
                'province_id' => 22,
                'district_id' => 190,
            ),
            57 => 
            array (
                'id' => 1558,
                'name_kh' => 'ពង្រ',
                'name_en' => 'Pongro',
                'code' => '220304',
                'province_id' => 22,
                'district_id' => 190,
            ),
            58 => 
            array (
                'id' => 1559,
                'name_kh' => 'បន្សាយរាក់',
                'name_en' => 'Bansay Reak',
                'code' => '220401',
                'province_id' => 22,
                'district_id' => 191,
            ),
            59 => 
            array (
                'id' => 1560,
                'name_kh' => 'បុស្បូវ',
                'name_en' => 'Bos Sbov',
                'code' => '220402',
                'province_id' => 22,
                'district_id' => 191,
            ),
            60 => 
            array (
                'id' => 1561,
                'name_kh' => 'កូនក្រៀល',
                'name_en' => 'Koun Kriel',
                'code' => '220403',
                'province_id' => 22,
                'district_id' => 191,
            ),
            61 => 
            array (
                'id' => 1562,
                'name_kh' => 'សំរោង',
                'name_en' => 'Samraong',
                'code' => '220404',
                'province_id' => 22,
                'district_id' => 191,
            ),
            62 => 
            array (
                'id' => 1563,
                'name_kh' => 'អូរស្មាច់',
                'name_en' => 'Ou Smach',
                'code' => '220405',
                'province_id' => 22,
                'district_id' => 191,
            ),
            63 => 
            array (
                'id' => 1564,
                'name_kh' => 'បាក់អន្លូង',
                'name_en' => 'Bak Anloung',
                'code' => '220501',
                'province_id' => 22,
                'district_id' => 192,
            ),
            64 => 
            array (
                'id' => 1565,
                'name_kh' => 'ផ្អាវ',
                'name_en' => 'Phav',
                'code' => '220502',
                'province_id' => 22,
                'district_id' => 192,
            ),
            65 => 
            array (
                'id' => 1566,
                'name_kh' => 'អូរស្វាយ',
                'name_en' => 'Ou Svay',
                'code' => '220503',
                'province_id' => 22,
                'district_id' => 192,
            ),
            66 => 
            array (
                'id' => 1567,
                'name_kh' => 'ព្រះប្រឡាយ',
                'name_en' => 'Preah Pralay',
                'code' => '220504',
                'province_id' => 22,
                'district_id' => 192,
            ),
            67 => 
            array (
                'id' => 1568,
                'name_kh' => 'ទំនប់ដាច់',
                'name_en' => 'Tumnob Dach',
                'code' => '220505',
                'province_id' => 22,
                'district_id' => 192,
            ),
            68 => 
            array (
                'id' => 1569,
                'name_kh' => 'ត្រពាំងប្រាសាទ',
                'name_en' => 'Trapeang Prasat',
                'code' => '220506',
                'province_id' => 22,
                'district_id' => 192,
            ),
            69 => 
            array (
                'id' => 1570,
                'name_kh' => 'អង្កោល',
                'name_en' => 'Angkaol',
                'code' => '230101',
                'province_id' => 23,
                'district_id' => 193,
            ),
            70 => 
            array (
                'id' => 1571,
                'name_kh' => 'ពងទឹក',
                'name_en' => 'Pong Tuek',
                'code' => '230103',
                'province_id' => 23,
                'district_id' => 193,
            ),
            71 => 
            array (
                'id' => 1572,
                'name_kh' => 'កែប',
                'name_en' => 'Kaeb',
                'code' => '230201',
                'province_id' => 23,
                'district_id' => 194,
            ),
            72 => 
            array (
                'id' => 1573,
                'name_kh' => 'ព្រៃធំ',
                'name_en' => 'Prey Thum',
                'code' => '230202',
                'province_id' => 23,
                'district_id' => 194,
            ),
            73 => 
            array (
                'id' => 1574,
                'name_kh' => 'អូរក្រសារ',
                'name_en' => 'Ou Krasar',
                'code' => '230203',
                'province_id' => 23,
                'district_id' => 194,
            ),
            74 => 
            array (
                'id' => 1575,
                'name_kh' => 'ប៉ៃលិន',
                'name_en' => 'Pailin',
                'code' => '240101',
                'province_id' => 24,
                'district_id' => 195,
            ),
            75 => 
            array (
                'id' => 1576,
                'name_kh' => 'អូរតាវ៉ៅ',
                'name_en' => 'Ou Ta Vau',
                'code' => '240102',
                'province_id' => 24,
                'district_id' => 195,
            ),
            76 => 
            array (
                'id' => 1577,
                'name_kh' => 'ទួលល្វា',
                'name_en' => 'Tuol Lvea',
                'code' => '240103',
                'province_id' => 24,
                'district_id' => 195,
            ),
            77 => 
            array (
                'id' => 1578,
                'name_kh' => 'បរយ៉ាខា',
                'name_en' => 'Bar Yakha',
                'code' => '240104',
                'province_id' => 24,
                'district_id' => 195,
            ),
            78 => 
            array (
                'id' => 1579,
                'name_kh' => 'សាលាក្រៅ',
                'name_en' => 'Sala Krau',
                'code' => '240201',
                'province_id' => 24,
                'district_id' => 196,
            ),
            79 => 
            array (
                'id' => 1580,
                'name_kh' => 'ស្ទឹងត្រង់',
                'name_en' => 'Stueng Trang',
                'code' => '240202',
                'province_id' => 24,
                'district_id' => 196,
            ),
            80 => 
            array (
                'id' => 1581,
                'name_kh' => 'ស្ទឹងកាច់',
                'name_en' => 'Stueng Kach',
                'code' => '240203',
                'province_id' => 24,
                'district_id' => 196,
            ),
            81 => 
            array (
                'id' => 1582,
                'name_kh' => 'អូរអណ្ដូង',
                'name_en' => 'Ou Andoung',
                'code' => '240204',
                'province_id' => 24,
                'district_id' => 196,
            ),
            82 => 
            array (
                'id' => 1583,
                'name_kh' => 'ចុងជាច',
                'name_en' => 'Chong Cheach',
                'code' => '250101',
                'province_id' => 25,
                'district_id' => 197,
            ),
            83 => 
            array (
                'id' => 1584,
                'name_kh' => 'តំបែរ',
                'name_en' => 'Dambae',
                'code' => '250102',
                'province_id' => 25,
                'district_id' => 197,
            ),
            84 => 
            array (
                'id' => 1585,
                'name_kh' => 'គោកស្រុក',
                'name_en' => 'Kouk Srok',
                'code' => '250103',
                'province_id' => 25,
                'district_id' => 197,
            ),
            85 => 
            array (
                'id' => 1586,
                'name_kh' => 'នាងទើត',
                'name_en' => 'Neang Teut',
                'code' => '250104',
                'province_id' => 25,
                'district_id' => 197,
            ),
            86 => 
            array (
                'id' => 1587,
                'name_kh' => 'សេដា',
                'name_en' => 'Seda',
                'code' => '250105',
                'province_id' => 25,
                'district_id' => 197,
            ),
            87 => 
            array (
                'id' => 1588,
                'name_kh' => 'ត្រពាំងព្រីង',
                'name_en' => 'Trapeang Pring',
                'code' => '250106',
                'province_id' => 25,
                'district_id' => 197,
            ),
            88 => 
            array (
                'id' => 1589,
                'name_kh' => 'ទឹកជ្រៅ',
                'name_en' => 'Tuek Chrov',
                'code' => '250107',
                'province_id' => 25,
                'district_id' => 197,
            ),
            89 => 
            array (
                'id' => 1590,
                'name_kh' => 'ឈូក',
                'name_en' => 'Chhuk',
                'code' => '250201',
                'province_id' => 25,
                'district_id' => 198,
            ),
            90 => 
            array (
                'id' => 1591,
                'name_kh' => 'ជំនីក',
                'name_en' => 'Chumnik',
                'code' => '250202',
                'province_id' => 25,
                'district_id' => 198,
            ),
            91 => 
            array (
                'id' => 1592,
                'name_kh' => 'កំពង់ទ្រាស',
                'name_en' => 'Kampong Treas',
                'code' => '250203',
                'province_id' => 25,
                'district_id' => 198,
            ),
            92 => 
            array (
                'id' => 1593,
                'name_kh' => 'កោះពីរ',
                'name_en' => 'Kaoh Pir',
                'code' => '250204',
                'province_id' => 25,
                'district_id' => 198,
            ),
            93 => 
            array (
                'id' => 1594,
                'name_kh' => 'ក្រូចឆ្មារ',
                'name_en' => 'Krouch Chhmar',
                'code' => '250205',
                'province_id' => 25,
                'district_id' => 198,
            ),
            94 => 
            array (
                'id' => 1595,
                'name_kh' => 'ប៉ឺស១',
                'name_en' => 'Peus Muoy',
                'code' => '250206',
                'province_id' => 25,
                'district_id' => 198,
            ),
            95 => 
            array (
                'id' => 1596,
                'name_kh' => 'ប៉ឺស២',
                'name_en' => 'Peus Pir',
                'code' => '250207',
                'province_id' => 25,
                'district_id' => 198,
            ),
            96 => 
            array (
                'id' => 1597,
                'name_kh' => 'ព្រែកអាជី',
                'name_en' => 'Preaek A chi',
                'code' => '250208',
                'province_id' => 25,
                'district_id' => 198,
            ),
            97 => 
            array (
                'id' => 1598,
                'name_kh' => 'រការខ្នុរ',
                'name_en' => 'Roka Khnor',
                'code' => '250209',
                'province_id' => 25,
                'district_id' => 198,
            ),
            98 => 
            array (
                'id' => 1599,
                'name_kh' => 'ស្វាយឃ្លាំង',
                'name_en' => 'Svay Khleang',
                'code' => '250210',
                'province_id' => 25,
                'district_id' => 198,
            ),
            99 => 
            array (
                'id' => 1600,
                'name_kh' => 'ទ្រា',
                'name_en' => 'Trea',
                'code' => '250211',
                'province_id' => 25,
                'district_id' => 198,
            ),
            100 => 
            array (
                'id' => 1601,
                'name_kh' => 'ទួលស្នួល',
                'name_en' => 'Tuol Snuol',
                'code' => '250212',
                'province_id' => 25,
                'district_id' => 198,
            ),
            101 => 
            array (
                'id' => 1602,
                'name_kh' => 'ចាន់មូល',
                'name_en' => 'Chan Mul',
                'code' => '250301',
                'province_id' => 25,
                'district_id' => 199,
            ),
            102 => 
            array (
                'id' => 1603,
                'name_kh' => 'ជាំ',
                'name_en' => 'Choam',
                'code' => '250302',
                'province_id' => 25,
                'district_id' => 199,
            ),
            103 => 
            array (
                'id' => 1604,
                'name_kh' => 'ជាំក្រវៀន',
                'name_en' => 'Choam Kravien',
                'code' => '250303',
                'province_id' => 25,
                'district_id' => 199,
            ),
            104 => 
            array (
                'id' => 1605,
                'name_kh' => 'ជាំតាម៉ៅ',
                'name_en' => 'Choam Ta Mau',
                'code' => '250304',
                'province_id' => 25,
                'district_id' => 199,
            ),
            105 => 
            array (
                'id' => 1606,
                'name_kh' => 'ដារ',
                'name_en' => 'Dar',
                'code' => '250305',
                'province_id' => 25,
                'district_id' => 199,
            ),
            106 => 
            array (
                'id' => 1607,
                'name_kh' => 'កំពាន់',
                'name_en' => 'Kampoan',
                'code' => '250306',
                'province_id' => 25,
                'district_id' => 199,
            ),
            107 => 
            array (
                'id' => 1608,
                'name_kh' => 'គគីរ',
                'name_en' => 'Kokir',
                'code' => '250307',
                'province_id' => 25,
                'district_id' => 199,
            ),
            108 => 
            array (
                'id' => 1609,
                'name_kh' => 'មេមង',
                'name_en' => 'Memong',
                'code' => '250308',
                'province_id' => 25,
                'district_id' => 199,
            ),
            109 => 
            array (
                'id' => 1610,
                'name_kh' => 'មេមត់',
                'name_en' => 'Memot',
                'code' => '250309',
                'province_id' => 25,
                'district_id' => 199,
            ),
            110 => 
            array (
                'id' => 1611,
                'name_kh' => 'រំចេក',
                'name_en' => 'Rumchek',
                'code' => '250310',
                'province_id' => 25,
                'district_id' => 199,
            ),
            111 => 
            array (
                'id' => 1612,
                'name_kh' => 'រូង',
                'name_en' => 'Rung',
                'code' => '250311',
                'province_id' => 25,
                'district_id' => 199,
            ),
            112 => 
            array (
                'id' => 1613,
                'name_kh' => 'ទន្លូង',
                'name_en' => 'Tonlung',
                'code' => '250312',
                'province_id' => 25,
                'district_id' => 199,
            ),
            113 => 
            array (
                'id' => 1614,
                'name_kh' => 'ត្រមូង',
                'name_en' => 'Tramung',
                'code' => '250313',
                'province_id' => 25,
                'district_id' => 199,
            ),
            114 => 
            array (
                'id' => 1615,
                'name_kh' => 'ទ្រៀក',
                'name_en' => 'Triek',
                'code' => '250314',
                'province_id' => 25,
                'district_id' => 199,
            ),
            115 => 
            array (
                'id' => 1616,
                'name_kh' => 'អំពិលតាពក',
                'name_en' => 'Ampil Ta Pok',
                'code' => '250401',
                'province_id' => 25,
                'district_id' => 200,
            ),
            116 => 
            array (
                'id' => 1617,
                'name_kh' => 'ចក',
                'name_en' => 'Chak',
                'code' => '250402',
                'province_id' => 25,
                'district_id' => 200,
            ),
            117 => 
            array (
                'id' => 1618,
                'name_kh' => 'ដំរិល',
                'name_en' => 'Damril',
                'code' => '250403',
                'province_id' => 25,
                'district_id' => 200,
            ),
            118 => 
            array (
                'id' => 1619,
                'name_kh' => 'គងជ័យ',
                'name_en' => 'Kong Chey',
                'code' => '250404',
                'province_id' => 25,
                'district_id' => 200,
            ),
            119 => 
            array (
                'id' => 1620,
                'name_kh' => 'មៀន',
                'name_en' => 'Mien',
                'code' => '250405',
                'province_id' => 25,
                'district_id' => 200,
            ),
            120 => 
            array (
                'id' => 1621,
                'name_kh' => 'ព្រះធាតុ',
                'name_en' => 'Preah Theat',
                'code' => '250406',
                'province_id' => 25,
                'district_id' => 200,
            ),
            121 => 
            array (
                'id' => 1622,
                'name_kh' => 'ទួលសូភី',
                'name_en' => 'Tuol Souphi',
                'code' => '250407',
                'province_id' => 25,
                'district_id' => 200,
            ),
            122 => 
            array (
                'id' => 1623,
                'name_kh' => 'ដូនតី',
                'name_en' => 'Dountei',
                'code' => '250501',
                'province_id' => 25,
                'district_id' => 201,
            ),
            123 => 
            array (
                'id' => 1624,
                'name_kh' => 'កក់',
                'name_en' => 'Kak',
                'code' => '250502',
                'province_id' => 25,
                'district_id' => 201,
            ),
            124 => 
            array (
                'id' => 1625,
                'name_kh' => 'កណ្ដោលជ្រុំ',
                'name_en' => 'Kandaol Chrum',
                'code' => '250503',
                'province_id' => 25,
                'district_id' => 201,
            ),
            125 => 
            array (
                'id' => 1626,
                'name_kh' => 'កោងកាង',
                'name_en' => 'Kaong Kang',
                'code' => '250504',
                'province_id' => 25,
                'district_id' => 201,
            ),
            126 => 
            array (
                'id' => 1627,
                'name_kh' => 'ក្រែក',
                'name_en' => 'Kraek',
                'code' => '250505',
                'province_id' => 25,
                'district_id' => 201,
            ),
            127 => 
            array (
                'id' => 1628,
                'name_kh' => 'ពពេល',
                'name_en' => 'Popel',
                'code' => '250506',
                'province_id' => 25,
                'district_id' => 201,
            ),
            128 => 
            array (
                'id' => 1629,
                'name_kh' => 'ត្រពាំងផ្លុង',
                'name_en' => 'Trapeang Phlong',
                'code' => '250507',
                'province_id' => 25,
                'district_id' => 201,
            ),
            129 => 
            array (
                'id' => 1630,
                'name_kh' => 'វាលម្លូរ',
                'name_en' => 'Veal Mlu',
                'code' => '250508',
                'province_id' => 25,
                'district_id' => 201,
            ),
            130 => 
            array (
                'id' => 1631,
                'name_kh' => 'សួង',
                'name_en' => 'Suong',
                'code' => '250601',
                'province_id' => 25,
                'district_id' => 202,
            ),
            131 => 
            array (
                'id' => 1632,
                'name_kh' => 'វិហារលួង',
                'name_en' => 'Vihear Luong',
                'code' => '250602',
                'province_id' => 25,
                'district_id' => 202,
            ),
            132 => 
            array (
                'id' => 1633,
                'name_kh' => 'អញ្ចើម',
                'name_en' => 'Anhchaeum',
                'code' => '250701',
                'province_id' => 25,
                'district_id' => 203,
            ),
            133 => 
            array (
                'id' => 1634,
                'name_kh' => 'បឹងព្រួល',
                'name_en' => 'Boeng Pruol',
                'code' => '250702',
                'province_id' => 25,
                'district_id' => 203,
            ),
            134 => 
            array (
                'id' => 1635,
                'name_kh' => 'ជីគរ',
                'name_en' => 'Chikor',
                'code' => '250703',
                'province_id' => 25,
                'district_id' => 203,
            ),
            135 => 
            array (
                'id' => 1636,
                'name_kh' => 'ជីរោទ៍ ទី១',
                'name_en' => 'Chirou Ti Muoy',
                'code' => '250704',
                'province_id' => 25,
                'district_id' => 203,
            ),
            136 => 
            array (
                'id' => 1637,
                'name_kh' => 'ជីរោទ៍ ទី២',
                'name_en' => 'Chirou Ti Pir',
                'code' => '250705',
                'province_id' => 25,
                'district_id' => 203,
            ),
            137 => 
            array (
                'id' => 1638,
                'name_kh' => 'ជប់',
                'name_en' => 'Chob',
                'code' => '250706',
                'province_id' => 25,
                'district_id' => 203,
            ),
            138 => 
            array (
                'id' => 1639,
                'name_kh' => 'គរ',
                'name_en' => 'Kor',
                'code' => '250707',
                'province_id' => 25,
                'district_id' => 203,
            ),
            139 => 
            array (
                'id' => 1640,
                'name_kh' => 'ល្ងៀង',
                'name_en' => 'Lngieng',
                'code' => '250708',
                'province_id' => 25,
                'district_id' => 203,
            ),
            140 => 
            array (
                'id' => 1641,
                'name_kh' => 'មង់រៀវ',
                'name_en' => 'Mong Riev',
                'code' => '250709',
                'province_id' => 25,
                'district_id' => 203,
            ),
            141 => 
            array (
                'id' => 1642,
                'name_kh' => 'ពាមជីលាំង',
                'name_en' => 'Peam Chileang',
                'code' => '250710',
                'province_id' => 25,
                'district_id' => 203,
            ),
            142 => 
            array (
                'id' => 1643,
                'name_kh' => 'រកាពប្រាំ',
                'name_en' => 'Roka Po Pram',
                'code' => '250711',
                'province_id' => 25,
                'district_id' => 203,
            ),
            143 => 
            array (
                'id' => 1644,
                'name_kh' => 'ស្រឡប់',
                'name_en' => 'Sralab',
                'code' => '250712',
                'province_id' => 25,
                'district_id' => 203,
            ),
            144 => 
            array (
                'id' => 1645,
                'name_kh' => 'ថ្មពេជ្រ',
                'name_en' => 'Thma Pech',
                'code' => '250713',
                'province_id' => 25,
                'district_id' => 203,
            ),
            145 => 
            array (
                'id' => 1646,
                'name_kh' => 'ទន្លេបិទ',
                'name_en' => 'Tonle Bet',
                'code' => '250714',
                'province_id' => 25,
                'district_id' => 203,
            ),
        ));
        
        
    }
}