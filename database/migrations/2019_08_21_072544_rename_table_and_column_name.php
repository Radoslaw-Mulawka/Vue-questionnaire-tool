<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTableAndColumnName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('campaign_questions', 'questions');
        Schema::rename('campaign_question_options', 'options');
        Schema::rename('dictionary_option_types', 'option_types');

        Schema::table('answers', function(Blueprint $table) {
            $table->renameColumn('id_campaign', 'campaigns_id');
            $table->renameColumn('id_place', 'place_id');
            $table->renameColumn('id_question', 'questions_id');
            $table->renameColumn('id_option', 'options_id');
        });


        Schema::table('campaigns', function(Blueprint $table) {
            $table->renameColumn('id_user', 'users_id');
        });

        Schema::table('campaign_places', function(Blueprint $table) {
            $table->renameColumn('id_user', 'users_id');
            $table->renameColumn('id_place', 'places_id');
            $table->renameColumn('id_campaign', 'campaigns_id');
        });

        Schema::table('questions', function(Blueprint $table) {
            $table->renameColumn('id_user', 'users_id');
            $table->renameColumn('id_campaign', 'campaigns_id');
        });

        Schema::table('options', function(Blueprint $table) {
            $table->renameColumn('id_user', 'users_id');
            $table->renameColumn('id_campaign', 'campaigns_id');
            $table->renameColumn('id_question', 'questions_id');
        });

        Schema::table('places', function(Blueprint $table) {
            $table->renameColumn('id_user', 'users_id');
        });

        Schema::table('unique_views', function(Blueprint $table) {
            $table->renameColumn('id_campaign', 'campaigns_id');
            $table->renameColumn('id_place', 'place_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('answers', function(Blueprint $table) {
            $table->renameColumn('campaigns_id', 'id_campaign');
            $table->renameColumn('place_id', 'id_place');
            $table->renameColumn('questions_id', 'id_question');
            $table->renameColumn('options_id', 'id_option');
        });


        Schema::table('campaigns', function(Blueprint $table) {
            $table->renameColumn('users_id', 'id_user');
        });

        Schema::table('campaign_places', function(Blueprint $table) {
            $table->renameColumn('users_id', 'id_user');
            $table->renameColumn('places_id', 'id_place');
            $table->renameColumn('campaigns_id', 'id_campaign');
        });

        Schema::table('questions', function(Blueprint $table) {
            $table->renameColumn('users_id', 'id_user');
            $table->renameColumn('campaigns_id', 'id_campaign');
        });

        Schema::table('options', function(Blueprint $table) {
            $table->renameColumn('users_id', 'id_user');
            $table->renameColumn('campaigns_id', 'id_campaign');
            $table->renameColumn('questions_id', 'id_question');
        });

        Schema::table('places', function(Blueprint $table) {
            $table->renameColumn('users_id', 'id_user');
        });

        Schema::table('unique_views', function(Blueprint $table) {
            $table->renameColumn('campaigns_id', 'id_campaign');
            $table->renameColumn('place_id', 'id_place');
        });

        Schema::rename('questions', 'campaign_questions');
        Schema::rename('options', 'campaign_question_options');
        Schema::rename('option_types', 'dictionary_option_types');
    }
}
