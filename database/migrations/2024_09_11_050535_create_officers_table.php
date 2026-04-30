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
        Schema::create('officers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 191)->nullable()->comment('The code of the officer');
            $table->string('date', 191)->nullable(true)->comment('The date that the officer got promoted');
            $table->string('official_date', 191)->nullable(true)->comment('The date that the officer got promoted');
            $table->string('unofficial_date', 191)->nullable(true)->comment('The date that the officer become unofficial officer');
            $table->string('public_key', 191)->default('1e95926c06441aa6f8cfc1075889d454')->comment('The public of the officer, use when accessing from the outside of the system.');
            $table->integer('user_id')->nullable()->default(0);
            $table->integer('people_id');
            $table->string('email', 191)->nullable();
            $table->string('phone', 191)->nullable();
            $table->integer('countesy_id')->nullable()->default(0);
            $table->string('salary_rank',50)->nullable(true);
            $table->string('officer_type',191)->nullable(true)->comment('krobkhan of the officer');
            $table->string('additional_officer_type',191)->nullable(true)->comment('this field use to identify others condition of officer_type');

            $table->string('career_status', 50)->nullable(true)->default('active')->comment('Is the officer is active or deactivate');
            $table->date('career_start_date')->nullable(true)->comment('The start date of the office_jobs');
            $table->date('career_end_date')->nullable(true)->comment('The start end of the office_jobs');

            // Optional
            $table->integer('organization_id')->default(0)->comment('The current organization of the officer ');
            $table->integer('position_id')->default(0)->comment('The current position of the officer');
            $table->integer('rank_id')->nullable(true)->default(0)->comment('The current rank or level of the officer');
            $table->integer('leader')->default(0);
            $table->string('image', 191)->nullable();
            $table->text('pdf', 191)->nullable();
            $table->string('passport', 191)->nullable();

            // Immergency Contract
            $table->string('emergency_firstname',191)->nullable(true);
            $table->string('emergency_lastname',191)->nullable(true);
            $table->string('emergency_enfirstname',191)->nullable(true);
            $table->string('emergency_enlastname',191)->nullable(true);
            $table->string('emergency_gender',20)->nullable(true);
            $table->string('emergency_phone',50)->nullable(true);
            $table->string('emergency_email',191)->nullable(true);
            $table->text('emergency_profession')->nullable(true);
            $table->text('emergency_address')->nullable(true);
            $table->text('emergency_relationship')->nullable(true);

            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officers');
    }
};
