<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tanggal',
        'waktu_scan',
        'status',
        'menit_terlambat',
        'keterangan',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'menit_terlambat' => 'integer',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // Scopes
    public function scopeHadir($query)
    {
        return $query->whereIn('status', ['Hadir', 'Terlambat']);
    }

    public function scopeAlpha($query)
    {
        return $query->where('status', 'Alpha');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    public function scopeByKelas($query, int $kelasId)
    {
        return $query->whereHas('student', fn($q) => $q->where('kelas_id', $kelasId));
    }

    // Badge color for status
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'Hadir'    => 'badge-hadir',
            'Terlambat'=> 'badge-terlambat',
            'Izin'     => 'badge-izin',
            'Sakit'    => 'badge-sakit',
            'Alpha'    => 'badge-alpha',
            default    => 'badge-secondary',
        };
    }
}
