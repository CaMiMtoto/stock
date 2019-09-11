@extends('layouts.master')
@section('title','Suppliers')
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
                            Manage Suppliers
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('suppliers.all') }}" method="get">
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
                <div class="box-body">
                    <table class="table table-condensed table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Address</th>
                            <th scope="col">Phone</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($suppliers as $sup)
                            <tr>
                                <td>{{ $sup->name}}</td>
                                <td>{{ $sup->email}}</td>
                                <td>{{ $sup->address}}</td>
                                <td>{{ $sup->phone_number}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                                data-url="{{ route('suppliers.show',['id'=>$sup->id]) }}"
                                                class="btn btn-secondary js-edit">
                                            <i class="glyphicon glyphicon-edit"></i>

                                        </button>
                                        <button
                                                data-url="{{ route('suppliers.destroy',['id'=>$sup->id]) }}"
                                                class="btn btn-danger js-delete">
                                            <i class="glyphicon glyphicon-trash"></i>
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
                        Supplier
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h4>
                </div>
                <form novalidate action="{{ route('suppliers.store') }}" method="post" id="submitForm"
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
                                <label for="email" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-sm-3 control-label">Address</label>
                                <div class="col-sm-9">
                                    <input type="text" required minlength="2" class="form-control" name="address"
                                           id="address">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_number" class="col-sm-3 control-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="tel"
                                           required minlength="10" maxlength="13"
                                           class="form-control" name="phone_number" id="phone_number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer editFooter">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            $('.mn-suppliers').addClass('active');
            //edit
            $('.js-edit').on('click', function () {
                var url = $(this).attr('data-url');
                $('#addModal').modal('show');
                showLoader();
                $.getJSON(url)
                    .done(function (data) {
                        hideLoader();
                        $('#name').val(data.name);
                        $('#id').val(data.id);
                        $('#email').val(data.email);
                        $('#address').val(data.address);
                        $('#phone_number').val(data.phone_number);
                    });
            });


        });
    </script>
@endsection
