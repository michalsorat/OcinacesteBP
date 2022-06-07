<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemDetectedByAlgorithmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_detected_by_algorithms', function (Blueprint $table) {
            $table->bigIncrements('detected_by_algorithm_id');
            $table->text('algorithm');
            $table->bigInteger('problem_id')->unsigned();
            $table->foreign('problem_id')
                ->references('problem_id')->on('problem')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problem_detected_by_algoritms');
    }
}
