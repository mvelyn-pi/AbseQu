<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_scan')->nullable();
            $table->enum('status', ['Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpha'])->default('Alpha');
            $table->integer('menit_terlambat')->default(0);
            $table->string('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete(); // guru/admin
            $table->timestamps();

            $table->unique(['student_id', 'tanggal']); // satu record per siswa per hari
            $table->index('tanggal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
