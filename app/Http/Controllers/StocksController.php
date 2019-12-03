<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocksController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->get();

        if (empty($request->input('q'))) {
            $stocks = Stock::with('product')->orderBy('id', 'desc')
                ->orderBy("created_at", "desc")
                ->paginate(10);
        } else {
            $q = $request->input('q');
            $stocks = Stock::join('products', 'products.id', '=', 'stocks.product_id')
                ->where('products.name', 'LIKE', "%{$q}%")
                ->orderBy("stocks.created_at", "desc")
                ->select("stocks.*")
                ->paginate(10);
//           dd($stocks);
            $stocks->appends(['q' => $q]);
        }
        return view('admin.stocks', ['stocks' => $stocks, 'products' => $products]);
    }

    public function store(Request $request)
    {
        if ($request->id && $request->id > 0) {
            $stock = Stock::with('product')->find($request->id);
            if ($stock) {

                // update stock product qty
                $this->updateProductQtyWhenEditStockItem($request, $stock);

                $stock->product_id = $request->product_id;
                $stock->qty = $request->qty;
                $stock->price = $request->price;
                $stock->update();
            }
        } else {
            $stock = new Stock();
            $stock->product_id = $request->product_id;
            $stock->qty = $request->qty;
            $stock->price = $request->price;
            $stock->save();
            // update stock product qty
            $this->updateProductQtyWhenStockingItem($stock);
        }
        return response()->json($stock, 200);
    }

    /**
     * @param Request $request
     * @param $stock
     */
    private function updateProductQtyWhenEditStockItem(Request $request, $stock): void
    {
        $prevSupQty = $stock->qty;

        $newSupQty = $request->qty;
        $prodQty = $stock->product->qty;
        $newProdQty = ($prodQty - $prevSupQty) + $newSupQty;

        $stock->product->qty = $newProdQty;
        $stock->product->update();
    }

    /**
     * @param Stock $stock
     */
    public function updateProductQtyWhenStockingItem(Stock $stock): void
    {
     /*   if ($stock->product->original_qty == 0) {
            $stock->product->original_qty = $stock->qty;
        }*/
        $stock->product->qty += $stock->qty;
        $stock->product->update();
    }

    public function show(Stock $stock)
    {
        return response()->json($stock, 200);
    }


    public function destroy(Stock $stock)
    {
        $stock->delete();
        return response()->json(null, 204);
    }


}
