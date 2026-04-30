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
        Schema::create('folders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name', 191)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 191)->nullable();
            $table->text('pdf')->nullable();
            $table->integer('pid')->comment('The parent id of the folder');
            $table->integer('active')->comment('The status of the folder to be used or disabled.');
            $table->integer('accessibility')->nullable()->default(0)->comment('The accessibility of the file to the outside. 0 -> disable for all, 1 -> private for owner or shared user, 2 -> Public for the whole system, 4 -> Global access for the world.');
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
        Schema::dropIfExists('folders');
    }
};
