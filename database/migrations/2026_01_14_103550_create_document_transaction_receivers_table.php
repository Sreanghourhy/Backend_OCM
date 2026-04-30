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
        Schema::create('document_transaction_receivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('seen_at',50)->nullable(true);
            $table->string('download_at',50)->nullable(true);
            $table->string('preview_at',50)->nullable(true);
            $table->integer('document_transaction_id')->nullable(false)->default(0);
            $table->integer('organization_structure_id')->nullable(true)->default(0)->comment('The id of the organization');
            $table->integer('receiver_id')->nullable(true)->default(0)->comment('The id of the receiver is id of user because user is the one who use system.');
            $table->integer('created_by')->default(0)->comment('Author of the record');
            $table->integer('updated_by')->default(0)->comment('Editor of the record');
            $table->integer('deleted_by')->default(0)->comment('Destroyer of the record');
            $table->string('accepted_at')->nullable(true);//added
            $table->timestamps(); // created_at , updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_transaction_receivers');
    }
};
