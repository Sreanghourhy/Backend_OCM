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
        Schema::create('officer_ranks', function (Blueprint $table) {
            $table->id();
            $table->integer('officer_id')->nullable(false);
            $table->integer('rank_id')->nullable(false);
            $table->string('start',50)->nullable(false);
            $table->string('end',50)->nullable(false);
            $table->integer('countesy_id')->nullable(true);
            $table->integer('organization_structure_position_id')->nullable(true);
            $table->string('changing_type',50)->nullable(true)->comment('Type of changing rank.');

            // For changing ranks with certificate
            $table->string('education_center',191)->nullable(true)->comment('The name of the education center that officer get the certificate from.');
            $table->text('location')->nullable( true )->comment('The location or address of the education center');
            $table->string('certificate' , 191 )->nullable(true)->comment('The name and type of the certificate');
            
            $table->text('desp')->nullable(true);
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
        Schema::dropIfExists('officer_ranks');
    }
};
