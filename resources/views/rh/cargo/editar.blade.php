@extends('core.app')

@section('titulo', 'Cargos')

@section('content')

<migalha titulo="Cargos" descricao="Editar cargo"></migalha>

<!-- Main content -->
<section class="content" ng-controller="editarCargoCtrl">

    <!-- first row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <form role="form" data-toggle="validator" name="form" ng-submit="atualizar(cargo)">
                    <div class="box-body row" style="padding-bottom: 0;">
                        <div class="form-group col-md-4">
                            <label>Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm"  ng-model="cargo.nome" data-match-error="Campo obrigatório" style="text-transform: uppercase: ;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label >Departamento:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <select class="form-control input-sm" ng-model="cargo.departamento" ng-options="item as item.nome for item in departamentos track by item.id" required>
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
                            <label >Gestor:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" ng-model="cargo.gestor" ng-options="item as item.nome for item in gestores track by item.id" required>
                                        <option value=""></option> 
                                    </select>  
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body row" style="padding-top: 0; padding-bottom: 0;">
                         <div class="form-group col-md-12">
                            <label>Descrição:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <textarea rows="4" class="form-control input-sm" ng-model="cargo.descricao" style="text-transform: uppercase; resize:none" required></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>                            
                        </div>
                    </div>
                
                    <input type="hidden" ng-model="cargo.id">

                    @can( 'RH_CARGO_EDITAR' )
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
    var _id            = {!! $id !!};
    var _nome            = {!! $nome !!};
    var _descricao       = {!! $descricao !!};
    var _funcionarios    = {!! $funcionarios !!};
    var _departamentos   = {!! $departamentos !!};
    var _func_selected   = {!! $func_selected !!}; 
    var _depart_selected = {!! $depart_selected !!};
    var _migalhas =  <?=json_encode($migalhas);?>;    
</script>

@endsection
