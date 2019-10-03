<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameOptionsColumnsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function(Blueprint $table) {
            $table->renameColumn('option_label', 'label');
            $table->renameColumn('o_order', 'order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function(Blueprint $table) {
            $table->renameColumn('label', 'option_label');
            $table->renameColumn('order', 'o_order');
        });
    }
}
