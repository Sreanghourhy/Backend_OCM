<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable(true)->default(0)->comment('Document that will be attached with the transaction');
            $table->integer('sender_id')->nullable(false)->default(0)->comment('The sender of the transaction');
            $table->text('subject')->nullable(false)->comment('The subject of the document to be sent with this transaction');
            $table->string('sent_at',50)->nullable(true)->comment('The datetime that the document sent out. sent_at == null ? draft : not draft.');
            // $table->string('date_in',20)->nullable(false)->comment('The datetime that the document is check in');
            $table->string('date_in',20)->nullable()->comment('The datetime that the document is check in');
            $table->integer('previous_transaction_id')->nullable(true)->default(0)->comment('The id of the previous transaction_id');
            $table->integer('next_transaction_id')->nullable(true)->default(0)->comment('The id of the next transaction_id');
            $table->integer('organization_structure_id')->nullable(true)->default(0)->comment('The id of the organizatoin structure');
            // $table->string('tpid',191)->nullable(false)->comment('The structure ids');
            $table->string('tpid',191)->nullable(true)->comment('The structure ids');
            // $table->string('status',20)->nullable(false)->comment('The status of the transaction');
            $table->string('status',20)->nullable()->comment('The status of the transaction');
            $table->integer('created_by')->default(0)->comment('Author of the record');
            $table->integer('updated_by')->default(0)->comment('Editor of the record');
            $table->integer('deleted_by')->default(0)->comment('Destroyer of the record');
            $table->timestamps(); // created_at , updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_transactions');
    }
};
