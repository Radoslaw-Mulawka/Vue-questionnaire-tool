<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('campaigns')) {
            Schema::create('campaigns', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('id_user')
                    ->length(10)
                    ->nullable(false);
                $table->string('name', 1000)->nullable(false);
                $table->text('description')->nullable(true);
                $table->integer('status')
                    ->length(10)
                    ->nullable(false);
                $table->text('F_intro_text')
                    ->nullable(true)
                    ->default(null);
                $table->text('F_ending_text')
                    ->nullable(true)
                    ->default(null);
                $table->string('F_logo', 1000)
                    ->nullable(true)
                    ->default(null);
                $table->string('F_E_label_title', 1000)->nullable(false);
                $table->date('date_from')->nullable(false);
                $table->date('date_to')->nullable(true);
                $table->dateTime('date_created')->nullable(false);
                $table->dateTime('date_modified')->nullable(false);
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
        Schema::dropIfExists('campaigns');
    }
}
