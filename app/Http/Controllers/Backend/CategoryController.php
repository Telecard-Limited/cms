<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = Category::query();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (Category $category) {
                    $route = route('category.edit', $category->id);
                    return "<a href='$route' class='mb-2 mr-2 btn-icon btn btn-primary'><i class='pe-7s-tools btn-icon-wrapper'></i> Edit</a>";
                })
                ->addColumn('delete', function (Category $category) {
                    $route = route('category.destroy', $category->id);
                    return "<a href='$route' class='mb-2 mr-2 btn-icon btn btn-danger'><i class='pe-7s-tools btn-icon-wrapper'></i> Delete</a>";
                })
                ->editColumn('status', function (Category $category) {
                    $outletText = $category->status == true ? "Active" : "Inactive";
                    $badge = $category->status  == true ? "success" : "danger";
                    return "<a href='javascript:void(0);' class='mb-2 mr-2 badge badge-$badge ? success : danger'>$outletText</a>";
                })
                ->editColumn('created_at', function (Category $category) {
                    return Carbon::parse($category->created_at)->diffForHumans();
                })
                ->rawColumns(['edit', 'status', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => '#'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'status', 'title' => 'Status'],
            ['data' => 'created_at', 'title' => 'Created At'],
            ['data' => 'edit', 'title' => ''],
            ['data' => 'delete', 'title' => '']
        ]);
        return view('architect.category.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.category.create');
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
            'name' => ['required', 'unique:categories,name'],
            'status' => ['nullable', 'in:on']
        ]);

        $category = Category::create([
            'name' => $request->name,
            'status' => $request->has('status') && $request->status ? true : false
        ]);
        return redirect()->route('category.index')->with('success', "Category $category->name has been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('architect.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
