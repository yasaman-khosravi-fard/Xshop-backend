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

    public function createProduct(Request $request)
    {
        $product = Product::create([
            'title' => $request->title,
            'price' => $request->price,
            'description' => $request->description,
        ]);
        $images = $request->images;
        if ($images) {
            foreach ($images as $image) {
                $path = "/storage/" . $image->store('products', 'public');
                $product->images()->create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'main' => true,
                ]);
            }

        }
        return response()->json(['code'=>200 ,'product'=>$product], 200);

    }


    public function showProduct($id){
        $product = Product::with(['images'=> function ($query) {
            $query->where('main', true);
        }])->findOrFail($id);
        return response()->json($product, 200);
    }


}
