<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', Rule::unique('categories', 'name')->ignoreModel($category)],
            'status' => ['nullable', 'in:on']
        ]);

        $category->update($request->all());
        return redirect()->route('category.index')->with('status', "Category $category->name has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->issues()->count() > 0) {
            return redirect()->route('category.index')->with('failure', "This category $category->name has multiple issues assigned to it. First unassign them before procedding to deletion.");
        }
        try {
            $category->delete();
            return redirect()->route('category.index')->with("status", "Category $category->name has been deleted.");
        } catch (\Exception $exception) {
            return redirect()->route('category.index')->with("failure", "Category $category->name deletion failed.");
        }
    }
}
