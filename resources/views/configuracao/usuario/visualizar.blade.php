@extends('core.app')

@section('titulo', 'Usuário')

@section('content')

<migalha titulo="Usuário" descricao="Visualizar usuário"></migalha>

<!-- Main content -->
<section class="content" ng-controller="visualizarUsuarioCtrl">

    <!-- first row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <form role="form">

                    <div class="box-body row" style="padding-bottom: 0;">
                        <div class="col-md-4">
                            <label >Nome:</label>
                            <p class="text-muted">@{{ usuario.nome }}</p>
                        </div>
                        <div class="form-group col-md-4">
                            <label >Perfil:</label>
                            <p class="text-muted">@{{ usuario.perfil }}</p>
                        </div>
                        <div class="form-group col-md-4">
                            <label >Funcionário:</label>
                            <p class="text-muted">@{{ usuario.funcionario }}</p>
                        </div>
                    </div>

                    <div class="box-body row" style="padding-top: 0;">
                        <div class="form-group col-md-4">
                            <label >Usuário:</label>
                            <p class="text-muted">@{{ usuario.usuario }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Data de inclusão:</strong>
                            <p class="text-muted" ng-if='usuario.created_at'>
                                @{{ usuario.created_at | date: 'dd/MM/yyyy - HH:mm:ss' }}
                                <span ng-if='usuario.usuarioInclusao[0]'>
                                    (@{{ usuario.usuarioInclusao[0].nome }})
                                </span>
                            </p>
                            <p class="text-muted" ng-if='!usuario.created_at'>-</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Data de alteração:</strong>
                            <p class="text-muted" ng-if='usuario.updated_at'>
                                @{{ usuario.updated_at | date: 'dd/MM/yyyy - HH:mm:ss' }}
                                <span ng-if='usuario.usuarioAlteracao[0]'>
                                    (@{{ usuario.usuarioAlteracao[0].nome }})
                                </span>
                            </p>
                            <p class="text-muted" ng-if='!usuario.updated_at'>-</p>
                        </div>
                    </div>

                    @can( 'CONFIGURACAO_USUARIO_EDITAR' )
                    <div class="box-footer">
                        <a href="/configuracao/usuario/@{{ usuario.id }}/edit" title="Editar">
                            <button type="button" class="btn btn-primary">Editar</button>
                        </a>
                    </div>
                    @endcan
                </form>
                
            </div>
            <!-- /.box -->
            
        </div>
        <!-- /.left colum -->
    </div>
    <!-- /.first row -->

</section>
<!-- /.content -->

<script>    
    var _usuario =  {!! $usuario !!};       
    var _migalhas =  <?=json_encode($migalhas);?>;
</script>

@endsection
