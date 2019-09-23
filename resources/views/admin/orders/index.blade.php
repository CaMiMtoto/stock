@extends('layouts.master')
@section('title','Orders')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .modal-header {
            padding: 15px;
            border-bottom: 1px solid transparent;
        }
    </style>
@endsection

@section('content')
    <section class="content">
        {{--        <div id="app">
                    <add-order-component></add-order-component>
                </div>--}}
        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">Add New Order</a>
        <div class="col-12">
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            Orders
                        </h4>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Amount To Pay</th>
                            <th>Amount Paid</th>
                            <th>Amount Due</th>
                            <th>Py Method</th>
                            <th>Delivered</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ number_format($order->totalOrderPrice()) }}</td>
                                <td>{{ number_format($order->amount_paid) }}</td>
                                <td>{{ number_format($order->amountDue()) }}</td>
                                <td>{{ $order->payment_mode }}</td>
                                <td>
                                    @if($order->received==1)
                                        <span class="label label-success">Yes</span>
                                    @else
                                        <span class="label label-warning">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->status=='Full')
                                        <span class="label label-success">{{ $order->status }}</span>
                                    @elseif($order->status=='Advance')
                                        <span class="label label-warning">{{ $order->status }}</span>
                                    @else
                                        <span class="label label-danger">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.edit',['id'=>$order->id]) }}"
                                       class="btn btn-default btn-sm ">
                                        <i class="glyphicon glyphicon-edit"></i>
                                        Edit
                                    </a>
                                    <button
                                        data-url="{{ route('orders.orderDetails',['id'=>$order->id]) }}"
                                        class="btn btn-primary btn-sm js-details">
                                        Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {{ $orders->links() }}
                </div>
            </div>

        </div>

    </section>



    <div class="modal fade " tabindex="-1" role="dialog" id="detailsModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                    @include('layouts._loader')
                    <div id="message"></div>

                    <div class="edit-result">
                        <div id="itemsDiv"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>

        $(function () {
            $('.mn-orders').addClass('active');

            $('.js-details').on('click', function () {
                var url = $(this).attr('data-url');
                $('#detailsModal').modal('show');

                showLoader();
                $.ajax({
                    url: url,
                    method: 'GET',
                    // dataType: 'text/html'
                }).done(function (data) {
                    hideLoader();
                    $('#itemsDiv').html(data);
                });
            });
        });

    </script>
@endsection
