@extends('layouts.master')

@section('title','Product History')

@section('content')

    <?php
    use App\Connection;
    $con = Connection::getConnection();
    $date = $_GET['date'];
    $sql = 'select p.name,s.opening,s.received,s.sold,s.closing  from stock_transactions s inner join products p on s.product_id = p.id where DATE(s.created_at) = "' . $date . '" and p.category_id=1
group by p.id';
    $result = mysqli_query($con, $sql);
    ?>
    <section class="content">
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4>Product History
                    <small>{{ $date }}</small>

                    <button class="btn btn-primary pull-right btn-sm no-print" onclick="window.print()">
                        <i class="fa fa-print"></i>
                        Print Report
                    </button>
                </h4>
            </div>
            <div class="box-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="6">
                            <h4>Drinks</h4>
                        </th>
                    </tr>
                    <tr>
                        <th>Product</th>
                        <th>Opening</th>
                        <th>Received</th>
                        <th>Sold</th>
                        <th>Closing</th>
                        <th>Total avail.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['opening'] }}</td>
                        <td>{{ $row['received'] }}</td>
                        <td>{{ $row['sold'] }}</td>
                        <td>{{ $row['closing'] }}</td>
                        <td>{{ $row['opening']+$row['received'] }}</td>
                    </tr>
                    <?php
                    }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </section>
@endsection
