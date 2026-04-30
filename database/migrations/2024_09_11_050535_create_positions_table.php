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
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->comment('Name of position');
            $table->text('name_key')->nullable()->comment('keyname use to diffentiate from others');
            $table->text('desp')->nullable()->comment('description of position');
            $table->string('tpid', 191)->nullable()->comment('The id of the parent which identify the whole type of them.');
            $table->integer('pid')->nullable()->comment('Parent id of this position');
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
        Schema::dropIfExists('positions');
    }
};
