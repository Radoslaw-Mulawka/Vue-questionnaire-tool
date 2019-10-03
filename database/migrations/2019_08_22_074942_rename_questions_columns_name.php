<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameQuestionsColumnsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function(Blueprint $table) {
            $table->renameColumn('q_order', 'order');
            $table->renameColumn('q_require', 'require');
            $table->renameColumn('F_question', 'question');
            $table->renameColumn('F_label_other', 'label_other');
            $table->renameColumn('F_extended_desc', 'extended_desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function(Blueprint $table) {
            $table->renameColumn('order', 'q_order');
            $table->renameColumn('require', 'q_require');
            $table->renameColumn('question', 'F_question');
            $table->renameColumn('label_other', 'F_label_other');
            $table->renameColumn('extended_desc', 'F_extended_desc');
        });
    }
}
