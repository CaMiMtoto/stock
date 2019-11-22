<style>
    .billing-history tbody > tr > td {
        padding: 10px;
    }

</style>

@if(\Illuminate\Support\Facades\Auth::user()->role==='Admin')
    <div>
        <a target="_blank" href="{{ route('orders.printOrder',['id'=>$order->id]) }}"
           class="btn btn-primary btn-sm pull-right">
            <i class="fa fa-print"></i>
            Print order
        </a>
    </div>

@endif

<h5>Client information</h5>

<div id="printOrder">
    <table class="table billing-history">
        <thead class="sr-only">
        <tr>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
            <span>
                <b>Oder date</b>
            </span>
            </td>
            <td> : {{ date('j M Y h:i a', strtotime($order->created_at)) }}</td>
        </tr>
        <tr>
            <td>
            <span>
                <b>Customer</b>
            </span>
            </td>
            <td> : {{ $order->customer_name }}</td>
        </tr>
        <tr>
            <td>
            <span>
            <b>Waiter</b>
            </span>
            </td>
            <td> : {{ $order->waiter}}</td>
        </tr>
        <tr>
            <td>
            <span>
            <b>Tax</b>
            </span>
            </td>
            <td> : {{number_format( $order->tax)}}</td>
        </tr>
        <tr>
            <td>
            <span>
            <b>Order Status</b>
            </span>
            </td>
            <td>
                :
                @if($order->order_status==='Delivered')
                    <span class="label label-success">{{$order->order_status}}</span>
                @elseif($order->order_status==='Processing')
                    <span class="label label-info">{{$order->order_status}}</span>
                @else
                    <span class="label label-primary">{{$order->order_status}}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>
            <span>
            <b>Payment Status</b>
            </span>
            </td>
            <td> :
                @if($order->payment_status==='Paid')
                    <span class="label label-success">{{$order->payment_status}}</span>
                @elseif($order->payment_status==='Advance')
                    <span class="label label-info">{{$order->payment_status}}</span>
                @else
                    <span class="label label-danger">{{$order->payment_status}}</span>
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <h5>Products ordered</h5>
    <table class="table table-bordered table-responsive table-striped">
        <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $total=0;?>
        @foreach($order->productOrderItems as $orderItem)
            <?php $total+=$orderItem->price*$orderItem->qty;?>
            <tr>
                <td>{{ $orderItem->product->name }}</td>
                <td>{{ number_format($orderItem->price) }}</td>
                <td>{{ $orderItem->qty }}</td>
                <td>{{ number_format($orderItem->price*$orderItem->qty) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">
                                    <span>
                                        <b>Total:</b>
                                    </span>
            </td>
            <td>
                <b>{{ number_format($total) }} Rwf</b>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
@if(\Illuminate\Support\Facades\Auth::user()->role==='admin')

    {{ csrf_field() }}
    <input type="hidden" value="{{ $order->id }}" name="id">
{{--    <div class="form-group">--}}
{{--        <label for="order_status" class="control-label col-sm-3">Order Status</label>--}}
{{--        <label for="order_status" class="control-label col-sm-1">:</label>--}}
{{--        <div class="col-sm-8">--}}
{{--            <select required class="form-control" name="order_status" id="order_status">--}}
{{--                <option value=""></option>--}}
{{--                <option value="Pending" {{ $order->order_status=="Pending"? 'selected':'' }}>Pending</option>--}}
{{--                <option value="Processing" {{ $order->order_status=="Processing"? 'selected':'' }}>Processing</option>--}}
{{--                <option value="Shipped" {{ $order->order_status=="Shipped"? 'selected':'' }}>Shipped</option>--}}
{{--            </select>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="form-group">
        <label for="payment_status" class="control-label col-sm-3">Payment Status</label>
        <label for="payment_status" class="control-label col-sm-1">:</label>
        <div class="col-sm-8">
            <select required class="form-control"  name="payment_status" id="payment_status">
                <option value=""></option>
                <option value="Not Paid" {{ $order->payment_status=="Not Paid"? 'selected':'' }}>Not Paid</option>
                <option value="Advance" {{ $order->payment_status=="Advance"? 'selected':'' }}>Advance</option>
                <option value="Paid" {{ $order->payment_status=="Paid"? 'selected':'' }}>Paid</option>
            </select>
        </div>
    </div>

@endif

