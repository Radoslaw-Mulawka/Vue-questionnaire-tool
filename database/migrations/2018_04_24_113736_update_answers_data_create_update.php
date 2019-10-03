<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Api\Answers;

class UpdateAnswersDataCreateUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `answers` CHANGE COLUMN `date_created` `created_at` TIMESTAMP NULL;");

        Schema::table('answers', function ($table) {
            $table->timestamp('updated_at')->nullable(true);
        });

        if (Answers::count() > 0) {
            DB::statement("UPDATE answers SET updated_at = created_at ;");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `answers` CHANGE COLUMN `created_at` `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ;");
        Schema::table('answers', function ($table) {
            $table->dropColumn('updated_at');
        });
    }
}
