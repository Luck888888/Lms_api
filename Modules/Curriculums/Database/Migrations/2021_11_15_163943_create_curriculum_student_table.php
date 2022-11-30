<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurriculumStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("curriculum_id");
            $table->unsignedBigInteger("user_id");

            $table->foreign("curriculum_id")
                  ->references("id")
                  ->on("curriculums")
                  ->onDelete("cascade");
            $table->foreign("user_id")
                  ->references("id")
                  ->on("users")
                  ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_student');
    }
}
