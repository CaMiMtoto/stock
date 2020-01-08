@extends('layouts.master')
@section('title','Requisitions item report')
@section('content')
    <section class="content">
        <div class="col-12">
            <form action="{{ route('requestedItemsReports') }}" autocomplete="off" class="form-inline no-print">
                <div class="form-group">
                    <input type="text" placeholder="Start Date" name="startDate" class="datepicker form-control">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control datepicker" name="endDate" placeholder="End date">
                </div>
                <button class="btn btn-default">Go</button>
            </form>
            <br>
            <div class="clearfix"></div>
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        Requests { {{ $items->count() }} }
                         from {{ $startDate }} to  {{ $endDate }}
                    </h4>
                    <div class="box-tools">

                        <button onclick="window.print();" class="btn btn-primary btn-sm no-print">
                            <i class="fa fa-print"></i>
                            Print
                        </button>
                    </div>
                </div>
<?php $qty=0;$price=0; ?>
                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <?php
                            $qty+=$item->totalQty;
                            $price+=$item->totalPrice;
                            ?>
                            <tr>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->totalQty }}</td>
                                <td>{{ number_format($item->totalPrice) }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>

                           <tr>
                               <th></th>
                               <th></th>
                               <th>{{ $qty }}</th>
                               <th>{{ number_format($price) }}</th>
                           </tr>

                        </tfoot>
                    </table>

                </div>
            </div>

        </div>

    </section>


@endsection

@section('scripts')
@endsection
