<?php

namespace App\Http\Controllers\Backend;

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
}
