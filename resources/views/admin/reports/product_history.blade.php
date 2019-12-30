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
                        <th>Closing</th>
                        <th>Total avail.</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->opening }}</td>
                            <td>{{ $item->received }}</td>
                            <td>{{ $item->sold }}</td>
                            <td>{{ $item->closing }}</td>
                            <td>{{ $item->opening+$item->received }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
