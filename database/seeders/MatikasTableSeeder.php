<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MatikasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('matikas')->delete();
        
        \DB::table('matikas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => 'ច្បាប់ព្រហ្មទណ្ឌ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 3,
                'kunty_id' => 19,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => 'ការទទួលខុសត្រូវព្រហ្មទណ្ឌ ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 3,
                'kunty_id' => 19,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'title' => 'ទោស ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 3,
                'kunty_id' => 19,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'title' => 'បទឧក្រិដ្ឋប្រល័យពូជសាសន៍ បទឧក្រិដ្ឋប្រឆាំងមនុស្សជាតិ បទឧក្រិដ្ឋសង្រ្គាម ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 3,
                'kunty_id' => 20,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'title' => 'ការប៉ះពាល់ដល់មនុស្ស ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 3,
                'kunty_id' => 20,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'title' => 'ការប៉ះពាល់ដល់អនីតិជន និងគ្រួសារ ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 3,
                'kunty_id' => 20,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'title' => 'ការយកទ្រព្យសម្បត្ដិអ្នកដទៃមកធ្វើជាកម្មសិទ្ធិរបស់ខ្លួនដោយទុច្ចរិត ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 3,
                'kunty_id' => 21,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'title' => 'ការប៉ះពាល់ដល់ទ្រព្យសម្បត្ដិ ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 3,
                'kunty_id' => 21,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            8 => 
            array (
                'id' => 9,
                'title' => 'ការប៉ះពាល់ដល់ស្ថាប័នចម្បងរបស់រដ្ឋ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 3,
                'kunty_id' => 22,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            9 => 
            array (
                'id' => 10,
                'title' => 'ការប៉ះពាល់ដល់វិស័យយុត្ដិធម៌ ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 3,
                'kunty_id' => 22,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            10 => 
            array (
                'id' => 11,
                'title' => 'ការប៉ះពាល់ដល់ដំណើរការរបស់រដ្ឋបាលសាធារណៈ ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 3,
                'kunty_id' => 22,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            11 => 
            array (
                'id' => 12,
                'title' => 'ការប៉ះពាល់ដល់ទំនុកចិត្ដសាធារណៈ ',
                'number' => 'មាតិកាទី ៤ ',
                'book_id' => 3,
                'kunty_id' => 22,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            12 => 
            array (
                'id' => 13,
                'title' => 'គោលការណ៍ទូទៅ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 25,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            13 => 
            array (
                'id' => 14,
                'title' => 'បណ្ដឹងអាជ្ញា ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 25,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            14 => 
            array (
                'id' => 15,
                'title' => 'បណ្ដឹងរដ្ឋប្បវេណី ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 4,
                'kunty_id' => 25,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            15 => 
            array (
                'id' => 16,
                'title' => 'អង្គការអយ្យការ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 26,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            16 => 
            array (
                'id' => 17,
                'title' => 'ចៅក្រមស៊ើបសួរ ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 26,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            17 => 
            array (
                'id' => 18,
                'title' => 'សភាស៊ើបសួរ ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 4,
                'kunty_id' => 26,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            18 => 
            array (
                'id' => 19,
                'title' => 'នគរបាលយុត្ដិធម៌ ',
                'number' => 'មាតិកាទី ៤ ',
                'book_id' => 4,
                'kunty_id' => 26,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            19 => 
            array (
                'id' => 20,
                'title' => 'បទប្បញ្ញត្ដិទូទៅ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 27,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            20 => 
            array (
                'id' => 21,
                'title' => 'ការស៊ើបអង្កេតករណីជាក់ស្ដែង ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 27,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            21 => 
            array (
                'id' => 22,
                'title' => 'ការស៊ើបអង្កេតបឋម ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 4,
                'kunty_id' => 27,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            22 => 
            array (
                'id' => 23,
                'title' => 'ការប្រគល់ឲ្យម្ចាស់ដើមវិញ នូវវត្ថុដែលចាប់យក ក្នុងក្របខ័ណ្ឌនៃការស៊ើបអង្កេត ',
                'number' => 'មាតិកាទី ៤ ',
                'book_id' => 4,
                'kunty_id' => 27,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            23 => 
            array (
                'id' => 24,
                'title' => 'ចៅក្រមស៊ើបសួរ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 28,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            24 => 
            array (
                'id' => 25,
                'title' => 'សភាស៊ើបសួរ ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 28,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            25 => 
            array (
                'id' => 26,
                'title' => 'សាលក្រមសាលាដំបូង ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 29,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            26 => 
            array (
                'id' => 27,
                'title' => 'បណ្ដឹងឧទ្ធរណ៍ចំពោះសាលក្រម ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 30,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            27 => 
            array (
                'id' => 28,
                'title' => 'បណ្ដឹងសាទុក្ខ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 31,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            28 => 
            array (
                'id' => 29,
                'title' => 'បណ្ដឹងសើរើរឿងក្ដី ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 31,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            29 => 
            array (
                'id' => 30,
                'title' => 'ដីកាកោះហៅបញ្ជូនទៅជំនុំជម្រះផ្ទាល់ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 32,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            30 => 
            array (
                'id' => 31,
                'title' => 'ដីកាកោះហៅជនជាប់ចោទឲ្យចូលសវនាការ ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 32,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            31 => 
            array (
                'id' => 32,
                'title' => 'ដីកាកោះហៅជនក្រៅពីជនជាប់ចោទឲ្យចូលសវនាការ ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 4,
                'kunty_id' => 32,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            32 => 
            array (
                'id' => 33,
                'title' => 'ដីកាឲ្យដំណឹងអំពីសេចក្ដីសម្រេចរបស់តុលាការ ',
                'number' => 'មាតិកាទី ៤ ',
                'book_id' => 4,
                'kunty_id' => 32,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            33 => 
            array (
                'id' => 34,
                'title' => 'បទប្បញ្ញត្ដិរួម ',
                'number' => 'មាតិកាទី ៥ ',
                'book_id' => 4,
                'kunty_id' => 32,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            34 => 
            array (
                'id' => 35,
                'title' => 'បទប្បញ្ញត្ដិទូទៅ ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 33,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            35 => 
            array (
                'id' => 36,
                'title' => 'ការប្រតិបត្ដការឃុំខ្លួនបណ្ដោះអាសន្ន និងទោសដកហូតសេរីភាព ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 33,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            36 => 
            array (
                'id' => 37,
                'title' => 'ការបង្ខំដល់រូបកាយ ',
                'number' => 'មាតិកាទី ៣ ',
                'book_id' => 4,
                'kunty_id' => 33,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            37 => 
            array (
                'id' => 38,
                'title' => 'ការផ្ដល់ និងការមាននីតិសម្បទាឡើងវិញ ',
                'number' => 'មាតិកាទី ៤ ',
                'book_id' => 4,
                'kunty_id' => 33,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            38 => 
            array (
                'id' => 39,
                'title' => 'បញ្ជីថ្កោលទោស ',
                'number' => 'មាតិកាទី ៥ ',
                'book_id' => 4,
                'kunty_id' => 33,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            39 => 
            array (
                'id' => 40,
                'title' => 'ប្រាក់ប្រដាប់ក្ដី ',
                'number' => 'មាតិកាទី ៦ ',
                'book_id' => 4,
                'kunty_id' => 33,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            40 => 
            array (
                'id' => 41,
                'title' => 'បទប្បញ្ញត្ដិទាក់ទងនឹងបុគ្គល ',
                'number' => 'មាតិកាទី ១ ',
                'book_id' => 4,
                'kunty_id' => 34,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
            41 => 
            array (
                'id' => 42,
                'title' => 'ការបាត់បង់លិខិតស្នាម ការបកស្រាយ និងការកែសម្រួលសេចក្ដីសម្រេច ',
                'number' => 'មាតិកាទី ២ ',
                'book_id' => 4,
                'kunty_id' => 34,
                'created_by' => 0,
                'updated_by' => 0,
                'created_at' => '2015-06-16 00:00:00',
                'updated_at' => '2015-06-16 00:00:00',
            ),
        ));
        
        
    }
}