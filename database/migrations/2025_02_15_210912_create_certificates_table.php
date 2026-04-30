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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('field_name',191)->nullable(false);
            $table->string('start',191)->nullable(false);
            $table->string('end',191)->nullable(false);
            $table->string('location',191)->nullable(false);
            $table->string('place_name',191)->nullable(true);
            $table->string('certificate_note',191)->nullable(true)->comment('This field is used to note the certificate name incase it does not exist in the certificate_group');
            $table->integer('certificate_group_id')->nullable(true);
            $table->integer('people_id')->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->text('pdf')->nullable(true);
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
        Schema::dropIfExists('certificates');
    }
};