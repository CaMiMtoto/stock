<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('img/avatar.png') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>


            <li class="mn-dashboard">
                <a href="{{ url('/admin/dashboard') }}">
                    <i class="fa fa-th"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="mn-categories">
                <a href="{{ url('/admin/categories') }}">
                    <i class="fa fa-list-ul"></i> <span>Categories</span>
                </a>
            </li>


            <li class="treeview tr-products">
                <a href="#">
                    <i class="ion ion-ios-list"></i> <span>Products</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="mn-products">
                        <a href="{{ url('/admin/products') }}">
                            <i class="fa fa-circle-o"></i>
                            Manage products
                        </a>
                    </li>
                    <li class="mn-stocks">
                        <a href="{{ route('stocks.all') }}">
                            <i class="fa fa-circle-o"></i>
                            Stocking
                        </a>
                    </li>
                    <li class="mn-requisitions">
                        <a href="{{ route('requisitions.all') }}">
                            <i class="fa fa-circle-o"></i>
                            Requisitions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('requests') }}">
                            <i class="fa fa-circle-o"></i>
                            Requests
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mn-menus">
                <a href="{{ route('menus.all') }}">
                    <i class="fa fa-list-ol"></i>
                    <span>Menus</span>
                </a>
            </li>
            <li class="mn-orders">
                <a href="{{ route('orders.index') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="mn-expenses">
                <a href="{{ route('expenses.all') }}">
                    <i class="ion ion-pricetags"></i>
                    <span>Expenses</span>
                </a>
            </li>

            <li class="treeview tr-eod">
                <a href="#">
                    <i class="ion ion-calendar"></i> <span>
                        End of Day
                    </span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li class="mn-eod">
                        <a href="{{ route('eod') }}">
                            <i class="fa fa-circle-o"></i>
                            Run EOD
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mn-reports">
                <a href="{{ route('reports') }}">
                    <i class="ion ion-printer"> </i>
                    <span>Reports</span>
                </a>
            </li>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
