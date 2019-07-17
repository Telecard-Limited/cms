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
        $query = Outlet::all();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (Outlet $outlet) {
                    $route = route('outlet.edit', $outlet->id);
                    return "<a href='$route' class='mb-2 mr-2 btn-icon btn btn-primary'><i class='pe-7s-tools btn-icon-wrapper'></i> Edit</a>";
                })
                ->editColumn('active', function (Outlet $outlet) {
                    $outletText = $outlet->active == true ? "Active" : "Inactive";
                    $badge = $outlet->active  == true ? "success" : "danger";
                    return "<a href='javascript:void(0)' class='mb-2 mr-2 badge badge-$badge ? success : danger'>$outletText</a>";
                })
                ->editColumn('created_at', function (Outlet $outlet) {
                    return Carbon::parse($outlet->created_at)->diffForHumans();
                })
                ->rawColumns(['edit', 'active'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'active', 'title' => 'Status'],
            ['data' => 'created_at', 'title' => 'Created At'],
            ['data' => 'edit', 'title' => '']
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
            'active' => 'in:on'
        ]);

        $outlet = Outlet::create([
            'name' => $request->name,
            'active' => $request->has('active') && $request->active == "on" ? true : false,
            'desc' => $request->desc ?: null
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
            'active' => 'in:on'
        ]);

        $outlet->update([
            'name' => $request->name,
            'active' => $request->has('active') && $request->active == "on" ? 1 : 0
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
        //
    }
}
