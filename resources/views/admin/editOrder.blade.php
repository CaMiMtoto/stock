@extends('layouts.master')
@section('title','Edit order')
@section('styles')
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: unset;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #d2d6de !important;
            border-radius: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 24px;
        }

    </style>
@endsection

@section('content')
    <section class="content">

        <div class="col-md-10 col-md-offset-1">

            @if(Session::has('error'))
                <div class="flat alert alert-danger">
                    <p>{{ Session::get('error') }}</p>
                </div>
            @endif

            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            <i class="fa fa-plus-circle"></i> Edit order
                        </h4>
                    </div>
                </div>
                <form action="{{ route('productOrders.update',['id'=>$order->id]) }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        @if(Session::has('error'))
                            <div class="alert alert-warning border-10px">
                                <p>
                                    {{ Session::get('error') }}
                                </p>
                            </div>
                        @endif

                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_date" class="control-label">Date</label>
                                    <input type="text" disabled required class="form-control datepicker"
                                           name="order_date"
                                           id="order_date"
                                           placeholder="Order Date" value="{{ $order->created_at->format('d/m/Y') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_name" class="control-label">Customer</label>
                                    <input type="text" class="form-control" name="customer_name"
                                           id="customer_name"
                                           placeholder="Customer name" value="{{ $order->customer_name }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="waiter" class="control-label">Waiter</label>
                                    <select name="waiter" id="waiter" class="form-control">
                                        <option value=""></option>
                                        @foreach($waiters as $waiter)
                                            <option
                                                value="{{ $waiter->id }}" {{ $order->waiter_id==$waiter->id?'selected':'' }}>
                                                {{ $waiter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table class="table table-responsive" id="productTable">
                            <thead>
                            <tr>
                                <th style="width:45%;">Product</th>
                                <th style="width:15%;">Price</th>
                                <th style="width:15%;">Quantity</th>
                                <th style="width:15%;">Total</th>
                                <th style="width:10%;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->productOrderItems as $orderItem)
                                <tr id="row{{$orderItem->id}}">
                                    <td>
                                        <div class="form-group">
                                            <select required onchange="getProductData({{$orderItem->id}})"
                                                    name="product[]" id="product{{$orderItem->id}}"
                                                    class="form-control select2" style="width: 100%">
                                                <option value="">--product--</option>
                                                @foreach($products as $product)
                                                    <option
                                                        value="{{ $product->id }}" {{$orderItem->product_id==$product->id?'selected':''}}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label id="product1-error" class="error"
                                                   for="product{{$orderItem->id}}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required type="text" readonly class="form-control"
                                                   id="rate{{$orderItem->id}}"
                                                   name="rate[]"
                                                   value="{{$orderItem->price}}"
                                                   placeholder="Price">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required type="text" onkeyup="getTotal({{$orderItem->id}})"
                                                   class="form-control"
                                                   id="quantity{{$orderItem->id}}" name="quantity[]"
                                                   value="{{$orderItem->qty}}"
                                                   placeholder="Qty">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required type="text" readonly class="form-control" name="total[]"
                                                   id="total{{$orderItem->id}}"
                                                   value="{{$orderItem->qty * $orderItem->price}}"
                                                   placeholder="Total">
                                        </div>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_to_pay">Amount To Pay</label>
                                    <input required type="text" readonly name="amount_to_pay" class="form-control"
                                           value="{{ $order->amount_to_pay }}"
                                           id="amount_to_pay">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_paid">Amount Paid</label>
                                    <input required min="0" type="number" onkeyup="paidAmount()" name="amount_paid"
                                           class="form-control"
                                           value="{{ $order->amount_paid }}"
                                           id="amount_paid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax">Tax (18%)</label>
                                    <input required type="text" readonly name="tax" class="form-control"
                                           value="{{ $order->tax }}"
                                           id="tax">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="due">Amount Due</label>
                                    <input required type="text" readonly name="due" class="form-control"
                                           value="{{ $order->amountDue() }}"
                                           id="due">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_mode">Payment Method</label>
                                    <select name="payment_mode" id="payment_mode" class="form-control">
                                        <option value=""></option>
                                        <option value="Cash" {{ $order->payment_mode=='Cash'?'selected':'' }}>Cash
                                        </option>
                                        <option value="Card" {{ $order->payment_mode=='Card'?'selected':'' }}>Card
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_status">Payment status </label>
                                    <select required name="payment_status" class="form-control" id="payment_status">
                                        <option value="Full" {{ $order->payment_status=='Full'?'selected':'' }}>Full
                                        </option>
                                        <option value="Not Paid" {{ $order->payment_status=='Not Paid'?'selected':'' }}>
                                            Not Paid
                                        </option>
                                        <option value="Advance" {{ $order->payment_status=='Advance'?'selected':'' }}>
                                            Advance
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" onclick="addRow()" id="addRowBtn"
                                data-loading-text="Loading...">
                            <i class="fa fa-plus"></i> Add Row
                        </button>
                        <button type="reset" class="btn btn-default pull-left">
                            <i class="fa fa-eraser"></i>
                            Reset
                        </button>

                        <button type="submit" class="btn btn-primary createBtn pull-right">
                            <i class="fa fa-check-circle"></i>
                            Save changes
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </section>


@endsection

@section('scripts')
    <script src="{{ asset('js/product_order.js') }}"></script>
    <script>
        $('.tr-orders').addClass('active');
        $('.mn-drinks').addClass('active');
    </script>
@endsection
