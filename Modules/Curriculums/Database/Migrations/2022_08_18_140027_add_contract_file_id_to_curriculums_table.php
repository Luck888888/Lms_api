<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractFileIdToCurriculumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->unsignedBigInteger("contract_file_id")->nullable();
            $table->unsignedTinyInteger('years_of_study')->nullable();

            $table->foreign("contract_file_id")->references("id")->on("files")->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curriculums', function (Blueprint $table) {
            $table->dropForeign(['contract_file_id']);
            $table->dropColumn("contract_file_id");
            $table->dropColumn("years_of_study");
        });
    }
}
