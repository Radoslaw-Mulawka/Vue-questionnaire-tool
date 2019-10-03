<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsletter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('newsletter')) {
            Schema::create('newsletter', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email_newsletter', 100)->nullable(false);
                $table->tinyInteger('status')->nullable(false);
                $table->tinyInteger('consent')->nullable(true);
                $table->string('hash_unsubscribe', 100)->nullable(false);
                $table->string('hash_resubscribe', 100)->nullable(false);
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('newsletter');
    }
}
