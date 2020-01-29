@extends('layouts.master')

@section('title','Product History')

@section('content')


    <section class="content">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4>
                    Product History
                    <small>{{ $date }}</small>
                    <button class="btn btn-primary pull-right btn-sm no-print" onclick="window.print()">
                        <i class="fa fa-print"></i>
                        Print Report
                    </button>
                </h4>
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
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $item)
                        <?php
                        $totalPreviousStockQty = App\ReportData::getPreviousStockQty($date, $categoryId,$item->id);
                        $totalPreviousSoldQty = App\ReportData::getPreviousDrinksSoldQty($date,$item->id);
                        $opening = $totalPreviousStockQty - $totalPreviousSoldQty;
                        $received = App\ReportData::getReceivedQty($date, $categoryId,$item->id);
                        $sold = App\ReportData::getDrinkSoldToday($date,$item->id);
                        $total = $opening + $received -$sold;

                        ?>
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $opening }}</td>
                            <td>{{$received}}</td>
                            <td>{{ $sold }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
