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
        Schema::create('meeting_attendants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('meeting_member_id')->comment('The id of the meeting member');
            $table->integer('people_id')->comment('The person who come to join the meeting');
            $table->text('remark')->nullable()->comment('the remark');
            $table->string('checktime', 50)->nullable()->comment('the checktime that the member has joined the meeting.');
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
        Schema::dropIfExists('meeting_attendants');
    }
};
