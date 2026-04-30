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
        Schema::create('meetings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('legal_draft_id')->nullable()->comment('The legal draft that the meeting is talking about.');
            $table->text('objective')->comment('The objective of the meaing or title');
            $table->date('date')->comment('The date of the meeting');
            $table->string('start', 50)->nullable()->comment('The start time of the meeting');
            $table->string('end', 50)->nullable()->comment('The expected end time of the meeting');
            $table->string('actual_start', 50)->nullable()->comment('The actual start time of the meeting');
            $table->string('actual_end', 50)->nullable()->comment('The actual expected end time of the meeting');
            $table->string('timestamp_start')->nullable()->comment('The start time of the meeting');
            $table->string('timestamp_end', 50)->nullable()->comment('The expected end time of the meeting');
            $table->string('timestamp_actual_start', 50)->nullable()->comment('The actual start time of the meeting');
            $table->string('timestamp_actual_end', 50)->nullable()->comment('The actual expected end time of the meeting');
            $table->integer('status')->default(1)->comment('1 => New, 2 => Meeting, 4 => Continue , 8 => Change , 16 => Delayed, 32 => Finished');
            $table->text('minister_preeng')->nullable()->comment('The SEICHDEY_PREENG_FROM_MINISTER documents of the meeting');
            $table->text('attendant_pdf')->nullable()->comment('The ATTENDANT FILE PDF documents of the meeting');            
            $table->text('seichdey_preeng')->nullable()->comment('The SEICHDEY_PREENG documents of the meeting');
            $table->text('tech_reports')->nullable()->comment('The technical report(s) at the end of the meeting.');
            $table->text('reports')->nullable()->comment('The report(s) at the end of the meeting.');
            $table->text('other_documents')->nullable()->comment('Another documentst that is/are support the meeting');
            $table->text('contact_info')->nullable()->comment('The contact information about the meeting.');
            $table->integer('pid')->nullable()->comment('The the previous meeting of this meeting');
            $table->integer('type_id')->default(0)->comment('The meeting type');
            $table->text('images')->nullable()->comment('collection of the image as array');
            $table->text('summary')->text()->comment('The summary of the meeting');
            $table->integer('active')->default(0);
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
        Schema::dropIfExists('meetings');
    }
};
