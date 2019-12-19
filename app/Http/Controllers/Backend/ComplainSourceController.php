<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\ComplainSource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ComplainSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Builder $builder)
    {
        $query = ComplainSource::query();
        if(request()->ajax()) {
            return DataTables::eloquent($query)
                ->addColumn('edit', function (ComplainSource $complainSource) {
                    return view('architect.datatables.form-edit', ['model' => $complainSource, 'route' => 'complainSource']);
                })
                ->addColumn('delete', function (ComplainSource $complainSource) {
                    return view('architect.datatables.form-delete', ['model' => $complainSource, 'route' => 'complainSource']);
                })
                ->editColumn('created_at', function (ComplainSource $complainSource) {
                    return Carbon::parse($complainSource->created_at)->diffForHumans();
                })
                ->rawColumns(['edit', 'status', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => '#'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'description', 'title' => 'Description'],
            ['data' => 'created_at', 'title' => 'Created At'],
            ['data' => 'edit', 'title' => ''],
            ['data' => 'delete', 'title' => '']
        ]);
        return view('architect.source.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('architect.source.create');
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
            'name' => ['required', 'unique:complain_sources,name'],
        ]);

        $source = ComplainSource::create($request->all());
        return redirect()->route('source.index')->with('success', "Complain source $source->name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ComplainSource  $complainSource
     * @return \Illuminate\Http\Response
     */
    public function show(ComplainSource $complainSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ComplainSource  $complainSource
     * @return \Illuminate\Http\Response
     */
    public function edit(ComplainSource $complainSource)
    {
        return view('architect.source.edit', compact('complainSource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ComplainSource  $complainSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComplainSource $complainSource)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('complain_sources')->ignoreModel($complainSource)]
        ]);

        $complainSource->update($request->all());
        return redirect()->route('complainSource.index')->with('success', "Complain source updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ComplainSource  $complainSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComplainSource $complainSource)
    {
        //
    }
}
