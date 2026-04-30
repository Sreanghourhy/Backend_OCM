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
        Schema::create('timeslots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 191)->default('Timeslot')->comment('The name of the timeslot');
            $table->string('start', 50)->default('00:00')->comment('The start time of slot');
            $table->string('end', 50)->default('00:00')->comment('The start time of slot');
            $table->string('effective_day', 191)->default('1,2,3,4,5')->comment('The days of week that timeslot will be used. 1 -> Mon , 2 -> Tue ...');
            $table->string('uneffective_day', 191)->default('6,0')->comment('The days of week that timeslot will be used. 6 -> Sat , 0 -> Sun ...');
            $table->integer('active')->default(1)->comment('The status of the record is in active or unactive');
            $table->float('rest_duration', null, 0)->default(0)->comment('The duration in minutes for resting within the timeslot');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('timeslots');
    }
};
