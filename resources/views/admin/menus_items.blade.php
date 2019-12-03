<br>
<div class="box box-default flat">
    <div class="box-header with-border">
        <h4 class="box-title">
            Items
            <small>
                <span>[ {{ $menu->name }} ] </span>
            </small>
        </h4>
    </div>
    <div class="box-body">
        @if($menuItems->count()==0)
            <div class="alert alert-info flat">
                <h4 class="text-center">
                    No items available for this Menu
                </h4>
            </div>
        @else
            <table class="table table-hover table-condensed table-responsive">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Measure</th>
                    <th>Cost</th>
                    <th>Options</th>
                </tr>
                <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ number_format($menuItems->sum('cost')) }} Rwf</th>
                    <th></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($menuItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td contenteditable="false" id="qty{{$item->id}}">{{ $item->qty }}</td>
                        <td>{{ $item->product->unit_measure }}</td>
                        <td contenteditable="false" id="cost{{$item->id}}">{{ number_format($item->cost) }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button"
                                        data-id="{{ $item->id }}"
                                        id="js-edit{{$item->id}}"
                                        class="btn btn-default btn-sm js-edit-item">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button"
                                        data-id="{{ $item->id }}"
                                        data-url="{{ route('updateMenuItem',[$item->id]) }}"
                                        id="js-update{{$item->id}}"
                                        class="btn btn-primary btn-sm js-update-item div-hide">
                                    <i class="fa fa-check-circle"></i>
                                    Update
                                </button>
                                <button type="button"
                                        data-id="{{ $item->id }}"
                                        id="js-cancel{{$item->id}}"
                                        class="btn btn-default btn-sm div-hide js-cancel">
                                    <i class="fa fa-close"></i>
                                    Cancel
                                </button>
                                <button type="button"
                                        id="js-close{{$item->id}}"
                                        data-url="{{ route('menus.removeItem',['id'=>$item->id]) }}"
                                        class="btn btn-danger btn-sm text-danger js-remove-item">
                                    <i class="fa fa-close"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        @endif
    </div>

</div>

