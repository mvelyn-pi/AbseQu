<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('tanggal_kirim');
            $table->enum('status_kirim', ['success', 'failed', 'pending'])->default('pending');
            $table->string('no_tujuan', 20); // target WA number
            $table->text('isi_pesan');
            $table->string('error_message')->nullable();
            $table->string('fonnte_response')->nullable();
            $table->timestamps();

            $table->index('tanggal_kirim');
            $table->index('status_kirim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
