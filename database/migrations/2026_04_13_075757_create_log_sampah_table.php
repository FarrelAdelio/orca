<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('log_sampah', function (Blueprint $table) {
            $table->id();
            $table->string('foto'); // URL foto dari Supabase
            $table->float('skor'); // confidence score
            $table->string('label'); // jenis sampah
            $table->string('supabase_id')->nullable(); // ID dari Supabase (untuk sync)
            $table->timestamp('detected_at')->nullable(); // waktu deteksi asli
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_sampah');
    }

};
