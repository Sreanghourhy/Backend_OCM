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
        Schema::create('legal_drafts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('regulator_id')->nullable()->comment('The regulator that publish by this legal draft');
            $table->string('title', 191)->nullable()->comment('The title of the draft');
            $table->text('objective')->comment('The objective of the draft');
            $table->text('files')->nullable()->comment('The links of the files');
            $table->text('content')->comment('The whole content of the draft');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->integer('status')->default(0)->comment('The status of the legal draft, 0 : NEW , 1 : PROGRESSING , 2 : FINISHED');
            $table->integer('pid')->default(0)->comment('The id of the parent legal draft.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_drafts');
    }
};
