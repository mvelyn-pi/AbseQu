<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Settings ─────────────────────────────────────────────────────────
        $settings = [
            ['key' => 'jam_batas_hadir',      'value' => '07:30', 'label' => 'Jam Batas Hadir',      'type' => 'time'],
            ['key' => 'jam_tutup_absensi',     'value' => '10:00', 'label' => 'Jam Tutup Absensi',    'type' => 'time'],
            ['key' => 'batas_pengajuan_izin',  'value' => '1',     'label' => 'Batas Pengajuan Izin', 'type' => 'number'],
            ['key' => 'fonnte_api_key',        'value' => '',      'label' => 'Fonnte API Key',        'type' => 'text'],
            ['key' => 'nama_sekolah',          'value' => 'SMP 1 Palopo', 'label' => 'Nama Sekolah',  'type' => 'text'],
            ['key' => 'template_pesan_wa',     'value' => "Yth. Orang Tua/Wali {nama_siswa},\n\nKami informasikan bahwa {nama_siswa} dari kelas {kelas} TIDAK HADIR pada tanggal {tanggal} tanpa keterangan (Alpha).\n\nMohon konfirmasi kehadiran ke pihak sekolah.\n\nSalam,\nSMP 1 Palopo",
             'label' => 'Template Pesan WA', 'type' => 'textarea'],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }

        // ─── Admin ────────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@absenqu.id'], [
            'nama'     => 'Administrator',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ─── Guru ─────────────────────────────────────────────────────────────
        $guru1 = User::firstOrCreate(['email' => 'guru1@absenqu.id'], [
            'nama'         => 'Budi Santoso, S.Pd',
            'password'     => Hash::make('password'),
            'role'         => 'guru',
            'no_whatsapp'  => '08123456789',
        ]);
        $guru2 = User::firstOrCreate(['email' => 'guru2@absenqu.id'], [
            'nama'         => 'Siti Rahayu, S.Pd',
            'password'     => Hash::make('password'),
            'role'         => 'guru',
            'no_whatsapp'  => '08198765432',
        ]);

        // ─── Orang Tua ────────────────────────────────────────────────────────
        $ortu1 = User::firstOrCreate(['email' => 'ortu1@absenqu.id'], [
            'nama'        => 'Ahmad Hidayat',
            'password'    => Hash::make('password'),
            'role'        => 'orangtua',
            'no_whatsapp' => '08111111111',
        ]);
        $ortu2 = User::firstOrCreate(['email' => 'ortu2@absenqu.id'], [
            'nama'        => 'Dewi Sartika',
            'password'    => Hash::make('password'),
            'role'        => 'orangtua',
            'no_whatsapp' => '08222222222',
        ]);
        $ortu3 = User::firstOrCreate(['email' => 'ortu3@absenqu.id'], [
            'nama'        => 'Rudi Hartono',
            'password'    => Hash::make('password'),
            'role'        => 'orangtua',
            'no_whatsapp' => '08333333333',
        ]);

        // ─── Kelas ────────────────────────────────────────────────────────────
        $kelas7a = Kelas::firstOrCreate(['nama_kelas' => 'VII A'], [
            'tingkat'       => 'VII',
            'wali_kelas_id' => $guru1->id,
            'tahun_ajaran'  => 2025,
        ]);
        $kelas7b = Kelas::firstOrCreate(['nama_kelas' => 'VII B'], [
            'tingkat'       => 'VII',
            'wali_kelas_id' => $guru2->id,
            'tahun_ajaran'  => 2025,
        ]);
        $kelas8a = Kelas::firstOrCreate(['nama_kelas' => 'VIII A'], [
            'tingkat'       => 'VIII',
            'wali_kelas_id' => null,
            'tahun_ajaran'  => 2025,
        ]);

        // ─── Siswa ────────────────────────────────────────────────────────────
        $siswaData = [
            ['nama' => 'Andi Pratama',    'nis' => '2025001', 'kelas_id' => $kelas7a->id, 'parent_user_id' => $ortu1->id, 'jk' => 'L'],
            ['nama' => 'Bela Safitri',    'nis' => '2025002', 'kelas_id' => $kelas7a->id, 'parent_user_id' => $ortu2->id, 'jk' => 'P'],
            ['nama' => 'Candra Wijaya',   'nis' => '2025003', 'kelas_id' => $kelas7a->id, 'parent_user_id' => null,        'jk' => 'L'],
            ['nama' => 'Dina Marlina',    'nis' => '2025004', 'kelas_id' => $kelas7a->id, 'parent_user_id' => $ortu3->id, 'jk' => 'P'],
            ['nama' => 'Eko Saputra',     'nis' => '2025005', 'kelas_id' => $kelas7b->id, 'parent_user_id' => null,        'jk' => 'L'],
            ['nama' => 'Fitri Handayani', 'nis' => '2025006', 'kelas_id' => $kelas7b->id, 'parent_user_id' => null,        'jk' => 'P'],
            ['nama' => 'Gunawan Hadi',    'nis' => '2025007', 'kelas_id' => $kelas8a->id, 'parent_user_id' => null,        'jk' => 'L'],
            ['nama' => 'Heni Puspita',    'nis' => '2025008', 'kelas_id' => $kelas8a->id, 'parent_user_id' => null,        'jk' => 'P'],
        ];

        $students = [];
        foreach ($siswaData as $s) {
            $students[] = Student::firstOrCreate(['nis' => $s['nis']], [
                'nama'           => $s['nama'],
                'kelas_id'       => $s['kelas_id'],
                'parent_user_id' => $s['parent_user_id'],
                'jenis_kelamin'  => $s['jk'],
                'aktif'          => true,
            ]);
        }

        // ─── Sample Attendance (last 7 days) ──────────────────────────────────
        $statuses = ['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Terlambat', 'Alpha', 'Izin'];

        foreach ($students as $student) {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                if (Attendance::where('student_id', $student->id)->whereDate('tanggal', $date)->exists()) {
                    continue;
                }
                $status = $statuses[array_rand($statuses)];
                Attendance::create([
                    'student_id'      => $student->id,
                    'tanggal'         => $date,
                    'waktu_scan'      => $status !== 'Alpha' ? '07:' . rand(10, 45) . ':00' : null,
                    'status'          => $status,
                    'menit_terlambat' => $status === 'Terlambat' ? rand(5, 30) : 0,
                    'keterangan'      => null,
                    'dicatat_oleh'    => $guru1->id,
                ]);
            }
        }

        $this->command->info('✅ Seeding selesai!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',      'admin@absenqu.id', 'password'],
                ['Guru 1',     'guru1@absenqu.id', 'password'],
                ['Guru 2',     'guru2@absenqu.id', 'password'],
                ['Orang Tua 1','ortu1@absenqu.id', 'password'],
                ['Orang Tua 2','ortu2@absenqu.id', 'password'],
                ['Orang Tua 3','ortu3@absenqu.id', 'password'],
            ]
        );
    }
}
