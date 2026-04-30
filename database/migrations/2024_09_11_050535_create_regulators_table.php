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
        Schema::create('regulators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fid', 191)->nullable();
            $table->text('title')->nullable();
            $table->text('objective')->nullable();
            $table->string('pdf', 191)->nullable();
            $table->string('year', 50);
            $table->integer('document_type')->nullable()->default(0);
            $table->integer('publish')->nullable()->default(0);
            $table->integer('active')->nullable()->default(0);
            $table->integer('approved_by')->nullable()->default(0);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->integer('accessibility')->nullable()->default(0)->comment('The accessibility of the file to the outside. 0 -> disable for all, 1 -> private for owner or shared user, 2 -> Public for the whole system, 4 -> Global access for the world.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulators');
    }
};
