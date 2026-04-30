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
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 191)->comment('The unique number of the card');
            $table->string('number', 191)->comment('The unique number of the card');
            $table->integer('people_id')->comment('The id of the owner of the card');
            $table->integer('officer_id')->comment('The id of the owner of the card');
            $table->string('start',191)->nullable(true)->comment("start using card");
            $table->string('end',191)->nullable(true)->comment("end using card");
            $table->integer('active')->default(0)->comment("Disabled or active the card");
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
