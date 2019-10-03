<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCampaignsColumnsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function(Blueprint $table) {
            $table->renameColumn('F_intro_text', 'intro_text');
            $table->renameColumn('F_ending_text', 'ending_text');
            $table->renameColumn('F_logo', 'logo');
            $table->renameColumn('F_E_label_title', 'label_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function(Blueprint $table) {
            $table->renameColumn('intro_text', 'F_intro_text');
            $table->renameColumn('ending_text', 'F_ending_text');
            $table->renameColumn('logo', 'F_logo');
            $table->renameColumn('label_title', 'F_E_label_title');
        });
    }
}
