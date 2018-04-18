<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_counters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attendance_record_id');
            $table->year('year_start');
            $table->year('year_end');
            $table->integer('sl_count')->default(15);
            $table->integer('vlp_count')->default(12);
            $table->integer('spl_count')->default(7);
            $table->integer('gl_count')->default(60);
            $table->integer('vawcl_count')->default(10);
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
        Schema::dropIfExists('leave_counters');
    }
}
