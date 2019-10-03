<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('answers')) {
            Schema::create('answers', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_campaign')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_place')
                    ->length(10)
                    ->nullable(false);
                $table->integer('id_question')
                    ->length(10)
                    ->nullable(true);
                $table->integer('id_option')
                    ->length(10)
                    ->nullable(true);
                $table->text('a_value');
                $table->string('guest_phid', 100)->nullable(false);
                $table->string('guest_ip', 15)->nullable(false);
                $table->dateTime('date_created')->nullable(false);
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
        Schema::dropIfExists('answers');
    }
}
