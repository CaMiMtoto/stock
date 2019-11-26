@extends('layouts.master')
@section('title','Users')
@section('styles')
    <link rel="stylesheet" href="{{ asset('lib/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary flat">
                    <div class="box-header with-border">
                        <h4 class="panel-title">
                            <i class="fa fa-square"></i> Manage users
                        </h4>
                        <div class="box-tools">
                            <button data-toggle="modal" data-target="#addModal" type="button"
                                    class="btn btn-default pull-right btn-sm flat">
                                <i class="fa fa-user-plus"></i>
                                Add New User
                            </button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-condensed table-bordered table-striped table-responsive table-hover"
                               id="manageTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- add categories -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form novalidate class="form-horizontal" id="submitForm" action="{{ route('users.store') }}"
                      method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add User</h4>
                    </div>
                    <div class="modal-body">

                        <div id="add-messages"></div>
                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">

                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Name</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-7">
                                <input type="text" required class="form-control" id="name" placeholder="User full Name"
                                       name="name" autocomplete="off">
                            </div>
                        </div> <!-- /form-group-->
                        <div class="form-group">
                            <label for="email" class="col-sm-4 control-label">Email</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-7">
                                <input type="email" required class="form-control" id="email" placeholder="Email address"
                                       name="email" autocomplete="off">
                            </div>
                        </div> <!-- /form-group-->
                        <div class="form-group">
                            <label for="role" class="col-sm-4 control-label">Role</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-7">
                                <select name="role" required id="role" class="form-control">
                                    <option value="">--select user role--</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> <!-- /form-group-->
                        <div class="form-group">
                            <label for="password" class="col-sm-4 control-label">Password</label>
                            <label class="col-sm-1 control-label">: </label>
                            <div class="col-sm-7">
                                <input type="password" required class="form-control" id="password"
                                       placeholder="Password" name="password" autocomplete="off">
                            </div>
                        </div> <!-- /form-group-->
                    </div> <!-- /modal-body -->
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="glyphicon glyphicon-remove-sign"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary" id="createBtn" data-loading-text="Loading...">
                                <i class="glyphicon glyphicon-ok-sign"></i> Save Changes
                            </button>
                        </div>
                    </div> <!-- /modal-footer -->
                </form> <!-- /.form -->
            </div> <!-- /modal-content -->
        </div> <!-- /modal-dailog -->
    </div>
    <!-- /add categories -->

    <!-- edit categories  -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form novalidate class="form-horizontal" id="editForm" action="{{ route('users.update') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit user</h4>
                    </div>
                    <div class="modal-body">
                        <div id="edit-messages"></div>

                        <div class="modal-loading div-hide"
                             style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            <span class="sr-only">Loading...</span>
                        </div>

                        <div class="edit-result">
                            <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                            <div class="form-group">
                                <label for="edit_name" class="col-sm-4 control-label">Name</label>
                                <label class="col-sm-1 control-label">: </label>
                                <div class="col-sm-7">
                                    <input type="text" required class="form-control" id="edit_name"
                                           placeholder="User full Name" name="name" autocomplete="off">
                                </div>
                            </div> <!-- /form-group-->
                            <div class="form-group">
                                <label for="edit_email" class="col-sm-4 control-label">Email</label>
                                <label class="col-sm-1 control-label">: </label>
                                <div class="col-sm-7">
                                    <input type="email" required class="form-control" id="edit_email"
                                           placeholder="Email address" name="email" autocomplete="off">
                                </div>
                            </div> <!-- /form-group-->
                            <div class="form-group">
                                <label for="edit_role" class="col-sm-4 control-label">Role</label>
                                <label class="col-sm-1 control-label">: </label>
                                <div class="col-sm-7">
                                    <select name="role" id="edit_role" class="form-control">
                                        <option value="">--select user role--</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->display }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> <!-- /form-group-->
                            <div class="form-group">
                                <label for="password" class="col-sm-4 control-label">Password</label>
                                <label class="col-sm-1 control-label">: </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="password" placeholder="Password"
                                           name="password" autocomplete="off">
                                </div>
                            </div> <!-- /form-group-->
                        </div>
                        <!-- /edit brand result -->

                    </div> <!-- /modal-body -->

                    <div class="modal-footer editFooter">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="glyphicon glyphicon-remove-sign"></i> Close
                            </button>

                            <button type="submit" class="btn btn-primary" id="editBtn" data-loading-text="Loading..."><i
                                    class="glyphicon glyphicon-ok-sign"></i> Save Changes
                            </button>
                        </div>
                    </div>
                    <!-- /modal-footer -->
                </form>
                <!-- /.form -->
            </div>
            <!-- /modal-content -->
        </div>
        <!-- /modal-dailog -->
    </div>
    <!-- /categories  -->

@endsection
@section('scripts')
    <script src="{{ asset('lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lib/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>

        var defaultUrl = "{{ route('users.all')  }}";
        var table;
        var manageTable = $("#manageTable");

        function myFunc() {
            table = manageTable.DataTable({
                "bProcessing": true,
                "serverSide": true,
                ajax: {
                    url: defaultUrl,
                    method: 'POST',
                    dataSrc: 'data',
                    data: {_token: "{{csrf_token()}}"}
                },
                columns: [
                    {data: 'name', 'sortable': true},
                    {data: 'email', 'sortable': true},
                    {data: 'role.display', 'sortable': false},
                    {
                        data: 'id',
                        'sortable': false,
                        render: function (data, type, row) {
                            return "<div class='btn-group btn-group-sm'>" +
                                "<button class='btn btn-default btn-sm flat js-edit' " +
                                "data-url='/admin/users/show/" + row.id + "' data-id='" + row.id + "'> " +
                                "<i class='glyphicon glyphicon-edit'></i></button>" +
                                "<button class='btn btn-warning  btn-sm flat js-delete' data-id='" + data +
                                "' data-url='/admin/users/destroy/" + row.id + "'> " +
                                "<i class='glyphicon glyphicon-trash'></i>" +
                                "</button>" +
                                "</div>";
                        }
                    }
                ]
            });
        }


        $(document).ready(function () {
            $('.mn-users').addClass('active');
            myFunc();
            manageTable.on("click", ".js-edit", function () {
                var findUrl = $(this).attr("data-url");
                // remove hidden  id text
                $('#id').remove();
                // Launching edit modal
                $("#editModal").modal();
                // edit categories messages
                $("#edit-messages").html("");
                // modal spinner
                $('.modal-loading').removeClass('div-hide');
                // modal result
                $('.edit-result').addClass('div-hide');
                //modal footer
                var footer = $(".editFooter");
                footer.addClass('div-hide');
                $.ajax({
                    url: findUrl,
                    method: "get",
                    dataType: "json"
                }).done(function (response) {
                    // modal spinner
                    $('.modal-loading').addClass('div-hide');
                    // modal result
                    $('.edit-result').removeClass('div-hide');
                    //modal footer
                    footer.removeClass('div-hide');
                    // set the categories name
                    $("#edit_name").val(response.name);
                    $("#edit_role").val(response.role_id);
                    $("#edit_email").val(response.email);
                    // add the categories id
                    footer.after('<input type="hidden" name="id" id="id" value="' + response.id + '" />');
                }).fail(function (error) {
                    alert("Error getting data");
                });
                return false;
            });

            $('#editForm').on('submit', function (e) {

                e.preventDefault();
                var btn = $('#editBtn');

                var form = $(this);
                if (!form.valid()) return false;

                btn.button('loading');
                $.post(form.attr('action'), form.serialize())
                    .done(function (data) {
                        btn.button('reset');
                        table.ajax.reload(null, false);
                        $('#editModal').modal('hide');
                    }).fail(function (error) {
                    btn.button('reset');
                });
            });
        });
    </script>
@endsection
