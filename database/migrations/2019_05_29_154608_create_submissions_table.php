<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->bigIncrements('id');
            $table->string('form_name');
            $table->string('form_user_type');
            $table->unsignedInteger('form_uid');
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('appointment_id')->nullable()->default(null);
            $table->string('submitted_by');
            $table->boolean('self_submitted');
            $table->unsignedInteger('patient_id');
            $table->unsignedInteger('submitted_by_user_id');
            $table->longtext('responses')->nullable()->default(null);
            $table->longtext('autosave')->nullable()->default(null);
            $table->softDeletes();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
