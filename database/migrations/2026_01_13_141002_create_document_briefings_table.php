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
        Schema::create('document_briefings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->nullable(false)->default(0);
            $table->text('briefing')->nullable(true);
            $table->integer('briefer_id')->nullable(false)->default(0)->comment('The id of the user that give a breif.');
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
        Schema::dropIfExists('document_briefings');
    }
};
