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
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->comment('The file name');
            $table->text('meta')->nullable()->comment('The file metadata');
            $table->text('tags')->nullable()->comment('The tags of file');
            $table->string('model', 191)->nullable()->comment('The model path used to differentiate file');
            $table->integer('model_id')->default(0)->comment('The id of the table that under the model');
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
        Schema::dropIfExists('files');
    }
};
