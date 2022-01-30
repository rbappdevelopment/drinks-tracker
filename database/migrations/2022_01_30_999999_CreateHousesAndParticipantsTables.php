<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesAndParticipantsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house', function (Blueprint $table) {
            $table->bigIncrements('id');
        });

        Schema::create('houseInfo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('house_id')->unsigned();
            $table->foreign('house_id')->references('id')->on('house'); //foreign key constraint
            $table->string('name');
            $table->string('created_by_uid');
            $table->date('created_date');
        });  

        Schema::create('participant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('house_id')->unsigned();
            $table->foreign('house_id')->references('id')->on('house'); //foreign key constraint
            $table->string('display_name');
            $table->date('created_date');
            $table->foreignId('user_id')->nullable()->constrained('users'); //if not null -> user has registered an account and can be found in the users table
        });

        Schema::create('houseParticipantMap', function (Blueprint $table) {
            $table->unsignedBigInteger('house_id')->unsigned();
            $table->foreign('house_id')->references('id')->on('house'); //foreign key constraint
            $table->unsignedBigInteger('participant_id')->unsigned();
            $table->foreign('participant_id')->references('id')->on('participant'); //foreign key constraint
            $table->integer('drinks_count');
            $table->integer('is_admin')->default('0');
        });  
        
        Schema::create('mutaties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pid');
            $table->integer('amount');
            $table->integer('total_left');
            $table->foreignId('mutated_by_pid')->constrained('participant');
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
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
