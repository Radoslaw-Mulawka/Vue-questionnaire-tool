<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePlacesDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `places` CHANGE COLUMN `date_created` `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `places` CHANGE COLUMN `date_created` `date_created` DATETIME NOT NULL ;");
    }
}
