<?php

namespace App\Http\Controllers\Backend;

use App\Customer;
use App\Rating;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            $query = Rating::query()->orderBy('created_at', 'desc');
            return $this->getQuery($query);
        }
        return view('architect.rating.index');
    }

    public function getQuery($query)
    {
        return DataTables::of($query)
            ->addColumn('edit', function (Rating $rating) {
                return view('architect.datatables.form-edit', ['model' => $rating, 'route' => 'rating']);
            })
            ->addColumn('class', function (Rating $rating) {
                return Carbon::now()->diffInMinutes($rating->created_at) <= 1 ? true : false;
            })
            ->editColumn('id', function (Rating $rating) {
                return $rating->getRatingNumber();
            })
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
    }

    public function search(Request $request)
    {
        if(request()->ajax() && $request->isMethod('post')) {
            $q = ltrim($request->q, "0");
            $query = Rating::where("id", "like", "%$q%")->get();
            return $this->getQuery($query);
        } else {
            return abort(403);
        }
    }

    public function showSearch()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.rating.create');
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
            'informed_to' => ['required', 'string'],
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

        $rating = new Rating();
        $rating->title = $request->title;
        $rating->order_id = $request->order_id;
        $rating->outlet_id = $request->outlet_id;
        $rating->ticket_status_id = $request->ticket_status_id;
        $rating->user_id = Auth::user()->id;
        $rating->customer_id = $customer->id;
        $rating->desc = $request->desc;
        $rating->remarks = $request->remarks;
        $rating->informed_to = $request->informed_to;
        $rating->save();

        $rating->issues()->sync($request->issue_id);

        return redirect()->route('rating.index')->with('status', "Rating has been created with number: $rating->id");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Rating $rating)
    {
        return view('architect.rating.edit', compact('rating'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_number' => ['required'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'informed_to' => ['required', 'string'],
            'order_id' => ['nullable'],
            'outlet_id' => ['required', 'exists:outlets,id'],
            'issue_id' => ['array', 'required', 'exists:issues,id'],
            'ticket_status_id' => ['required', 'exists:ticket_statuses,id']
        ]);

        if($request->customer_name !== $rating->customer->name ||
            $request->customer_number !== $rating->customer->number) {
            // Update Customer

            $customer = Customer::findOrFail($request->customer_id);
            $customer->name = $request->customer_name;
            $customer->number = $request->customer_number;
            $customer->save();
        }

        $rating->title = $request->title;
        $rating->order_id = $request->order_id;
        $rating->outlet_id = $request->outlet_id;
        $rating->ticket_status_id = $request->ticket_status_id;
        $rating->user_id = Auth::user()->id;
        $rating->customer_id = $request->customer_id;
        $rating->desc = $request->desc;
        $rating->remarks = $request->remarks;
        $rating->informed_to = $request->informed_to;
        $rating->update();

        $rating->issues()->sync($request->issue_id);

        return redirect()->route('rating.index')->with('status', "Rating complain# $rating->id has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
