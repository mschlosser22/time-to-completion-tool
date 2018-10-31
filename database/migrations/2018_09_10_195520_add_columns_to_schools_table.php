<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->integer('default_max_credits_standard');
            $table->integer('first_semester_max_credits');
            $table->integer('default_credits_standard');
            $table->integer('default_credits_accelerated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['default_max_credits_standard', 'first_semester_max_credits', 'default_credits_standard', 'default_credits_accelerated']);
        });
    }
}
