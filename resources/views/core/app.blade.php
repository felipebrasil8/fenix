@include('core.inicio')

<!-- body | inicio.html -->
<body class="hold-transition skin-blue sidebar-mini" ng-cloak>

    <div class="wrapper">
        @include('core.header')
       
        @include('core.menu')

        <!-- Content Wrapper -->
        <div class="content-wrapper" id="content-wrapper">
                        
            @include('core.breadcrumb')

            <!-- Main content | View -->

            @yield('content')
            
            <!-- /.content -->
            
        </div>
        <!-- /.content-wrapper -->

        @include('core.footer')

    </div><!-- ./wrapper -->

</body>
<!-- /body | fim.html -->

</html>