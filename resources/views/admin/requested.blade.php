@extends('layouts.master')
@section('title','Requisitions')
@section('content')
    <section class="content">
        <div class="col-12">
            <div class="clearfix"></div>
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <h4 class="box-title">
                            Requests { {{ $requisitions->count() }} }
                        </h4>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
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
                                <td>{{ $req->product->name}}</td>
                                <td>{{ $req->qty}}</td>
                                <td>{{ $req->reason}}</td>
                                <td>
                                    @if($req->status=='pending')
                                        <span class="label label-info">{{ ucfirst($req->status) }}</span>
                                    @elseif($req->status=='accepted')
                                        <span class="label label-success">{{ ucfirst($req->status) }}</span>
                                    @elseif($req->status=='rejected')
                                        <span class="label label-danger">{{ ucfirst($req->status) }}</span>
                                    @else
                                        <span class="label label-default">{{ ucfirst($req->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                                data-update="{{ route('requisitions.update',['id'=>$req->id]) }}"
                                                data-url="{{ route('requisitions.show',['id'=>$req->id]) }}"
                                                class="btn btn-default js-details">
                                            Details
                                        </button>
                                        <button
                                                data-update="{{ route('requisitions.update',['id'=>$req->id]) }}"
                                                data-url="{{ route('requisitions.show',['id'=>$req->id]) }}"
                                                class="btn btn-default js-edit">
                                            <i class="fa fa-edit"></i>
                                            Edit
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
                        Product requisition
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h4>
                </div>

                <form novalidate action="" id="submitForm" method="post" class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="0">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <div class="form-group">
                                <label for="category_id" class="col-sm-3  control-label">Category</label>
                                <div class="col-sm-9">
                                    <select required disabled="" class="form-control" id="category_id"
                                            name="category_id">
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
                                    <select required disabled="" class="form-control" id="product_id" name="product_id">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qty" class="col-sm-3 control-label">Qty</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="qty" id="qty"
                                           required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="reason" class="col-sm-3 control-label">Reason</label>
                                <div class="col-sm-9">
                                    <textarea name="reason" id="reason" class="form-control" disabled=""
                                              placeholder="Reason"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status" class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-9">
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending">Pending</option>
                                        <option value="accepted">Accepted</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="comment" class="col-sm-3 control-label">Comment</label>
                                <div class="col-sm-9">
                                    <textarea name="comment" id="comment" class="form-control"
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
            $('.mn-products').addClass('active');

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
                $('#submitForm').attr('action', $(this).attr('data-update'));
                var url = $(this).attr('data-url');
                $('#addModal').modal('show');
                showLoader();
                $.getJSON(url)
                    .done(function (data) {
                        hideLoader();
                        $('#id').val(data.id);
                        $('#qty').val(data.qty);
                        $('#reason').val(data.reason);
                        $('#status').val(data.status);
                        $('#category_id').val(data.product.category_id);
                        loadProducts(data.product.category_id, data.product_id);
                        $('#comment').val(data.comment);
                    });
            });


        });
    </script>
@endsection
