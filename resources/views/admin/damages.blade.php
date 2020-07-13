@extends('layouts.master')
@section('title','Damaged damages')
@section('content')
    <section class="content">
        <div class="col-12">
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            Manage damages
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('damages.all') }}" method="get">
                            <div id="custom-search-input">
                                <div class="input-group ">
                                    <input type="text"
                                           disabled
                                           VALUE="{{ \request('q') }}"
                                           name="q" id="query" class="form-control flat"
                                           placeholder="Search .....">
                                    <span class="input-group-btn">
                                <button class="btn btn-primary flat" disabled type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{--                    {{ $damages }}--}}
                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Measure</th>
                            <th>Qty</th>
                            <th>Active</th>
                            <th>Cost</th>
                            <th>Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($damages as $prod)
                            <tr>
                                <td>{{ $prod->created_at->format('d/m/Y')}}</td>
                                <td>{{ $prod->product->name}}</td>
                                <td>{{ $prod->product->category->name}}</td>
                                <td>{{ $prod->product->unit_measure}}</td>
                                <td>{{ $prod->qty  }}</td>
                                <td>

                                    @if($prod->product->is_active)
                                        <span class="label label-danger">
                                            Yes
                                        </span>
                                    @else
                                        <span class="label label-info">
                                            No
                                        </span>
                                    @endif
                                </td>
                                <td>{{ number_format($prod->product->cost)  }}</td>
                                <td>{{ number_format($prod->product->price)  }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="box-footer">
                    {{ $damages->links() }}
                </div>
            </div>

        </div>

    </section>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('#damageForm').validate();
            $('.tr-products').addClass('active');
            $('.mn-damages').addClass('active');
            //edit product
            $('.js-edit').on('click', function () {
                var url = $(this).attr('data-url');
                $('#addModal').modal('show');
                showLoader();
                $.getJSON(url)
                    .done(function (data) {
                        hideLoader();
                        $('#name').val(data.name);
                        $('#id').val(data.id);
                        $('#unit_measure').val(data.unit_measure);
                        $('#category_id').val(data.category_id);
                        $('#original_qty').val(data.original_qty);
                        $('#price').val(data.price);
                        $('#cost').val(data.cost);
                        $('#is_active').val(data.is_active);
                    });
            });

            $('.js-stocking').on('click', function () {
                var id = $(this).attr('data-id');
                $('#product_id').val(id);
                $('#stockingModal').modal();
            });

            $('.js-damage').on('click', function () {
                var btn = $(this);
                var id = $(this).attr('data-id');
                $('#product_id').val(id);
                $('#damagedModal').modal();
                $('#damagedProduct').text(btn.data('name'));
                $('#productId').val(btn.data('id'));
            });

        });
    </script>
@endsection
