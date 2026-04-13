<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('robots', function (Blueprint $table) {
            $table->id();
            $table->string('nama_robot')->default('ESP32_CAM_1');
            $table->enum('status', ['online', 'offline', 'error'])->default('offline');
            $table->enum('mode', ['auto', 'manual', 'standby'])->default('auto');
            $table->integer('baterai')->default(100); // persen
            $table->enum('status_wadah', ['kosong', 'terisi', 'penuh'])->default('kosong');
            $table->timestamp('last_update')->nullable();
            $table->timestamps();
        });
        
        // Insert default robot
        DB::table('robots')->insert([
            'nama_robot' => 'ESP32_CAM_1',
            'status' => 'online',
            'mode' => 'auto',
            'baterai' => 85,
            'status_wadah' => 'terisi',
            'last_update' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('robots');
    }

};
