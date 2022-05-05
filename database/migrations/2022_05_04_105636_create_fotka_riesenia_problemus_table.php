<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotkaRieseniaProblemusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fotka_riesenia_problemu', function (Blueprint $table) {
            $table->bigIncrements('fotka_riesenia_problemu_id');
            $table->text('nazov_suboru');
            $table->timestamps();
        });

        Schema::table('fotka_riesenia_problemu', function (Blueprint $table) {

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
        Schema::dropIfExists('fotka_riesenia_problemu');
    }
}
