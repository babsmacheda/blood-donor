<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBloodDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blood_donors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 24)->nullable();
            $table->string('last_name', 24)->nullable();
            $table->string('blood_type', 2);
            $table->string('latitude');
            $table->string('longitude');
            $table->boolean('available')->default(true);
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
        Schema::dropIfExists('blood_donors');
    }
}
