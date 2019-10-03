<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCampaignsDateFromAndDateToToTimestapm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `date_from` `date_from` TIMESTAMP NULL DEFAULT NULL ;");
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `date_to` `date_to` TIMESTAMP NULL DEFAULT NULL ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `campaigns` CHANGE `date_from` `date_from` DATE NULL DEFAULT NULL ;");
        DB::statement("ALTER TABLE `campaigns` CHANGE `date_to` `date_to` DATE NULL DEFAULT NULL ;");
    }
}
