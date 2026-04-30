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
        Schema::create('third_party_authentications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_id', 50)->comment('The id of the social network.');
            $table->string('name', 191)->comment('The name of the social network.');
            $table->string('app_token', 191)->comment('The token of the social network.');
            $table->integer('user_id')->comment('The id of the owner of the social network.');
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
        Schema::dropIfExists('third_party_authentications');
    }
};
