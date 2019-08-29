<?php

namespace App\Http\Controllers\Backend;

use App\Complain;
use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = Customer::query();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (Customer $customer) {
                    return view('architect.datatables.form-edit', ['model' => $customer, 'route' => 'customer']);
                })
                ->addColumn('delete', function (Customer $customer) {
                    $route = 'customers';
                    return view('architect.datatables.form-delete', ['model' => $customer, 'route' => 'customer']);
                })
                ->order(function ($query) {
                    $query->orderBy('created_at', 'desc');
                })
                ->rawColumns(['edit', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'number', 'title' => 'Number'],
            ['data' => 'created_at', 'title' => 'Created'],
            ['data' => 'updated_at', 'title' => 'Updated'],
            ['data' => 'edit', 'title' => ''],
            ['data' => 'delete', 'title' => ''],
        ]);

        return view('architect.customer.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return dd($request);
        $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'numeric'],
            'active' => ['nullable', 'in:on']
        ]);

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->number = $request->number;
        $customer->active = $request->active == "on" ? true : false;
        $customer->save();
        return redirect()->route('customer.index')->with('status', 'Customer has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('architect.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'numeric'],
            'active' => ['nullable', 'in:on']
        ]);

        $customer->name = $request->name;
        $customer->number = $request->number;
        $customer->active = $request->active == "on" ? true : false;
        $customer->update();

        return redirect()->route('customer.index')->with('status', 'Customer has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if($customer->complains()->count() > 0) {
            $complains = $customer->complains->map(function (Complain $complain) {
                return $complain->getComplainNumber();
            });
            return redirect()->route('customer.index')->with('failure', "This customer $customer->name has complains associated with them. Please disassociate them before deleting.")->with('links', $complains);
        }

        try {
            $customer->delete();
        } catch (\Exception $e) {
            return redirect()->route('customer.index')->with('failure', "Customer $customer->name deletion failed.");
        }

        return redirect()->route('customer.index')->with("status", "Customer $customer->name has been deleted.");
    }
}
