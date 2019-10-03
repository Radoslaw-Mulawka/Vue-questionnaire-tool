<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignQuestionOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('campaign_question_options')) {
            Schema::create('campaign_question_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_campaign')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_question')
                    ->length(10)
                    ->nullable(false);
                $table->string('option_label', 1000)->nullable(false);
                $table->integer('o_order')
                    ->length(10)
                    ->nullable(false);
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
        Schema::dropIfExists('campaign_question_options');
    }
}
