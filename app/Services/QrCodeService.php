<?php

namespace App\Services;

use App\Models\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR code SVG for a student.
     */
    public function generateSvg(Student $student, int $size = 200): string
    {
        return (string) QrCode::size($size)
            ->format('svg')
            ->margin(1)
            ->errorCorrection('H')
            ->generate($student->qr_code);
    }

    /**
     * Generate QR code PNG for a student (returns binary).
     */
    public function generatePng(Student $student, int $size = 300): string
    {
        return QrCode::size($size)
            ->format('png')
            ->errorCorrection('H')
            ->generate($student->qr_code);
    }

    /**
     * Render a printable ID card HTML for a student.
     */
    public function renderIdCard(Student $student): string
    {
        $qrSvg = $this->generateSvg($student, 150);

        return view('components.id-card', [
            'student' => $student,
            'qrSvg'   => $qrSvg,
        ])->render();
    }
}
