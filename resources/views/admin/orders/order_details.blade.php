<section class="invoice">
    <?php
    $subtotal=0;
    ?>
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <img src="{{ asset('img/logo.png') }}" style="height: 50px;" alt="">
                <small class="pull-right">
                    <strong>Date:</strong>
                    {{ \Carbon\Carbon::now()->format('d m Y') }}
                </small>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-12 invoice-col">

            <strong>Client Name :</strong>{{ $order->customer_name }}<br>

            <b>Receipt Number:</b> #{{ str_pad($order->id,5,'0',STR_PAD_LEFT) }}<br>
            <b>Payment Date:</b> {{ $order->created_at->format('d/m/Y') }}<br>
            <b>Waiter:</b> {{ $order->waiter->name }}<br>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Qty</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <?php $subtotal+=$item->subTotal(); ?>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ number_format($item->price) }}</td>
                        <td>{{ number_format($item->subTotal()) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-sm-6">
        </div>
        <!-- /.col -->
        <div class="col-sm-12">
            <h4>Payment Info</h4>

            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Amount To Pay</th>
                        <td>{{ number_format($order->totalOrderPrice()) }}</td>
                    </tr>
                    <tr>
                        <th>Amount Paid</th>
                        <td>{{ number_format($order->amount_paid) }}</td>
                    </tr>
                    <tr>
                        <th>Tax </th>
                        <td>{{ number_format($order->tax) }}</td>
                    </tr>
                    <tr>
                        <th>Amount Due</th>
                        <td>{{ number_format($order->amountDue()) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

        <h4 class="text-center">Thank you for choosing us!</h4>

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-sm-12">

            <a href="{{ route('orders.print',['id'=>$order->id]) }}"
               class="btn btn-default pull-right no-print">
                <i class="fa fa-print"></i>
                Print
            </a>
        </div>
    </div>
</section>
