<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DistrictsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('districts')->delete();
        
        \DB::table('districts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name_en' => 'Mongkol Borei',
                'name_kh' => 'មង្គលបូរី',
                'code' => '102',
                'province_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name_en' => 'Phnum Srok',
                'name_kh' => 'ភ្នំស្រុក',
                'code' => '103',
                'province_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name_en' => 'Preah Netr Preah',
                'name_kh' => 'ព្រះនេត្រព្រះ',
                'code' => '104',
                'province_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name_en' => 'Ou Chrov',
                'name_kh' => 'អូរជ្រៅ',
                'code' => '105',
                'province_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name_en' => 'Serei Saophoan',
                'name_kh' => 'សិរីសោភ័ណ',
                'code' => '106',
                'province_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name_en' => 'Thma Puok',
                'name_kh' => 'ថ្មពួក',
                'code' => '107',
                'province_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name_en' => 'Svay Chek',
                'name_kh' => 'ស្វាយចេក',
                'code' => '108',
                'province_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name_en' => 'Malai',
                'name_kh' => 'ម៉ាឡៃ',
                'code' => '109',
                'province_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name_en' => 'Paoy Paet',
                'name_kh' => 'ប៉ោយប៉ែត',
                'code' => '110',
                'province_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name_en' => 'Banan',
                'name_kh' => 'បាណន់',
                'code' => '201',
                'province_id' => 2,
            ),
            10 => 
            array (
                'id' => 11,
                'name_en' => 'Thma Koul',
                'name_kh' => 'ថ្មគោល',
                'code' => '202',
                'province_id' => 2,
            ),
            11 => 
            array (
                'id' => 12,
                'name_en' => 'Battambang',
                'name_kh' => 'បាត់ដំបង',
                'code' => '203',
                'province_id' => 2,
            ),
            12 => 
            array (
                'id' => 13,
                'name_en' => 'Bavel',
                'name_kh' => 'បវេល',
                'code' => '204',
                'province_id' => 2,
            ),
            13 => 
            array (
                'id' => 14,
                'name_en' => 'Aek Phnum',
                'name_kh' => 'ឯកភ្នំ',
                'code' => '205',
                'province_id' => 2,
            ),
            14 => 
            array (
                'id' => 15,
                'name_en' => 'Moung Ruessei',
                'name_kh' => 'មោងឫស្សី',
                'code' => '206',
                'province_id' => 2,
            ),
            15 => 
            array (
                'id' => 16,
                'name_en' => 'Rotonak Mondol',
                'name_kh' => 'រតនមណ្ឌល',
                'code' => '207',
                'province_id' => 2,
            ),
            16 => 
            array (
                'id' => 17,
                'name_en' => 'Sangkae',
                'name_kh' => 'សង្កែ',
                'code' => '208',
                'province_id' => 2,
            ),
            17 => 
            array (
                'id' => 18,
                'name_en' => 'Samlout',
                'name_kh' => 'សំឡូត',
                'code' => '209',
                'province_id' => 2,
            ),
            18 => 
            array (
                'id' => 19,
                'name_en' => 'Sampov Lun',
                'name_kh' => 'សំពៅលូន',
                'code' => '210',
                'province_id' => 2,
            ),
            19 => 
            array (
                'id' => 20,
                'name_en' => 'Phnum Proek',
                'name_kh' => 'ភ្នំព្រឹក',
                'code' => '211',
                'province_id' => 2,
            ),
            20 => 
            array (
                'id' => 21,
                'name_en' => 'Kamrieng',
                'name_kh' => 'កំរៀង',
                'code' => '212',
                'province_id' => 2,
            ),
            21 => 
            array (
                'id' => 22,
                'name_en' => 'Koas Krala',
                'name_kh' => 'គាស់ក្រឡ',
                'code' => '213',
                'province_id' => 2,
            ),
            22 => 
            array (
                'id' => 23,
                'name_en' => 'Rukh Kiri',
                'name_kh' => 'រុក្ខគិរី',
                'code' => '214',
                'province_id' => 2,
            ),
            23 => 
            array (
                'id' => 24,
                'name_en' => 'Batheay',
                'name_kh' => 'បាធាយ',
                'code' => '301',
                'province_id' => 3,
            ),
            24 => 
            array (
                'id' => 25,
                'name_en' => 'Chamkar Leu',
                'name_kh' => 'ចំការលើ',
                'code' => '302',
                'province_id' => 3,
            ),
            25 => 
            array (
                'id' => 26,
                'name_en' => 'Cheung Prey',
                'name_kh' => 'ជើងព្រៃ',
                'code' => '303',
                'province_id' => 3,
            ),
            26 => 
            array (
                'id' => 27,
                'name_en' => 'Kampong Cham',
                'name_kh' => 'កំពង់ចាម',
                'code' => '305',
                'province_id' => 3,
            ),
            27 => 
            array (
                'id' => 28,
                'name_en' => 'Kampong Siem',
                'name_kh' => 'កំពង់សៀម',
                'code' => '306',
                'province_id' => 3,
            ),
            28 => 
            array (
                'id' => 29,
                'name_en' => 'Kang Meas',
                'name_kh' => 'កងមាស',
                'code' => '307',
                'province_id' => 3,
            ),
            29 => 
            array (
                'id' => 30,
                'name_en' => 'Kaoh Soutin',
                'name_kh' => 'កោះសូទិន',
                'code' => '308',
                'province_id' => 3,
            ),
            30 => 
            array (
                'id' => 31,
                'name_en' => 'Prey Chhor',
                'name_kh' => 'ព្រៃឈរ',
                'code' => '313',
                'province_id' => 3,
            ),
            31 => 
            array (
                'id' => 32,
                'name_en' => 'Srei Santhor',
                'name_kh' => 'ស្រីសន្ធរ',
                'code' => '314',
                'province_id' => 3,
            ),
            32 => 
            array (
                'id' => 33,
                'name_en' => 'Stueng Trang',
                'name_kh' => 'ស្ទឹងត្រង់',
                'code' => '315',
                'province_id' => 3,
            ),
            33 => 
            array (
                'id' => 34,
                'name_en' => 'Baribour',
                'name_kh' => 'បរិបូណ៌',
                'code' => '401',
                'province_id' => 4,
            ),
            34 => 
            array (
                'id' => 35,
                'name_en' => 'Chol Kiri',
                'name_kh' => 'ជលគីរី',
                'code' => '402',
                'province_id' => 4,
            ),
            35 => 
            array (
                'id' => 36,
                'name_en' => 'Kampong Chhnang',
                'name_kh' => 'កំពង់ឆ្នាំង',
                'code' => '403',
                'province_id' => 4,
            ),
            36 => 
            array (
                'id' => 37,
                'name_en' => 'Kampong Leaeng',
                'name_kh' => 'កំពង់លែង',
                'code' => '404',
                'province_id' => 4,
            ),
            37 => 
            array (
                'id' => 38,
                'name_en' => 'Kampong Tralach',
                'name_kh' => 'កំពង់ត្រឡាច',
                'code' => '405',
                'province_id' => 4,
            ),
            38 => 
            array (
                'id' => 39,
                'name_en' => 'Rolea Bier',
                'name_kh' => 'រលាប្អៀរ',
                'code' => '406',
                'province_id' => 4,
            ),
            39 => 
            array (
                'id' => 40,
                'name_en' => 'Sameakki Mean Chey',
                'name_kh' => 'សាមគ្គីមានជ័យ',
                'code' => '407',
                'province_id' => 4,
            ),
            40 => 
            array (
                'id' => 41,
                'name_en' => 'Tuek Phos',
                'name_kh' => 'ទឹកផុស',
                'code' => '408',
                'province_id' => 4,
            ),
            41 => 
            array (
                'id' => 42,
                'name_en' => 'Basedth',
                'name_kh' => 'បរសេដ្ឋ',
                'code' => '501',
                'province_id' => 5,
            ),
            42 => 
            array (
                'id' => 43,
                'name_en' => 'Chbar Mon',
                'name_kh' => 'ច្បារមន',
                'code' => '502',
                'province_id' => 5,
            ),
            43 => 
            array (
                'id' => 44,
                'name_en' => 'Kong Pisei',
                'name_kh' => 'គងពិសី',
                'code' => '503',
                'province_id' => 5,
            ),
            44 => 
            array (
                'id' => 45,
                'name_en' => 'Aoral',
                'name_kh' => 'ឱរ៉ាល់',
                'code' => '504',
                'province_id' => 5,
            ),
            45 => 
            array (
                'id' => 46,
                'name_en' => 'Odongk',
                'name_kh' => 'ឧដុង្គ',
                'code' => '505',
                'province_id' => 5,
            ),
            46 => 
            array (
                'id' => 47,
                'name_en' => 'Phnum Sruoch',
                'name_kh' => 'ភ្នំស្រួច',
                'code' => '506',
                'province_id' => 5,
            ),
            47 => 
            array (
                'id' => 48,
                'name_en' => 'Samraong Tong',
                'name_kh' => 'សំរោងទង',
                'code' => '507',
                'province_id' => 5,
            ),
            48 => 
            array (
                'id' => 49,
                'name_en' => 'Thpong',
                'name_kh' => 'ថ្ពង',
                'code' => '508',
                'province_id' => 5,
            ),
            49 => 
            array (
                'id' => 50,
                'name_en' => 'Baray',
                'name_kh' => 'បារាយណ៍',
                'code' => '601',
                'province_id' => 6,
            ),
            50 => 
            array (
                'id' => 51,
                'name_en' => 'Kampong Svay',
                'name_kh' => 'កំពង់ស្វាយ',
                'code' => '602',
                'province_id' => 6,
            ),
            51 => 
            array (
                'id' => 52,
                'name_en' => 'Stueng Saen',
                'name_kh' => 'ស្ទឹងសែន',
                'code' => '603',
                'province_id' => 6,
            ),
            52 => 
            array (
                'id' => 53,
                'name_en' => 'Prasat Ballangk',
                'name_kh' => 'ប្រាសាទបល្ល័ង្គ',
                'code' => '604',
                'province_id' => 6,
            ),
            53 => 
            array (
                'id' => 54,
                'name_en' => 'Prasat Sambour',
                'name_kh' => 'ប្រាសាទសំបូរ',
                'code' => '605',
                'province_id' => 6,
            ),
            54 => 
            array (
                'id' => 55,
                'name_en' => 'Sandan',
                'name_kh' => 'សណ្ដាន់',
                'code' => '606',
                'province_id' => 6,
            ),
            55 => 
            array (
                'id' => 56,
                'name_en' => 'Santuk',
                'name_kh' => 'សន្ទុក',
                'code' => '607',
                'province_id' => 6,
            ),
            56 => 
            array (
                'id' => 57,
                'name_en' => 'Stoung',
                'name_kh' => 'ស្ទោង',
                'code' => '608',
                'province_id' => 6,
            ),
            57 => 
            array (
                'id' => 58,
                'name_en' => 'Taing Kouk',
                'name_kh' => 'តាំងគោក',
                'code' => '609',
                'province_id' => 6,
            ),
            58 => 
            array (
                'id' => 59,
                'name_en' => 'Angkor Chey',
                'name_kh' => 'អង្គរជ័យ',
                'code' => '701',
                'province_id' => 7,
            ),
            59 => 
            array (
                'id' => 60,
                'name_en' => 'Banteay Meas',
                'name_kh' => 'បន្ទាយមាស',
                'code' => '702',
                'province_id' => 7,
            ),
            60 => 
            array (
                'id' => 61,
                'name_en' => 'Chhuk',
                'name_kh' => 'ឈូក',
                'code' => '703',
                'province_id' => 7,
            ),
            61 => 
            array (
                'id' => 62,
                'name_en' => 'Chum Kiri',
                'name_kh' => 'ជុំគិរី',
                'code' => '704',
                'province_id' => 7,
            ),
            62 => 
            array (
                'id' => 63,
                'name_en' => 'Dang Tong',
                'name_kh' => 'ដងទង់',
                'code' => '705',
                'province_id' => 7,
            ),
            63 => 
            array (
                'id' => 64,
                'name_en' => 'Kampong Trach',
                'name_kh' => 'កំពង់ត្រាច',
                'code' => '706',
                'province_id' => 7,
            ),
            64 => 
            array (
                'id' => 65,
                'name_en' => 'Tuek Chhou',
                'name_kh' => 'ទឹកឈូ',
                'code' => '707',
                'province_id' => 7,
            ),
            65 => 
            array (
                'id' => 66,
                'name_en' => 'Kampot',
                'name_kh' => 'កំពត',
                'code' => '708',
                'province_id' => 7,
            ),
            66 => 
            array (
                'id' => 67,
                'name_en' => 'Kandal Stueng',
                'name_kh' => 'កណ្ដាលស្ទឹង',
                'code' => '801',
                'province_id' => 8,
            ),
            67 => 
            array (
                'id' => 68,
                'name_en' => 'Kien Svay',
                'name_kh' => 'កៀនស្វាយ',
                'code' => '802',
                'province_id' => 8,
            ),
            68 => 
            array (
                'id' => 69,
                'name_en' => 'Khsach Kandal',
                'name_kh' => 'ខ្សាច់កណ្ដាល',
                'code' => '803',
                'province_id' => 8,
            ),
            69 => 
            array (
                'id' => 70,
                'name_en' => 'Kaoh Thum',
                'name_kh' => 'កោះធំ',
                'code' => '804',
                'province_id' => 8,
            ),
            70 => 
            array (
                'id' => 71,
                'name_en' => 'Leuk Daek',
                'name_kh' => 'លើកដែក',
                'code' => '805',
                'province_id' => 8,
            ),
            71 => 
            array (
                'id' => 72,
                'name_en' => 'Lvea Aem',
                'name_kh' => 'ល្វាឯម',
                'code' => '806',
                'province_id' => 8,
            ),
            72 => 
            array (
                'id' => 73,
                'name_en' => 'Mukh Kampul',
                'name_kh' => 'មុខកំពូល',
                'code' => '807',
                'province_id' => 8,
            ),
            73 => 
            array (
                'id' => 74,
                'name_en' => 'Angk Snuol',
                'name_kh' => 'អង្គស្នួល',
                'code' => '808',
                'province_id' => 8,
            ),
            74 => 
            array (
                'id' => 75,
                'name_en' => 'Ponhea Lueu',
                'name_kh' => 'ពញាឮ',
                'code' => '809',
                'province_id' => 8,
            ),
            75 => 
            array (
                'id' => 76,
                'name_en' => 'Sang',
                'name_kh' => 'ស្អាង',
                'code' => '810',
                'province_id' => 8,
            ),
            76 => 
            array (
                'id' => 77,
                'name_en' => 'Ta Khmau',
                'name_kh' => 'តាខ្មៅ',
                'code' => '811',
                'province_id' => 8,
            ),
            77 => 
            array (
                'id' => 78,
                'name_en' => 'Botum Sakor',
                'name_kh' => 'បុទុមសាគរ',
                'code' => '901',
                'province_id' => 9,
            ),
            78 => 
            array (
                'id' => 79,
                'name_en' => 'Kiri Sakor',
                'name_kh' => 'គិរីសាគរ',
                'code' => '902',
                'province_id' => 9,
            ),
            79 => 
            array (
                'id' => 80,
                'name_en' => 'Kaoh Kong',
                'name_kh' => 'កោះកុង',
                'code' => '903',
                'province_id' => 9,
            ),
            80 => 
            array (
                'id' => 81,
                'name_en' => 'Khemara Phoumin',
                'name_kh' => 'ខេមរភូមិន្ទ',
                'code' => '904',
                'province_id' => 9,
            ),
            81 => 
            array (
                'id' => 82,
                'name_en' => 'Mondol Seima',
                'name_kh' => 'មណ្ឌលសីមា',
                'code' => '905',
                'province_id' => 9,
            ),
            82 => 
            array (
                'id' => 83,
                'name_en' => 'Srae Ambel',
                'name_kh' => 'ស្រែ អំបិល',
                'code' => '906',
                'province_id' => 9,
            ),
            83 => 
            array (
                'id' => 84,
                'name_en' => 'Thma Bang',
                'name_kh' => 'ថ្មបាំង',
                'code' => '907',
                'province_id' => 9,
            ),
            84 => 
            array (
                'id' => 85,
                'name_en' => 'Chhloung',
                'name_kh' => 'ឆ្លូង',
                'code' => '1001',
                'province_id' => 10,
            ),
            85 => 
            array (
                'id' => 86,
                'name_en' => 'Kracheh',
                'name_kh' => 'ក្រចេះ',
                'code' => '1002',
                'province_id' => 10,
            ),
            86 => 
            array (
                'id' => 87,
                'name_en' => 'Prek Prasab',
                'name_kh' => 'ព្រែកប្រសព្វ',
                'code' => '1003',
                'province_id' => 10,
            ),
            87 => 
            array (
                'id' => 88,
                'name_en' => 'Sambour',
                'name_kh' => 'សំបូរ',
                'code' => '1004',
                'province_id' => 10,
            ),
            88 => 
            array (
                'id' => 89,
                'name_en' => 'Snuol',
                'name_kh' => 'ស្នួល',
                'code' => '1005',
                'province_id' => 10,
            ),
            89 => 
            array (
                'id' => 90,
                'name_en' => 'Chetr Borei',
                'name_kh' => 'ចិត្របុរី',
                'code' => '1006',
                'province_id' => 10,
            ),
            90 => 
            array (
                'id' => 91,
                'name_en' => 'Kaev Seima',
                'name_kh' => 'កែវសីមា',
                'code' => '1101',
                'province_id' => 11,
            ),
            91 => 
            array (
                'id' => 92,
                'name_en' => 'Kaoh Nheaek',
                'name_kh' => 'កោះញែក',
                'code' => '1102',
                'province_id' => 11,
            ),
            92 => 
            array (
                'id' => 93,
                'name_en' => 'Ou Reang',
                'name_kh' => 'អូររាំង',
                'code' => '1103',
                'province_id' => 11,
            ),
            93 => 
            array (
                'id' => 94,
                'name_en' => 'Pech Chreada',
                'name_kh' => 'ពេជ្រាដា',
                'code' => '1104',
                'province_id' => 11,
            ),
            94 => 
            array (
                'id' => 95,
                'name_en' => 'Saen Monourom',
                'name_kh' => 'សែនមនោរម្យ',
                'code' => '1105',
                'province_id' => 11,
            ),
            95 => 
            array (
                'id' => 96,
                'name_en' => 'Chamkar Mon',
                'name_kh' => 'ចំការមន',
                'code' => '1201',
                'province_id' => 12,
            ),
            96 => 
            array (
                'id' => 97,
                'name_en' => 'Doun Penh',
                'name_kh' => 'ដូនពេញ',
                'code' => '1202',
                'province_id' => 12,
            ),
            97 => 
            array (
                'id' => 98,
                'name_en' => 'Prampir Meakkakra',
                'name_kh' => '៧មករា',
                'code' => '1203',
                'province_id' => 12,
            ),
            98 => 
            array (
                'id' => 99,
                'name_en' => 'Tuol Kouk',
                'name_kh' => 'ទួលគោក',
                'code' => '1204',
                'province_id' => 12,
            ),
            99 => 
            array (
                'id' => 100,
                'name_en' => 'Dangkao',
                'name_kh' => 'ដង្កោ',
                'code' => '1205',
                'province_id' => 12,
            ),
            100 => 
            array (
                'id' => 101,
                'name_en' => 'Mean Chey',
                'name_kh' => 'មានជ័យ',
                'code' => '1206',
                'province_id' => 12,
            ),
            101 => 
            array (
                'id' => 102,
                'name_en' => 'Russey Keo',
                'name_kh' => 'ឫស្សីកែវ',
                'code' => '1207',
                'province_id' => 12,
            ),
            102 => 
            array (
                'id' => 103,
                'name_en' => 'Saensokh',
                'name_kh' => 'សែនសុខ',
                'code' => '1208',
                'province_id' => 12,
            ),
            103 => 
            array (
                'id' => 104,
                'name_en' => 'Pur SenChey',
                'name_kh' => 'ពោធិ៍សែនជ័យ',
                'code' => '1209',
                'province_id' => 12,
            ),
            104 => 
            array (
                'id' => 105,
                'name_en' => 'Chraoy Chongvar',
                'name_kh' => 'ជ្រោយចង្វារ',
                'code' => '1210',
                'province_id' => 12,
            ),
            105 => 
            array (
                'id' => 106,
                'name_en' => 'Praek Pnov',
                'name_kh' => 'ព្រែកព្នៅ',
                'code' => '1211',
                'province_id' => 12,
            ),
            106 => 
            array (
                'id' => 107,
                'name_en' => 'Chbar Ampov',
                'name_kh' => 'ច្បារអំពៅ',
                'code' => '1212',
                'province_id' => 12,
            ),
            107 => 
            array (
                'id' => 108,
                'name_en' => 'Boeng Keng Kang',
                'name_kh' => 'បឹងកេងកង',
                'code' => '1213',
                'province_id' => 12,
            ),
            108 => 
            array (
                'id' => 109,
                'name_en' => 'Kamboul',
                'name_kh' => 'កំបូល',
                'code' => '1214',
                'province_id' => 12,
            ),
            109 => 
            array (
                'id' => 110,
                'name_en' => 'Chey Saen',
                'name_kh' => 'ជ័យសែន',
                'code' => '1301',
                'province_id' => 13,
            ),
            110 => 
            array (
                'id' => 111,
                'name_en' => 'Chhaeb',
                'name_kh' => 'ឆែប',
                'code' => '1302',
                'province_id' => 13,
            ),
            111 => 
            array (
                'id' => 112,
                'name_en' => 'Choam Ksant',
                'name_kh' => 'ជាំក្សាន្ដ',
                'code' => '1303',
                'province_id' => 13,
            ),
            112 => 
            array (
                'id' => 113,
                'name_en' => 'Kuleaen',
                'name_kh' => 'គូលែន',
                'code' => '1304',
                'province_id' => 13,
            ),
            113 => 
            array (
                'id' => 114,
                'name_en' => 'Rovieng',
                'name_kh' => 'រវៀង',
                'code' => '1305',
                'province_id' => 13,
            ),
            114 => 
            array (
                'id' => 115,
                'name_en' => 'Sangkum Thmei',
                'name_kh' => 'សង្គមថ្មី',
                'code' => '1306',
                'province_id' => 13,
            ),
            115 => 
            array (
                'id' => 116,
                'name_en' => 'Tbaeng Mean Chey',
                'name_kh' => 'ត្បែងមានជ័យ',
                'code' => '1307',
                'province_id' => 13,
            ),
            116 => 
            array (
                'id' => 117,
                'name_en' => 'Preah Vihear',
                'name_kh' => 'ព្រះវិហារ',
                'code' => '1308',
                'province_id' => 13,
            ),
            117 => 
            array (
                'id' => 118,
                'name_en' => 'Ba Phnum',
                'name_kh' => 'បាភ្នំ',
                'code' => '1401',
                'province_id' => 14,
            ),
            118 => 
            array (
                'id' => 119,
                'name_en' => 'Kamchay Mear',
                'name_kh' => 'កំចាយមារ',
                'code' => '1402',
                'province_id' => 14,
            ),
            119 => 
            array (
                'id' => 120,
                'name_en' => 'Kampong Trabaek',
                'name_kh' => 'កំពង់ត្របែក',
                'code' => '1403',
                'province_id' => 14,
            ),
            120 => 
            array (
                'id' => 121,
                'name_en' => 'Kanhchriech',
                'name_kh' => 'កញ្ជ្រៀច',
                'code' => '1404',
                'province_id' => 14,
            ),
            121 => 
            array (
                'id' => 122,
                'name_en' => 'Me Sang',
                'name_kh' => 'មេសាង',
                'code' => '1405',
                'province_id' => 14,
            ),
            122 => 
            array (
                'id' => 123,
                'name_en' => 'Peam Chor',
                'name_kh' => 'ពាមជរ',
                'code' => '1406',
                'province_id' => 14,
            ),
            123 => 
            array (
                'id' => 124,
                'name_en' => 'Peam Ro',
                'name_kh' => 'ពាមរក៍',
                'code' => '1407',
                'province_id' => 14,
            ),
            124 => 
            array (
                'id' => 125,
                'name_en' => 'Pea Reang',
                'name_kh' => 'ពារាំង',
                'code' => '1408',
                'province_id' => 14,
            ),
            125 => 
            array (
                'id' => 126,
                'name_en' => 'Preah Sdach',
                'name_kh' => 'ព្រះស្ដេច',
                'code' => '1409',
                'province_id' => 14,
            ),
            126 => 
            array (
                'id' => 127,
                'name_en' => 'Prey Veng',
                'name_kh' => 'ព្រៃវែង',
                'code' => '1410',
                'province_id' => 14,
            ),
            127 => 
            array (
                'id' => 128,
                'name_en' => 'Pur Rieng',
                'name_kh' => 'ពោធិ៍រៀង',
                'code' => '1411',
                'province_id' => 14,
            ),
            128 => 
            array (
                'id' => 129,
                'name_en' => 'Sithor Kandal',
                'name_kh' => 'ស៊ីធរកណ្ដាល',
                'code' => '1412',
                'province_id' => 14,
            ),
            129 => 
            array (
                'id' => 130,
                'name_en' => 'Svay Antor',
                'name_kh' => 'ស្វាយអន្ទរ',
                'code' => '1413',
                'province_id' => 14,
            ),
            130 => 
            array (
                'id' => 131,
                'name_en' => 'Bakan',
                'name_kh' => 'បាកាន',
                'code' => '1501',
                'province_id' => 15,
            ),
            131 => 
            array (
                'id' => 132,
                'name_en' => 'Kandieng',
                'name_kh' => 'កណ្ដៀង',
                'code' => '1502',
                'province_id' => 15,
            ),
            132 => 
            array (
                'id' => 133,
                'name_en' => 'Krakor',
                'name_kh' => 'ក្រគរ',
                'code' => '1503',
                'province_id' => 15,
            ),
            133 => 
            array (
                'id' => 134,
                'name_en' => 'Phnum Kravanh',
                'name_kh' => 'ភ្នំក្រវ៉ាញ',
                'code' => '1504',
                'province_id' => 15,
            ),
            134 => 
            array (
                'id' => 135,
                'name_en' => 'Pursat',
                'name_kh' => 'ពោធិ៍សាត់',
                'code' => '1505',
                'province_id' => 15,
            ),
            135 => 
            array (
                'id' => 136,
                'name_en' => 'Veal Veaeng',
                'name_kh' => 'វាលវែង',
                'code' => '1506',
                'province_id' => 15,
            ),
            136 => 
            array (
                'id' => 137,
                'name_en' => 'Ta Lou Senchey',
                'name_kh' => 'តាលោសែនជ័យ',
                'code' => '1507',
                'province_id' => 15,
            ),
            137 => 
            array (
                'id' => 138,
                'name_en' => 'Andoung Meas',
                'name_kh' => 'អណ្ដូងមាស',
                'code' => '1601',
                'province_id' => 16,
            ),
            138 => 
            array (
                'id' => 139,
                'name_en' => 'Ban Lung',
                'name_kh' => 'បានលុង',
                'code' => '1602',
                'province_id' => 16,
            ),
            139 => 
            array (
                'id' => 140,
                'name_en' => 'Bar Kaev',
                'name_kh' => 'បរកែវ',
                'code' => '1603',
                'province_id' => 16,
            ),
            140 => 
            array (
                'id' => 141,
                'name_en' => 'Koun Mom',
                'name_kh' => 'កូនមុំ',
                'code' => '1604',
                'province_id' => 16,
            ),
            141 => 
            array (
                'id' => 142,
                'name_en' => 'Lumphat',
                'name_kh' => 'លំផាត់',
                'code' => '1605',
                'province_id' => 16,
            ),
            142 => 
            array (
                'id' => 143,
                'name_en' => 'Ou Chum',
                'name_kh' => 'អូរជុំ',
                'code' => '1606',
                'province_id' => 16,
            ),
            143 => 
            array (
                'id' => 144,
                'name_en' => 'Ou Ya Dav',
                'name_kh' => 'អូរយ៉ាដាវ',
                'code' => '1607',
                'province_id' => 16,
            ),
            144 => 
            array (
                'id' => 145,
                'name_en' => 'Ta Veaeng',
                'name_kh' => 'តាវែង',
                'code' => '1608',
                'province_id' => 16,
            ),
            145 => 
            array (
                'id' => 146,
                'name_en' => 'Veun Sai',
                'name_kh' => 'វើនសៃ',
                'code' => '1609',
                'province_id' => 16,
            ),
            146 => 
            array (
                'id' => 147,
                'name_en' => 'Angkor Chum',
                'name_kh' => 'អង្គរជុំ',
                'code' => '1701',
                'province_id' => 17,
            ),
            147 => 
            array (
                'id' => 148,
                'name_en' => 'Angkor Thum',
                'name_kh' => 'អង្គរធំ',
                'code' => '1702',
                'province_id' => 17,
            ),
            148 => 
            array (
                'id' => 149,
                'name_en' => 'Banteay Srei',
                'name_kh' => 'បន្ទាយស្រី',
                'code' => '1703',
                'province_id' => 17,
            ),
            149 => 
            array (
                'id' => 150,
                'name_en' => 'Chi Kraeng',
                'name_kh' => 'ជីក្រែង',
                'code' => '1704',
                'province_id' => 17,
            ),
            150 => 
            array (
                'id' => 151,
                'name_en' => 'Kralanh',
                'name_kh' => 'ក្រឡាញ់',
                'code' => '1706',
                'province_id' => 17,
            ),
            151 => 
            array (
                'id' => 152,
                'name_en' => 'Puok',
                'name_kh' => 'ពួក',
                'code' => '1707',
                'province_id' => 17,
            ),
            152 => 
            array (
                'id' => 153,
                'name_en' => 'Prasat Bakong',
                'name_kh' => 'ប្រាសាទបាគង',
                'code' => '1709',
                'province_id' => 17,
            ),
            153 => 
            array (
                'id' => 154,
                'name_en' => 'Siem Reap',
                'name_kh' => 'សៀមរាប',
                'code' => '1710',
                'province_id' => 17,
            ),
            154 => 
            array (
                'id' => 155,
                'name_en' => 'Soutr Nikom',
                'name_kh' => 'សូទ្រនិគម',
                'code' => '1711',
                'province_id' => 17,
            ),
            155 => 
            array (
                'id' => 156,
                'name_en' => 'Srei Snam',
                'name_kh' => 'ស្រីស្នំ',
                'code' => '1712',
                'province_id' => 17,
            ),
            156 => 
            array (
                'id' => 157,
                'name_en' => 'Svay Leu',
                'name_kh' => 'ស្វាយលើ',
                'code' => '1713',
                'province_id' => 17,
            ),
            157 => 
            array (
                'id' => 158,
                'name_en' => 'Varin',
                'name_kh' => 'វ៉ារិន',
                'code' => '1714',
                'province_id' => 17,
            ),
            158 => 
            array (
                'id' => 159,
                'name_en' => 'Preah Sihanouk',
                'name_kh' => 'ព្រះសីហនុ',
                'code' => '1801',
                'province_id' => 18,
            ),
            159 => 
            array (
                'id' => 160,
                'name_en' => 'Prey Nob',
                'name_kh' => 'ព្រៃនប់',
                'code' => '1802',
                'province_id' => 18,
            ),
            160 => 
            array (
                'id' => 161,
                'name_en' => 'Stueng Hav',
                'name_kh' => 'ស្ទឹងហាវ',
                'code' => '1803',
                'province_id' => 18,
            ),
            161 => 
            array (
                'id' => 162,
                'name_en' => 'Kampong Seila',
                'name_kh' => 'កំពង់សីលា',
                'code' => '1804',
                'province_id' => 18,
            ),
            162 => 
            array (
                'id' => 163,
                'name_en' => 'Kaoh Rung',
                'name_kh' => 'កោះរ៉ុង',
                'code' => '1805',
                'province_id' => 18,
            ),
            163 => 
            array (
                'id' => 164,
                'name_en' => 'Sesan',
                'name_kh' => 'សេសាន',
                'code' => '1901',
                'province_id' => 19,
            ),
            164 => 
            array (
                'id' => 165,
                'name_en' => 'Siem Bouk',
                'name_kh' => 'សៀមបូក',
                'code' => '1902',
                'province_id' => 19,
            ),
            165 => 
            array (
                'id' => 166,
                'name_en' => 'Siem Pang',
                'name_kh' => 'សៀមប៉ាង',
                'code' => '1903',
                'province_id' => 19,
            ),
            166 => 
            array (
                'id' => 167,
                'name_en' => 'Stueng Traeng',
                'name_kh' => 'ស្ទឹងត្រែង',
                'code' => '1904',
                'province_id' => 19,
            ),
            167 => 
            array (
                'id' => 168,
                'name_en' => 'Thala Barivat',
                'name_kh' => 'ថាឡាបរិវ៉ាត់',
                'code' => '1905',
                'province_id' => 19,
            ),
            168 => 
            array (
                'id' => 169,
                'name_en' => 'Borei Ou Svay Senchey',
                'name_kh' => 'បុរីអូរស្វាយសែនជ័យ',
                'code' => '1906',
                'province_id' => 19,
            ),
            169 => 
            array (
                'id' => 170,
                'name_en' => 'Chantrea',
                'name_kh' => 'ចន្ទ្រា',
                'code' => '2001',
                'province_id' => 20,
            ),
            170 => 
            array (
                'id' => 171,
                'name_en' => 'Kampong Rou',
                'name_kh' => 'កំពង់រោទិ៍',
                'code' => '2002',
                'province_id' => 20,
            ),
            171 => 
            array (
                'id' => 172,
                'name_en' => 'Rumduol',
                'name_kh' => 'រំដួល',
                'code' => '2003',
                'province_id' => 20,
            ),
            172 => 
            array (
                'id' => 173,
                'name_en' => 'Romeas Haek',
                'name_kh' => 'រមាសហែក',
                'code' => '2004',
                'province_id' => 20,
            ),
            173 => 
            array (
                'id' => 174,
                'name_en' => 'Svay Chrum',
                'name_kh' => 'ស្វាយជ្រំ',
                'code' => '2005',
                'province_id' => 20,
            ),
            174 => 
            array (
                'id' => 175,
                'name_en' => 'Svay Rieng',
                'name_kh' => 'ស្វាយរៀង',
                'code' => '2006',
                'province_id' => 20,
            ),
            175 => 
            array (
                'id' => 176,
                'name_en' => 'Svay Teab',
                'name_kh' => 'ស្វាយទាប',
                'code' => '2007',
                'province_id' => 20,
            ),
            176 => 
            array (
                'id' => 177,
                'name_en' => 'Bavet',
                'name_kh' => 'បាវិត',
                'code' => '2008',
                'province_id' => 20,
            ),
            177 => 
            array (
                'id' => 178,
                'name_en' => 'Angkor Borei',
                'name_kh' => 'អង្គរបូរី',
                'code' => '2101',
                'province_id' => 21,
            ),
            178 => 
            array (
                'id' => 179,
                'name_en' => 'Bati',
                'name_kh' => 'បាទី',
                'code' => '2102',
                'province_id' => 21,
            ),
            179 => 
            array (
                'id' => 180,
                'name_en' => 'Borei Cholsar',
                'name_kh' => 'បូរីជលសារ',
                'code' => '2103',
                'province_id' => 21,
            ),
            180 => 
            array (
                'id' => 181,
                'name_en' => 'Kiri Vong',
                'name_kh' => 'គីរីវង់',
                'code' => '2104',
                'province_id' => 21,
            ),
            181 => 
            array (
                'id' => 182,
                'name_en' => 'Kaoh Andaet',
                'name_kh' => 'កោះអណ្ដែត',
                'code' => '2105',
                'province_id' => 21,
            ),
            182 => 
            array (
                'id' => 183,
                'name_en' => 'Prey Kabbas',
                'name_kh' => 'ព្រៃកប្បាស',
                'code' => '2106',
                'province_id' => 21,
            ),
            183 => 
            array (
                'id' => 184,
                'name_en' => 'Samraong',
                'name_kh' => 'សំរោង',
                'code' => '2107',
                'province_id' => 21,
            ),
            184 => 
            array (
                'id' => 185,
                'name_en' => 'Doun Kaev',
                'name_kh' => 'ដូនកែវ',
                'code' => '2108',
                'province_id' => 21,
            ),
            185 => 
            array (
                'id' => 186,
                'name_en' => 'Tram Kak',
                'name_kh' => 'ត្រាំកក់',
                'code' => '2109',
                'province_id' => 21,
            ),
            186 => 
            array (
                'id' => 187,
                'name_en' => 'Treang',
                'name_kh' => 'ទ្រាំង',
                'code' => '2110',
                'province_id' => 21,
            ),
            187 => 
            array (
                'id' => 188,
                'name_en' => 'Anlong Veaeng',
                'name_kh' => 'អន្លង់វែង',
                'code' => '2201',
                'province_id' => 22,
            ),
            188 => 
            array (
                'id' => 189,
                'name_en' => 'Banteay Ampil',
                'name_kh' => 'បន្ទាយអំពិល',
                'code' => '2202',
                'province_id' => 22,
            ),
            189 => 
            array (
                'id' => 190,
                'name_en' => 'Chong Kal',
                'name_kh' => 'ចុងកាល់',
                'code' => '2203',
                'province_id' => 22,
            ),
            190 => 
            array (
                'id' => 191,
                'name_en' => 'Samraong',
                'name_kh' => 'សំរោង',
                'code' => '2204',
                'province_id' => 22,
            ),
            191 => 
            array (
                'id' => 192,
                'name_en' => 'Trapeang Prasat',
                'name_kh' => 'ត្រពាំងប្រាសាទ',
                'code' => '2205',
                'province_id' => 22,
            ),
            192 => 
            array (
                'id' => 193,
                'name_en' => 'Damnak Changaeur',
                'name_kh' => 'ដំណាក់ចង្អើរ',
                'code' => '2301',
                'province_id' => 23,
            ),
            193 => 
            array (
                'id' => 194,
                'name_en' => 'Kaeb',
                'name_kh' => 'កែប',
                'code' => '2302',
                'province_id' => 23,
            ),
            194 => 
            array (
                'id' => 195,
                'name_en' => 'Pailin',
                'name_kh' => 'ប៉ៃលិន',
                'code' => '2401',
                'province_id' => 24,
            ),
            195 => 
            array (
                'id' => 196,
                'name_en' => 'Sala Krau',
                'name_kh' => 'សាលាក្រៅ',
                'code' => '2402',
                'province_id' => 24,
            ),
            196 => 
            array (
                'id' => 197,
                'name_en' => 'Dambae',
                'name_kh' => 'តំបែរ',
                'code' => '2501',
                'province_id' => 25,
            ),
            197 => 
            array (
                'id' => 198,
                'name_en' => 'Krouch Chhmar',
                'name_kh' => 'ក្រូចឆ្មារ',
                'code' => '2502',
                'province_id' => 25,
            ),
            198 => 
            array (
                'id' => 199,
                'name_en' => 'Memot',
                'name_kh' => 'មេមត់',
                'code' => '2503',
                'province_id' => 25,
            ),
            199 => 
            array (
                'id' => 200,
                'name_en' => 'Ou Reang Ov',
                'name_kh' => 'អូររាំងឪ',
                'code' => '2504',
                'province_id' => 25,
            ),
            200 => 
            array (
                'id' => 201,
                'name_en' => 'Ponhea Kraek',
                'name_kh' => 'ពញាក្រែក',
                'code' => '2505',
                'province_id' => 25,
            ),
            201 => 
            array (
                'id' => 202,
                'name_en' => 'Suong',
                'name_kh' => 'សួង',
                'code' => '2506',
                'province_id' => 25,
            ),
            202 => 
            array (
                'id' => 203,
                'name_en' => 'Tboung Khmum',
                'name_kh' => 'ត្បូងឃ្មុំ',
                'code' => '2507',
                'province_id' => 25,
            ),
        ));
        
        
    }
}