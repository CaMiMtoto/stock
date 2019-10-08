@extends('layouts.master')

@section('title','Product History')

@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h4>Product History
                    <small>{{ $date }}</small>

                    <button class="btn btn-primary pull-right btn-sm no-print" onclick="window.print()">
                        <i class="fa fa-print"></i>
                        Print
                    </button>
                </h4>
            </div>
            <div class="box-body">
                <table class="table">
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
                    @foreach($movements as $mov)
                        <tr>
                            <td>{{ $mov->product->name }}</td>
                            <td>{{ number_format($mov->opening ) }}</td>
                            <td>{{ number_format($mov->received()) }}</td>
                            <td>{{ number_format($mov->available())  }}</td>
                            <td>{{ number_format($mov->used($date))  }}</td>
                            <td>{{ number_format($mov->closing) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </section>
@endsection
