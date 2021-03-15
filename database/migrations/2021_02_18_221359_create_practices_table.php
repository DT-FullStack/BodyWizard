<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('clinicwizard_base')->create('practices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('practice_id')->unique();
            $table->string('name');
            $table->string('dbname');
            $table->string('host');
            $table->string('cryptokey')->nullable()->default(null);
            $table->json('schedule')->nullable()->default(null);
            $table->json('settings')->nullable()->default(null);
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
        Schema::dropIfExists('practices');
    }
}
