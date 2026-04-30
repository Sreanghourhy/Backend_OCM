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
        Schema::create('attendants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0)->comment('The ID of the user');
            $table->date('date')->comment('The date of the attendant');
            $table->integer('late_or_early')->default(0)->comment('Total minutes that overtime (+) or owned (-).');
            $table->integer('worked_time')->default(0)->comment('Total minutes that has worded the whole day');
            $table->integer('duration')->default(0)->comment('Total minutes that need to complete for the whole day');
            $table->string('attendant_type' , 5 )->default("A")->comment('The attendant type');
            $table->timestamps();
            $table->softDeletes();
            $table->text('notes')->nullable(true);
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
        Schema::dropIfExists('attendants');
    }
};
