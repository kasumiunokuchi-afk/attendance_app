<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStampCorrectionRests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamp_correction_rests', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'stamp_correction_id')->constrained()->cascadeOnDelete();
            $table->foreignId(column: 'rest_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamp(column: 'rest_start_at');
            $table->timestamp(column: 'rest_end_at')->nullable();
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
        Schema::dropIfExists('rest_requests');
    }
}
