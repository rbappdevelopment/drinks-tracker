<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBierstandAndMutatiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Bierstand', function (Blueprint $table) {
            $table->id();
            $table->string('Heer');
            $table->integer('Bier');
            $table->integer('TotaalOnzichtbaar');
            $table->timestamps();
        });

        Schema::create('Mutaties', function (Blueprint $table) {
            $table->id();
            $table->integer('HeerId');
            $table->integer('AantalBier');
            $table->integer('TotaalBierNaMutatie');
            $table->integer('GemuteerdDoorHeer');
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
        Schema::dropIfExists('Bierstand');
        Schema::dropIfExists('Mutaties');
    }
}
