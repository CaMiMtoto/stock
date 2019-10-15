@extends('layouts.master')
@section('title','Products')
@section('content')
    <section class="content">
        <div class="col-12">
            <button class="btn btn btn-primary btn-sm pull-right" id="addButton">
                <i class="fa fa-plus"></i>
                Add New
            </button>
            <div class="clearfix"></div>
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            Manage Products
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('products.all') }}" method="get">
                            <div id="custom-search-input">
                                <div class="input-group ">
                                    <input type="text" name="q" id="query" class="form-control flat"
                                           placeholder="Search .....">
                                    <span class="input-group-btn">
                                <button class="btn btn-primary flat" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{--                    {{ $products }}--}}
                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Unit Measure</th>
                            <th scope="col">In stock</th>
                            <th scope="col">Beg. Qty</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $prod)
                            <tr>
                                <td>{{ $prod->name}}</td>
                                <td>{{ $prod->category->name}}</td>
                                <td>{{ $prod->unit_measure}}</td>
                                <td>
                                    @if($prod->qty <= 5)
                                        <span class="label label-danger">{{ $prod->qty }}</span>
                                    @else
                                        <span class="label label-info">{{ $prod->qty }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($prod->original_qty)  }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                                data-url="{{ route('products.show',['id'=>$prod->id]) }}"
                                                class="btn btn-default js-edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button
                                                data-url="{{ route('products.destroy',['id'=>$prod->id]) }}"
                                                class="btn btn-danger js-delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <button
                                                data-id="{{ $prod->id }}"
                                                class="btn btn-default js-stocking">
                                            Stock In
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </section>


    <div class="modal fade myModal" tabindex="-1" role="dialog" id="addModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Products
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h4>
                </div>
                <form novalidate action="{{ route('products.store') }}" method="post" id="submitForm"
                      class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="0">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input required minlength="2" type="text" class="form-control" name="name"
                                           id="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="col-sm-3  control-label">Category</label>
                                <div class="col-sm-9">
                                    <select required class="form-control" id="category_id" name="category_id">
                                        <option></option>
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id}}">
                                                {{$cat->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="unit_measure" class="col-sm-3 control-label">Measure</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="unit_measure" id="unit_measure"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="original_qty" class="col-sm-3 control-label">Beginning Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" value="0" name="original_qty" id="original_qty"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer editFooter">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-close"></i>
                                Close
                            </button>
                            <button type="submit" id="createBtn" class="btn btn-primary">
                                <i class="fa fa-check-circle-o"></i>
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade myModal" tabindex="-1" role="dialog" id="stockingModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Stocking
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h4>
                </div>
                <form novalidate action="{{ route('stocks.store') }}" method="post" id="submitForm"
                      class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="0">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <input type="hidden" value="0" name="product_id" id="product_id">
                            <div class="form-group">
                                <label for="qty" class="col-sm-3 control-label">Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="qty" id="qty"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price" class="col-sm-3 control-label">Unit cost</label>
                                <div class="col-sm-9">
                                    <input type="text" number="true" class="form-control" name="price" id="price"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer editFooter">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-close"></i>
                                Close
                            </button>
                            <button type="submit"  class="btn btn-primary btn-save">
                                <i class="fa fa-check-circle-o"></i>
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        $(function () {
            $('.tr-products').addClass('active');
            $('.mn-products').addClass('active');
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
                    });
            });

            $('.js-stocking').on('click',function () {
                var id=$(this).attr('data-id');
                $('#product_id').val(id);
                $('#stockingModal').modal();
            });

        });
    </script>
@endsection
