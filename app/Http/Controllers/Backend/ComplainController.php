<?php

namespace App\Http\Controllers\Backend;

use App\Complain;
use App\Customer;
use App\Department;
use App\Exports\ComplainExport;
use App\Outlet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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
        if(request()->ajax()) {
            $query = Complain::query()->orderBy('created_at', 'desc');
            return DataTables::of($query)
                ->addColumn('edit', function (Complain $complain) {
                    return view('architect.datatables.form-edit', ['model' => $complain, 'route' => 'complain']);
                })
                ->addColumn('class', function (Complain $complain) {
                    return Carbon::now()->diffInMinutes($complain->created_at) <= 1 ? true : false;
                })
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
                ->rawColumns(['edit', 'ticket_status_id', 'issue_id'])
                ->toJson();
        }

        return view('architect.complain.index');
    }

    public function export()
    {
        return Excel::download(new ComplainExport, 'complains.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.complain.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_number' => ['required'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'title' => ['nullable', 'string'],
            'order_id' => ['nullable'],
            'outlet_id' => ['required', 'exists:outlets,id'],
            'issue_id' => ['array', 'required', 'exists:issues,id'],
            'ticket_status_id' => ['required', 'exists:ticket_statuses,id']
        ]);

        if($request->customer_id !== null) {
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            $customer = Customer::create($request->only(['customer_name', 'customer_number']));
        }

        $complain = new Complain();
        $complain->title = $request->title;
        $complain->order_id = $request->order_id;
        $complain->outlet_id = $request->outlet_id;
        $complain->ticket_status_id = $request->ticket_status_id;
        $complain->user_id = Auth::user()->id;
        $complain->customer_id = $customer->id;
        $complain->desc = $request->desc;
        $complain->remarks = $request->remarks;
        $complain->save();

        $complain->issues()->sync($request->issue_id);

        return redirect()->route('complain.index')->with('status', "Complain has been created with number: $complain->id");

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
        $request->validate([
            "customer_name" => ["required", "string"],
            "customer_number" => ["required", "string"],
            "customer_id" => ["required", "exists:customers,id"],
            "title" => ["string"],
            "order_id" => ["nullable"],
            "outlet_id" => ["required", "exists:outlets,id"],
            "issue_id" => ["array", "required", "exists:issues,id"],
            "ticket_status_id" => ["required", "exists:ticket_statuses,id"]
        ]);

        if($request->customer_name !== $complain->customer->name ||
            $request->customer_number !== $complain->customer->number) {
            // Update Customer

            $customer = Customer::findOrFail($request->customer_id);
            $customer->name = $request->customer_name;
            $customer->number = $request->customer_number;
            $customer->save();
        }

        $complain->title = $request->title;
        $complain->order_id = $request->order_id;
        $complain->outlet_id = $request->outlet_id;
        $complain->ticket_status_id = $request->ticket_status_id;
        $complain->customer_id = $request->customer_id;
        $complain->desc = $request->desc;
        $complain->remarks = $request->remarks;
        $complain->update();

        $complain->issues()->sync($request->issue_id);

        return redirect()->route('complain.index')->with('status', "Complain #" . $complain->getComplainNumber() . " has been updated");
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
