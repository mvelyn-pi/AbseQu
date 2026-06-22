<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete(); // parent user
            $table->date('tanggal_izin');
            $table->enum('jenis', ['Izin', 'Sakit']);
            $table->text('alasan');
            $table->string('bukti')->nullable(); // file path
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('catatan_guru')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete(); // guru/admin
            $table->timestamp('diproses_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'tanggal_izin']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
