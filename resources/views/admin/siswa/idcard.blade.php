<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ID Card — {{ $siswa->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .id-card {
            width: 85.6mm; height: 53.98mm;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #1e1b4b 100%);
            border-radius: 10px; padding: 12px;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            color: white; position: relative; overflow: hidden;
        }
        .id-card::before {
            content: ''; position: absolute; top: -20px; right: -20px;
            width: 80px; height: 80px; border-radius: 50%;
            background: rgba(99,102,241,0.2);
        }
        .school-name { font-size: 7px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #a5b4fc; margin-bottom: 4px; }
        .student-name { font-size: 11px; font-weight: 800; color: white; line-height: 1.2; }
        .student-class { font-size: 8px; color: #c7d2fe; margin-top: 2px; }
        .student-nis { font-size: 7px; color: #a5b4fc; margin-top: 4px; }
        .qr-box { background: white; border-radius: 6px; padding: 4px; flex-shrink: 0; }
        .qr-box svg { width: 60px; height: 60px; display: block; }
        .card-type { font-size: 6px; color: #818cf8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .print-btn {
            display: block; margin: 20px auto; padding: 10px 24px;
            background: #4f46e5; color: white; border: none; border-radius: 8px;
            cursor: pointer; font-size: 14px; font-weight: 600;
        }
        @media print {
            body { background: white; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <div>
        <div class="id-card">
            <div class="qr-box">
                {!! $qrSvg !!}
            </div>
            <div>
                <div class="card-type">ID Card Siswa</div>
                <div class="school-name">SMP 1 Palopo</div>
                <div class="student-name">{{ $siswa->nama }}</div>
                <div class="student-class">{{ $siswa->kelas->nama_kelas ?? 'Kelas -' }}</div>
                <div class="student-nis">NIS: {{ $siswa->nis }}</div>
            </div>
        </div>
        <button class="print-btn" onclick="window.print()">🖨️ Cetak ID Card</button>
    </div>
</body>
</html>
