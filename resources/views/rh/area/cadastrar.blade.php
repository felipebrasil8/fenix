@extends('core.app')

@section('titulo', 'Áreas')

@section('content')

<migalha titulo="Áreas" descricao="Cadastrar área"></migalha>

<section class="content" ng-controller="cadastrarAreaCtrl">

    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" ng-submit="cadastrar(area)">
                    
                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-4">
                            <label>Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" ng-model="area.nome" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label>Gestor:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">            
                                    <select class="form-control input-sm" ng-model="area.gestor" required>
                                        <option value=""></option>
                                         @foreach ($gestores as $value)
                                            <option value="{{ $value->id }}">{{ $value->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
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
                                    <textarea rows="4" class="form-control input-sm" ng-model="area.descricao" style="text-transform: uppercase; resize:none;" required></textarea>
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
