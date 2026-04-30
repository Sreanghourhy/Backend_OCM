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
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->text('objective')->comment('name of the task');
            $table->float('minutes', null, 0)->comment('Duration that the task should be done, measure as minutes');
            $table->timestamp('start')->nullable()->comment('start date of task');
            $table->timestamp('end')->nullable()->comment('end date of the task');
            $table->integer('active')->default(0)->comment('status of using record');
            $table->integer('status')->default(0)->comment('0: New, 1: Start, 2: End, 4: Pending, Cancel: 8');
            $table->text('pdfs')->nullable()->comment('reference files');
            $table->integer('pid')->default(0)->comment('id of parent task');
            $table->integer('tpid')->default(0)->comment('id of parent at the top level of task');
            $table->string('parent_level', 191)->default('0')->comment('the series of parent ids seperated by colon');
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
        Schema::dropIfExists('tasks');
    }
};
