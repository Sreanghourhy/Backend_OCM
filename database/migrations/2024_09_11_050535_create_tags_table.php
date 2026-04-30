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
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->comment('Name of tag');
            $table->text('desp')->nullable()->comment('description of tag');
            $table->string('tpid', 191)->nullable()->comment('The id of the parent which identify the whole type of them.');
            $table->integer('pid')->nullable()->comment('Parent id of this tag');
            $table->string('model', 191)->nullable()->comment('The model of this tag, it is used to differential tag base on the model objective. And only the root tag has model');
            $table->text('cids')->nullable()->comment('Children IDs will be store here.');
            $table->string('image', 191)->nullable();
            $table->integer('record_index')->nullable()->comment('The index of the record');
            $table->integer('active')->nullable()->comment('The activation of the record');
            $table->timestamps();
            $table->softDeletes();
            $table->string('organization_code', 191)->nullable()->comment('The identification number of the organization.');
            $table->string('code', 50)->nullable()->comment('The prefix of the code which use to indentify the organization.');
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
        Schema::dropIfExists('tags');
    }
};
