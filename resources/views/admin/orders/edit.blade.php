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

        <div class="col-12">

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
                <form action="{{ route('orders.update',['id'=>$order->id]) }}" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_date" class="control-label">Date</label>
                                    <input disabled type="text" class="form-control datepicker" name="order_date" id="order_date"
                                           placeholder="Order Date" value="{{ $order->created_at->format('d-m-Y') }}">
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
                                    <select name="waiter" id="waiter" class="form-control">
                                        <option value=""></option>
                                        @foreach($waiters as $waiter)
                                            <option value="{{ $waiter->id }}" {{ $waiter->id==$order->waiter_id?'selected':'' }}>
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
                                <th style="width:45%;">Menu</th>
                                <th style="width:15%;">Price</th>
                                <th style="width:15%;">Quantity</th>
                                <th style="width:15%;">Total</th>
                                <th style="width:10%;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x = 1;?>
                            @foreach($order->orderItems as $item)
                                <tr id="row<?php echo $x; ?>">
                                    <td>
                                        <div class="form-group">
                                            <select onchange="getProductData(<?php echo $x; ?>)" name="menu[]"
                                                    id="menu<?php echo $x; ?>" class="form-control select2" style="width: 100%">
                                                <option value="">--menu--</option>
                                                @foreach($menus as $menu)
                                                    <option
                                                        value="{{ $menu->id }}" {{ $menu->id==$item->menu_id?'selected':'' }}>
                                                        {{ $menu->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" readonly class="form-control" id="rate<?php print $x;?>"
                                                   name="rate[]"
                                                   value="{{ $item->price }}"
                                                   placeholder="Price">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" onkeyup="getTotal(<?php print $x;?>)"
                                                   class="form-control" id="quantity<?php print $x;?>"
                                                   name="quantity[]" value="{{ $item->qty }}"
                                                   placeholder="Qty">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" readonly class="form-control" name="total[]"
                                                   id="total<?php print $x;?>"
                                                   value="{{ $item->qty * $item->price }}"
                                                   placeholder="Total">
                                        </div>
                                    </td>
                                </tr>
                                <?php $x++;?>
                            @endforeach
                            </tbody>
                        </table>


                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount_to_pay">Amount To Pay</label>
                                    <input type="text" readonly name="amount_to_pay" class="form-control"
                                           value="{{ $order->totalOrderPrice() }}"
                                           id="amount_to_pay">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount_paid">Amount Paid</label>
                                    <input type="text" onkeyup="paidAmount()" name="amount_paid" class="form-control"
                                           value="{{ $order->amount_paid }}"
                                           id="amount_paid">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="due">Amount Due</label>
                                    <input type="text" readonly name="due" class="form-control"
                                           value="{{ $order->amountDue() }}"
                                           id="due">
                                </div>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="delivered">Delivery</label>
                                    <select name="delivered" class="form-control" id="delivered">
                                        <option value=""></option>
                                        <option value="1" {{ $order->received==1?'selected':'' }}>Delivered</option>
                                        <option value="0" {{ $order->received==0?'selected':'' }}>Not Delivered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_status">Payment status </label>
                                    <select name="payment_status" class="form-control" id="payment_status">
                                        <option value=""></option>
                                        <option value="Full" {{ $order->status=='Full'?'selected':'' }}>Full</option>
                                        <option value="No payment" {{ $order->status=='No payment'?'selected':'' }}>No payment</option>
                                        <option value="Advance" {{ $order->status=='Advance'?'selected':'' }}>Advance</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-offset-3 col-md-6">
                            <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn"
                                    data-loading-text="Loading...">
                                <i class="glyphicon glyphicon-plus-sign"></i> Add Row
                            </button>
                            <button class="btn btn-success">
                                <i class="fa fa-check-circle"></i>
                                Save order
                            </button>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </section>

@endsection

@section('scripts')
    <script>
        $('.tr-orders').addClass('active');
        $('.mn-food').addClass('active');
        function addRow() {
            $("#addRowBtn").button("loading");

            var tableLength = $("#productTable tbody tr").length;

            var tableRow;
            var arrayNumber;
            var count;

            if (tableLength > 0) {
                tableRow = $("#productTable tbody tr:last").attr('id');
                arrayNumber = $("#productTable tbody tr:last").attr('class');
                count = tableRow.substring(3);
                count = Number(count) + 1;
                arrayNumber = Number(arrayNumber) + 1;
            } else {
                // no table row
                count = 1;
                arrayNumber = 0;
            }

            $.ajax({
                url: '/api/menus',
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    $("#addRowBtn").button("reset");

                    var tr = '<tr id="row' + count + '" class="' + arrayNumber + '">' +
                        '<td>' +
                        '<div class="form-group">' +

                        '<select class="form-control select2" name="menu[]" id="menu' + count + '" onchange="getProductData(' + count + ')" >' +
                        '<option value="">--menu--</option>';
                    // console.log(response);
                    $.each(response, function (index, value) {
                        tr += '<option value="' + value.id + '">' + value.name + '</option>';
                    });

                    tr += '</select>' +
                        '</div>' +
                        '</td>' +
                        '<td>' +
                        '<input type="text" placeholder="Price" name="rate[]" id="rate' + count + '" autocomplete="off" readonly class="form-control" />' +
                        '</td >' +
                        '<td>' +
                        '<div class="form-group">' +
                        '<input type="text" placeholder="Qty" name="quantity[]" id="quantity' + count + '" onkeyup="getTotal(' + count + ')" autocomplete="off" class="form-control" />' +
                        '</div>' +
                        '</td>' +
                        '<td>' +
                        '<input type="text" placeholder="Total" name="total[]" readonly id="total' + count + '" autocomplete="off" class="form-control"  />' +
                        '</td>' +
                        '<td>' +
                        '<button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow(' + count + ')">' +
                        '<i class="glyphicon glyphicon-trash"></i></button>' +
                        '</td>' +
                        '</tr>';
                    if (tableLength > 0) {
                        $("#productTable tbody tr:last").after(tr);
                    } else {
                        $("#productTable tbody").append(tr);
                    }
                    $(document).find('.select2').select2();
                } // /success
            });	// get the product data

        } // /add row
        function removeProductRow(row = null) {
            if (row) {
                $("#row" + row).remove();
                subAmount();
            } else {
                alert('error! Refresh the page again');
            }
        }

        // table total
        function getTotal(row = null) {
            if (row) {
                var total = Number($("#rate" + row).val()) * Number($("#quantity" + row).val());
                total = total.toFixed(2);
                $("#total" + row).val(total);
                subAmount();
            } else {
                alert('no row !! please refresh the page');
            }
        }

        function paidAmount() {
            var grandTotal = $("#amount_to_pay").val();

            if (grandTotal) {
                var dueAmount = Number($("#amount_to_pay").val()) - Number($("#amount_paid").val());
                dueAmount = dueAmount.toFixed(2);
                $("#due").val(dueAmount);
            } // /if
        } // /paid amoutn function

        function subAmount() {
            var tableProductLength = $("#productTable tbody tr").length;
            var totalSubAmount = 0;
            for (x = 0; x < tableProductLength; x++) {
                var tr = $("#productTable tbody tr")[x];
                var count = $(tr).attr('id');
                count = count.substring(3);

                totalSubAmount = Number(totalSubAmount) + Number($("#total" + count).val());
            } // /for

            totalSubAmount = totalSubAmount.toFixed(2);

            // sub total
            $("#amount_to_pay").val(totalSubAmount);
            var paidAmount = $("#amount_paid").val();
            if (paidAmount) {
                paidAmount = Number($("#amount_to_pay").val()) - Number(paidAmount);
                paidAmount = paidAmount.toFixed(2);
                $("#due").val(paidAmount);
            } else {
                $("#due").val($("#amount_to_pay").val());
            } // else

        } // /sub total amount

        // select on product data
        function getProductData(row = null) {
            if (row) {
                var menuId = $("#menu" + row).val();
                if (menuId === "") {
                    $("#rate" + row).val("");
                    $("#quantity" + row).val("");
                    $("#total" + row).val("");
                } else {
                    $.ajax({
                        url: '/admin/menus/' + menuId,
                        type: 'get',
                        dataType: 'json',
                        success: function (response) {
                            // setting the rate value into the rate input field
                            $("#rate" + row).val(response.price);

                            $("#quantity" + row).val(1);

                            var total = Number(response.price);
                            total = total.toFixed(2);
                            $("#total" + row).val(total);

                            subAmount();
                        } // /success
                    }); // /ajax function to fetch the product data
                }

            } else {
                alert('no row! please refresh the page');
            }
        } // /select on product data


        $(function () {

            // add order
            // top nav child bar
            $('#topNavAddOrder').addClass('active');

            // order date picker
            $("#orderDate").datepicker();

            // create order form function
            $("#createOrderForm").unbind('submit').bind('submit', function () {
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        // reset button
                        $("#createOrderBtn").button('reset');

                        $(".text-danger").remove();
                        $('.form-group').removeClass('has-error').removeClass('has-success');

                        if (response.success === true) {

                            // create order button
                            $(".success-messages").html('<div class="alert alert-success">' +
                                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' + response.messages +
                                ' <br /> <br /> <a type="button"  class="btn btn-primary">' +
                                ' <i class="glyphicon glyphicon-print"></i> Print </a>' +
                                '<a href="/add/order" class="btn btn-default" style="margin-left:10px;">' +
                                ' <i class="glyphicon glyphicon-plus-sign"></i> Add New Order </a>' +

                                '</div>');

                            $("html, body, div.panel, div.pane-body").animate({scrollTop: '0px'}, 100);

                            // disabled te modal footer button
                            $(".submitButtonFooter").addClass('div-hide');
                            // remove the product row
                            $(".removeProductRowBtn").addClass('div-hide');

                        } else {
                            alert(response.messages);
                        }
                    } // /response
                }); // /ajax


                return false;
            }); // /create order form function
        });

    </script>
@endsection
