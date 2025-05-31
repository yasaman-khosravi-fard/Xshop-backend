<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {

        $products = Product::with(['images' => function ($query) {
            $query->where('main', true);
        }])->get();

        return response()->json($products, 200);
    }


}
