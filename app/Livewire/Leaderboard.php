<?php

namespace App\Livewire;

use App\Services\LeaderboardService;
use App\Models\Kelas;
use Livewire\Component;

class Leaderboard extends Component
{
    public string $periode   = 'bulan';
    public ?int   $kelasId   = null;
    public array  $rankings  = [];

    public function mount(): void
    {
        $this->loadRankings();
    }

    public function updatedPeriode(): void { $this->loadRankings(); }
    public function updatedKelasId(): void { $this->loadRankings(); }

    private function loadRankings(): void
    {
        $service = app(LeaderboardService::class);
        $this->rankings = $service->getRankings($this->kelasId, $this->periode);
    }

    public function render()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('livewire.leaderboard', compact('kelasList'));
    }
}
