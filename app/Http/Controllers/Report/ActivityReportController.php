<?php

namespace App\Http\Controllers\Report;

use App\Complain;
use App\Customer;
use App\Department;
use App\Issue;
use App\Outlet;
use App\Rating;
use App\TicketStatus;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Html\Builder;

class ActivityReportController extends Controller
{
    public function index(Builder $builder)
    {
        $query = Activity::all();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->editColumn("description", function (Activity $activity) {
                    $status = $activity->description;
                    switch ($activity->description) {
                        case "created":
                            $class = "primary";
                            break;
                        case "deleted":
                            $class = "danger";
                            break;
                        case "updated":
                            $class = "info";
                            break;
                        default:
                            $class = "secondary";
                            break;
                    }
                    return view('architect.datatables.activity', compact('status', 'class'));
                })
                ->editColumn('subject_id' , function (Activity $activity) {
                    $type = $activity->subject_type;
                    switch ($type) {
                        case "App\User":
                            return User::withTrashed()->find($activity->subject_id)["name"] ?: "-";
                            break;
                        case "App\Customer":
                            return Customer::withTrashed()->find($activity->subject_id)["name"] ?: "-";
                            break;
                        case "App\Outlet":
                            return Outlet::withTrashed()->find($activity->subject_id)["name"] ?: "-";
                            break;
                        case "App\Department":
                            return Department::withTrashed()->find($activity->subject_id)["name"] ?: "-";
                            break;
                        case "App\Issue":
                            return Issue::withTrashed()->find($activity->subject_id)["name"] ?: "-";
                            break;
                        case "App\TicketStatus":
                            return TicketStatus::withTrashed()->find($activity->subject_id)["name"] ?: "-";
                            break;
                        case "App\Complain":
                            return Complain::withTrashed()->find($activity->subject_id)["title"] ?: "-";
                            break;
                        case "App\Rating":
                            return Rating::withTrashed()->find($activity->subject_id)["title"] ?: "-";
                            break;
                        default:
                            return $activity->subject_id;
                            break;
                    }
                })
                ->editColumn('subject_type' , function (Activity $activity) {
                    return explode("\\", $activity->subject_type)[1];
                })
                ->editColumn('causer_type' , function (Activity $activity) {
                    return $activity->causer_type == null ? "NULL" : explode("\\", $activity->causer_type)[1];
                })
                ->editColumn('causer_id' , function (Activity $activity) {
                    return $activity->causer == null ? "NULL" : $activity->causer->name;
                })
                ->rawColumns(['description'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'description', 'title' => 'Event'],
            ['data' => 'subject_id', 'title' => 'Subject'],
            ['data' => 'subject_type', 'title' => 'Subject Type'],
            ['data' => 'causer_id', 'title' => 'Causer'],
            ['data' => 'causer_type', 'title' => 'Causer Type'],
            ['data' => 'created_at', 'title' => 'Created'],
        ]);

        return view('architect.reports.activity', compact('html'));
    }
}
