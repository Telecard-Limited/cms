<?php

namespace App\Http\Controllers\Report;

use App\Complain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityWiseController extends Controller
{
    private function getColor($num) {
        $hash = md5('abdullah' . $num); // modify 'color' to get a different palette
        return array(
            hexdec(substr($hash, 0, 2)), // r
            hexdec(substr($hash, 2, 2)), // g
            hexdec(substr($hash, 4, 2))); //b
    }

    public function show(Request $request)
    {
        $complains = Complain::query()->with('outlet')->where('ticket_status_id', '=',$request->ticket_status)->whereBetween('created_at', [$request->from_datetime, $request->to_datetime])->get();
        $counted = $complains->pluck('outlet')->countBy('city');
        $data = $counted->values();
        $labels = $counted->keys();
        $label = "{$request->from_datetime} - {$request->to_datetime} Complaint Contribution";
        $backgroundColor = [];

        foreach ($labels as $key => $label) {
            $color = $this->getColor($key);
            $backgroundColor[] = "rgba($color[0], $color[1], $color[2], 0.9)";
        }

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => $label,
                    'data' => $data,
                    'backgroundColor' => $backgroundColor
                ]
            ]
        ]);
    }
}
