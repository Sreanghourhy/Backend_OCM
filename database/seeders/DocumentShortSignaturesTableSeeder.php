<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentShortSignaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('document_short_signatures')->delete();
        
        \DB::table('document_short_signatures')->insert(array (
            0 => 
            array (
                'id' => 69,
                'document_id' => 136,
                'user_id' => 1342,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => '2026-02-04 15:50:41',
                'updated_at' => '2026-02-04 15:50:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 70,
                'document_id' => 137,
                'user_id' => 1342,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => '2026-02-04 16:00:47',
                'updated_at' => '2026-02-04 16:00:47',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 71,
                'document_id' => 138,
                'user_id' => 1157,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 10:44:54',
                'updated_at' => '2026-02-05 10:44:54',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 72,
                'document_id' => 147,
                'user_id' => 1157,
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 13:41:35',
                'updated_at' => '2026-02-05 13:41:35',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}