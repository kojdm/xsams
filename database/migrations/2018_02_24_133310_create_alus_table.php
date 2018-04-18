<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attendance_record_id')->unsigned();
            $table->enum('type', ['A', 'L', 'U', 'NT']);
            $table->date('date');
            $table->time('time')->nullable();
            $table->date('date_alu_due');
            $table->boolean('is_alu_filed')->default(false);
            $table->boolean('is_alu_approved')->default(false);
            $table->boolean('is_confirmed')->default(true);
            $table->boolean('is_expired')->default(false);          
            $table->enum('decision', ['EO', 'EW', 'UO', 'UW', 'Edit'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('attendance_record_id')->references('id')->on('attendance_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alus');
    }
}
