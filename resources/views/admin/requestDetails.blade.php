<style>
    .billing-history tbody > tr > td {
        padding: 10px;
    }

</style>


<h5>Request information</h5>

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
                <b>Requested date</b>
            </span>
            </td>
            <td> : {{ date('j M Y h:i a', strtotime($request->date)) }}</td>
        </tr>
        <tr>
            <td>
            <span>
                <b>Department</b>
            </span>
            </td>
            <td> : {{ $request->department }}</td>
        </tr>
        <tr>
            <td>
            <span>
            <b>Prepared By</b>
            </span>
            </td>
            <td> : {{ $request->prepared_by}}</td>
        </tr>
        <tr>
            <td>
            <span>
            <b>Status</b>
            </span>
            </td>
            <td>
                :
                @if($request->status==='Delivered')
                    <span class="label label-success">{{$request->status}}</span>
                @elseif($request->status==='Processing')
                    <span class="label label-info">{{$request->status}}</span>
                @else
                    <span class="label label-primary">{{$request->status}}</span>
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <h5>Request items</h5>
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
        <?php $total = 0;?>
        @foreach($request->requestItems as $item)
            <?php $total += $item->unit_price * $item->qty;?>
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ number_format($item->unit_price) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->unit_price*$item->qty) }}</td>
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
    <input type="hidden" value="{{ $request->id }}" name="id">
    {{--    <div class="form-group">--}}
    {{--        <label for="order_status" class="control-label col-sm-3">Order Status</label>--}}
    {{--        <label for="order_status" class="control-label col-sm-1">:</label>--}}
    {{--        <div class="col-sm-8">--}}
    {{--            <select required class="form-control" name="order_status" id="order_status">--}}
    {{--                <option value=""></option>--}}
    {{--                <option value="Pending" {{ $request->order_status=="Pending"? 'selected':'' }}>Pending</option>--}}
    {{--                <option value="Processing" {{ $request->order_status=="Processing"? 'selected':'' }}>Processing</option>--}}
    {{--                <option value="Shipped" {{ $request->order_status=="Shipped"? 'selected':'' }}>Shipped</option>--}}
    {{--            </select>--}}
    {{--        </div>--}}
    {{--    </div>--}}

@endif

