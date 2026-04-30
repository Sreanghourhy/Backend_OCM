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
        Schema::create('officer_rank_by_workings', function (Blueprint $table) {
            $table->id();
            $table->integer('officer_id')->nullable(false);
            $table->integer('previous_rank_id')->nullable(false);
            $table->integer('rank_id')->nullable(false);
            $table->string('date',50)->nullable(false);
            $table->string('changing_type',50)->nullable(true)->comment('Type of changing rank.');

            // For changing ranks with certificate
            $table->string('organization', 191 )->nullable(true)->comment('The name of the top organization');
            $table->string('sub_organization', 191 )->nullable( true )->comment('organization under organization');
            $table->string('sub_sub_organization' , 191 )->nullable(true)->comment('Organization under sub_organization');
            
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
