<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Customer;
use App\Traits\ReportTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    use ReportTrait;

    public function orders()
    {
        $query = Order::query();

        return $this->prepareDataForBarChart($query, 'Ordini per Giorno');
    }

    public function customers()
    {
        $query = Customer::query();

        return $this->prepareDataForBarChart($query, 'Clienti per Giorno');
    }

    private function prepareDataForBarChart($query, $label)
    {
        $fromDate = $this->getFromDate() ?: Carbon::now()->subDay(30);
        $query
            ->select([DB::raw('CAST(created_at as DATE) AS day'), DB::raw('COUNT(created_at) AS count')])
            ->groupBy(DB::raw('CAST(created_at as DATE)'));
        if ($fromDate) {
            $query->where('created_at', '>', $fromDate);
        }
        $records = $query->get()->keyBy('day');

        // Process for chartjs
        $days = [];
        $labels = [];
        $now = Carbon::now();
        while ($fromDate < $now) {
            $key = $fromDate->format('Y-m-d');
            $labels[] = $key;
            $fromDate = $fromDate->addDay(1);
            $days[] = isset($records[$key]) ? $records[$key]['count'] : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => $label,
                'backgroundColor' => '#f87979',
                'data' => $days
            ]]
        ];
    }
}