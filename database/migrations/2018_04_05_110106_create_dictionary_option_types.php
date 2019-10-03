<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionaryOptionTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dictionary_option_types')) {
            Schema::create('dictionary_option_types', function (Blueprint $table) {
                $table->increments('id');
                $table->text('description')->nullable(false);
                $table->text('type_explain')->nullable(false);
                $table->string('name', 100)->nullable(false);
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
        Schema::dropIfExists('dictionary_option_types');
    }
}
