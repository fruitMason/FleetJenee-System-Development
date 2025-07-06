<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsArchivedToRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
        });
    }

    public function down()
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
}
