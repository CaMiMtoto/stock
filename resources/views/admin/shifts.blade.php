@extends('layouts.master')
@section('title','Shift Manager')
@section('styles')
    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <section class="content">
        <div class="col-md-8">
            <div class="box box-info flat">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <i class="ion-ios-calendar"></i>
                        Shift Manager
                    </h4>
                </div>
                <div class="box-body">
                    @if($shifts->count() ==0)
                        <div class="alert flat alert-info">
                            <h4>No shift yet recorded</h4>
                        </div>
                    @else
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Opened By</th>
                                <th>Closed By</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shifts as $item)
                                <tr>
                                    <td>{{ $item->created_at->format('l') }}</td>
                                    <td>{{ $item->start_time }}</td>
                                    <td>{!!  $item->end_time??'<span class="label label-info">Not yet closed</span>' !!}</td>
                                    <td>{{ $item->openedBy()->name }}</td>
                                    <td>{!! $item->closedBy()->name??'<span class="label label-info">Not yet closed</span>' !!} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="box-footer">
                    {{ $shifts->links() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-info flat">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <i class="ion-ios-clock"></i>
                        Current shift
                    </h4>
                </div>
                <div class="box-body"><!-- Rounded switch -->

                    @if(Session::has('error'))
                        <div class="alert alert-info flat">
                            <p>{{ Session::get('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('shifts.save') }}" method="post" id="form">
                        {{ csrf_field() }}
                        <label class="switch">
                            <input id="shift_id" name="shift" type="checkbox" {{ $todayShift!=null?'checked':'' }} >
                            <span class="slider round"></span>
                        </label>
                        @if($todayShift)
                            <strong> Open</strong>
                            <div>
                                <p class="h4">
                                    Shift From: <strong>{{ $todayShift->start_time }}</strong>
                                </p>
                                <p class="h4">
                                    Shift By: <strong>{{ $todayShift->openedBy()->name }}</strong>
                                </p>
                            </div>
                        @else
                            <strong> Closed</strong>

                        @endif
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>

        $('.mn-shifts').addClass('active');

        $('#form').on('submit', function (e) {
            e.preventDefault();
            $('#createBtn').button('loading');
            e.target.submit();
        });

        $('#shift_id').on('change', function () {
            var checkBox=$(this);
            var result = confirm('Are you sure you want to change this shift?');
            if (result){
                $('#form').submit();
            }else{
                var value=checkBox.prop('checked');
                checkBox.prop('checked',!value);
                console.log(value);
            }
        });
    </script>
@endsection
