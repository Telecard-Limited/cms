<?php

namespace App\Http\Controllers\Backend;

use App\Outlet;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = Outlet::query()->orderBy('created_at', 'desc');
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (Outlet $outlet) {
                    return view('architect.datatables.form-edit', ['model' => $outlet, 'route' => 'outlet']);
                })
                ->addColumn('delete', function (Outlet $outlet) {
                    return view('architect.datatables.form-delete', ['model' => $outlet, 'route' => 'outlet']);
                })
                ->editColumn('active', 'architect.datatables.form-active')
                ->editColumn('created_at', function (Outlet $outlet) {
                    return Carbon::parse($outlet->created_at)->diffForHumans();
                })
                ->rawColumns(['edit', 'active', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'active', 'title' => 'Status'],
            ['data' => 'city', 'title' => 'City'],
            ['data' => 'created_at', 'title' => 'Created'],
            ['data' => 'updated_at', 'title' => 'Updated'],
            ['data' => 'edit', 'title' => ''],
            ['data' => 'delete', 'title' => '']
        ]);
        return view('architect.outlet.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.outlet.create');
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
            'name' => 'required', 'string', 'unique:outlets,name',
            'active' => 'in:on',
            'city' => ['required', 'string'],
            'desc' => ['nullable']
        ]);

        $outlet = Outlet::create([
            'name' => $request->name,
            'active' => $request->has('active') && $request->active == "on" ? true : false,
            'desc' => $request->desc ?: null,
            'city' => $request->city
        ]);
        return redirect()->route('outlet.index')->with('status', 'Outlet has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function show(Outlet $outlet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function edit(Outlet $outlet)
    {
        return view('architect.outlet.edit', compact('outlet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'required', 'string', Rule::unique('outlets', 'name')->ignore($outlet->id, 'name'),
            'active' => ['nullable', 'in:on'],
            'city' => ['required', 'string'],
            'desc' => ['nullable']
        ]);

        $outlet->update([
            'name' => $request->name,
            'active' => $request->has('active') && $request->active == "on" ? true : false,
            'city' => $request->city,
            'desc' => $request->desc ?: null
        ]);
        return redirect()->route('outlet.index')->with('status', 'Outlet has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        try {
            $outlet->delete();
        } catch (\Exception $e) {
            return redirect()->route('outlet.index')->with('failure', 'Outlet deletion failed with reason: ' . $e->getMessage());
        }

        return redirect()->route('outlet.index')->with('status', 'Outlet has been deleted.');
    }
}
