@extends('layouts.master')
@section('title','Requisitions')
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
                            Product requisitions
                        </h4>
                    </div>
                </div>
                {{--                    {{ $products }}--}}
                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Product</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requisitions as $req)
                            <tr>
                                <td>{{ date_format($req->created_at,'Y M d')}}</td>
                                <td>{{ $req->product->name}}</td>
                                <td>{{ $req->qty}}</td>
                                <td>{{ $req->reason}}</td>
                                <td>
                                    @if($req->status=='pending')
                                        <span class="label label-info">{{ ucfirst($req->status) }}</span>
                                    @elseif($req->status=='accepted')
                                        <span class="label label-success">{{ ucfirst($req->status) }}</span>
                                    @elseif($req->status=='confirmed')
                                        <span class="label label-default">{{ ucfirst($req->status) }}</span>
                                    @else
                                        <span class="label label-danger">{{ ucfirst($req->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($req->status=='accepted')
                                            <button
                                                    data-url="{{ route('requisitions.confirm',['id'=>$req->id]) }}"
                                                    class="btn btn-success js-confirm btn-sm">
                                                <i class="fa fa-check-circle-o"></i>
                                                Confirm
                                            </button>
                                        @elseif($req->status=='pending')
                                            <button
                                                    data-url="{{ route('requisitions.show',['id'=>$req->id]) }}"
                                                    class="btn btn-default js-edit btn-sm">
                                                <i class="fa fa-edit"></i>
                                                Edit
                                            </button>
                                        @elseif($req->status=='rejected')
                                            <p>{{ $req->comment }}</p>
                                        @endif
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
                        Product requisition
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h4>
                </div>
                <form novalidate action="{{ route('requisitions.store') }}" method="post" id="submitForm"
                      class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="0">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <div class="form-group">
                                <label for="category_id" class="col-sm-3  control-label">Category</label>
                                <div class="col-sm-9">
                                    <select required class="form-control" id="category_id" name="category_id">
                                        <option></option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id}}">
                                                {{$cat->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="product_id" class="col-sm-3  control-label">Product</label>
                                <div class="col-sm-9">
                                    <select required class="form-control" id="product_id" name="product_id">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qty" class="col-sm-3 control-label">Qty</label>
                                <div class="col-sm-9">
                                    <input  type="number" class="form-control" name="qty" id="qty"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="reason" class="col-sm-3 control-label">Reason</label>
                                <div class="col-sm-9">
                                    <textarea name="reason" id="reason" class="form-control"
                                              placeholder="Reason"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="comment" class="col-sm-3 control-label">Comment</label>
                                <div class="col-sm-9">
                                    <textarea name="comment" id="comment" class="form-control" disabled=""
                                              placeholder="Comment"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer editFooter">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" id="createBtn" class="btn btn-primary">Save changes</button>
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
            $('.mn-requisitions').addClass('active');

            $('#category_id').on('change', function () {
                loadProducts($(this).val());
            });

            var loadProducts = function (categoryId, product_id = 0) {

                $.getJSON('/admin/products/category/' + categoryId)
                    .done(function (data) {
                        var product = $('#product_id');
                        product.empty();
                        product.append('<option></option>');
                        data.forEach(function (item) {
                            product.append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        product.val(product_id);
                    });
            };

            //edit product
            $('.js-edit').on('click', function () {
                var url = $(this).attr('data-url');
                $('#addModal').modal('show');
                showLoader();
                $.getJSON(url)
                    .done(function (data) {
                        hideLoader();
                        $('#id').val(data.id);
                        $('#qty').val(data.qty);
                        $('#reason').val(data.reason);
                        $('#category_id').val(data.product.category_id);
                        loadProducts(data.product.category_id, data.product_id);
                        $('#comment').val(data.comment);
                    });
            });

            // confirm

            $('.js-confirm').on('click', function () {
                var url = $(this).attr('data-url');
                bootbox.confirm('Are you sure', function (result) {
                    if (result) {
                        $.ajax({
                            url: url,
                            method: 'PATCH',
                            data:{_token:$('meta[name="csrf-token"]').attr('content')},
                            success: function () {
                                location.reload();
                            }
                        })
                    }
                });
            });


        });
    </script>
@endsection
