<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUsersColumnsName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('firstName', 'first_name');
            $table->renameColumn('lastName', 'last_name');
            $table->renameColumn('verificationHash', 'verification_hash');
            $table->renameColumn('isVerified', 'is_verified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->renameColumn('first_name', 'firstName');
            $table->renameColumn('last_name', 'lastName');
            $table->renameColumn('verification_hash', 'verificationHash');
            $table->renameColumn('is_verified', 'isVerified');
        });
    }
}
