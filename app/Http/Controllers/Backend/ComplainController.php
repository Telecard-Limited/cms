<?php

namespace App\Http\Controllers\Backend;

use App\Complain;
use App\Department;
use App\Outlet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = Complain::all();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (Complain $complain) {
                    $route = route('complain.edit', ltrim($complain->id, "0"));
                    return "<a href='$route' class='mb-2 mr-2 btn-icon btn btn-primary'><i class='pe-7s-tools btn-icon-wrapper'></i> Edit</a>";
                })
                /*->addColumn('delete', function (Complain $complain) {
                    $route = route('smsRecipient.destroy', $complain->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');
                    return "<form action='$route' method='post'>$csrf$method<button class='mb-2 mr-2 btn btn-icon btn-danger'><i class='pe-7s-delete-user'></i> Delete</button></form>";
                })*/
                ->editColumn('type_name', function (Complain $complain) {
                    return $complain->complainable->name;
                })
                ->editColumn('ticket_status_id', function (Complain $complain) {
                    $status = $complain->ticket_status->name;
                    $badge = $status == "Closed" ? "success" : "danger";
                    return "<span class='badge badge-$badge'>$status</span>";
                })
                ->editColumn('issue_id', function (Complain $complain) {
                    return $complain->issue->name;
                })
                ->rawColumns(['edit', 'ticket_status_id'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'Complain #'],
            ['data' => 'customer_name', 'title' => 'Customer'],
            ['data' => 'customer_number', 'title' => 'Customer #'],
            ['data' => 'order_number', 'title' => 'Order #'],
            ['data' => 'type_name', 'title' => 'Outlet / Dept.'],
            ['data' => 'remarks', 'title' => 'Remarks'],
            ['data' => 'ticket_status_id', 'title' => 'Status'],
            ['data' => 'issue_id', 'title' => 'Issue'],
            ['data' => 'edit', 'title' => ''],
        ]);
        return view('architect.complain.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $outlets = Outlet::pluck('name', 'id')->toArray();
        $departments = Department::pluck('name', 'id')->toArray();
        $groups = [
            "outlet" => $outlets,
            "department" => $departments
        ];
        return view('architect.complain.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ruleExists = 'exists:departments,id';
        if($request->type == "outlet") {
            $ruleExists = 'exists:outlets,id';
        }

        $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_number' => ['required'],
            'type' => ['required', 'in:outlet,department'],
            'type_id' => ['required', 'numeric', $ruleExists],
            'ticket_status_id' => ['required', 'exists:ticket_statuses,id'],
            'issue_id' => ['nullable', 'exists:issues,id']
        ]);

        $complain = new Complain();
        $complain->customer_name = $request->customer_name;
        $complain->customer_number = $request->customer_number;
        $complain->ticket_status_id = $request->ticket_status_id;
        $complain->issue_id = $request->issue_id;
        $complain->order_number = $request->order_number;
        $complain->desc = $request->desc;
        $complain->remarks = $request->remarks;
        $complain->user_id = Auth::user()->id;

        $type = $request->type;

        switch ($type)
        {
            case 'outlet':
                $outlet = Outlet::findOrFail($request->type_id);
                $outlet->complains()->save($complain);
                break;
            case 'department':
                $depart = Department::findOrFail($request->type_id);
                $depart->complains()->save($complain);
                break;
            default:
                return redirect()->route('complain.index')->with('failure', "The $type you're trying to assign doesn't exists.");
                break;
        }

        return redirect()->route('complain.index')->with('status', "New Complain has been created. Complain number: $complain->id");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Backend\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function show(Complain $complain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Backend\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function edit(Complain $complain)
    {
        return view('architect.complain.edit', compact('complain'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Backend\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complain $complain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Backend\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complain $complain)
    {
        //
    }
}
