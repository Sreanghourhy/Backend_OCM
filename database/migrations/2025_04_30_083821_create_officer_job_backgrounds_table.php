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
        Schema::create('officer_job_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->integer('officer_id')->nullable(false);
            $table->integer('officer_job_id')->nullable(true);
            $table->string('organization',191)->nullable(false);
            $table->string('sub_organization',191)->nullable(false);
            $table->string('position',191)->nullable(false);
            $table->string('start',50)->nullable(false);
            $table->string('end',50)->nullable(true);
            $table->string('pdf',191)->nullable(true);
            $table->text('skill_of_position')->nullable(true);
            $table->integer('sector')->default(0)->nullable(true);
            $table->boolean('is_primary')->default(false)->comment('Is this job is the primary job of the officer_id');
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
        Schema::dropIfExists('officer_job_backgrounds');
    }
};
