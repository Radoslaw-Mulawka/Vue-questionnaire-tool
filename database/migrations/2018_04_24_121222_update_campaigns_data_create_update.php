<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCampaignsDataCreateUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `date_created` `created_at` TIMESTAMP NULL;");
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `date_modified` `updated_at` TIMESTAMP NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `created_at` `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
        DB::statement("ALTER TABLE `campaigns` CHANGE COLUMN `updated_at` `date_modified` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
    }
}
