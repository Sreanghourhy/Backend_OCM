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
        Schema::create('attendant_check_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('attendant_id')->comment('The id of the attendant');
            $table->integer('timeslot_id')->comment('The id of the attendant timeslot');
            $table->integer('organization_id')->default(0)->comment('The id of the organization that the user check in/out');
            $table->string('checktime', 50)->comment('The check time of the attendant');
            $table->string('check_status', 10)->nullable()->default('1')->comment('The status identify that the checktime is the check-in => "1" or check-out => "0" ');
            $table->string('checktype', 50)->default('0')->comment('0 => SYSETM , 1 => FACE , 2 => FINGER');
            $table->integer('parent_checktime_id')->nullable()->comment('This field used to link the previous record with this one, used in checkin -> checkout.');
            $table->text('meta')->nullable()->comment('The metadata of the checking time. Device\'s information, Agent\'s information, ...');
            $table->timestamps();
            $table->softDeletes();
            $table->string('lat', 50)->nullable()->comment('The latitute of the map coordination');
            $table->string('lng', 50)->nullable()->comment('The longitute of the map coordination');
            $table->string('photo', 191)->nullable()->comment('The photo at the session of check attendant');
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
        Schema::dropIfExists('attendant_check_times');
    }
};
