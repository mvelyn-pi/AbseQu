<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $settingKeys = [
        'jam_batas_hadir'      => ['label' => 'Jam Batas Hadir', 'type' => 'time'],
        'jam_tutup_absensi'    => ['label' => 'Jam Tutup Absensi', 'type' => 'time'],
        'batas_pengajuan_izin' => ['label' => 'Batas Hari Pengajuan Izin (H+n)', 'type' => 'number'],
        'fonnte_api_key'       => ['label' => 'Fonnte API Key', 'type' => 'text'],
        'nama_sekolah'         => ['label' => 'Nama Sekolah', 'type' => 'text'],
        'template_pesan_wa'    => ['label' => 'Template Pesan WhatsApp Alpha', 'type' => 'textarea'],
    ];

    public function index()
    {
        $settings = Setting::getAllAsArray();
        $fields   = $this->settingKeys;
        return view('admin.settings', compact('settings', 'fields'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'jam_batas_hadir'      => 'nullable|date_format:H:i',
            'jam_tutup_absensi'    => 'nullable|date_format:H:i',
            'batas_pengajuan_izin' => 'nullable|integer|min:0|max:7',
            'fonnte_api_key'       => 'nullable|string|max:255',
            'nama_sekolah'         => 'nullable|string|max:100',
            'template_pesan_wa'    => 'nullable|string|max:1000',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
