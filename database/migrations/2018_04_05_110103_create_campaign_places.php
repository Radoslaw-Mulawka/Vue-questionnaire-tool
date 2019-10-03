<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignPlaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('campaign_places')) {
            Schema::create('campaign_places', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_place')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_campaign')
                    ->length(10)
                    ->nullable(false);
                $table->string('shortcode', 5)->nullable(false);
                $table->string('label_name', 100)->nullable(false);
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
        Schema::dropIfExists('campaign_places');
    }
}
