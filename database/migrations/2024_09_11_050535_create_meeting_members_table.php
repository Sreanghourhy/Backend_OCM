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
        Schema::create('meeting_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('meeting_id')->comment('The id of meeting table');
            $table->integer('people_id')->nullable(true)->comment('The id of people table');
            $table->integer('officer_id')->nullable(true)->comment('The id of officer table');
            $table->string('role', 191)->nullable()->default('member')->comment('The role name of the member to join the meeting.');
            $table->string('group', 191)->nullable()->default('audient')->comment('The group name of the member to join the meeting.');
            $table->text('remark')->nullable()->comment('The remark');
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
        Schema::dropIfExists('meeting_members');
    }
};
