@extends('layouts.master')

@section('title','Product History')

@section('content')
    <section class="content">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4>Product History
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
                        <th>Product</th>
                        <th>Opening</th>
                        <th>Received</th>
                        <th>Total avail.</th>
                        <th>Sold</th>
                        <th>Closing</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($orderItems as $orderItem)
                        @foreach($orderItem->menu->menuItems->unique() as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ number_format($orderItem->opening($item->product_id,$item->product->qty))  }}</td>
                                <td>{{ number_format(App\Stock::received($date,$item->product_id)) }}</td>
                                <td>{{ number_format($orderItem->available($item->product_id, $item->product->qty,$date))  }}</td>
                                <td>{{ number_format($orderItem->used($item->product_id))  }}</td>
                                <td>{{ number_format($item->product->qty) }}</td>
                            </tr>
                        @endforeach
                        @break(true)
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </section>
@endsection
