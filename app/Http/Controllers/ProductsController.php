<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\MenuItem;
use App\Product;
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
//        $products = Product::where('name', 'LIKE', "%{$q}%")
//            ->orderBy("id", "desc")->get();

        $products = Product::with('category')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where([
                ['categories.name', '=', 'Food'],
                ['products.name', 'LIKE', "%{$q}%"]
            ])
            ->select('products.*')
            ->orderBy("products.id", "desc")
            ->get();

        return response()->json($products, 200);
    }

    public function getAllProducts()
    {
        $products = Product::with('category')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->where('categories.name', '!=', 'Food')
            ->select('products.*')
            ->get();
        return response()->json($products, 200);
    }


    public function store(Request $request)
    {
        if ($request->id && $request->id > 0) {
            $prod = Product::find($request->id);
        } else {
            $prod = new Product();
            $prod->qty = $request->original_qty;
        }
        $prod->name = $request->name;
        $prod->unit_measure = $request->unit_measure;
        $prod->category_id = $request->category_id;
        $prod->original_qty = $request->original_qty;
        $prod->price = $request->price;
        $prod->save();


        if ($prod->category->name=='Drinks'){
            $this->createMenu($prod);

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

    /**
     * @param Product $prod
     */
    private function createMenu(Product $prod): void
    {
        $menu = new Menu();
        $menu->name = $prod->name;
        $menu->price = $prod->price;
        $menu->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->qty = 1;
        $menuItem->product_id = $prod->id;
        $menuItem->cost = $prod->price;
        $menuItem->save();
    }


}
