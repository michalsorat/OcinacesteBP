<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFk4problemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem', function (Blueprint $table) {

            $table->bigInteger('priorita_id')->unsigned();
            $table->bigInteger('cesta_id')->unsigned();
            $table->bigInteger('pouzivatel_id')->unsigned();
            $table->bigInteger('stav_riesenia_problemu_id')->unsigned();
            $table->bigInteger('kategoria_problemu_id')->unsigned();
            $table->bigInteger('stav_problemu_id')->unsigned();
            $table->bigInteger('popis_stavu_riesenia_problemu_id')->unsigned();
            $table->bigInteger('priradeny_zamestnanec_id')->unsigned();
            $table->bigInteger('priradene_vozidlo_id')->unsigned();



            $table->foreign('priorita_id')
                ->references('priorita_id')->on('priorita')
                ->onDelete('cascade');

            $table->foreign('cesta_id')
                ->references('cesta_id')->on('cesta')
                ->onDelete('cascade');

            $table->foreign('pouzivatel_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('stav_riesenia_problemu_id')
                ->references('stav_riesenia_problemu_id')->on('stav_riesenia_problemu')
                ->onDelete('cascade');

            $table->foreign('kategoria_problemu_id')
                ->references('kategoria_problemu_id')->on('kategoria_problemu')
                ->onDelete('cascade');

            $table->foreign('stav_problemu_id')
                ->references('stav_problemu_id')->on('stav_problemu')
                ->onDelete('cascade');

            $table->foreign('popis_stavu_riesenia_problemu_id')
                ->references('popis_stavu_riesenia_problemu_id')->on('popis_stavu_riesenia_problemu')
                ->onDelete('cascade');

            $table->foreign('priradeny_zamestnanec_id')
                ->references('priradeny_zamestnanec_id')->on('priradeny_zamestnanec')
                ->onDelete('cascade');

            $table->foreign('priradene_vozidlo_id')
                ->references('priradene_vozidlo_id')->on('priradene_vozidlo')
                ->onDelete('cascade');

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
        Schema::dropIfExists('fk4problem');
    }
}
