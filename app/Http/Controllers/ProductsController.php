<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function index(Request $request)
    {
        $category = Category::all();

        if (empty($request->input('q'))) {
            $products = Product::with('category')
                ->paginate(10);
        } else {
            $q = $request->input('q');
            $products = Product::with('category')
                ->where('name', 'LIKE', "%{$q}%")
                ->orderBy("id", "desc")
                ->paginate(10);
            $products->appends(['q' => $q]);
        }
        return view('admin.products', ['products' => $products, 'category' => $category]);
    }

    public function getProducts(Request $request)
    {
        $q = $request->input('query');
        $products = Product::where('name', 'LIKE', "%{$q}%")
            ->orderBy("id", "desc")->get();
        return response()->json($products, 200);
    }


    public function store(Request $request)
    {
        if ($request->id && $request->id > 0) {
            $prod = Product::find($request->id);
            if ($prod) {
                $prod->name = $request->name;
                $prod->unit_measure = $request->unit_measure;
                $prod->category_id = $request->category_id;
                $prod->update();
            }
        } else {
            $prod = new Product();
            $prod->name = $request->name;
            $prod->unit_measure = $request->unit_measure;
            $prod->category_id = $request->category_id;
            $prod->save();
        }
        return response()->json($prod, 200);
    }


    public function byCategory($categoryId)
    {
        $product = Product::where([
            ['category_id', '=', $categoryId],
            ['qty', '>', 0]
        ])->get();
        return response()->json($product, 200);
    }

    public function show(Product $product)
    {
        return response()->json($product, 200);
    }


    public function destroy(product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
