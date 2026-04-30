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
        Schema::create('people_languages', function (Blueprint $table) {
            $table->id();
            $table->integer('people_id')->nullable(false);
            $table->string('name',191)->nullable(false);
            $table->string('reading',10)->nullable(false);
            $table->string('speaking',10)->nullable(false);
            $table->string('writing',10)->nullable(false);
            $table->string('pdf',191)->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_languages');
    }
};
