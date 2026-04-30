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
        Schema::create('document_organization_focal_people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_structure_id')->nullable(true)->default(0);
            $table->integer('officer_id')->nullable(true)->default(0);
            $table->integer('priority')->nullable(true)->detault(0)->comment('The record with this field will have power over others');
            $table->integer('created_by')->default(0)->comment('Author of the record');
            $table->integer('updated_by')->default(0)->comment('Editor of the record');
            $table->integer('deleted_by')->default(0)->comment('Destroyer of the record');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_organization_focal_people');
    }
};
