@extends('layouts.master')
@section('title','Categories')
@section('content')
    <section class="content">


        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Manage categories
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-primary  btn-sm" id="addButton">
                        <i class="fa fa-plus"></i>
                        Add New
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Products</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>{{$cat->products->count()}}</td>
                            <td>
                                @if(Auth::user()->role=='manager' || Auth::user()->role=='admin' || Auth::user()->role=='keeper')
                                    <div class="btn-group flat">
                                        <button class="btn flat btn-secondary js-edit"
                                                data-url="{{ route('category.show',['id'=>$cat->id]) }}">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn flat btn-danger js-delete"
                                                data-url="{{ route('category.destroy',['id'=>$cat->id]) }}">
                                            <i class="fa fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ $categories->links() }}
            </div>
        </div>
    </section>

    <div class="modal fade myModal" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">
                        Category
                    </h4>
                </div>
                <form novalidate class="form-horizontal" action="{{ route('category.store') }}" method="post"
                      id="submitForm">

                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <input type="hidden" id="id" name="id" value="0">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Name</label>
                                <div class="col-sm-9">
                                    <input required minlength="2" maxlength="50" type="text" class="form-control"
                                           name="name" id="name">
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('.tr-settings').addClass('active');
            $('.mn-categories').addClass('active');

            $('#addButton').on('click', function () {
                $('#addModal').modal();
                $('#id').val(0);
                $('#submitForm')[0].reset(); //resetting form
            });


            $('.js-edit').on('click', function () {
                var url = $(this).attr('data-url');
                $('#addModal').modal();
                showLoader();
                $.getJSON(url)
                    .done(function (data) {
                        hideLoader();
                        $('#id').val(data.id);
                        $('#name').val(data.name);
                    });
            });

        });
    </script>
@endsection
