<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officer_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_structure_position_id')->nullable(false);
            $table->integer('unofficial_position_id')->nullable(true)->default(0)->comment('This field is used to assign another position that is not official it related to positions table');
            $table->integer('officer_id')->nullable(false);
            $table->integer('countesy_id')->nullable(false);
            $table->boolean('is_primary')->default(false)->comment('Is this job is the primary job of the officer_id');
            $table->integer('priority')->default(0)->comment('Higher = more important');
            $table->string('context_type',50)->default(false)->comment('default, budget, command , ceremonial');
            $table->string('assignment_type',50)->default('primary')->comment('-- Types: primary, secondary, temporary, acting, honorary');
            $table->string('email',191)->nullable(true);
            $table->string('start',50)->nullable(false);
            $table->string('end',50)->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['officer_id'], 'idx_officer_primary_active')
                ->where('is_primary', true)
                ->whereNull('end_date');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('officer_jobs', function (Blueprint $table) {
            $table->dropUnique('idx_officer_primary_active');
        });
        Schema::dropIfExists('officer_jobs');
    }
};
