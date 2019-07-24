<?php

namespace App\Http\Controllers\Backend;

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
        $query = Customer::all();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (Customer $customer) {
                    return view('architect.datatables.form-edit', ['model' => $customer, 'route' => 'customer']);
                })
                ->addColumn('delete', function (Customer $customer) {
                    $route = 'customers';
                    return view('architect.datatables.form-delete', ['model' => $customer, 'route' => 'customer']);
                })
                ->rawColumns(['edit', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
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

        $customer = Customer::create($request->all());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
        } catch (\Exception $e) {
            return redirect()->route('customer.index')->with('failure', "Customer $customer->name deletion failed.");
        }

        return redirect()->route('customer.index')->with("status", "Customer $customer->name has been deleted.");
    }
}
