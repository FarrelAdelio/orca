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
        Schema::create('supabase_sync_log', function (Blueprint $table) {
            $table->id();
            $table->timestamp('last_sync_at')->nullable();
            $table->integer('synced_count')->default(0);
            $table->string('status')->default('success');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supabase_sync_log');
    }

};
