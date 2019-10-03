<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('campaign_questions')) {
            Schema::create('campaign_questions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_campaign')
                    ->length(10)
                    ->nullable(false);
                $table->integer('option_type')
                    ->length(10)
                    ->nullable(false);
                $table->integer('q_order')
                    ->length(10)
                    ->nullable(false);
                $table->integer('other')
                    ->length(10)
                    ->nullable(true);
                $table->string('F_question', 1000)
                    ->nullable(false);
                $table->string('F_label_other', 1000)
                    ->nullable(true);
                $table->text('F_extended_desc', 1000)
                    ->nullable(true);
                $table->integer('q_require')
                    ->length(10)
                    ->nullable(false);
                $table->dateTime('date_created')
                    ->nullable(false);
                $table->dateTime('date_modified')
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
        Schema::dropIfExists('campaign_questions');
    }
}
