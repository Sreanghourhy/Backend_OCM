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
        Schema::create('regulator_structure', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->integer('regulator_id')->default(0)->comment('Id of the document that describe the relationship.');
            $table->integer('tpid')->nullable()->comment('Id of the document which is the top level of this document.');
            $table->integer('pid')->nullable()->comment('Id of the record which is the parent of this record.');
            $table->string('amend', 191)->nullable()->comment('this field is used to store all the ids of the related document with this document.');
            $table->text('desc')->nullable();
            $table->string('image', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('pdf', 191)->nullable();
            $table->text('cids')->nullable();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulator_structure');
    }
};
