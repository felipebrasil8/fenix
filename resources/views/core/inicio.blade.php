<!DOCTYPE html>
<html ng-app="app">
<head>
    
    <!-- Charset da página -->
    <meta charset="UTF-8">
    
    <!-- Tell the browser to be responsive to screen width -->
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>

    <!-- Favicon -->
    <link rel="shortcut icon" id="favicon" href="{{ asset ('/img/favicon.ico') }}" >
    
    <!-- Titulo da página -->
    <title>Fenix - @yield('titulo')</title>

    <!-- CSS -->
    <link href="{{ asset ('/css/all.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Fonts -->
    <link href="{{ asset ('/fonts/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset ('/fonts/font-SourceSansPro.css') }}" rel="stylesheet" type="text/css" />

    <!-- JavaScript -->
    
    @stack('css')  
        
    <!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/hammer.js/1.0.5/hammer.min.js'></script>    
    <script src='https://s.cdpn.io/61329/angular-hammer.js'>        
    </script><script src='https://s3-us-west-2.amazonaws.com/s.cdpn.io/61329/ngRepeatReorder.js'></script> -->
    
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ asset ('/js/all.js') }}"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
