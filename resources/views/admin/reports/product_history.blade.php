@extends('layouts.master')

@section('title','Product History')

@section('content')


    <section class="content">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4>
                    Product History
                    <small>{{ $date }}</small>
                </h4>
                <div class="box-tools">
                    <button class="btn btn-primary btn-sm no-print border-10px" onclick="window.print()">
                        <i class="fa fa-print"></i>
                        Print Report
                    </button>
                </div>

            </div>
            <div class="box-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="6">
                            <h4>{{$category}}</h4>
                        </th>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <th>Opening</th>
                        <th>Received</th>
                        <th>Sold</th>
                        <th>Total avail.</th>
                        <th>Closing</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $item)
                        <?php
                        $totalPreviousStockQty = App\ReportData::getPreviousStockQty($date, $categoryId, $item->id);
                        if ($categoryId == App\Category::$DRINK) {
                            $totalPreviousSoldQty = App\ReportData::getPreviousDrinksSoldQty($date, $item->id);
                            $sold = App\ReportData::getDrinkSoldToday($date, $item->id);
                        } else {
                            $totalPreviousSoldQty = App\ReportData::getPreviousFoodSoldQty($date, $item->id);
                            $sold = App\ReportData::getFoodSoldToday($date, $item->id);
                        }
                        $opening = $totalPreviousStockQty - $totalPreviousSoldQty;
                        $received = App\ReportData::getReceivedQty($date, $categoryId, $item->id);

                        $total = $opening + $received;
                        ?>
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $opening }}</td>
                            <td>{{$received}}</td>
                            <td>{{ $sold }}</td>
                            <td>{{ $total }}</td>
                            <td>{{ $total-$sold }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
