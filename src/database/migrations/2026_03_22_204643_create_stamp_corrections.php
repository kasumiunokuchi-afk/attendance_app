<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampCorrections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'user_id')->constrained()->cascadeOnDelete();
            $table->foreignId(column: 'attendance_id')->constrained()->cascadeOnDelete();
            $table->integer(column: 'request_status');
            $table->timestamp(column: 'request_date');
            $table->string(column: 'note');
            $table->timestamp(column: 'new_clock_in_at');
            $table->timestamp(column: 'new_clock_out_at')->nullable();
            $table->timestamp(column: 'approved_at')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
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
        Schema::dropIfExists('attendance_requests');
    }
}
