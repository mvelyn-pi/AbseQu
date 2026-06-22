<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'wali_kelas_id',
        'tahun_ajaran',
    ];

    protected function casts(): array
    {
        return [
            'tahun_ajaran' => 'integer',
        ];
    }

    // Relations
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'kelas_id');
    }

    public function getNamaLengkapAttribute(): string
    {
        return $this->nama_kelas . ' (Kelas ' . $this->tingkat . ')';
    }

    public function jumlahSiswa(): int
    {
        return $this->students()->where('aktif', true)->count();
    }
}
