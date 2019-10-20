@extends('core.app')

@section('titulo', 'Departamento')

@section('content')

<migalha titulo="Departamento" descricao="Cadastrar departamento"></migalha>

<section class="content" ng-controller="cadastrarDepartamentoCtrl">

    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" ng-submit="cadastrar(departamento)">
                    
                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-3">
                            <label>Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="departamento.nome" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label>Gestor:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                    <select class="form-control input-sm" ng-model="departamento.gestor" required>
                                        <option value=""></option>
                                         @foreach ($gestores as $gestore)
                                            <option value="{{ $gestore->id }}">{{ $gestore->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div> 
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Área:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                    <select class="form-control input-sm" ng-model="departamento.area" required>
                                        <option value=""></option>
                                         @foreach ($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div> 
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Ticket:</label>
                            <div class="row">
                                 <div class="col-md-4 col-xs-10">
                                    <label>
                                        <input type="radio" ng-value="true" ng-model="departamento.ticket" ng-checked="(filter.tickt == 'true')"> Sim
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" ng-value="false" ng-model="departamento.ticket" ng-checked="(filter.tickt == 'false')"}> Não
                                    </label>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                                
                            </div>
                        </div>


            

                    </div>

                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-12">
                            <label>Descrição:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <textarea rows="4" class="form-control input-sm" ng-model="departamento.descricao" style="text-transform: uppercase;" required></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                                        
                    @can( 'RH_AREA_CADASTRAR' )
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

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>     
        
    <!-- Sucesso -->
    <msg-success ng-show="success"></msg-success>     

</section>

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
</script>

@endsection
