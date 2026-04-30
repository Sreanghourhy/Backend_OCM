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
        Schema::create('wedding_certificates', function (Blueprint $table) {
            $table->id();
            
            $table->integer('people_id')->nullable(false);
            
            $table->string('wedding_number',20)->nullable(false);
            $table->string('book_number',20)->nullable(false);
            $table->string('year',20)->nullable(false);            
            $table->integer('province_id')->nullable(false);
            $table->integer('district_id')->nullable(false);
            $table->integer('commune_id')->nullable(false);

            $table->integer('spouse_id')->nullable(false);
            $table->string('spouse_firstname',191)->nullable(false);
            $table->string('spouse_lastname',191)->nullable(false);
            $table->string('spouse_enfirstname',191)->nullable(false);
            $table->string('spouse_enlastname',191)->nullable(false);
            $table->string('spouse_national',191)->nullable(false);
            $table->string('spouse_nid',191)->nullable(false);
            $table->string('spouse_passport',191)->nullable(false);
            $table->string('spouse_nationality',191)->nullable(false);
            $table->string('spouse_dob',191)->nullable(false);
            $table->string('spouse_profession',191)->nullable(false);
            $table->string('spouse_profession_organization',191)->nullable(false);
            $table->text('spouse_pob',191)->nullable(false);
            $table->text('spouse_address')->nullable(false);
            $table->integer('spouse_death')->default(0)->nullable(true);
            
            // Father information
            $table->string('spouse_father_firstname',191)->nullable(false);
            $table->string('spouse_father_lastname',191)->nullable(false);
            $table->string('spouse_father_enfirstname',191)->nullable(false);
            $table->string('spouse_father_enlastname',191)->nullable(false);
            $table->string('spouse_father_dob',50)->nullable(true);
            $table->string('spouse_father_nationality',50)->nullable(true);
            $table->string('spouse_father_national',50)->nullable(true);
            $table->text('spouse_father_pob')->nullable(true);
            $table->string('spouse_father_address',50)->nullable(true);
            $table->string('spouse_father_profession',50)->nullable(true);
            $table->text('spouse_father_picture')->nullable(true);
            $table->integer('spouse_father_death')->default(0);

            // Mother information
            $table->string('spouse_mother_firstname',191)->nullable(false);
            $table->string('spouse_mother_lastname',191)->nullable(false);
            $table->string('spouse_mother_enfirstname',191)->nullable(false);
            $table->string('spouse_mother_enlastname',191)->nullable(false);
            $table->string('spouse_mother_dob',50)->nullable(true);
            $table->string('spouse_mother_nationality',50)->nullable(true);
            $table->string('spouse_mother_national',50)->nullable(true);
            $table->text('spouse_mother_pob')->nullable(true);
            $table->text('spouse_mother_address')->nullable(true);
            $table->text('spouse_mother_profession')->nullable(true);
            $table->text('spouse_mother_picture')->nullable(true);
            $table->integer('spouse_mother_death')->default(0);

            $table->string('issued_date',50)->nullable(false);
            $table->text('issued_location')->nullable(true);
            $table->string('signed_name',50)->nullable(true);
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
        Schema::dropIfExists('wedding_certificates');
    }
};
