<?php

namespace App\Http\Controllers\Backend;

use App\Complain;
use App\ComplainSource;
use App\Customer;
use App\Department;
use App\Events\SendSMSEvent;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View || @return View
     */
    public function index(Builder $builder)
    {
        if(request()->ajax()) {
            $query = Complain::query()->orderBy('created_at', 'desc');
            return $this->getQuery($query);
        }

        return view('architect.complain.index');
    }

    public function getQuery($query)
    {
        return DataTables::of($query)
            ->addColumn('edit', function (Complain $complain) {
                return view('architect.datatables.form-edit', ['model' => $complain, 'route' => 'complain']);
            })
            ->addColumn('class', function (Complain $complain) {
                return Carbon::now()->diffInMinutes($complain->created_at) <= 1 ? true : false;
            })
            ->editColumn('id', function (Complain $complain) {
                return "<a href='" . route('complain.show', $complain->id) . "' class='btn-link'>" . $complain->getComplainNumber() . "</a>";
            })
            ->editColumn('desc', function (Complain $complain) {
                return $complain->desc ?? $complain->activities()->where('subject_id', $complain->id)->first()->properties['attributes']['desc'];
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
            ->editColumn('order_datetime', function (Complain $complain) {
                return Carbon::parse($complain->order_datetime)->toDayDateTimeString();
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
            ->rawColumns(['edit', 'ticket_status_id', 'issue_id', 'id'])
            ->toJson();
    }

    public function export()
    {
        return Excel::download(new ComplainExport, 'complains.xlsx');
    }

    public function showSearch()
    {
        return view('architect.complain.search');
    }

    public function search(Request $request)
    {
        if(request()->ajax() && $request->isMethod('post')) {
            $q = ltrim($request->q, "0");
            $query = Complain::where("id", "like", "%$q%")->get();
            return $this->getQuery($query);
        } else {
            return abort(403);
        }
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_number' => ['required', 'numeric'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'informed_to' => ['nullable', 'string'],
            'informed_by' => ['nullable', 'string'],
            'order_id' => ['nullable'],
            'order_number' => ['nullable'],
            'order_datetime' => ['nullable', 'date'],
            'outlet_id' => ['required', 'exists:outlets,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'issue_id' => ['array', 'required', 'exists:issues,id'],
            'message_recipient_id' => ['array', 'nullable', 'exists:message_recipients,id'],
            'ticket_status_id' => ['required', 'exists:ticket_statuses,id'],
            'complain_source_id' => ['required', 'exists:complain_sources,id'],
        ]);

        if($request->customer_id !== null) {
            $customer = Customer::findOrFail($request->customer_id);
        } else {
            $customer = Customer::create([
                'name' => $request->customer_name,
                'number' => $request->customer_number
            ]);
        }

        $newComplains = [];

        foreach ($request->issue_id as $key => $item) {
            $complain = new Complain();
            $complain->title = $request->title;
            $complain->order_id = $request->order_id;
            $complain->order_datetime = Carbon::parse($request->order_datetime);
            $complain->promised_time = Carbon::parse($request->promised_time);
            $complain->outlet_id = $request->outlet_id;
            $complain->ticket_status_id = $request->ticket_status_id;
            $complain->user_id = Auth::user()->id;
            $complain->desc = $request->get("desc_$item");
            $complain->remarks = $request->remarks;
            $complain->informed_to = $request->informed_to;
            $complain->informed_by = $request->informed_by;
            $complain->customer()->associate($customer);
            $complain->complain_source()->associate($request->complain_source_id);
            $complain->save();

            $complain->issues()->sync([$item]);
            $complain->message_recipients()->sync($request->message_recipient_id);

            $newComplains[$key] = $complain->getComplainNumber();

            event(new SendSMSEvent($complain));
        }

        return redirect()->route('complain.index')->with('status', "Complain(s) have been created with number(s): " . collect($newComplains)->implode(", "));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Backend\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function show(Complain $complain)
    {
        return view('architect.complain.show', compact('complain'));
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
     * @param  Complain  $complain
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Complain $complain)
    {
        $request->validate([
            "ticket_status_id" => ["required", "exists:ticket_statuses,id"],
        ]);

        $complain->remarks = $request->remarks;
        $complain->desc = $request->desc;
        $complain->ticket_status()->associate($request->ticket_status_id);
        $complain->save();

        event(new SendSMSEvent($complain));

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
