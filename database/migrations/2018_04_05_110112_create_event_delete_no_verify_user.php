<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventDeleteNoVerifyUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE EVENT IF NOT EXISTS `DeleteNoVerifyUser` ON SCHEDULE EVERY 10 MINUTE STARTS \'2017-11-02 10:00:00.000000\' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `users` WHERE `created_at` < (NOW() - INTERVAL 1 DAY) AND `isVerified` = 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP EVENT IF EXISTS `DeleteNoVerifyUser`');
    }
}
