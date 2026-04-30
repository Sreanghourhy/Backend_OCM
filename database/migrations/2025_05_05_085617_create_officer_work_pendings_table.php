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
        Schema::create('officer_work_pendings', function (Blueprint $table) {
            $table->id();
            $table->integer('officer_id')->nullable(false);
            $table->string( 'start' , 50 )->nullable(false);
            $table->string( 'end' , 50 )->nullable(false);
            $table->string( 'organization' , 191 )->nullable(false);
            $table->string( 'position' , 191 )->nullable(false);
            $table->string( 'total_months' , 50 )->nullable(false);
            $table->string( 'pdf' , 191 )->nullable(true);
            $table->integer('type')->default(0)->comment('0 : change krokhan , 1 : free with no salary');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
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
        Schema::dropIfExists('officer_work_pendings');
    }
};
