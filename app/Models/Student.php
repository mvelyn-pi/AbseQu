<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'kelas_id',
        'qr_code',
        'foto',
        'parent_user_id',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'aktif' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($student) {
            if (empty($student->qr_code)) {
                $student->qr_code = 'ABSENQU-' . strtoupper(Str::random(12));
            }
        });
    }

    // Relations
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'student_id');
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class, 'student_id');
    }

    // Helpers
    public function getAttendanceToday(): ?Attendance
    {
        return $this->attendances()->whereDate('tanggal', today())->first();
    }

    public function getPersentaseKehadiran(?string $bulan = null, ?string $tahun = null): float
    {
        $query = $this->attendances();

        if ($bulan && $tahun) {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        } elseif ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $total = $query->count();
        if ($total === 0) return 0;

        $hadir = $query->clone()->whereIn('status', ['Hadir', 'Terlambat'])->count();
        return round(($hadir / $total) * 100, 1);
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        $initial = strtoupper(substr($this->nama, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=4F46E5&color=fff&size=128";
    }
}
