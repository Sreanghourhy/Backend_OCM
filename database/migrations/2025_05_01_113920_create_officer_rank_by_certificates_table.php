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
        Schema::create('officer_rank_by_certificates', function (Blueprint $table) {
            $table->id();
            $table->integer('officer_id')->nullable(false);
            $table->integer('previous_rank_id')->nullable(false);
            $table->integer('rank_id')->nullable(false);
            $table->string('date',50)->nullable(false);

            // For changing ranks with certificate
            $table->string('organization', 191 )->nullable(true)->comment('The name of the top organization');
            $table->text('location' )->nullable( true )->comment('organization under organization');
            $table->text('certificate' )->nullable( true )->comment('organization under organization');
            
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
