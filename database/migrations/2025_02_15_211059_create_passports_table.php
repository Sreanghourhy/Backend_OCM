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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->integer('people_id')->nullable(false);
            $table->string('number',191)->nullable(false);
            $table->string('serial_number',70)->nullable(false);

            $table->string('profession',191)->nullable(true);
            $table->string('height',50)->nullable(true);
            $table->string('distinguishing_mark',191)->nullable(true);
            $table->string('emergency_contact_person',191)->nullable(true);
            $table->text('address')->nullable(true);
            $table->string('type',191)->nullable(true);
            $table->string('country_code',50)->nullable(true);
            $table->string('nationality',50)->nullable(true);
            $table->string('dob',50)->nullable(true);
            $table->string('gender',50)->nullable(true);
            $table->string('effective_date',50)->nullable(true);
            $table->string('expired_date',50)->nullable(true);
            $table->text('pob')->nullable(true);
            
            $table->text('pdf')->nullable(true);
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
        Schema::dropIfExists('passports');
    }
};
