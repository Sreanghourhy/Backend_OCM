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
        Schema::create('birth_certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('people_id')->nullable(false);
            $table->string('birth_number',20)->nullable(false);
            $table->string('book_number',20)->nullable(false);
            $table->string('year',20)->nullable(false);
            $table->integer('province_id')->nullable(false);
            $table->integer('district_id')->nullable(false);
            $table->integer('commune_id')->nullable(false);
            $table->string('firstname',191)->nullable(false);
            $table->string('lastname',191)->nullable(false);
            $table->string('enfirstname',191)->nullable(false);
            $table->string('enlastname',191)->nullable(false);
            $table->string('dob',50)->nullable(true);
            $table->string('gender',50)->nullable(true);
            $table->string('nationality',191)->nullable(true);
            $table->string('profession',191)->nullable(true);
            $table->string('organization',191)->nullable(true);
            $table->string('national',191)->nullable(true);
            $table->text('pob')->nullable(true);
            $table->string('issued_date',191)->nullable(false);
            // $table->text('issued_location')->nullable(true);
            // $table->string('signed_name',50)->nullable(true);
            // Father information
            // $table->integer('father_id')->nullable(true);
            // $table->string('father_firstname',191)->nullable(false);
            // $table->string('father_lastname',191)->nullable(false);
            // $table->string('father_enfirstname',191)->nullable(false);
            // $table->string('father_enlastname',191)->nullable(false);
            // $table->string('father_dob',50)->nullable(true);
            // $table->string('father_nationality',50)->nullable(true);
            // $table->text('father_pob')->nullable(true);
            // $table->text('father_current_address')->nullable(true);
            // $table->text('father_picture')->nullable(true);
            // Mother information
            // $table->integer('mother_id')->nullable(true);
            // $table->string('mother_firstname',191)->nullable(false);
            // $table->string('mother_lastname',191)->nullable(false);
            // $table->string('mother_enfirstname',191)->nullable(false);
            // $table->string('mother_enlastname',191)->nullable(false);
            // $table->string('mother_dob',50)->nullable(true);
            // $table->string('mother_nationality',50)->nullable(true);
            // $table->text('mother_pob')->nullable(true);
            // $table->text('mother_current_address')->nullable(true);
            // $table->text('mother_picture')->nullable(true);
            //
            $table->integer('wedding_certificate_id')->default(0)->nullable(true);
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
        Schema::dropIfExists('birth_certificates');
    }
};
