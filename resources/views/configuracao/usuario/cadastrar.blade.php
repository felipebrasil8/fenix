@extends('core.app')

@section('titulo', 'Usuário')

@section('content')

<migalha titulo="Usuário" descricao="Cadastrar usuário"></migalha>       

<!-- Main content -->
<section class="content" ng-controller="cadastrarUsuarioCtrl">
    
    <!-- first row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" ng-submit="cadastrar(usuario)">
                    
                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-4">
                            <label>Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="usuario.nome" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Perfil:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                
                                    <select class="form-control input-sm" ng-model="usuario.perfil" ng-options="x.nome for x in perfis" required>
                                        <option value=""></option>
                                    </select>                                              
                                    <div class="help-block with-errors" ></div>
                                </div>                                 

                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Funcionário:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                    <select class="form-control input-sm" ng-model="usuario.funcionario" ng-options="x.nome for x in funcionarios">
                                        <option value=""></option>
                                    </select>                                    
                                    <div class="help-block with-errors" ></div>
                                </div>                                 
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-body row" style="padding-top: 0; padding-bottom: 0;">
                        <div class="form-group col-md-4">
                            <label>Usuário:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm"  ng-model="usuario.usuario" data-match-error="Campo obrigatório" style="text-transform: lowercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label >Senha: 
                                <span data-toggle="tooltip" data-placement="right" data-original-title="@{{politicaSenha}}" data-html="true">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                            </label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="password" class="form-control input-sm" ng-model="usuario.senha" data-match-error="Campo obrigatório" required>
                                    <div class="help-block with-errors"></div>                                    
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group col-md-4">
                            <label >Confirmar senha:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="password" class="form-control input-sm" ng-model="usuario.campoConfirmarSenha" data-match-error="Campo obrigatório" data-match-error="Campo obrigatório" required>
                                    <div class="help-block with-errors"></div>                                    
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>  
                    </div>
                    
                    @can( 'CONFIGURACAO_USUARIO_CADASTRAR' )
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
    var _perfis = {!!$perfis!!};
    var _funcionarios = {!!$funcionarios!!};
    var _migalhas =  <?=json_encode($migalhas);?>;    
</script>

@endsection
