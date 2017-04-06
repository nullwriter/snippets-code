<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToHistoryRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_records', function(Blueprint $table)
        {
            $table->foreign('employee_id', 'employee_history_ibfk_1')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_records', function(Blueprint $table)
        {
            $table->dropForeign('employee_history_ibfk_1');
        });
    }
}
