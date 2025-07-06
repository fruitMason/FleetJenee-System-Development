<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->index();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable()->index();
            $table->string('email')->unique()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile', 20)->nullable()->index();
            $table->string('role', 100)->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->string('status', 100)->nullable()->index();
            $table->string('auth_code')->nullable()->index();
            $table->string('pic')->nullable();
            $table->string('gender', 50)->nullable()->index();
            $table->string('type', 100)->nullable()->index();
            $table->string('driver_type')->nullable()->index();
            $table->string('license_class', 100)->nullable()->index();
            $table->string('license_number', 100)->nullable()->index();
            $table->string('license_expiry', 100)->nullable()->index();
            $table->unsignedBigInteger('vendor_id')->nullable()->index();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
