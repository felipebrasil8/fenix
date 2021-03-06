@extends('core.app')

@section('titulo', 'Parâmetro')

@section('content')

<migalha titulo="Parâmetro" descricao="Editar parâmetro"></migalha> 

<!-- Main content -->
<section class="content" ng-controller="editarParametroCtrl">
    
    <!-- first row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" ng-submit="atualizar(parametro)" ng-show="visualizar_editar == true">
                    
                    <div class="box-body row" style="padding-bottom: 0;">
                        
                        <div class="form-group col-md-3">
                            <label>Grupo:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                
                                    <select class="form-control input-sm" ng-model="parametro.grupo" ng-options="item as item.nome for item in grupos track by item.id" required>
                                        <option value=""></option>
                                    </select>                                              
                                    <div class="help-block with-errors" ></div>
                                </div>                                 

                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Tipo:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                
                                    <select class="form-control input-sm" ng-model="parametro.tipo" ng-options="item as item.nome for item in tipos track by item.id" required>
                                        <option value=""></option>
                                    </select>                                              
                                    <div class="help-block with-errors" ></div>
                                </div>                                 

                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="parametro.nome" style="text-transform: uppercase;" required maxlength="50">
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Descrição:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="parametro.descricao" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    
                    <div class="box-body row" style="padding-top: 0; padding-bottom: 0;">
                        <div class="form-group col-md-3">
                            <label>Valor:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="parametro.valor" style="text-transform: uppercase;" >
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;"></div>                                
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label >Ordem:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="parametro.ordem" data-match-error="Campo obrigatório" ng-keypress="keyPress($event)" maxlength="10" required>
                                    <div class="help-block with-errors"></div>                                    
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label>Editar:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                    <select class="form-control input-sm" ng-model="parametro.editar" required>
                                        <option value=""></option>
                                        <option value="true">SIM</option>
                                        <option value="false">NÃO</option>
                                    </select>                                              
                                    <div class="help-block with-errors" ></div>
                                </div>                                 

                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>
                        
                    </div>
                    
                    @can( 'CONFIGURACAO_SISTEMA_PARAMETRO_EDITAR' )
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" ng-disabled="!form.$valid">Salvar</button> 
                    </div>
                    @endcan 

                </form>

                <div class="box-body row" style="padding-bottom: 0;" ng-show="visualizar_editar == false">
                    <div class="form-group col-md-12">
                        <label>Este parâmetro não pode ser editado.</label>
                    </div>
                </div>

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
    var _parametro = {!!$parametro!!};
    var _grupos = {!!$grupos!!};
    var _tipos = {!!$tipos!!};
    var _grupo_selected = {!!$grupo_selected!!};
    var _tipo_selected = {!!$tipo_selected!!};
    var _migalhas =  <?=json_encode($migalhas);?>;
</script>

@endsection
