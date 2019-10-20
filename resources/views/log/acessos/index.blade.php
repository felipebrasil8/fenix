@extends('core.app')

@section('titulo', 'Log de acesso')

@section('content')

<migalha titulo="Log de acessos" descricao="Visualizar log"></migalha>                 

<!-- Main content -->
<section class="content" ng-controller="listarLogsCtrl">

    <div class="row">

        <div class="col-md-12">

            <!-- general form elements | fechar: collapsed-box -->
            <div class="box box-default @{{ filtro == true ? '' : 'collapsed-box'}}">
                <form role="form" ng-submit="pesquisaLog(filter)" >
                    
                    <div class="box-header with-border cursor-pointer" ng-click="abrePesquisa()" data-widget="collapse" >
                        
                        <i class="fa fa-search cursor-pointer" style="display: none;"> </i>                        
                        <i class="fa fa-search cursor-pointer"> </i>                        

                        <h3 class="box-title cursor-pointer" data-widget="collapse">Filtrar resultado</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa @{{ filtrando == true ? 'fa-chevron-up' : 'fa-chevron-down'}}"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body row">
                        <div class="form-group col-md-3">
                            <label for="usuario">De:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <div class="input-group" moment-picker="filter.de" format="DD/MM/YYYY" locale="pt-br" start-view="month" today="true" >
                                        <input class="form-control input-sm" onpaste="return false;" mask="39/19/x999" restrict="reject"" clean="true" ng-model="filter.de"  ng-model-options="{ updateOn: 'blur' }" ng-keypress="keyPress($event)" maxlength="10" readonly="readonly">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>                                    
                                </div>                                
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Até:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <div class="input-group" moment-picker="filter.ate" format="DD/MM/YYYY" locale="pt-br" start-view="month" today="true" >
                                        <input class="form-control input-sm" onpaste="return false;" mask="39/19/x999" restrict="reject"" clean="true" ng-model="filter.ate"  ng-model-options="{ updateOn: 'blur' }" ng-keypress="keyPress($event)" maxlength="10" readonly="readonly">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    
                                </div>                                
                            </div>
                        </div>
                    </div>    
                    <div class="box-body row">
                        <div class="form-group col-md-3">
                            <label for="usuario">Nome do usuário:</label>
                            <input type="text" class="form-control input-sm" ng-model="filter.usuario" placeholder="Nome do usuário" style="text-transform: uppercase;">
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label>Perfil:</label>
                            <select class="form-control input-sm" ng-model="filter.perfil" ng-options="x for x in perfis" ng-init="verificaCookiesPerfil();">
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="usuario">IP:</label>
                            <input type="text" class="form-control input-sm" ng-model="filter.ip" placeholder="IP">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Tipo:</label>
                            <select class="form-control input-sm" ng-model="filter.tipo" ng-options="x for x in tipos" ng-init="verificaCookiesTipo();">
                            </select>                            
                        </div>
                    </div>

                    <div class="box-footer">   
                        <div class="pull-left">
                            <button type="submit" class="btn btn-primary" ng-disabled="disableButton">Pesquisar</button>
                            <button type="button" class="btn btn-default" ng-disabled="disableButton" ng-click="limpaPesquisa()">Limpar</button>                            
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary" ng-click="xlsx();" title="Exportar para Excel">
                                <i class="fa fa-download"></i> &nbsp;EXCEL
                            </button>
                            <button type="button" class="btn btn-primary" ng-click="csv();" ng-click="limpaPesquisa()" title="Exportar para CSV">
                                <i class="fa fa-download"></i> &nbsp;CSV
                            </button>
                        </div>
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
                            <th ng-click="defineFiltro('created_at')" class='sortable'>
                                DATA
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'created_at') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'created_at') }"></span>
                            </th>
                            <th ng-click="defineFiltro('usuario')" class='sortable'>
                                NOME DO USUÁRIO
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'usuario') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'usuario') }"></span>
                            </th>
                            <th ng-click="defineFiltro('perfil')" class='sortable'>
                                PERFIL
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'perfil') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'perfil') }"></span>
                            </th>
                            <th ng-click="defineFiltro('ip')" class='sortable'>
                                IP
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'ip') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'ip') }"></span>
                            </th>
                            <th ng-click="defineFiltro('tipo')" class='sortable'>
                                MENSAGEM
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'tipo') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'tipo') }"></span>
                            </th>
                        </tr>    
                        <tr ng-repeat="log in lista"> 
                            <td>@{{ log.data }}</td>
                            <td>@{{ log.usuario }}</td>
                            <td>@{{ log.perfil }}</td>
                            <td>@{{ log.ip }}</td>
                            <td>
                                <span class="" data-toggle="tooltip" tooltip-placement="top" title="@{{log.credencial}}" data-original-title="@{{log.credencial}}">@{{ log.mensagem }}</span>
                            </li>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" ng-show="lista.length == 0">Nenhuma informação encontrada!!!</td>
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
            
        </div>        
    </div>

    <!-- Formulario com trigger em angularjs -->
    <form name="exportar" method="post">
        <input type="hidden" value="@{{filter.de}}" name="de">
        <input type="hidden" value="@{{filter.ate}}" name="ate">
        <input type="hidden" value="@{{filter.usuario}}" name="usuario">
        <input type="hidden" value="@{{filter.perfil}}" name="perfil">
        <input type="hidden" value="@{{filter.ip}}" name="ip">
        <input type="hidden" value="@{{filter.tipo}}" name="tipo">
    </form>

</section>
<!-- /.content -->

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
    var _logs = <?=json_encode($logs);?>;
    var _perfis = <?=json_encode($perfis);?>;
    var _tipos = <?=json_encode($tipos);?>;
    var _filtro = '{!! $filtro !!}';
</script>

@endsection
