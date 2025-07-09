<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048' // max 2MB per image
        ]);

        if ($validated->fails()) {
            return response()->json($validated->messages(), 422);
        }

        $product = Product::create([
            'title' => $validated['title'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

        $images = $request->file('images');

        if ($images) {
            foreach ($images as $image) {
                $path = "/storage/" . $image->store('products', 'public');
                $product->images()->create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'main' => true, // You can extend this later to choose main
                ]);
            }
        }

        return response()->json(['code' => 200, 'product' => $product], 200);
    }

    public function showProduct($id)
    {
        $product = Product::with(['images' => function ($query) {
            $query->where('main', true);
        }])->findOrFail($id);

        return response()->json($product, 200);
    }

    public function updateProduct(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->messages(), 422);
        }

        $product = Product::findOrFail($id);

        $product->update([
            'title' => $validated['title'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'quantity' => $validated['quantity'],
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Delete images if needed
        foreach ($product->images as $image) {
            // Storage::disk('public')->delete(str_replace('/storage/', '', $image->image_url));
            $image->delete();
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }
}
