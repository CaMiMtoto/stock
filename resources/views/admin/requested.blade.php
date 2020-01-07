@extends('layouts.master')
@section('title','Requisitions')
@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/EasyAutocomplete-1.3.5/easy-autocomplete.min.css') }}">
    <style>
        .easy-autocomplete {
            position: relative;
            width: 100% !important;
            border-radius: 0 !important;
        }
    </style>
@endsection
@section('content')
    <section class="content">
        <div class="col-12">
            <div class="clearfix"></div>
            <div class="box box-primary flat">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        Requests { {{ $requisitions->count() }} }
                    </h4>
                    <div class="box-tools">
                        <button data-toggle="modal" data-target="#addModal" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i>
                            Add New
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Prepared By</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requisitions as $req)
                            <tr>
                                <td>{{ $req->date}}</td>
                                <td>{{ $req->department}}</td>
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
                                <td>{{ $req->prepared_by}}</td>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Product requisition
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h4>
                </div>

                <form novalidate action="{{ route('requests.store') }}" autocomplete="off" method="post">
                    <input type="hidden" id="id" name="id" value="0">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="products" class="control-label">Product</label>
                                        <div>
                                            <input type="text" id="products" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="qty" class="control-label">Qty</label>
                                        <div>
                                            <input type="number" id="qty" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="price" class="control-label">Unit price</label>
                                        <div>
                                            <input type="text" id="price" class="form-control" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="price" class="control-label">Button</label>
                                        <div>
                                            <button type="button" id="itemButton" class="btn btn-default btn-block">
                                                <i class="fa fa-plus-circle"></i>
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Items chosen</h4>
                                    <table class="table table-condensed table-hover table-striped" id="myTable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Qty</th>
                                            <th>Unit cost</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date" class="control-label">Date</label>
                                        <input type="text" name="date" id="date" class="form-control datepicker"
                                               required/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="department" class="control-label">Department</label>
                                        <input type="text" id="department" name="department" class="form-control"
                                               required/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="prepared_by" class="control-label">Prepared By</label>
                                        <input type="text" name="prepared_by" id="prepared_by" class="form-control"
                                               required/>
                                    </div>
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
                                <i class="fa fa-check-circle"></i>
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
    <script src="{{ asset('lib/EasyAutocomplete-1.3.5/jquery.easy-autocomplete.min.js') }}"></script>
    <script>
        var query = '';
        var product;

        $(function () {
            $('.tr-products').addClass('active');
            $('.mn-requests').addClass('active');


            $('#itemButton').on('click', function () {
                var btn = $(this);
                var qty = $('#qty');
                var price = $('#price');

                var tr = $('#myTable>tbody:last-child');
                tr.append('<tr><td><input type="hidden" name="product_id[]" value="' + product.id + '"/>' + product.name + '</td><td><input type="hidden" name="qty[]" value="' + qty.val() + '">' + qty.val() + '</td><td><input type="hidden" name="price[]" value="' + price.val() + '">' + price.val() + '</td><td>' + parseInt(qty.val()) * parseInt(price.val()) + '</td></tr>');
                qty.val('');
                price.val('');
            });

            $('#products').on('input', function () {
                query = $(this).val();
            });

        });


        var options = {
            url: function (phrase) {
                if (phrase === "") {
                    return "{{ route('api.search.products') }}";
                } else {
                    return "{{ route('api.search.products') }}?query=" + phrase;
                }
            },

            getValue: function (data) {
                return data.name;
            },
            ajaxSettings: {
                dataType: "json",
                method: "GET"
            },
            requestDelay: 400,
            list: {
                onSelectItemEvent: function () {
                    product = $("#products").getSelectedItemData();
                },
                onHideListEvent: function () {
                    console.log('hidden');
                },
                match: {
                    enabled: true
                }
            },

            theme: "square"
        };

        $("#products").easyAutocomplete(options);
    </script>
@endsection
