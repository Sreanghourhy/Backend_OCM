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
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable(false)->comment('unique key of the industry');
            $table->string('name',191)->nullable(false)->comment('the name of the industry');
            $table->text('tags')->nullable(false)->comment('Tags of the industries');
            $table->text('desp')->nullable(true)->comment('The description of the industry');
            $table->text('tpid')->nullable()->comment('The id of the parent which identify the whole type of them.');
            $table->integer('pid')->nullable()->comment('Parent id of this organization');
            $table->text('cids')->nullable()->comment('Children IDs will be store here.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('industries');
    }
};
