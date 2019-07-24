<?php

namespace App\Http\Controllers\Report;

use App\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Matrix\Builder;
use Yajra\DataTables\Facades\DataTables;

class RatingReportController extends Controller
{
    public function report(Request $request)
    {
        if ($request->ajax()) {
            $query = Rating::query();
            if ($request->has("datetimes")) {
                $datetimes = explode(" - ", $request->datetimes);
                $from = Carbon::parse($datetimes[0])->format("Y-m-d H:i:s");
                $to = Carbon::parse($datetimes[1])->format("Y-m-d H:i:s");
                $query->whereBetween("created_at", [$from, $to]);
            }
            if ($request->id !== null) {
                $query->where("id", ltrim($request->id, "0"));
            }
            if ($request->order_id !== null) {
                $query->where("order_id", $request->order_id);
            }
            if ($request->customer_name !== null) {
                $query->with(["customers" => function (Builder $builder) use ($request) {
                    $builder->where("name", "like", "%$request->customer_name%");
                }]);
            }
            if ($request->outlet_id !== null) {
                $query->whereIn("outlet_id", $request->outlet_id);
            }
            if ($request->user_id !== null) {
                $query->whereIn("user_id", $request->user_id);
            }
            if ($request->informed_to !== null) {
                $query->where("informed_to", "like", "%$request->informed_to%");
            }

            return DataTables::of($query->get())
                ->editColumn('outlet_id', function (Rating $rating) {
                    return $rating->outlet->name;
                })
                ->editColumn('customer_name', function (Rating $rating) {
                    return $rating->customer->name;
                })
                ->editColumn('customer_number', function (Rating $rating) {
                    return $rating->customer->number;
                })
                ->editColumn('ticket_status_id', function (Rating $rating) {
                    return view('architect.datatables.status', ['status' => $rating->ticket_status->name]);
                })
                ->editColumn('user_id', function (Rating $rating) {
                    return $rating->created_by->name;
                })
                ->editColumn('issue_id', function (Rating $rating) {
                    return view('architect.datatables.issues', ['issues' => $rating->issues]);
                })
                ->rawColumns(['edit', 'ticket_status_id', 'issue_id'])
                ->toJson();
        } else {
            return response()->json([], 401);
        }
    }
}
