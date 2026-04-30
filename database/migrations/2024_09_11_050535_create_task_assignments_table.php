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
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('task_id')->comment('The id of the task to assign to the worker');
            $table->integer('assignor_id')->comment('The people (assignor) that assign the task to the assignee');
            $table->integer('assignee_id')->comment('The people (assignee) that will responsible for the task to be completed');
            $table->float('completion_percentage', null, 0)->default(0)->comment('The percentage of the complation of the task');
            $table->integer('started_by')->default(0)->comment('The id of the worker that start the task');
            $table->integer('completed_by')->default(0)->comment('The id of the worker that finished the task');
            $table->string('start', 50)->nullable()->comment('The date that the task has been started');
            $table->string('end', 50)->nullable()->comment('The date that the task has been finished');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->comment('The id of the people that assign the task to others');
            $table->integer('updated_by')->comment('The id of the people that update the task assignment');
            $table->integer('deleted_by')->nullable()->comment('The id of the people that delete the task assignment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
