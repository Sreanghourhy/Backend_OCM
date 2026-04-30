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
        Schema::create('organization_structures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->nullable(false)->comment('id of this organization');
            $table->integer('pid')->default(0)->comment('Parent id of this organization');
            $table->text('name')->nullable(true)->comment('The name of the organization based on its structure.');
            $table->text('tpid')->nullable(true)->comment('The id of the parent which identify the whole type of them.');
            $table->text('cids')->nullable(true)->comment('The array of ids of the child nodes');
            $table->text('desc')->nullable(true);
            $table->string('image', 191)->nullable();
            $table->string('pdf', 191)->nullable();
            $table->integer('active')->default(0)->comment('The disabled and active mode');
            $table->integer('total_childs')->nullable()->default(0)->comment('total childs of this record base on its relationship field id and pid ');
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
        Schema::dropIfExists('organization_structures');
    }
};
