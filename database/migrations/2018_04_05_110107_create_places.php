<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('places')) {
            Schema::create('places', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user')
                    ->length(10)
                    ->nullable(false);
                $table->string('name', 100)
                    ->nullable(false);
                $table->text('comment')
                    ->nullable(true);
                $table->dateTime('date_created')
                    ->nullable(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
