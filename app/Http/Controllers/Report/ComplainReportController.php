<?php

namespace App\Http\Controllers\Report;

use App\Complain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Matrix\Builder;
use Yajra\DataTables\Facades\DataTables;

class ComplainReportController extends Controller
{
    public function index()
    {
        return view('architect.reports.complains');
    }

    public function report(Request $request)
    {
        if($request->ajax()) {
            $query = Complain::query();
            if($request->has("datetimes")) {
                $datetimes = explode(" - ", $request->datetimes);
                $from = Carbon::parse($datetimes[0])->format("Y-m-d H:i:s");
                $to = Carbon::parse($datetimes[1])->format("Y-m-d H:i:s");
                $query->whereBetween("created_at", [$from, $to]);
            }
            if($request->id !== null) {
                $query->where("id", ltrim($request->id, "0"));
            }
            if($request->order_id !== null) {
                $query->where("order_id", $request->order_id);
            }
            if($request->customer_name !== null) {
                $query->with(["customers" => function(Builder $builder) use ($request) {
                    $builder->where("name", "like", "%$request->customer_name%");
                }]);
            }
            if($request->outlet_id !== null) {
                $query->whereIn("outlet_id", $request->outlet_id);
            }
            if($request->user_id !== null) {
                $query->whereIn("user_id", $request->user_id);
            }
            if($request->title !== null) {
                $query->where("title", $request->title);
            }

            return DataTables::of($query->get())
                ->editColumn('id', function (Complain $complain) {
                    return $complain->getComplainNumber();
                })
                ->editColumn('outlet_id', function (Complain $complain) {
                    return $complain->outlet->name;
                })
                ->editColumn('customer_name', function (Complain $complain) {
                    return $complain->customer->name;
                })
                ->editColumn('customer_number', function (Complain $complain) {
                    return $complain->customer->number;
                })
                ->editColumn('ticket_status_id', function (Complain $complain) {
                    return view('architect.datatables.status', ['status' => $complain->ticket_status->name]);
                })
                ->editColumn('user_id', function (Complain $complain) {
                    return $complain->created_by->name;
                })
                ->editColumn('issue_id', function (Complain $complain) {
                    return view('architect.datatables.issues', ['issues' => $complain->issues]);
                })
                ->addColumn('complain_source_id', function (Complain $complain) {
                    return $complain->complain_source->name ?? "N/A";
                })
                ->addColumn('category', function (Complain $complain) {
                    return $complain->issues()->first()->category->name;
                })
                ->rawColumns(['ticket_status_id', 'issue_id'])
                ->toJson();
        } else {
            return response()->json([], 401);
        }
    }
}
