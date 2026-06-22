<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'no_whatsapp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isGuru(): bool  { return $this->role === 'guru'; }
    public function isOrangtua(): bool { return $this->role === 'orangtua'; }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru',
            'orangtua' => 'Orang Tua',
            default => ucfirst($this->role),
        };
    }

    // Relations
    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function students()
    {
        // For parent users
        return $this->hasMany(Student::class, 'parent_user_id');
    }

    // Accessor: Laravel's auth uses `name` attribute
    public function getNameAttribute(): string
    {
        return $this->nama ?? '';
    }
}
