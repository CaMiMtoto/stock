@extends('layouts.master')
@section('title','Dashboard')
@section('content')
    <section class="content-header">
        <h1>
            Dashboard
            <small>Summary</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ \App\Category::count() }}</h3>

                        <p>Categories</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-list"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ \App\Product::count() }}</h3>

                        <p>Products</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ number_format(App\Order::count()) }}</h3>

                        <p>Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-android-cart"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ number_format(\App\Product::sum('qty')) }}</h3>

                        <p>In stock</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ route('products.all') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary flat">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <i class="ion ion-ios-pricetags"></i>
                            Recent products</h4>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit Measure</th>
                                <th>In Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(App\Product::with('category')->orderByDesc('id')->limit(5)->get() as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->unit_measure }}</td>
                                    <td>{{ number_format($product->qty) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer text-center">
                        <a href="{{ route('products.all') }}" class="btn btn-link btn-sm">
                            More Info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning flat">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <i class="ion ion-fork"></i>
                            Recent food orders</h4>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Customer</th>
                                <th>Waiter</th>
                                <th>Payment Mode</th>
                                <th>Items</th>
                                <th>Amount Paid</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(App\Order::orderByDesc('id')->limit(5)->get() as $product)
                                <tr>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $product->customer_name }}</td>
                                    <td>{{ $product->waiter->name }}</td>
                                    <td>{{ $product->payment_mode }}</td>
                                    <td>{{ $product->orderItems->sum('qty') }}</td>
                                    <td>{{ number_format($product->amount_paid) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-center">
                        <a href="{{ route('orders.index') }}" class="btn btn-link btn-sm">
                            More Info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning flat">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <i class="ion ion-wineglass"></i>
                            Recent drinks orders</h4>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Customer</th>
                                <th>Waiter</th>
                                <th>Payment Mode</th>
                                <th>Items</th>
                                <th>Amount Paid</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(App\ProductOrder::orderByDesc('id')->limit(5)->get() as $product)
                                <tr>
                                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $product->customer_name }}</td>
                                    <td>{{ $product->waiter->name }}</td>
                                    <td>{{ $product->payment_mode }}</td>
                                    <td>{{ $product->productOrderItems->sum('qty') }}</td>
                                    <td>{{ number_format($product->amount_paid) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-center">
                        <a href="{{ route('productOrders.index') }}" class="btn btn-link btn-sm">
                            More Info <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $(function () {
            $('.mn-dashboard').addClass('active');
        });
    </script>
@endsection
