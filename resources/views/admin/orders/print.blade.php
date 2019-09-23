@extends('layouts.master')
@section('title','Print')

@section('content')
  <section class="content">
{{--      <div style="display: flex;justify-content: center;">
          <button  onclick="window.print();" class="btn btn-primary no-print">
              <i class="fa fa-print"></i>
              Print
          </button>
      </div>--}}

      <div class="clearfix"></div>
      @include('admin.orders.order_details')
  </section>
@endsection

@section('scripts')
    <script>
        window.print();
    </script>
@endsection
