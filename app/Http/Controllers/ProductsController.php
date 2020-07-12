<?php

namespace App\Http\Controllers;

use App\Category;
use App\Menu;
use App\MenuItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{

    public function index(Request $request)
    {
        $category = Category::all();

        if (empty($request->input('q'))) {
            $products = Product::with('category');
            if (\request('active')) {
                $isActive = \request('active');
                if ($isActive == 'true') {
                    $isActive = 1;
                    $products = $products->where('is_active', '=', $isActive);
                } else if ($isActive == 'false') {
                    $isActive = false;
                    $products = $products->where('is_active', '=', $isActive);
                }
            }
            $products = $products->paginate(10);
        } else {
            $q = $request->input('q');
            $products = Product::with('category')
                ->where('name', 'LIKE', "%{$q}%");
            if (\request('active')) {
                $isActive = \request('active');
                $products = $products->orWhere('is_active', '=', $isActive);
            }
            $products = $products->orderBy("id", "desc")
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
            ->where('is_active', '=', 1)
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
        $isNew = false;
        if ($request->id && $request->id > 0) {
            $prod = Product::find($request->id);
        } else {
            $find = Product::where('name', '=', $request->name)->get();
            if (count($find) > 0) {
                return response()->json(['error' => 'Product already exist.'], 200);
            }
            $prod = new Product();
            $prod->qty = $request->original_qty;
            $isNew = true;
        }
        $prod->name = $request->name;
        $prod->unit_measure = $request->unit_measure;
        $prod->category_id = $request->category_id;
        $prod->original_qty = $request->original_qty;
        $prod->price = $request->price;
        $prod->cost = $request->cost;
        $prod->is_active = $request->is_active;
        $prod->save();

        if ($prod->category_id == Category::$DRINK) {
            if ($isNew) {
                $this->createMenu($prod);
            }
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
        $menu->category_id = Category::$DRINK;
        $menu->save();

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menu->id;
        $menuItem->qty = 1;
        $menuItem->product_id = $prod->id;
        $menuItem->cost = $prod->cost;
        $menuItem->save();
    }


}
