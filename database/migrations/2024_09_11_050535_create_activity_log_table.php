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
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('content_type', 72)->nullable();
            $table->integer('content_id')->nullable();
            $table->string('action', 32)->nullable();
            $table->text('description')->nullable();
            $table->text('details')->nullable();
            $table->text('data')->nullable();
            $table->boolean('language_key')->default(false);
            $table->boolean('public')->default(false);
            $table->boolean('developer')->default(false);
            $table->string('ip_address', 64);
            $table->string('user_agent', 191);
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
        Schema::dropIfExists('activity_log');
    }
};
