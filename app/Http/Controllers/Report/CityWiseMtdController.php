<?php

namespace App\Http\Controllers\Report;

use App\Complain;
use App\Outlet;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CityWiseMtdController extends Controller
{
    private function getColor($num) {
        $hash = md5('color' . $num); // modify 'color' to get a different palette
        return array(
            hexdec(substr($hash, 0, 2)), // r
            hexdec(substr($hash, 2, 2)), // g
            hexdec(substr($hash, 4, 2))); //b
    }

    public function show(Request $request)
    {
        $s = explode("/", $request->start);
        $e = explode("/", $request->end);
        $start = Carbon::create($s[1], $s[0]);
        $end = Carbon::create($e[1], $e[0]);
        //$outlets = Outlet::query()->whereIn('city', $request->city)->get();
        $range = [];
        $complains = [];

        /*for ($i = $s[0]; $i <= $e[0]; $i++) {
            $range[] = substr((string)$i, 0, 1) !== "0" ? "0$i" : "$i";
        }*/

        foreach (CarbonPeriod::create($start, $end) as $item) {
            $range[] = $item->startOfMonth();
        }

        $dates = collect($range)->unique();
        $datasets = [];

        foreach ($request->city as $key => $city) {
            $outlets = Outlet::query()->where('city', $city)->get();
            $datasets[$key]['label'] = $city;
            foreach ($dates as $date) {
                $datasets[$key]['data'][] = Complain::query()->whereMonth("created_at", $date)->whereYear("created_at", $date->year)->whereIn('outlet_id', $outlets->pluck('id'))->count();
            }
            $datasets[$key]['fill'] = false;
            $color = $this->getColor($key);
            $datasets[$key]['backgroundColor'] = "rgba($color[0], $color[1], $color[2], 0.9)";
            $datasets[$key]['borderColor'] = "rgba($color[0], $color[1], $color[2], 0.9)";
        }

        /*foreach ($dates as $key => $item) {
            $complains['label'] = "Dataset $key";
            $complains['data'] = Complain::query()->whereMonth("created_at", $item)->whereIn('outlet_id', $outlets->pluck('id'))->count();
        }*/

        $labels = [];

        foreach ($dates as $date) {
            $labels[] = $date->monthName;
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }
}
