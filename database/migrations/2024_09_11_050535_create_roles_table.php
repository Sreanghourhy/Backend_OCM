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
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('key_name', 191);
            // $table->string('sub_role', 191);
            $table->string('sub_role', 191)->nullable();
            // $table->integer('sub_role_index')->default(0);
            $table->integer('sub_role_index')->nullable();
            $table->string('khname', 191);
            $table->string('enname', 191);
            $table->string('guard_name', 191);
            $table->string('tag', 191)->comment('The tag / group of the role.');
            $table->text('desp')->nullable(true);
            $table->string('code', 191)->nullable(true);
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
        Schema::dropIfExists('roles');
    }
};
