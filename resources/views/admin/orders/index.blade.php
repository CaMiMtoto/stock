@extends('layouts.master')
@section('title','Orders')

@section('content')
    <section class="content">
        {{--        <div id="app">
                    <add-order-component></add-order-component>
                </div>--}}
        {{--        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">Add New Order</a>--}}
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default">
            Add New Order
        </button>

        <div class="modal fade myModal" id="modal-default">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">
                            Add New Order
                        </h4>
                    </div>
                    <form action="{{ route('orders.store') }}" method="post" id="submitForm">
                        <div class="modal-body">
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
                                        <input type="text" required class="form-control datepicker" name="order_date"
                                               id="order_date"
                                               placeholder="Order Date" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer_name" class="control-label">Customer</label>
                                        <input type="text" required  class="form-control" name="customer_name"
                                               id="customer_name"
                                               placeholder="Customer name" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="waiter" class="control-label">Waiter</label>
                                        <input type="text" class="form-control" name="waiter" id="waiter"
                                               placeholder="Waiter" value="">
                                    </div>
                                </div>
                            </div>
                            <table class="table table-responsive" id="productTable">
                                <thead>
                                <tr>
                                    <th style="width:45%;">Menu</th>
                                    <th style="width:15%;">Price</th>
                                    <th style="width:15%;">Quantity</th>
                                    <th style="width:15%;">Total</th>
                                    <th style="width:10%;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr id="row1">
                                    <td>
                                        <div class="form-group">
                                            <select required  onchange="getProductData(1)" name="menu[]" id="menu1"
                                                    class="form-control">
                                                <option value="">--menu--</option>
                                                @foreach($menus as $menu)
                                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required  type="text" readonly class="form-control" id="rate1"
                                                   name="rate[]"
                                                   value=""
                                                   placeholder="Price">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required  type="text" onkeyup="getTotal(1)" class="form-control"
                                                   id="quantity1" name="quantity[]" value=""
                                                   placeholder="Qty">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input required  type="text" readonly class="form-control" name="total[]"
                                                   id="total1"
                                                   value=""
                                                   placeholder="Total">
                                        </div>
                                    </td>
                                    <td>

                                   {{--     <button class="btn btn-default removeProductRowBtn" type="button"
                                                id="removeProductRowBtn" onclick="removeProductRow(1)">
                                            <i class="fa fa-trash"></i></button>--}}
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount_to_pay">Amount To Pay</label>
                                        <input required  type="text" readonly name="amount_to_pay" class="form-control"
                                               value=""
                                               id="amount_to_pay">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount_paid">Amount Paid</label>
                                        <input required  type="text" onkeyup="paidAmount()" name="amount_paid"
                                               class="form-control"
                                               value=""
                                               id="amount_paid">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="due">Amount Due</label>
                                        <input required  type="text" readonly name="due" class="form-control" value=""
                                               id="due">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_mode">Payment Method</label>
                                        <select required  name="payment_mode" id="payment_mode" class="form-control">
                                            <option value=""></option>
                                            <option value="Cash">Cash</option>
                                            <option value="Card">Card</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="delivered">Delivery</label>
                                        <select required name="delivered" class="form-control" id="delivered">
                                            <option  value=""></option>
                                            <option selected value="1">Delivered</option>
                                            <option value="0">Not Delivered</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_status">Payment status </label>
                                        <select required  name="payment_status" class="form-control" id="payment_status">
                                            <option value=""></option>
                                            <option value="Full">Full</option>
                                            <option value="No payment">No payment</option>
                                            <option value="Advance">Advance</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer editFooter">
                            <button type="button" class="btn btn-default pull-left" onclick="addRow()" id="addRowBtn"
                                    data-loading-text="Loading...">
                                <i class="fa fa-plus-square"></i> Add Row
                            </button>
                            <button type="reset" class="btn btn-warning pull-left">
                                <i class="fa fa-eraser"></i>
                                Reset
                            </button>

                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-close"></i>
                                Close
                            </button>
                            <button type="submit" id="createBtn" class="btn btn-primary">
                                <i class="fa fa-check-circle"></i>
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <div class="col-12">
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            Orders
                        </h4>
                    </div>
                </div>

                <div class="box-body table-responsive">
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
    @if(Session::has('error'))
        <input type="hidden" id="dbError" value="{{ Session::get('error') }}">
    @endif
@endsection

@section('scripts')
    {{--    <script src="{{ asset('js/app.js') }}"></script>--}}
    <script src="{{ asset('js/orders.js') }}"></script>
    <script>

        $(function () {

            if ($('#dbError').val()) {
                $('#modal-default').modal('show');
            }

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
