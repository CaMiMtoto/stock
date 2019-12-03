@extends('layouts.master')
@section('title','Sales report')
@section('content')

    <?php
    use App\Connection;$totalAmount = 0;
    $con = Connection::getConnection();
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
    $sql = 'select i.created_at, m.name as name, sum(i.qty) as quantity, i.price as price, (sum(i.qty) * i.price) as Total from order_items as i inner join menus as m on i.menu_id = m.id where DATE(i.created_at) between "' . $startDate . '" and "' . $endDate . '"group by i.menu_id';
    $result = mysqli_query($con, $sql);
    ?>
    <section class="content">

        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <!-- this row will not appear when printing -->
                <div class="no-print pull-right">
                    <div class="col-xs-12">
                        <button onclick="printDoc()" class="no-print btn btn-warning">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i>
                        Stock
                        <small class="pull-right">Date: {{ now()->format('D , d/m/Y') }}</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        <strong>{{ \Carbon\Carbon::parse($start_date)->format('l , d M Y') }}</strong><br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>{{ \Carbon\Carbon::parse($end_date)->format('l , d M Y') }}</strong><br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <h5>Sales reports</h5>
                    <table class="table table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                        $totalAmount += $row['Total'];
                        ?>
                        <tr>
                            <td>{{ $row['created_at'] }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['quantity'] }}</td>
                            <td>{{ number_format($row['price'] )}}</td>
                            <td>{{ number_format($row['Total']) }}</td>
                        </tr>
                        <?php
                        }
                        }
                        ?>


                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total amount</th>
                            <th>{{ number_format($totalAmount) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


        </section>
    </section>
    <?php
    mysqli_close($con);
    ?>
@endsection
@section('scripts')
    <script>
        $('.mn-reports').addClass('active');
    </script>
@endsection
