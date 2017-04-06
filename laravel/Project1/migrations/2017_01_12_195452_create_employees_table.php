<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->text('firstname')->nullable();
            $table->text('lastname')->nullable();
            $table->string('cedula', 100)->default(null);
            $table->string('code_employee', 100)->default(null);
            $table->string('phone')->nullable()->default(null);
            $table->text('address')->nullable()->default(null);
            $table->text('bank_account')->nullable()->default(null);
            $table->string('sex', 5)->nullable()->default(null);
            $table->date('birthdate')->nullable()->default(null);
            $table->date('start_date')->nullable()->default(null);
            $table->date('end_date')->nullable()->default(null);
            $table->integer('bank_id')->nullable()->default(null)->unsigned();
            $table->integer('job_role_id')->nullable()->default(null)->unsigned();
            $table->integer('branch_store_id')->nullable()->default(null)->unsigned();
            $table->integer('type_egress_id')->nullable()->default(null)->unsigned();
            $table->integer('contract_id')->nullable()->default(null)->unsigned();
            $table->integer('status_id')->nullable()->default(null)->unsigned();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('confirmation_code')->nullable();
            $table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);
            $table->integer('reset_pass')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
