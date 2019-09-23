<?php

namespace App\Http\Controllers\Backend;

use App\Category;
use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function searchCustomer(Request $request)
    {
        $query = $request->q;
        $results = Customer::where("name", "like", "%$query%")->get();
        return response()->json($results);
    }

    public function searchIssue(Request $request)
    {
        try {
            $category = Category::findOrFail($request->category);
            return response()->json($category->issues()->get());
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 401);
        }
    }
}
