<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniqueViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('unique_views')) {
            Schema::create('unique_views', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_campaign')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_place')
                    ->length(10)
                    ->nullable(false);
                $table->string('guest_phid', 100)->nullable(false);
                $table->string('guest_ip', 15)->nullable(false);
                $table->dateTime('date_created')->nullable(false);

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
        Schema::dropIfExists('unique_views');
    }
}
