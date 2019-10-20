@section('titulo', 'Login')

@include('core.inicio')

<!-- body | inicio.html -->
<body class="login-page pace-done">

    <!-- .login-box -->
    <div class="login-box">

        <!-- .login-box-body -->
        <div class="login-box-body">

            <div class="login-logo">
                <img src="{{ asset($logoLogin) }}" alt="">
            </div>
    
            <form action="{{ route('login') }}" role="form" method="POST" data-toggle="validator">
            {{ csrf_field() }}

                <div class="form-group has-feedback">
                    <input type="text" name="usuario" id="email" value="" class="form-control" placeholder="Usuário" autofocus data-error="Por favor, preencha este campo!" required>
                    <span class="fa fa-user form-control-feedback"></span>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="form-group has-feedback">
                    <input id="password" type="password" name="password" value="" class="form-control" placeholder="Senha" data-error="Por favor, preencha este campo!" required>
                    <span class="fa fa-lock form-control-feedback"></span>
                    <div class="help-block with-errors"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar</button>                

                @if (count($errors) > 0)
                <div class="form-group has-error" style="margin-bottom:0px; padding-top: 10px;">                    
                    @if($errors->any())                        
                        <span class="help-block text-center" style="margin-bottom:0px;">
                            <strong>{{  $errors->first( ) }}</strong>
                        </span> 
                    @endif
                </div>
                @endif

                <div class="form-group has-success" style="margin-bottom:0px; padding-top:0px;">
                    <span class="help-block text-center" style="margin-bottom:0px;"></span>
                </div>
              
            </form>

        </div>
        <!-- /.login-box-body -->

    </div>
    <!-- /.login-box -->

</body>
<!-- /body | fim.html -->

</html>
    
