<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'vehicles',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('json_id')->nullable();
                $table->string('number_plate');
                $table->text('column_id');
                $table->date('datums')->nullable();
                $table->date('s_datums')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
