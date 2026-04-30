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
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('public_key',191)->nullable(false)->comment('The the identify number of the document as md5');
            $table->string('public_key',191)->nullable()->comment('The the identify number of the document as md5');
            $table->string('number',50)->nullable()->comment('The the identify number of the document');
            // $table->string('number',50)->nullable(false)->comment('The the identify number of the document');
            $table->text('objective')->nullable(false)->comment("The title of the document is requred");
            $table->string('word_file',191)->nullable(true)->comment('The word document');
            $table->string('file_word_name',191)->nullable(true)->comment('The name of the file word before change');
            $table->string('pdf_file',191)->nullable(true)->comment('The pdf document');
            $table->string('file_pdf_name',191)->nullable(true)->comment('The name of the file pdf before change');
            $table->integer('document_type')->nullable(false)->default(0);
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
        Schema::dropIfExists('documents');
    }
};
