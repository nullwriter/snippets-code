<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table)
        {
            $table->foreign('bank_id', 'bank_ibfk_1')->references('id')->on('banks');
            $table->foreign('job_role_id', 'job_role_ibfk_2')->references('id')->on('job_roles');
            $table->foreign('branch_store_id', 'branch_store_ibfk_3')->references('id')->on('branch_stores');
            $table->foreign('type_egress_id', 'type_egress_ibfk_4')->references('id')->on('type_egress');
            $table->foreign('contract_id', 'contract_ibfk_5')->references('id')->on('contracts');
            $table->foreign('status_id', 'status_ibfk_6')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function(Blueprint $table)
        {
            $table->dropForeign('bank_ibfk_1');
            $table->dropForeign('job_role_ibfk_2');
            $table->dropForeign('branch_store_ibfk_3');
            $table->dropForeign('type_egress_ibfk_4');
            $table->dropForeign('contract_ibfk_5');
            $table->dropForeign('status_ibfk_6');
        });
    }
}
