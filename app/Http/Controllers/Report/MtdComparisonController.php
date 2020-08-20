<?php

namespace App\Http\Controllers\Report;

use App\Category;
use App\Complain;
use App\Issue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MtdComparisonController extends Controller
{

    private function getColor($num) {
        $hash = md5('vibrant' . $num); // modify 'color' to get a different palette
        return array(
            hexdec(substr($hash, 0, 2)), // r
            hexdec(substr($hash, 2, 2)), // g
            hexdec(substr($hash, 4, 2))); //b
    }

    public function show(Request $request)
    {
        [$year1, $month1] = explode("-", $request->month1);
        [$year2, $month2] = explode("-", $request->month2);
        [$year3, $month3] = explode("-", $request->month3);

        $complains = [];
        $counts = [];
        $backgroundColor = [];

        foreach ($request->complain_type as $key => $item) {
            $color = $this->getColor($key);
            $backgroundColor[] = "rgba($color[0], $color[1], $color[2], 0.9)";

            $complains[1][$item] = Issue::query()->where('category_id', $item)->with(['complains' => function($query) use ($month1, $year1) {
                $query->whereMonth('created_at', $month1)->whereYear('created_at', $year1);
            }])->get();

            $complains[2][$item] = Issue::query()->where('category_id', $item)->with(['complains' => function($query) use ($month2, $year2) {
                $query->whereMonth('created_at', $month2)->whereYear('created_at', $year2);
            }])->get();

            $complains[3][$item] = Issue::query()->where('category_id', $item)->with(['complains' => function($query) use ($month3, $year3) {
                $query->whereMonth('created_at', $month3)->whereYear('created_at', $year3);
            }])->get();
        }

        foreach ($complains[1] as $index => $complain) {
            foreach ($complain as $item) {
                $counts[1][Category::query()->findOrFail($index)->name] = $item->complains->count();
            }
        }

        foreach ($complains[2] as $index => $complain) {
            foreach ($complain as $item) {
                $counts[2][Category::query()->findOrFail($index)->name] = $item->complains->count();
            }
        }

        foreach ($complains[3] as $index => $complain) {
            foreach ($complain as $item) {
                $counts[3][Category::query()->findOrFail($index)->name] = $item->complains->count();
            }
        }

        return response()->json([
            'labels' => array_keys($counts[1]),
            'datasets' => [
                [
                    'label' => Carbon::create($year1, $month1)->monthName,
                    'data' => array_values($counts[1]),
                    'backgroundColor' => $backgroundColor[0]
                ],
                [
                    'label' => Carbon::create($year2, $month2)->monthName,
                    'data' => array_values($counts[2]),
                    'backgroundColor' => $backgroundColor[1]
                ],
                [
                    'label' => Carbon::create($year3, $month3)->monthName,
                    'data' => array_values($counts[3]),
                    'backgroundColor' => $backgroundColor[2]
                ]
            ]
        ]);
    }
}
