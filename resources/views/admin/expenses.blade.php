@extends('layouts.master')
@section('title','Expenses')
@section('content')
    <section class="content">


        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h3 class="box-title">
                    Manage expenses
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-primary  btn-sm float-right" id="addButton">
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
                        <th scope="col">Date</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Descriotion</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date }}</td>
                            <td>{{number_format($expense->amount)}}</td>
                            <td>{{ $expense->description }}</td>
                            <td>
                                <div class="btn-group flat">
                                    <button class="btn flat btn-secondary js-edit"
                                            data-url="{{ route('expenses.show',['id'=>$expense->id]) }}">
                                        Edit
                                    </button>
                                    <button class="btn flat btn-danger js-delete"
                                            data-url="{{ route('expenses.destroy',['id'=>$expense->id]) }}">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ $expenses->links() }}
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
                        Expense
                    </h4>
                </div>
                <form novalidate class="form-horizontal" action="{{ route('expenses.store') }}" method="post"
                      id="submitForm">

                    <div class="modal-body">
                        @include('layouts._loader')
                        <div class="edit-result">
                            <input type="hidden" id="id" name="id" value="0">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="date" class="col-sm-3 control-label">Date</label>
                                <div class="col-sm-9">
                                    <input required type="text" class="form-control datepicker"
                                           name="date" id="date">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="amount" class="col-sm-3 control-label">Amount</label>
                                <div class="col-sm-9">
                                    <input required type="text" class="form-control"
                                           name="amount" id="amount">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9">
                                    <input required type="text" class="form-control"
                                           name="description" id="description">
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
            $('.mn-expenses').addClass('active');

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
                        $('#date').val(data.date);
                        $('#amount').val(data.amount);
                        $('#description').val(data.description);
                    });
            });

        });
    </script>
@endsection
