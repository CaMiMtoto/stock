@extends('layouts.master')
@section('title','Sales report')
@section('content')

    <?php
    $totalAmount=0;
    ?>
    <section class="content">

        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <!-- this row will not appear when printing -->
                <div class="no-print pull-right">
                    <div class="col-xs-12">
                        <button onclick="printDoc()" class="no-print btn btn-warning">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i>
                        Stock
                        <small class="pull-right">Date: {{ now()->format('D , d/m/Y') }}</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong>{{ \Carbon\Carbon::parse($start_date)->format('l , d M Y') }}</strong><br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>{{ \Carbon\Carbon::parse($end_date)->format('l , d M Y') }}</strong><br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <h5>Sales reports</h5>
                    <table class="table table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sales as $sale)
                            <?php
                            $totalAmount+=$sale->sum('price')* $sale->qty;
                            ?>
                            <tr>
                                <td>{{ $sale->created_at }}</td>
                                <td>{{ $sale->menu->name}}</td>
                                <td>{{ $sale->qty}}</td>
                                <td>{{ number_format($sale->sum('price')) }}</td>
                                <td>{{ number_format($sale->sum('price')* $sale->qty) }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total amount</th>
                            <th>{{ number_format($totalAmount) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


        </section>
    </section>
@endsection
@section('scripts')
    <script>
        $('.mn-reports').addClass('active');
    </script>
@endsection
