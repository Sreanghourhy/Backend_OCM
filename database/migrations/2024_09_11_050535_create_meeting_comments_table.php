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
        Schema::create('meeting_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('meeting_id')->comment('The id of meeting table');
            $table->integer('people_id')->comment('The id of commentor table');
            $table->text('comment')->nullable()->comment('The comment of the meeting member');
            $table->text('pdfs')->nullable()->comment('The pdf file attached with the comment');
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_comments');
    }
};
