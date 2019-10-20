@extends('core.app')

@section('titulo', 'Cargos')

@section('content')

<migalha titulo="Cargos" descricao="Pesquisar cargo"></migalha>
    
<section class="content" ng-controller="listarCargoCtrl"> 

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default @{{ filtro == true ? '' : 'collapsed-box'}}">
                
                <form role="form" ng-submit="pesquisaCargo(filter)">                

                    <div class="box-header with-border cursor-pointer" ng-click="abrePesquisa()" data-widget="collapse" >
                        
                        <i class="fa fa-search cursor-pointer" style="display: none;"> </i>                        
                        <i class="fa fa-search cursor-pointer"> </i>    
                   
                        <h3 class="box-title cursor-pointer" data-widget="collapse">Filtrar resultado</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" ng-click='toggleSearch()'>
                                <i class="fa @{{ filtrando == true ? 'fa-chevron-up' : 'fa-chevron-down'}}"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="box-body row">
                        <div class="form-group col-md-3">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control input-sm" ng-model="filter.nome" placeholder="Nome" style="text-transform: uppercase;">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Gestor:</label>
                            <select class="form-control input-sm" ng-model="filter.funcionario" ng-options="x.nome for x in funcionarios" ng-init="setSelectFuncionario();">
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label>Departamento:</label>
                            <select class="form-control input-sm" ng-model="filter.departamento" ng-options="x.nome for x in departamentos" ng-init="setSelectDepartamento();">
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label><strong>Status:</strong></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>
                                        <input type="radio" ng-value="true" ng-model="filter.ativo" ng-checked="(filter.ativo == 'true')"> Ativo
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" ng-value="false" ng-model="filter.ativo" ng-checked="(filter.ativo == 'false')"}> Inativo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer">   
                        <div class="pull-left">
                            <button type="submit" class="btn btn-primary" ng-disabled="disableButton">Pesquisar</button>
                            <button type="button" class="btn btn-default" ng-disabled="disableButton" ng-click="limpaPesquisa()">Limpar</button>                            
                        </div>                        
                    </div>
                </form>
            </div><!-- /.box -->
        </div><!-- /col-md-12 -->
                   
    </div>
    <!-- /.row --> 

    <div class="row">
        
        <div class="col-md-12">
        
            <!-- general form elements -->
            <div class="box box-default">
                <div class="table-responsive">                    
                    <table class="table table-striped table-hover">
                        <tr id='cabecalho-da-pagina'>
                            <th ng-click="defineFiltro('nome')" class='sortable'>
                                Nome
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'nome') }"></span>
                            </th>                        
                            <th>
                                Gestor
                            </th>
                            <th>
                                Departamento
                            </th>
                            <th class="text-center" width="200px">Status</th>
                            <th class="text-center" width="100px">Ações</th>
                        </tr>                    
                        <tr ng-repeat="cargo in lista">
                            <td>@{{ cargo.nome }}</td>
                            <td>@{{ cargo.funcionario }}</td>
                            <td>@{{ cargo.departamento }}</td>
                            <td class="text-center">                        
                                @can( 'RH_CARGO_STATUS' )
                                <a href="#" title="@{{ cargo.ativo == true ? 'Ativo, clique para desativar.' : 'Inativo, clique para ativar.'}}"  ng-click="modalConfirm(cargo, lista, 'rh/cargo')" data-toggle="modal">
                                @endcan
                                    <i ng-class="{true:'fa fa-check-square-o', false:'fa fa-square-o'}[cargo.ativo]"></i>
                                @can( 'RH_CARGO_STATUS' )
                                </a>
                                @endcan
                                
                            </td>
                            <td class="text-center">                        
                                @can( 'RH_CARGO_EDITAR' )
                                <a href="/rh/cargo/@{{ cargo.id }}/edit" title="Editar">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                @endcan
                                &nbsp;
                                @can( 'RH_CARGO_VISUALIZAR' )                                
                                <a href="/rh/cargo/@{{ cargo.id }}" title="Visualizar"><i class="fa fa-file-text-o"></i></a>
                                @endcan                                
                            </td>                   
                        </tr>                    
                        <tr>
                            <td colspan="5" ng-show="lista.length == 0">Nenhuma informação encontrada!!!</td>
                        </tr>
                    </table>
                </div>
                    
                <div id='rodape-da-tabela' ng-show='lista.length != 0'>
                    <div class='total col-md-4'>
                        Total: (@{{ paginacao.de }} - @{{ paginacao.ate }} de @{{ paginacao.total }})
                    </div>

                    <div class='lista-de-paginas col-md-4 text-center'>
                        <span
                            class='fa fa-angle-double-left numero-da-pagina'
                            ng-class='{notClickable: paginacao.pagina == 1}'
                            ng-click='onGetPage(1)'></span>
                        <span
                            class='fa fa-angle-left numero-da-pagina'
                            ng-class='{notClickable: paginacao.pagina == 1}'
                            ng-click='onGetPage(paginacao.pagina - 1)'></span>

                        <span
                            ng-repeat="n in [] | range:paginacao.totalDePaginas"
                            class='numero-da-pagina'
                            ng-click='onGetPage($index + 1)'
                            ng-class='{selected: $index + 1 == paginacao.pagina}'>
                            @{{$index + 1}}
                        </span>

                        <span
                            class='fa fa-angle-right numero-da-pagina'
                            ng-class='{notClickable: paginacao.pagina == paginacao.limite}'
                            ng-click='onGetPage(paginacao.pagina + 1)'></span>
                        <span
                            class='fa fa-angle-double-right numero-da-pagina'
                            ng-class='{notClickable: paginacao.pagina == paginacao.limite}'
                            ng-click='onGetPage(paginacao.totalDePaginas)'></span>
                    </div>

                    <div class='limite-de-items-por-pagina col-md-4 text-right'>
                        <label>
                            Registro por página
                        </label>
                        <select ng-model="filter.limite" ng-change='onGetPage(1)'>
                            <option ng-value='15' ng-selected='filter.limite == 15'>15</option>
                            <option ng-value='30' ng-selected='filter.limite == 30'>30</option>
                            <option ng-value='50' ng-selected='filter.limite == 50'>50</option>
                            <option ng-value='100' ng-selected='filter.limite == 100'>100</option>
                        </select>
                    </div>
                </div>
                <!-- col-md-12 -->

                <div class="overlay" ng-show="carregando">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>

            </div>
            <!-- /.box -->          
            
            
            @can( 'RH_CARGO_CADASTRAR' )
            <div class="">
                <a href="/rh/cargo/create" title="Cadastrar">
                    <button type="button" class="btn btn-primary">Cadastrar</button>
                </a>
            </div>
            @endcan

        </div>
        <!-- /.left colum -->
    </div>

    <!-- Formulario com trigger em angularjs -->
    <form name="exportar" method="post">
        <input type="hidden" value="@{{filter.nome}}" name="nome">
        <input type="hidden" value="@{{filter.email}}" name="email">
        <input type="hidden" value="@{{filter.ativo}}" name="ativo">
    </form>

    <modal-confirm></modal-confirm>

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>        
        
</section>

<script>      
    var _token = '{!! csrf_token() !!}';
    var _ativo =  '{!! $ativo !!}';
    var _departamentos = <?=json_encode($departamentos);?>;    
    var _funcionarios = <?=json_encode($funcionarios);?>;    
    var _filtro = '{!! $filtro !!}';
    var _migalhas = <?=json_encode($migalhas);?>;
</script>

@endsection
