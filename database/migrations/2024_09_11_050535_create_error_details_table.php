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
        Schema::create('error_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->comment('The authenticated user that face the error.');
            $table->string('app_name', 191)->nullable()->comment('The name of app.');
            $table->string('module_name', 191)->nullable()->comment('The module that error occurred in the client side app.');
            $table->string('page_name', 191)->nullable()->comment('The page that error occurred in the client side app.');
            $table->text('user_agent')->nullable()->comment('Browser');
            $table->text('desp')->nullable()->comment('The details information of the error.');
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
        Schema::dropIfExists('error_details');
    }
};
