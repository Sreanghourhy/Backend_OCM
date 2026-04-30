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
        Schema::create('position_structure', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_position_id')->default(0)->comment('The id of the organization that own the position structure');
            $table->string('desp', 191)->nullable()->comment('The description of the position structures');
            $table->integer('child_position_id')->comment('The ID of the position from Tags table');
            $table->integer('active')->default(0)->nullable(true);
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
        Schema::dropIfExists('position_structures');
    }
};
