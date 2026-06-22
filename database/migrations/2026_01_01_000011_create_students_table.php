<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis', 20)->unique(); // Nomor Induk Siswa
            $table->foreignId('kelas_id')->constrained('classes')->cascadeOnDelete();
            $table->string('qr_code')->unique(); // UUID-based QR code value
            $table->string('foto')->nullable(); // photo path
            $table->foreignId('parent_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
