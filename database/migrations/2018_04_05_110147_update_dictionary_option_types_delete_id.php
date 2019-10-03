<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDictionaryOptionTypesDeleteId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_questions', function ($table) {
            $table->string('option_type', 100)->change();
        });

        DB::statement("UPDATE campaign_questions q SET q.option_type = (SELECT d.name FROM dictionary_option_types d WHERE d.id = q.option_type) ;");

        Schema::table('dictionary_option_types', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->string('name', 100)->change();
            $table->primary('name');
        });

        DB::statement("ALTER TABLE `dictionary_option_types` CHANGE COLUMN `name` `name` VARCHAR(100) FIRST ;");

        Schema::table('campaign_questions', function ($table) {
            $table->string('option_type', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionary_option_types', function (Blueprint $table) {
            $table->dropPrimary();
        });

        DB::statement("ALTER TABLE `dictionary_option_types` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;");

        DB::statement("ALTER TABLE `dictionary_option_types` CHANGE COLUMN `name` `name` VARCHAR(100) AFTER `type_explain` ;");

        DB::statement("UPDATE campaign_questions q SET q.option_type = (SELECT d.id FROM dictionary_option_types d WHERE d.name = q.option_type) ;");

        Schema::table('campaign_questions', function ($table) {
            $table->integer('option_type')->change();
        });
    }
}
