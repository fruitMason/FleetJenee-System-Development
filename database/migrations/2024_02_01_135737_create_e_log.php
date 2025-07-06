<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('car_id')->nullable()->index();
            $table->date('date_logged')->nullable()->index();
            $table->string('current_location')->nullable()->index();
            $table->string('destination')->nullable()->index();
            $table->string('description')->nullable()->index();
            $table->string('status', 100)->nullable()->index();
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
        Schema::dropIfExists('e_log');
    }
}
