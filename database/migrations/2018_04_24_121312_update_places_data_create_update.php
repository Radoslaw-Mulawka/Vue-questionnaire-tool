<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdatePlacesDataCreateUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `places` CHANGE COLUMN `date_created` `created_at` TIMESTAMP NULL;");

        Schema::table('places', function ($table) {
            $table->timestamp('updated_at')->nullable(true);
        });

        DB::statement("UPDATE places SET updated_at = created_at ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `places` CHANGE COLUMN `created_at` `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
        Schema::table('places', function ($table) {
            $table->dropColumn('updated_at');
        });
    }
}
