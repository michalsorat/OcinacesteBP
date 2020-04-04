<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotkaStavuRieseniaProblemuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fotka_stavu_riesenia_problemu', function (Blueprint $table) {
            $table->bigIncrements('fotka_stavu_riesenia_problemu_id');
            $table->text('cesta_k_suboru');
            $table->collation = 'utf8mb4_slovak_ci';
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fotka_stavu_riesenia_problemu');
    }
}
