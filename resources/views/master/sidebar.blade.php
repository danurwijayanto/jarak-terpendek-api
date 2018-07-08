<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a href="{{url('/admin/home')}}" style="color:#000 !important; font-size:15px !important">Angkutan Kota Semarang</a>
        </div>
        <!-- /Logo -->
        <ul class="nav navbar-top-links navbar-right pull-right">
            <li>
                <!--
                    <form role="search" class="app-search hidden-sm hidden-xs m-r-10">
                    <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
                -->
            </li>
            <li>
                <a class="profile-pic" href="#"> <img src="{{ url('plugins/images/users/varun.jpg') }}" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">{{ Auth::user()->name }}</b></a>
            </li>
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
<!-- End Top Navigation -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
        </div>
        <ul class="nav" id="side-menu">
            <li style="padding: 70px 0 0;">
                <a href="{{ url('/admin/home') }}" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>Home</a>
            </li>
            <li>
                <a href="{{ url('/admin/angkutan') }}" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Daftar Angkutan Kota</a>
            </li>
            <li>
                <a href="{{ url('/admin/lokasi') }}" class="waves-effect"><i class="fa fa-table fa-fw" aria-hidden="true"></i>Daftar Nama Tempat</a>
            </li>
            @guest
            @else
            <li>
                <a href="{{ route('logout') }}" class="waves-effect" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @endguest
        </ul>
    </div>
    
</div>
<!-- ============================================================== -->
<!-- End Left Sidebar -->
<!-- ============================================================== -->