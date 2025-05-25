<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function products(Request $request)
    {
        $search = $request->get('term');

        $query = Product::where('count', '>', 0)
            ->select('id', 'name', 'count', 'expense');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->limit(20)->get();
    }
}
