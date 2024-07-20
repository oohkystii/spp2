<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ArielMejiaDev\LarapexCharts\PieChart; 

class TagihanStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(array $label, array $data): PieChart
    {
        return $this->chart->pieChart()
            ->setTitle('Grafik Status Tagihan')
            ->setSubtitle(date(' F Y'))
            ->setDataset($data)
            ->setDataLabels(true)
            ->setLabels($label);
    }
}
