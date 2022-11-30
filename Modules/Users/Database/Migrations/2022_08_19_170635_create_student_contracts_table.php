<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->morphs('contractable');

            $table->foreign("student_id")->references("id")
                  ->on("users")
                  ->cascadeOnDelete();

            $table->timestamps();
           // $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_contracts');
    }
}
