<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tanggal_kirim',
        'status_kirim',
        'no_tujuan',
        'isi_pesan',
        'error_message',
        'fonnte_response',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kirim' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status_kirim', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status_kirim', 'failed');
    }
}
