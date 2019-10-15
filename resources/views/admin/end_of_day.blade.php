@extends('layouts.master')
@section('title','Run EOD')
@section('content')
    <section class="content">
        <div class="col-md-6 col-md-offset-3">
            @if(Session::has("message"))
                <div class="alert alert-success">
                    <p>{{ Session::get("message") }}</p>
                </div>
            @endif
            @if(Session::has("error"))
                <div class="alert alert-danger">
                    <p>{{ Session::get("error") }}</p>
                </div>
            @endif
            <div class="box box-primary box-solid flat">
                <div class="box-header with-border">
                    <h4>
                        <i class="ion-ios-calendar"></i>
                        End of Day
                    </h4>
                </div>
                <form action="{{ route('runEod') }}" id="form" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group form-group-lg">
                            <label for="date_now" class="control-label">Date</label>
                            <input type="text" id="date_now" class="form-control" value="{{ $date->format('d-m-Y') }}"
                                   disabled>
                        </div>
                        <div class="form-group form-group-lg">
                            <label for="date_now" class="control-label">New Date</label>
                            <input type="text" id="date_now" class="form-control"
                                   value="{{ $date->addDay()->format('d-m-Y') }}" disabled>
                        </div>
                    </div>
                    <div class="box-footer">

                        <button type="submit" {{ $date->addDays(2) > now()->addDays(3) ? 'disabled':'' }} class="btn btn-primary center-block btn-block btn-lg" id="createBtn">
                            <i class="fa fa-check-circle"></i>
                            Submit EOD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $('.tr-eod').addClass('active');
        $('.mn-eod').addClass('active');
        $('#form').on('submit', function (e) {
            e.preventDefault();
            $('#createBtn').button('loading');
            e.target.submit();
        });
    </script>
@endsection
