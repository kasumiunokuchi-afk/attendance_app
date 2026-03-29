<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'user_id')->constrained()->cascadeOnDelete();
            $table->date(column: 'work_date');
            $table->timestamp(column: 'clock_in_at');
            $table->timestamp(column: 'clock_out_at')->nullable();
            $table->integer(column: 'rest_minutes')->nullable();
            $table->integer(column: 'work_minutes')->nullable();
            $table->integer(column: 'attendance_status');
            $table->string(column: 'note')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
