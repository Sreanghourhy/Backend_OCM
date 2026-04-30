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
        Schema::create('regulator_types', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->text('name')->nullable();
            $table->text('desp')->nullable();
            $table->string('tpid', 191)->nullable();
            $table->integer('pid')->nullable();
            $table->text('cids')->nullable();
            $table->string('image', 191)->nullable();
            $table->integer('record_index')->nullable();
            $table->integer('active')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->string('code', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulator_types');
    }
};
