<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loa_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attendance_record_id')->unsigned();
            $table->date('date_filed');
            $table->enum('type', ['regular', 'sick']);
            $table->integer('num_work_days');
            $table->string('classification');
            $table->text('reason')->nullable();
            $table->string('supervisor_remarks')->nullable();
            $table->string('admin_remarks')->nullable();
            $table->boolean('is_approved_supervisor')->default(false);
            $table->boolean('is_approved_admin')->default(false);
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
        Schema::dropIfExists('loa_forms');
    }
}
