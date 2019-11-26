@extends('layouts.master')
@section('title','Change Password')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if(isset($error))
                    <div class="alert alert-danger flat">
                        <p>
                            <i class="fa fa-warning"></i>
                            {{ $error}}
                        </p>
                    </div>
                @endif
                <div class="box box-success flat">
                    <div class="box-header with-border">
                        <h4>
                            <i class="fa fa-wrench"></i>
                            Change Password
                        </h4>
                        <div class="box-tools">
                        </div>
                    </div>
                    <form action="{{ route('password.update',[$user->id]) }}" autocomplete="off" method="post"
                          class="form-horizontal" id="saveForm">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group form-group-lg">
                                <label for="email" class="control-label col-md-3">Email</label>
                                <div class="col-md-9">
                                    <input type="email" value="{{ $user->email }}" disabled class="form-control flat"
                                           id="email" required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label for="old_password" class="control-label col-md-3">
                                    Old Password
                                </label>
                                <div class="col-md-9">
                                    <input type="password" name="oldPassword" class="form-control flat"
                                           id="old_password"
                                           required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label for="new_password" class="control-label col-md-3">
                                    New Password
                                </label>
                                <div class="col-md-9">
                                    <input type="password" name="newPassword" class="form-control flat"
                                           id="new_password"
                                           required>
                                </div>
                            </div>
                            <div class="form-group form-group-lg">
                                <label for="repeat_password" class="control-label col-md-3">
                                    Repeat New Password
                                </label>
                                <div class="col-md-9">
                                    <input type="password" equalTo="#new_password" name="repeat_password"
                                           class="form-control flat" id="repeat_password"
                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-9 col-md-offset-3">
                                    <button type="submit" class="btn btn-success btn-lg" id="saveBtn">
                                        <i class="fa fa-check-circle"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </section>

@endsection
@section('scripts')
    <script>
        $('.tr-settings').addClass('active');
        $('.mn-changePassword').addClass('active');

        $('#saveForm').validate();

        $(function () {
            $('#saveForm').on('submit', function (event) {
                event.preventDefault();
                var form = $(this);
                if (!form.valid()) return false;
                $('#saveBtn').button('loading');
                event.target.submit();
            });
        });
    </script>
@endsection
