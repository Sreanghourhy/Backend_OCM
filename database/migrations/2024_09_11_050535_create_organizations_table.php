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
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('keyname')->nullable()->comment('keyname use to diffentiate from others');
            $table->text('name')->comment('Name of organization');
            $table->text('desp')->nullable()->comment('description of organization');
            $table->text('tpid')->nullable()->comment('The id of the parent which identify the whole type of them.');
            $table->integer('pid')->nullable()->comment('Parent id of this organization');
            $table->text('cids')->nullable()->comment('Children IDs will be store here.');
            $table->text('address')->nullable()->comment('Children IDs will be store here.');
            $table->string('image', 191)->nullable();
            $table->text('pdf')->nullable(true);
            $table->integer('record_index')->nullable()->comment('The index of the record');
            $table->integer('active')->nullable()->comment('The activation of the record');
            $table->string('prefix', 50)->nullable();
            $table->string('lat', 50)->nullable();
            $table->string('long', 50)->nullable();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->string('ownership_type',50)->default('public')->nullable(true);
            $table->integer('industry_id')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
