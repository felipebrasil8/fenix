@extends('core.app')

@section('titulo', 'Parâmetro')

@section('content')

<migalha titulo="Parâmetro" descricao="Pesquisar parâmetro"></migalha>

<section class="content" ng-controller="listarParametroCtrl">
    
    <div class="row">

        <div class="col-md-12">

            <!-- general form elements | fechar: collapsed-box -->
            <div class="box box-default @{{ filtro == true ? '' : 'collapsed-box'}}">

                <form role="form" ng-submit="pesquisaParametro(filter)">
                    <input type="hidden" name="_token" value="{{!!csrf_token()!!}}">
                    <div class="box-header with-border cursor-pointer" ng-click="abrePesquisa()" data-widget="collapse" >
                        
                        <i class="fa fa-search cursor-pointer" style="display: none;"> </i>                        
                        <i class="fa fa-search cursor-pointer"> </i>  
                        <h3 class="box-title cursor-pointer" data-widget="collapse">Filtrar resultado</h3>
                        <div class="box-tools pull-right">
                            <i class="fa @{{ filtrando == true ? 'fa-chevron-up' : 'fa-chevron-down'}}"></i>
                        </div>
                    </div>
                   
                    <div class="box-body row">
                        <div class="form-group col-md-3">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control input-sm" ng-model="filter.nome" placeholder="Nome" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="grupo">Grupo:</label>
                            <select class="form-control input-sm" ng-model="filter.grupo" ng-options="x.nome for x in grupos" ng-init="verificaCookiesGrupo();">
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="tipo">Tipo:</label>
                            <select class="form-control input-sm" ng-model="filter.tipo" ng-options="x.nome for x in tipos" ng-init="verificaCookiesTipo();">
                            </select>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" ng-disabled="disableButton">Pesquisar</button>
                        <button type="button" class="btn btn-default" ng-disabled="disableButton" ng-click="limpaPesquisa()">Limpar</button>
                    </div>
                </form>

            </div>
            <!-- /.box -->
        </div>
        <!-- /col-md-12 -->
                   
    </div>
    <!-- /.row --> 

    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">
                <div class="table-responsive">  
                    <table class="table table-striped table-hover">
                        <tr id='cabecalho-da-pagina'>
                            <th ng-click="defineFiltro('grupo_nome')" class='sortable'>
                                Grupo
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'grupo_nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'grupo_nome') }"></span>
                            </th>
                            <th ng-click="defineFiltro('tipo_nome')" class='sortable'>
                                Tipo
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'tipo_nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'tipo_nome') }"></span>
                            </th>
                            <th ng-click="defineFiltro('nome')" class='sortable'>
                                Nome
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'nome') }"></span>
                            </th>
                            <th ng-click="defineFiltro('descricao')" class='sortable'>
                                Descrição
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'descricao') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'descricao') }"></span>
                            </th>
                            <th>
                                Valor
                            </th>
                            <th class="text-center" width="100px">Ações</th>
                        </tr>
                        <tr  ng-repeat="parametro in lista">
                            <td>@{{ parametro.grupo_nome }}</td>
                            <td>@{{ parametro.tipo_nome }}</td>
                            <td>@{{ parametro.nome }}</td>
                            <td>@{{ parametro.descricao }}</td>
                            <td ng-show="parametro.parametro_tipo_id==1">@{{ parametro.valor_texto }}</td>
                            <td ng-show="parametro.parametro_tipo_id==2">@{{ parametro.valor_numero }}</td>
                            <td ng-show="parametro.parametro_tipo_id==3">@{{ parametro.valor_booleano | filterBooleano }}</td>
                            <td class="text-center">
                                @can( 'CONFIGURACAO_SISTEMA_PARAMETRO_EDITAR' )
                                <a href="/configuracao/sistema/parametro/@{{ parametro.id }}/edit" ng-show="parametro.editar==true" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                @endcan
                            </td>                    
                        </tr>
                        <tr>
                            <td colspan="6" ng-show="lista.length == 0">Nenhuma informação encontrada!!!</td>                    
                        </tr>
                    </table>
                </div>
                <!-- Copiar -->
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
                <!-- Parar de copiar -->

                <div class="overlay" ng-show="carregando">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>

            </div>
            <!-- /.box -->
            
            @can( 'CONFIGURACAO_SISTEMA_PARAMETRO_CADASTRAR' )
            <div class="">
                <a href="/configuracao/sistema/parametro/create" title="Cadastrar">
                    <button type="button" class="btn btn-primary">Cadastrar</button>
                </a>
            </div>
            @endcan

        </div>
        <!-- /.left colum -->
    </div>

    <modal-confirm></modal-confirm>   
    
    <!-- Erros -->
    <msg-error ng-show="erro"></msg-error>
    
</section>

<script>    
    var _grupos = {!!$grupos!!};
    var _tipos = {!!$tipos!!};
    var _filtro = {!! $filtro !!};   
    var _ativo =  {!! $ativo !!};
    var _migalhas =  <?=json_encode($migalhas);?>;
</script>

@endsection
