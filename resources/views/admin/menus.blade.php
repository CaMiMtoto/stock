@extends('layouts.master')
@section('title','Menus')
@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/css/typeahead.css') }}">
@endsection
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
                            Menus
                        </h4>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>Created At</th>
                            <th>Name</th>
                            <th>Items</th>
                            <th>Cost</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{ date_format($menu->created_at,'d M Y')  }}</td>
                                <td>{{ $menu->name }}</td>
                                <td>{{ number_format($menu->menuItems->count()) }}</td>
                                <td>{{ number_format($menu->menuItems->sum('cost')) }}</td>
                                <td>{{ number_format($menu->price) }}</td>
                                <td>
                                    <button
                                            data-url="{{ route('menus.show',['id'=>$menu->id]) }}"
                                            class="btn btn-default btn-sm js-edit">
                                        Edit
                                    </button>
                                    <button
                                            data-url="{{ route('menus.destroy',['id'=>$menu->id]) }}"
                                            class="btn btn-warning btn-sm js-delete">
                                        Delete
                                    </button>
                                    <button
                                            data-add-items-url="{{ route('menus.addItems',['id'=>$menu->id]) }}"
                                            data-url="{{ route('menus.items',['id'=>$menu->id]) }}"
                                            class="btn btn-primary btn-sm js-details">
                                        Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="box-footer">
                    {{ $menus->links() }}
                </div>
            </div>

        </div>

    </section>


    <div class="modal fade myModal" tabindex="-1" role="dialog" id="addModal">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Menu
                    </h4>
                </div>
                <form novalidate action="{{ route('menus.store') }}" method="post" id="submitForm"
                      class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="0">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="price" id="price" required>
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

    <div class="modal fade " tabindex="-1" role="dialog" id="detailsModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        Menu
                    </h4>
                </div>

                <div class="modal-body">
                    @include('layouts._loader')
                    <div id="message"></div>

                    <div class="edit-result">
                        <div class="box box-primary flat">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    New item
                                </h4>
                            </div>
                            <div class="box-body">
                                <form class="form-inline" id="addItemsForm">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" placeholder="Product name" class="form-control"
                                                       id="product" required>
                                                <input type="hidden" name="product_id" id="product_id" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="qty" id="qty" placeholder="Qty" required>
                                            </div>

                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="cost" id="cost"
                                                       placeholder="Cost" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" id="addItemButton" class="btn btn-primary pull-right">
                                                <i class="fa fa-check-circle-o"></i>
                                                Add Item
                                            </button>
                                        </div>
                                    </div>




                                </form>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-12">
                                <div id="itemsDiv"></div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-default pull-right" onclick="location.reload();" data-dismiss="modal">Close</button>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('lib/js/typeahead.bundle.js') }}"></script>
    <script>
        $('.tr-food').addClass('active');
        $('.mn-menus').addClass('active');

        var addItemsUrl = "";
        var menuItemsUrl="";

        function loadMenuItems(menuItemsUrl) {
            showLoader();
            $.ajax({
                url: menuItemsUrl,
                method: 'GET',
                // dataType: 'text/html'
            }).done(function (data) {
                hideLoader();
                $('#itemsDiv').html(data);
            });
        }

        $(function () {
            $('.mn-menus').addClass('active');

            //edit
            $('.js-edit').on('click', function () {
                var url = $(this).attr('data-url');
                $('#addModal').modal('show');
                showLoader();
                $.getJSON(url)
                    .done(function (data) {
                        hideLoader();
                        $('#id').val(data.id);
                        $('#name').val(data.name);
                        $('#price').val(data.price);
                    });
            });

            $('.js-details').on('click', function () {
                addItemsUrl = $(this).attr('data-add-items-url');
                menuItemsUrl=$(this).attr('data-url');
                $('#detailsModal').modal('show');
                loadMenuItems(menuItemsUrl);
            });

            $('#itemsDiv').on('click', '.js-remove-item', function () {
                var url = $(this).attr('data-url');
                var button = $(this);
                button.button('loading');
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                }).done(function () {
                    loadMenuItems(menuItemsUrl);
                });
            });

            const products = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/api/products?query=%QUERY',
                    wildcard: '%QUERY'
                }
            });

            $('#product').typeahead({
                    minLength: 1,
                    highlight: true
                },
                {
                    name: 'products',
                    display: 'name',
                    source: products
                }).on('typeahead:select',
                function (e, product) {
                    $('#product_id').val(product.id);
                });

            $('#addItemsForm').on('submit', function (e) {
                e.preventDefault();
                $('#message').html("");
                var form = $(this);
                var button = $('#addItemButton');
                button.button('loading');
                $.post(addItemsUrl, form.serialize())
                    .done(function (data) {
                        $('#itemsDiv').html(data);
                        form[0].reset();
                        button.button('reset');
                        $('#product_id').val("");
                    })
                    .fail(function (data) {
                        button.button('reset');
                        $('#message').html('<div class="alert alert-warning flat  alert-dismissible" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button>' + data.responseJSON.message + '</div>');
                    });
            });

        });


        var loadItems=function () {

        };
    </script>
@endsection
