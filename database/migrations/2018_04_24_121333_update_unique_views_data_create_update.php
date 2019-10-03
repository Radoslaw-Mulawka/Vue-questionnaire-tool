<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUniqueViewsDataCreateUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `unique_views` CHANGE COLUMN `date_created` `created_at` TIMESTAMP NULL;");

        Schema::table('unique_views', function ($table) {
            $table->timestamp('updated_at')->nullable(true);
        });

        DB::statement("UPDATE unique_views SET updated_at = created_at ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `unique_views` CHANGE COLUMN `created_at` `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
        Schema::table('unique_views', function ($table) {
            $table->dropColumn('updated_at');
        });
    }
}
