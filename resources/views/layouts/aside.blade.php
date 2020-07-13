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

            @if(\App\Shift::getCurrentShift())
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
                                <i class="fa fa-circle"></i>
                                Manage products
                            </a>
                        </li>
                        @if(Auth::user()->role->name!='cashier')
                            <li class="mn-stocks">
                                <a href="{{ route('stocks.all') }}">
                                    <i class="fa fa-circle"></i>
                                    Stocking
                                </a>
                            </li>
                            <li class="mn-damages">
                                <a href="{{ route('damages.all') }}">
                                    <i class="fa fa-circle"></i>
                                    Damaged Products
                                </a>
                            </li>
                        @endif

                        <li class="mn-requests">
                            <a href="{{ route('requests') }}">
                                <i class="fa fa-circle"></i>
                                Requisitions
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->role->name!='keeper')
                <li class="mn-menus">
                    <a href="{{ route('menus.all') }}">
                        <i class="fa fa-circle"></i>
                        Menus
                    </a>
                </li>
                @if(\App\Shift::getCurrentShift())
                    <li class="treeview tr-orders">
                        <a href="#">
                            <i class="ion ion-ios-pricetag-outline"></i>
                            <span>Orders</span>
                            <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="mn-food">
                                <a href="{{ route('orders.index') }}">
                                    <i class="fa fa-circle"></i>
                                    Food & Beverage
                                </a>
                            </li>
                            <li class="mn-drinks">
                                <a href="{{ route('productOrders.index') }}">
                                    <i class="fa fa-circle"></i>
                                    Drinks
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            @endif
            <li class="treeview tr-settings">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>Settings</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="mn-changePassword">
                        <a href="{{ route('changePassword') }}">
                            <i class="fa fa-circle"></i>
                            Change Password
                        </a>
                    </li>

                    <li class="mn-categories">
                        <a href="{{ url('/admin/categories') }}">
                            <i class="fa fa-circle"></i> <span>Categories</span>
                        </a>
                    </li>

                </ul>
            </li>

            {{--            <li class="mn-expenses">--}}
            {{--                <a href="{{ route('expenses.all') }}">--}}
            {{--                    <i class="ion ion-pricetags"></i>--}}
            {{--                    <span>Expenses</span>--}}
            {{--                </a>--}}
            {{--            </li>--}}
            <li class="mn-shifts">
                <a href="{{ route('shifts') }}">
                    <i class="fa fa-clock-o"></i>
                    <span>Shift Manager</span>
                </a>
            </li>
            @if(Auth::user()->role->name=='admin')
                <li class="mn-users">
                    <a href="{{ route('users.index') }}">
                        <i class="ion ion-android-people"> </i>
                        <span>Users</span>
                    </a>
                </li>
            @endif
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
