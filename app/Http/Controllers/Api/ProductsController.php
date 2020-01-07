<?php

namespace App\Http\Controllers\Api;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    public function getProducts(Request $request)
    {
        $q = $request->input('query');

        $products = Product::with('category')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where([
                ['products.name', 'LIKE', "%{$q}%"]
            ])
            ->select('products.*')
            ->orderBy("products.id", "desc")
            ->get();

        return response()->json($products, 200);
    }
}
