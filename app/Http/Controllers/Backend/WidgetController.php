<?php

namespace App\Http\Controllers\Backend;

use App\Complain;
use App\Rating;
use App\TicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WidgetController extends Controller
{
    public function getData(Request $request)
    {
        $param = $request->param;

        switch ($param) {
            case "complainCount":
                return response()->json(Complain::all()->count());
                break;
            case "ratingCount":
                return response()->json(Rating::all()->count());
                break;
            case "openTickets":
                $status = TicketStatus::where("name", "Open")->first()->complains()->count();
                $total = Complain::all()->count();
                $percent = $total == 0 ? 0 : number_format($status/$total * 100);
                return response()->json("$percent%");
            case "closedTickets":
                $status = TicketStatus::where("name", "Closed")->first()->complains()->count();
                $total = Complain::all()->count();
                $percent = $total == 0 ? 0 : number_format($status/$total * 100);
                return response()->json("$percent%");
                break;
            default:
                return response()->json([], 401);
                break;
        }
    }

    public function getChartLabels()
    {
        $arr = [];
        $data = [];
        $data2 = [];
        $date = Carbon::now();
        $totalDays = $date->daysInMonth;
        $year = $date->year;
        $month = $date->month;

        for ($i = 1; $i <= $totalDays; $i++) {
            $arr[] = $i;
            $data[] = Complain::whereDate("created_at", Carbon::createFromDate($year, $month, $i)->format("Y-m-d"))->get()->count();
            $data2[] = Rating::whereDate("created_at", Carbon::createFromDate($year, $month, $i)->format("Y-m-d"))->get()->count();
        }

        return response()->json(['labels' => $arr, 'data' => $data, 'data2' => $data2]);
    }
}
