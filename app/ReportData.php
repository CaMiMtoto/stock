<?php


namespace App;


class ReportData
{
    public static function getPreviousStockQty($date, $categoryId,$productId)
    {
        return Stock::with('product')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->whereDate('stocks.created_at', '<', $date)
            ->where([
                ['products.category_id', '=', $categoryId],
                ['products.id', '=', $productId],
            ])
            ->sum('stocks.qty');
    }

    public static function getPreviousFoodSoldQty($date,$productId)
    {
        return OrderItem::with('menu')
            ->join('menus', 'order_items.menu_id', 'menus.id')
            ->join('menu_items', 'menu_items.menu_id', 'menus.id')
            ->join('products', 'menu_items.product_id', 'products.id')
            ->whereDate('order_items.created_at', '<', $date)
            ->where('products.category_id', '=', Category::$FOOD)
            ->sum('order_items.qty');
    }

    public static function getPreviousDrinksSoldQty($date,$productId)
    {
        return ProductOrderItem::with('product')
            ->join('products', 'product_order_items.product_id', 'products.id')
            ->whereDate('product_order_items.created_at', '<', $date)
            ->where([
                ['products.category_id', '=', Category::$DRINK],
                ['products.id', '=', $productId],
            ])
            ->sum('product_order_items.qty');
    }

    public static function getReceivedQty($date, $categoryId,$productId)
    {
        return Stock::with('product')
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->whereDate('stocks.created_at', '=', $date)
            ->where([
                ['products.category_id', '=', $categoryId],
                ['products.id', '=', $productId],
            ])
            ->sum('stocks.qty');
    }

    public static function getFoodSoldToday($date,$productId)
    {
        return OrderItem::with('menu')
            ->join('menus', 'order_items.menu_id', 'menus.id')
            ->join('menu_items', 'menu_items.menu_id', 'menus.id')
            ->join('products', 'menu_items.product_id', 'products.id')
            ->whereDate('order_items.created_at', '=', $date)
            ->where([
                ['products.category_id', '=', Category::$FOOD],
                ['products.id', '=', $productId],
            ])
            ->sum('order_items.qty');
    }

    public static function getDrinkSoldToday($date,$productId)
    {
        return ProductOrderItem::with('product')
            ->join('products', 'product_order_items.product_id', 'products.id')
            ->whereDate('product_order_items.created_at', '=', $date)
            ->where([
                ['products.category_id', '=', Category::$DRINK],
                ['products.id', '=', $productId],
            ])
            ->sum('product_order_items.qty');
    }

}
