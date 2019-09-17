@extends('layouts.master')
@section('title','Reports')
@section('content')
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
    <?php
    $array1 = array();
    ?>
    <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <h4>Expenses</h4>
                <table class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date }}</td>
                            <td>{{ number_format($expense->amount) }}</td>
                            <td>{{ $expense->description}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>{{ number_format($expenses->sum('amount')) }}</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


    </section>
@endsection
@section('scripts')
    <script>
        $('.mn-reports').addClass('active');
    </script>
@endsection
