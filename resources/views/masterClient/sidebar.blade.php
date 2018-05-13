<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo" href="index.html">
                <!-- Logo icon image, you can use font-icon also --><b>
                <!--This is dark logo icon--><img src="{{ url('plugins/images/admin-logo.png') }}" alt="home" class="dark-logo" /><!--This is light logo icon--><img src="{{ url('plugins/images/admin-logo-dark.png') }}" alt="home" class="light-logo" />
                </b>
                <!-- Logo text image you can use text also --><span class="hidden-xs">
                <!--This is dark logo text--><img src="{{ url('plugins/images/admin-text.png') }}" alt="home" class="dark-logo" /><!--This is light logo text--><img src="{{ url('plugins/images/admin-text-dark.png') }}" alt="home" class="light-logo" />
                </span> </a>
        </div>
        <!-- /Logo -->
        <ul class="nav navbar-top-links navbar-right pull-right">
            <li>
                <!--
                    <form role="search" class="app-search hidden-sm hidden-xs m-r-10">
                    <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
                -->
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
            <form action="{{ url('/proses') }}" method="POST">
                @csrf
                <?php
                    if (!empty($place_detail)){
                        $detail = json_decode($place_detail, true);
                        $count_data = count($detail);
                    }
                ?>
                <li style="padding: 70px 20px 0;">
                    <div class="form-group">
                        <label for="dari">Dari :</label>
                        <select class="form-control" name="dari">
                            @if (isset($lokasi) && !empty($lokasi))
                            @foreach ($lokasi as $list)
                                <option 
                                    value="{{ $list->pd_id }}"
                                    <?php 
                                        if (!empty($place_detail) && ($list->pd_id == $detail[0]['pd_id'])){
                                            echo ' selected="selected"';
                                        }
                                    ?>
                                >
                                {{ $list->pd_name }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </li>
                <li style="padding: 0px 20px 0;">
                    <div class="form-group">
                        <label for="tujuan">Ke :</label>
                        <select class="form-control" name="tujuan">
                            @if (isset($lokasi) && !empty($lokasi))
                            @foreach ($lokasi as $list)
                                <option 
                                    value="{{ $list->pd_id }}"
                                    <?php
                                    if (!empty($place_detail) && ($list->pd_id == $detail[$count_data-1]['pd_id'])){
                                        echo ' selected="selected"';
                                    }
                                    ?>
                                >{{ $list->pd_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default">Cari</button>
                </li>
            </form>
        </ul>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Left Sidebar -->
<!-- ============================================================== -->