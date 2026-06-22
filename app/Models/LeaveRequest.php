<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'requested_by',
        'tanggal_izin',
        'jenis',
        'alasan',
        'bukti',
        'status',
        'catatan_guru',
        'diproses_oleh',
        'diproses_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_izin' => 'date',
            'diproses_at' => 'datetime',
        ];
    }

    // Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function isPending(): bool { return $this->status === 'Pending'; }
    public function isApproved(): bool { return $this->status === 'Approved'; }
    public function isRejected(): bool { return $this->status === 'Rejected'; }

    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti ? asset('storage/' . $this->bukti) : null;
    }
}
