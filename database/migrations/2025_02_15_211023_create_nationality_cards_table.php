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
        Schema::create('nationality_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('people_id')->nullable(true)->comment('The nationality identification card');
            $table->string('number',191)->nullable(false);
            $table->string('height',191)->nullable(true);
            $table->string('start',191)->nullable(false);
            $table->string('end',191)->nullable(false);
            $table->text('desp')->nullable(true);
            $table->string('serial',191)->nullable(true);
            $table->text('pdf')->nullable(true);
            $table->text('distinguishing_mark')->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->integer('default')->default(0)->comment('The default card to use');
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
        Schema::dropIfExists('nationality_cards');
    }
};
