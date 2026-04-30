<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentTransactionReceiversTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('document_transaction_receivers')->delete();
        
        \DB::table('document_transaction_receivers')->insert(array (
            0 => 
            array (
                'id' => 399,
                'document_transaction_id' => 136,
                'receiver_id' => 2484,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1342,
                'updated_by' => 1342,
                'deleted_by' => 0,
                'created_at' => '2026-02-04 16:00:00',
                'updated_at' => '2026-02-04 16:00:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 400,
                'document_transaction_id' => 136,
                'receiver_id' => 3604,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1342,
                'updated_by' => 1342,
                'deleted_by' => 0,
                'created_at' => '2026-02-04 16:00:00',
                'updated_at' => '2026-02-04 16:00:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 401,
                'document_transaction_id' => 137,
                'receiver_id' => 2484,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1157,
                'updated_by' => 1157,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 10:44:00',
                'updated_at' => '2026-02-05 10:44:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 402,
                'document_transaction_id' => 137,
                'receiver_id' => 3604,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1157,
                'updated_by' => 1157,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 10:44:00',
                'updated_at' => '2026-02-05 10:44:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 403,
                'document_transaction_id' => 145,
                'receiver_id' => 2484,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1157,
                'updated_by' => 1157,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 13:41:00',
                'updated_at' => '2026-02-05 13:41:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 404,
                'document_transaction_id' => 145,
                'receiver_id' => 3604,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1157,
                'updated_by' => 1157,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 13:41:00',
                'updated_at' => '2026-02-05 13:41:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 405,
                'document_transaction_id' => 146,
                'receiver_id' => 2484,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1157,
                'updated_by' => 1157,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 13:41:00',
                'updated_at' => '2026-02-05 13:41:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 406,
                'document_transaction_id' => 146,
                'receiver_id' => 3604,
                'seen_at' => NULL,
                'download_at' => NULL,
                'preview_at' => NULL,
                'created_by' => 1157,
                'updated_by' => 1157,
                'deleted_by' => 0,
                'created_at' => '2026-02-05 13:41:00',
                'updated_at' => '2026-02-05 13:41:00',
                'deleted_at' => NULL,
                'accepted_at' => NULL,
            ),
        ));
        
        
    }
}