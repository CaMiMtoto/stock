@extends('layouts.master')
@section('title')
    Product Orders
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')


    <section class="content">
        <div class="section-heading">
            <h4>Orders</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary  flat">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <i class="ion ion-wineglass"></i>
                            Drinks Orders
                        </h4>

                     {{--   <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal"
                                data-target="#modal-default">
                            Add New Order
                        </button>--}}
                    </div>
                    <div class="box-body table-responsive ">
                        <table class="table table-striped table-hover" id="manageTable"
                               style="border: 1px solid #f1f1f1">
                            <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Client</th>
                                <th>Waiter</th>
                                <th>M.to pay</th>
                                <th>M.Paid</th>
                                <th>M.Due</th>
                                <th>Tax</th>
                                <th>Payment status</th>
                                <th>Payment Mode</th>
                                <th style="width: 15%">Options</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>



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
                <form action="{{ route('productOrders.store') }}" method="post" novalidate class="validate-form"
                      id="createOrderForm">
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
                                    <input type="text" disabled required class="form-control datepicker"
                                           name="order_date"
                                           id="order_date"
                                           placeholder="Order Date" value="{{ now()->format('d/m/Y') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_name" class="control-label">Customer</label>
                                    <input type="text" class="form-control" name="customer_name"
                                           id="customer_name" required
                                           placeholder="Customer name" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="waiter" class="control-label">Waiter</label>
                                    <select name="waiter" required id="waiter" class="form-control">
                                        <option value=""></option>
                                        @foreach($waiters as $waiter)
                                            <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
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
                            <tr id="row1">
                                <td>
                                    <div class="form-group">
                                        <select required onchange="getProductData(1)" name="product[]" id="product1"
                                                class="form-control select2" style="width: 100%">
                                            <option value="">--product--</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <label id="product1-error" class="error" for="product1"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input required type="text" readonly class="form-control" id="rate1"
                                               name="rate[]"
                                               value=""
                                               placeholder="Price">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input required type="text" onkeyup="getTotal(1)" class="form-control"
                                               id="quantity1" name="quantity[]" value=""
                                               placeholder="Qty">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input required type="text" readonly class="form-control" name="total[]"
                                               id="total1"
                                               value=""
                                               placeholder="Total">
                                    </div>
                                </td>
                                <td>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_to_pay">Amount To Pay</label>
                                    <input required type="text" readonly name="amount_to_pay" class="form-control"
                                           value=""
                                           id="amount_to_pay">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount_paid">Amount Paid</label>
                                    <input required min="0" type="number" onkeyup="paidAmount()" name="amount_paid"
                                           class="form-control"
                                           value="0"
                                           id="amount_paid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax">Tax (18%)</label>
                                    <input required type="text" readonly name="tax" class="form-control" value=""
                                           id="tax">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="due">Amount Due</label>
                                    <input required type="text" readonly name="due" class="form-control" value=""
                                           id="due">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_mode">Payment Method</label>
                                    <select required name="payment_mode" id="payment_mode" class="form-control">
                                        <option value=""></option>
                                        <option value="Cash" selected>Cash</option>
                                        <option value="Card">Card</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_status">Payment status </label>
                                    <select required name="payment_status" class="form-control" id="payment_status">
                                        <option value="Full">Full</option>
                                        <option value="Not Paid" selected>Not Paid</option>
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
                        <button type="submit" class="btn btn-primary createBtn">
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






    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal validate-form" method="post" id="marksForm"
                      action="{{ route('productOrders.mark') }}" novalidate>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"><i class="glyphicon glyphicon-pencil"></i>
                            Order details
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div id="edit-messages"></div>
                        <div class="modal-loading div-hide"
                             style="width: 50px;margin: auto;padding-top: 50px;padding-bottom: 50px;">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="edit-result">
                        </div>
                        <!-- END TABS PILL STYLE -->
                    </div> <!-- /modal-body -->

                    <div class="modal-footer  editFooter">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="glyphicon glyphicon-remove-sign"></i>Close
                            </button>
                            <button type="submit" class="btn btn-primary createBtn" data-loading-text="Loading...">
                                <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                            </button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- /  -->

@endsection

@section('scripts')
    <script src="{{ asset('lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lib/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/product_order.js') }}"></script>
    <script>
        $('.tr-orders').addClass('active');
        $('.mn-drinks').addClass('active');
        var defaultUrl = "{{ route('productOrders.all')  }}";
        var table;
        var manageTable = $("#manageTable");

        function myFunc() {
            table = manageTable.DataTable({
                "bProcessing": true,
                "serverSide": true,
                ajax: {
                    url: defaultUrl,
                    method: 'POST',
                    dataSrc: 'data',
                    data: {_token: "{{csrf_token()}}"}
                },
                columns: [
                    {data: 'created_at', 'sortable': true},
                    {data: 'customer_name', 'sortable': true},
                    {data: 'waiter.name', 'sortable': true,},
                    {data: 'amount_to_pay', 'sortable': true},
                    {data: 'amount_paid', 'sortable': true},
                    {data: 'amount_due', 'sortable': false},
                    {data: 'tax', 'sortable': true},
                    {
                        data: 'payment_status', 'sortable': true,
                        render: function (data) {
                            if (data === "Advance") {
                                return "<a class='label label-warning'> " + data + "</a>";
                            } else if (data === "Not Paid") {
                                return "<a class='label label-danger'>" + data + "</a>";
                            }
                            return "<a class='label label-success'><i class='fa fa-check-circle-o'></i> " + data + "</a>";
                        }
                    },
                    {data: 'payment_mode', 'sortable': true},
                    {
                        data: 'id',
                        'sortable': false,
                        render: function (data, type, row) {
                            return "<div class='btn-group btn-group-sm'>" +
                                "<a href='/admin/product/orders/" + row.id + "/edit' class='btn btn-warning btn-xs'> " +
                                "<i class='glyphicon glyphicon-edit'></i></a>" +
                                "<button class='btn btn-default btn-xs  js-details' " +
                                "data-url='/admin/product/orders/details/" + row.id + "' data-id='" + row.id + "'> " +
                                "Details</button>" +
                                "</div>";
                        }
                    }
                ]
            });
        }


        $(document).ready(function () {
            $('.tr-drinks').addClass('active');
            $('.mn-productOrders').addClass('active');

            myFunc();

            manageTable.on("click", ".js-details", function (e) {
                e.preventDefault();
                var findUrl = $(this).attr("data-url");
                // Launching edit modal
                $("#editModal").modal();
                // edit products messages
                $("#edit-messages").html("");
                // modal spinner
                $('.modal-loading').removeClass('div-hide');
                // modal result
                $('.edit-result').addClass('div-hide');
                //modal footer
                var footer = $(".editFooter");
                footer.addClass('div-hide');
                $.ajax({
                    url: findUrl,
                    method: "get"
                }).done(function (response) {

                    // modal spinner
                    $('.modal-loading').addClass('div-hide');
                    // modal result
                    $('.edit-result').removeClass('div-hide');
                    //modal footer
                    footer.removeClass('div-hide');
                    $('.edit-result').html(response);
                }).fail(function (error) {
                    alert("Error getting data");
                });
                return false;
            });


            $('#marksForm').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                if (!form.valid()) return;
                var btn = $('.createBtn');
                btn.button('loading');
                $.ajax({
                    url: form.attr('action'),
                    method: 'post',
                    data: form.serialize(),
                    success: function (data) {
                        btn.button('reset');
                        table.ajax.reload(null, false);
                        $('#editModal').modal('hide');
                    },
                    error: function () {
                        btn.button('reset');
                    }
                })

            });

            $('#createOrderForm').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                if (!form.valid()) return false;
                var btn = $('.createBtn');
                btn.button('loading');
                $.ajax({
                    url: form.attr('action'),
                    method: 'post',
                    data: form.serialize(),
                    success: function (data) {
                        btn.button('reset');
                        table.ajax.reload();
                        form[0].reset();
                        $('.myModal').modal('hide');

                    },
                    error: function () {
                        btn.button('reset');
                    }
                })

            });
        });
    </script>
@endsection
