<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrigerUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::unprepared('UPDATE campaigns SET uuid = (SELECT uuid()); ');
      DB::unprepared('
        CREATE TRIGGER before_insert_campaigns
        BEFORE INSERT ON campaigns
        FOR EACH ROW
        BEGIN
          IF new.uuid IS NULL THEN
            SET new.uuid = uuid();
          END IF;
        END
      ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::unprepared('DROP TRIGGER IF EXISTS`before_insert_campaigns`');
    }
}
