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
        Schema::create('officer_medal_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('officer_id')->nullable(false);
            $table->string('fid',50)->nullable(false);
            $table->string('date',50)->nullable(false);
            $table->string('organization',191)->nullable(true);
            $table->string( 'position' , 191 )->nullable(true);
            $table->string('type',191)->nullable(false);
            $table->text('desp')->nullable(true);
            $table->string( 'pdf' , 191 )->nullable(true);
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
        Schema::dropIfExists('officer_medal_histories');
    }
};
