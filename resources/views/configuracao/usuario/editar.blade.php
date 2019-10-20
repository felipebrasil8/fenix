@extends('core.app')

@section('titulo', 'Usuário')

@section('content')

<migalha titulo="Usuário" descricao="Editar usuário"></migalha> 
    
<!-- Main content -->
<section class="content" ng-controller="editarUsuarioCtrl">
    
    <!-- first row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" ng-submit="atualizar(usuario)">
                    
                    <div class="box-body row" style="padding-bottom: 0;">
                        <div class="form-group col-md-4">
                            <label >Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" class="form-control input-sm" ng-model="usuario.nome" data-match-error="Campo obrigatório" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label >Perfil:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <select class="form-control input-sm" ng-model="usuario.perfil" ng-options="item as item.nome for item in perfis track by item.id" required>
                                        <option value=""></option>
                                    </select>  
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label >Funcionário:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <select class="form-control input-sm" ng-model="usuario.funcionario" ng-options="item as item.nome for item in funcionarios track by item.id">
                                        <option value=""></option> 
                                    </select>  
                                    <div class="help-block with-errors" ></div>
                                </div>                                
                            </div>
                        </div>
                    </div>

                    <div class="box-body row" style="padding-top: 0; padding-bottom: 0;">
                        <div class="form-group col-md-4">
                            <label >Usuário:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" class="form-control input-sm" ng-model="usuario.usuario" style="text-transform: lowercase;" data-match-error="Campo obrigatório"  required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    @can( 'CONFIGURACAO_USUARIO_EDITAR' )
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" ng-disabled="!form.$valid">Salvar</button>
                    </div>
                    @endcan

                </form>

            </div>
            <!-- /.box -->
            
        </div>
        <!-- /.left colum -->
    </div>
    <!-- /.first row -->

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>     
        
    <!-- Sucesso -->
    <msg-success ng-show="success"></msg-success>     
    
</section>
<!-- /.content -->

<script>    
    var _usuario = {!! $usuario !!};    
    var _perfis = {!!$perfis!!};   
    var _funcionarios = {!!$funcionarios!!};
    var _perf_selected = {!!$perf_selected!!};    
    var _func_selected = {!!$func_selected!!};    
    var _migalhas =  <?=json_encode($migalhas);?>;
</script>
   
@endsection
