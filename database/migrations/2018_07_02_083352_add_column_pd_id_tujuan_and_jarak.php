<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPdIdTujuanAndJarak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('code_detail', function (Blueprint $table) {
            $table->unsignedInteger('pd_id_destination')->nullable();
            $table->float('distance', 8, 2)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('code_detail', function (Blueprint $table) {
            $table->dropColumn('pd_id_destination');
            $table->dropColumn('distance');
        });
    }
}
