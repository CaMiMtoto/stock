@extends('layouts.master')
@section('title','Edit order')
@section('styles')
@endsection

@section('content')
    <section class="content">

        <div class="col-12">
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            <i class="fa fa-edit"></i> Edit order
                        </h4>
                    </div>
                </div>
                <form action="">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_date" class="control-label">Date</label>
                                    <input type="text" class="form-control datepicker" name="order_date" id="order_date"
                                           placeholder="Order Date" value="{{ $order->order_date }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_name" class="control-label">Customer</label>
                                    <input type="text" class="form-control" name="customer_name" id="customer_name"
                                           placeholder="Customer name" value="{{ $order->customer_name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="waiter" class="control-label">Waiter</label>
                                    <input type="text" class="form-control" name="waiter" id="waiter"
                                           placeholder="Waiter" value="{{ $order->waiter }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="menu" class="control-label">Menu</label>
                                    <select name="menu" class="form-control" id="menu">
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qty" class="control-label">Quantity</label>
                                    <input type="text" class="form-control" name="qty" placeholder="Quantity" id="qty">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="price" class="control-label">Price</label>
                                    <input type="text" class="form-control" id="price" placeholder="Price"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="qty" class="control-label"></label><br>
                                    <button class="btn btn-primary" type="button">Add New Order</button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @foreach($order->orderItems as  $item)
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="menu" class="form-control">
                                            @foreach($menus as $menu)
                                                <option
                                                    value="{{ $menu->id }}" {{ $menu->id==$item->menu_id?'selected':'' }}>
                                                    {{ $menu->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="qty" value="{{ $item->qty }}"
                                               placeholder="Qty">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="price" value="{{ $item->price }}"
                                               placeholder="Price">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_paid">Amount Paid</label>
                                    <input type="text" name="amount_paid" class="form-control" value="{{ $order->amount_paid }}" id="amount_paid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_mode">Payment Method</label>
                                    <select name="payment_mode" id="payment_mode" class="form-control">
                                        <option value=""></option>
                                        <option value="Cash" {{ $order->payment_mode=='Cash'?'selected':'' }}>Cash</option>
                                        <option value="Card" {{ $order->payment_mode=='Card'?'selected':'' }}>Card</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="delivered">Delivery</label>
                                    <select name="delivered" class="form-control" id="delivered">
                                        <option value=""></option>
                                        <option value="1" {{ $order->delivered==1?'selected':'' }}>Delivered</option>
                                        <option value="0"  {{ $order->delivered==0?'selected':'' }}>Not Delivered</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-success">
                            <i class="fa fa-check-circle"></i>
                            Update order
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </section>


@endsection

@section('scripts')
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
