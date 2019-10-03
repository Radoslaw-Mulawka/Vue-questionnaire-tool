<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNewsletterDataCreateUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `newsletter` CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL;");
        DB::statement("ALTER TABLE `newsletter` CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `newsletter` CHANGE COLUMN `created_at` `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
        DB::statement("ALTER TABLE `newsletter` CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
    }
}
