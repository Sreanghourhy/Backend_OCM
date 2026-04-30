<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_structure_positions', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable(true)->comment('The name of the organization based on its structure.');
            $table->integer('pid')->nullable()->comment('Parent id of this organization');
            $table->text('tpid', 191)->nullable()->comment('The id of the parent which identify the whole type of them.');
            $table->text('cids')->nullable()->comment('Children IDs will be store here.');
            $table->string('image', 191)->nullable(true);
            $table->text('pdf')->nullable(true);
            $table->integer('organization_structure_id')->nullable(false);
            $table->integer('position_id')->nullable(false);
            $table->text('job_desp')->nullable(true);
            $table->integer('total_jobs')->nullable(true)->default(0)->comment('Total officers within this position base on the officer_jobs table');
            $table->text('desp')->nullable(true);
            $table->integer('index')->default(0)->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_structure_positions');
    }
};
