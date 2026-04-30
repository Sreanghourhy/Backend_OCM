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
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('passport_id')->nullable(true);
            $table->string('public_key', 191)->default('1e95926c06441aa6f8cfc1075889d454')->comment('The public of the people, use when accessing from the outside of the system.');
            $table->string('namekh', 191)->nullable(true); // អ៊ុំចំរើន
            $table->string('namekhreverse', 191)->nullable(true); // ចំរើនអ៊ុំ
            $table->string('nameen', 191)->nullable(true); // chamroeunoum
            $table->string('nameenreverse', 191)->nullable(true); // oumchamroeun
            $table->string('firstname', 191);
            $table->string('lastname', 191);
            $table->string('enfirstname', 191)->nullable();
            $table->string('enlastname', 191)->nullable();
            $table->integer('gender')->nullable()->comment('0 is Female');
            $table->integer('dead')->nullable()->comment('0 is alive , 1 is dead');
            $table->date('dob')->nullable();
            $table->text('pob')->nullable();
            $table->integer('pob_province_id')->nullable(true)->default(0);
            $table->integer('pob_district_id')->nullable(true)->default(0);
            $table->integer('pob_commune_id')->nullable(true)->default(0);
            $table->integer('pob_village_id')->nullable(true)->default(0);
            $table->string('mobile_phone', 50)->nullable();
            $table->string('office_phone', 50)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('nid',191)->nullable();
            $table->string('nid_start_at',191)->nullable();
            $table->string('nid_expired_at',191)->nullable();
            $table->string('passport',191)->nullable();
            $table->string('image', 191)->nullable();
            $table->string('marry_status', 191)->nullable();
            $table->string('profession',191)->nullable();
            $table->text('address')->nullable();
            $table->integer('address_province_id')->nullable(true)->default(0);
            $table->integer('address_district_id')->nullable(true)->default(0);
            $table->integer('address_commune_id')->nullable(true)->default(0);
            $table->integer('address_village_id')->nullable(true)->default(0);
            $table->text('current_address')->nullable();
            $table->integer('current_address_province_id')->nullable(true)->default(0);
            $table->integer('current_address_district_id')->nullable(true)->default(0);
            $table->integer('current_address_commune_id')->nullable(true)->default(0);
            $table->integer('current_address_village_id')->nullable(true)->default(0);
            $table->string('nationality',50)->nullable(true);
            $table->string('national',50)->nullable(true);
            $table->integer('death')->nullable(true)->default(0);
            $table->integer('body_condition')->nullable(true)->default(0);
            $table->text('body_condition_desp')->nullable(true)->default(0);
            $table->string('father')->nullable();  //added
            $table->string('mother')->nullable();  //added
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->integer('modified_by')->nullable(true);   //added
            $table->integer('organization_id')->nullable(true)->comment('The organization that people is in');
            $table->integer('countesy_id')->nullable(true)->comment('The countesy of the people');
            $table->integer('position_id')->nullable(true)->comment('The position of the people within the organization');

            /**
             * All about family
             */

            // Father information
            $table->integer('father_id')->nullable(true)->comment('The id that identify that which record is the father');
            $table->string('father_firstname',191)->nullable();
            $table->string('father_lastname',191)->nullable();
            $table->string('father_enfirstname',191)->nullable();
            $table->string('father_enlastname',191)->nullable();
            $table->string('father_dob',50)->nullable(true);
            $table->string('father_nationality',50)->nullable(true);
            $table->string('father_national',50)->nullable(true);
            $table->string('father_nid',191)->nullable();
            $table->text('father_pob')->nullable(true);
            $table->text('father_address',50)->nullable(true);
            $table->integer('father_address_province_id')->nullable(true)->default(0);
            $table->integer('father_address_district_id')->nullable(true)->default(0);
            $table->integer('father_address_commune_id')->nullable(true)->default(0);
            $table->integer('father_address_village_id')->nullable(true)->default(0);
            $table->string('father_profession',50)->nullable(true);
            $table->text('father_picture')->nullable(true);
            $table->integer('father_death')->nullable(true)->default(0);

            // Mother information
            $table->integer('mother_id')->nullable(true)->comment('The id that identify that which record is the mother');
            $table->string('mother_firstname',191)->nullable();
            $table->string('mother_lastname',191)->nullable();
            $table->string('mother_enfirstname',191)->nullable();
            $table->string('mother_enlastname',191)->nullable();
            $table->string('mother_dob',50)->nullable(true);
            $table->string('mother_nationality',50)->nullable(true);
            $table->string('mother_national',50)->nullable(true);
            $table->string('mother_nid',191)->nullable();
            $table->text('mother_pob')->nullable(true);
            $table->text('mother_address')->nullable(true);
            $table->integer('mother_address_province_id')->nullable(true)->default(0);
            $table->integer('mother_address_district_id')->nullable(true)->default(0);
            $table->integer('mother_address_commune_id')->nullable(true)->default(0);
            $table->integer('mother_address_village_id')->nullable(true)->default(0);
            $table->text('mother_profession')->nullable(true);
            $table->text('mother_picture')->nullable(true);
            $table->integer('mother_death')->nullable(true)->default(0);

            // Emergency
            $table->string('emergency_lastname',191)->nullable(true);
            $table->string('emergency_firstname',191)->nullable(true);
            $table->integer('emergency_gender')->default(0)->nullable(true);
            $table->string('emergency_relationship',191)->nullable(true);
            $table->string('emergency_profession',191)->nullable(true);
            $table->string('emergency_phone',191)->nullable(true);
            $table->string('emergency_email',191)->nullable(true);
            $table->text('emergency_address')->nullable(true);
            $table->integer('emergency_address_province_id')->nullable(true)->default(0);
            $table->integer('emergency_address_district_id')->nullable(true)->default(0);
            $table->integer('emergency_address_commune_id')->nullable(true)->default(0);
            $table->integer('emergency_address_village_id')->nullable(true)->default(0);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
