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
        Schema::create('ranks', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->comment('Name of rank');
            $table->string('ank',191)->nullable(true);
            $table->string('krobkhan_name',191)->nullable(true);
            $table->string('krobkhan',191)->nullable(true);
            $table->string('rank',191)->nullable(false);            
            $table->string('thnak',191)->nullable(true);
            
            $table->text('desp')->nullable()->comment('description of rank');
            $table->string('tpid', 191)->nullable()->comment('The id of the parent which identify the whole type of them.');
            $table->integer('pid')->nullable()->comment('Parent id of this rank');
            $table->text('cids')->nullable()->comment('Children IDs will be store here.');
            $table->string('image', 191)->nullable();
            $table->integer('record_index')->nullable()->comment('The index of the record');
            $table->integer('active')->nullable()->comment('The activation of the record');
            $table->timestamps();
            $table->softDeletes();
            $table->string('prefix', 50)->nullable();
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
        Schema::dropIfExists('ranks');
    }
};
