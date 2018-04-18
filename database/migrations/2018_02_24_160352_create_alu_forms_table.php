<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAluFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alu_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('alu_id')->unsigned();
            $table->date('date_filed');
            $table->text('reason');
            $table->enum('recommendation', ['EO', 'EW', 'UO', 'UW'])->nullable();          
            $table->string('supervisor_remarks')->nullable();
            $table->string('admin_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('alu_id')->references('id')->on('alus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alu_forms');
    }
}
