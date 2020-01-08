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
            <th>In Stock</th>
            <th>Stock Unit Measure</th>
            <th>Unit Price</th>
            <th>Qty Requested</th>
            <th>Total</th>
            @if($request->status=='approved' && (Auth::user()->role->name=='keeper' || Auth::user()->role->name=='manager'))
                <input type="hidden" value="{{ route('requests.updateStock',[$request->id]) }}" id="updateUrl">
                <th>Unit to be stored</th>
            @endif
            @if($request->status!='approved'&& (Auth::user()->role->name=='keeper' || Auth::user()->role->name=='manager'))
                <script>
                    $('#saveChangesBtn').addClass('div-hide');
                </script>
            @endif
        </tr>
        </thead>
        <tbody>
        <?php $total = 0;?>
        @foreach($request->requestItems as $item)
            <?php $total += $item->unit_price * $item->qty;?>
            <input type="hidden" value="{{$item->product->id}}" name="product[]"/>
            <input type="hidden" value="{{$item->qty}}" name="qty[]"/>
            <input type="hidden" value="{{$item->unit_price}}" name="unit_price[]"/>
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->product->qty }}</td>
                <td>{{ $item->product->unit_measure }}</td>
                <td>{{ number_format($item->unit_price) }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->unit_price*$item->qty) }}</td>
                @if($request->status=='approved' && (Auth::user()->role->name=='keeper' || Auth::user()->role->name=='manager'))
                    <td>
                        <input type="text" class="form-control" id="id_{{$item->id}}" required name="qtyToBeStocked[]"
                               style="max-width: 100px;">
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    <p class="text-right">
        <strong>Total:</strong>
        <b>{{ number_format($total) }} Rwf</b>
    </p>
</div>
@if($request->status=='approved' && (Auth::user()->role->name=='keeper' || Auth::user()->role->name=='manager'))
    <div class="form-group">
        <label for="delivered_by" class="control-label col-sm-4">Delivered by</label>
        <div class="col-sm-8">
            <input type="text" required name="delivered_by" id="delivered_by" class="form-control">
        </div>
    </div>

@endif

@if(Auth::user()->role->name==='admin' || Auth::user()->role->name==='manager')

    <input type="hidden" value="{{ $request->id }}" name="id">
    <input type="hidden" value="{{ route('requests.updateStatus',[$request->id]) }}" id="updateUrl">
    <div class="form-group">
        <label for="order_status" class="control-label col-sm-4">Status</label>
        <div class="col-sm-8">
            <select required class="form-control" name="status" id="status">
                <option value=""></option>
                <option value="pending" {{ $request->status=="pending"? 'selected':'' }}>Pending</option>
                <option value="approved" {{ $request->status=="approved"? 'selected':'' }}>Approved</option>
                <option value="modify" {{ $request->status=="modify"? 'selected':'' }}>Modify</option>
                <option value="rejected" {{ $request->status=="rejected"? 'selected':'' }}>Rejected</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="comment" class="control-label col-sm-4">Comment</label>
        <div class="col-sm-8">
            <textarea name="comment" required id="comment" class="form-control"></textarea>
        </div>
    </div>


@endif

