<?php

namespace App\Charts;

use App\Models\Siswa;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class SiswaKelasChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $siswaKelas = Siswa::get();
        $data = [
            $siswaKelas->where('kelas', 10)->count(),
            $siswaKelas->where('kelas', 11)->count(),
            $siswaKelas->where('kelas', 11)->count(),
        ];
        $label = [
            'kelas 10',
            'kelas 11',
            'kelas 12',
        ];

        return $this->chart->donutChart()
            ->setTitle('Data Siswa PerKelas')
            ->setSubtitle(date('Y'))
            ->setWidth(500)
            ->setHeight(500)
            ->addData($data)
            ->setLabels($label);
    }
}
