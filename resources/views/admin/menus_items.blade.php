<br>
<div class="box box-info flat">
    <div class="box-header with-border">
        <div class="box-title">
            <h4>
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
                        <th></th>
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
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->measure }}</td>
                            <td>{{ number_format($item->cost) }}</td>
                            <td>
                                <div class="btn-group">
                                  {{--  <button type="button"
                                            data-url="{{ route('menus.removeItem',['id'=>$item->id]) }}"
                                            class="btn btn-default btn-sm js-edit-item">
                                        <i class="fa fa-edit"></i>
                                    </button>--}}
                                    <button type="button"
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
</div>

