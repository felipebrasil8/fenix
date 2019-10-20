@extends('core.app')

@section('titulo', 'Meus tickets')

@section('content')

<migalha titulo="Meus tickets" descricao="Pesquisar ticket"></migalha>
    
<section class="content" ng-controller="listarProprioTicketCtrl"> 

    <div class="row">
        <div class="col-md-12">
            <div class="box box-default @{{ filtro == true ? '' : 'collapsed-box'}}">
                
                <form role="form" ng-submit="pesquisaTicket(filter)">                

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
                            <label>Departamento:</label>
                            <select class="form-control input-sm" nome="departamento" id="departamento"
                                ng-model="filter.departamento" 
                                ng-click="selecionaDepartamento();" 
                                ng-init="verificaCookiesDepartamento();selecionaDepartamento();" 
                                ng-options="x.nome for x in departamentos">
                            </select>                            
                        </div>

                        <div class="form-group col-md-3">
                            <label for="usuario">De:</label>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="input-group" moment-picker="filter.de" format="DD/MM/YYYY" locale="pt-br" start-view="month" today="true" >
                                        <input class="form-control input-sm" onpaste="return false;" mask="39/19/x999" restrict="reject"" clean="true" ng-model="filter.de"  ng-model-options="{ updateOn: 'blur' }" ng-keypress="keyPress($event)" maxlength="10">
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
                                <div class="col-md-12 col-xs-12">
                                    <div class="input-group" moment-picker="filter.ate" format="DD/MM/YYYY" locale="pt-br" start-view="month" today="true" >
                                        <input class="form-control input-sm" onpaste="return false;" mask="39/19/x999" restrict="reject"" clean="true" ng-model="filter.ate"  ng-model-options="{ updateOn: 'blur' }" ng-keypress="keyPress($event)" maxlength="10">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    
                                </div>                                
                            </div>
                        </div>

                         <div class="form-group col-md-2">
                            <label><strong>Aberto:</strong></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>
                                        <input type="radio" ng-value="true" ng-model="filter.aberto" ng-checked="(filter.aberto == 'true')"> Sim 
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" ng-value="false" ng-model="filter.aberto" ng-checked="(filter.aberto == 'false')"}> Não
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" ng-value="3" ng-model="filter.aberto" ng-checked="(filter.aberto == '3')"}> Todos
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>  

                    <div class="box-body row">

                         <div class="form-group col-md-3">
                            <label for="codigo">Código:</label>
                            <input type="text" class="form-control input-sm" ng-model="filter.codigo" placeholder="Código" style="text-transform: uppercase;">
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="codigo">Assunto:</label>
                            <input type="text" class="form-control input-sm" ng-model="filter.assunto" placeholder="Assunto" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Responsável:</label>
                            <select class="form-control input-sm" ng-model="filter.usuario_responsavel" ng-options="x.nome for x in usuarios" ng-init="verificaCookiesResponsavel();">
                            </select>                            
                        </div>

                    </div>
                    
                    <div class="box-body row" ng-show="filter.departamento.id != 0">

                        <div class="form-group col-md-3">
                            <label>Status:</label>
                            <select class="form-control input-sm" ng-model="filter.statuse" ng-options="x.nome for x in statuses" ng-init="verificaCookiesStatuse();">
                            </select>                            
                        </div>
                       

                        <div class="form-group col-md-3">
                            <label>Prioridade:</label>
                            <select class="form-control input-sm" ng-model="filter.prioridade" ng-options="x.nome for x in prioridades" ng-init="verificaCookiesPrioridade();">
                            </select>                            
                        </div>
                        

                        <div class="form-group col-md-3">
                            <label>Categoria:</label>
                            <select class="form-control input-sm" ng-model="filter.categoria" ng-options="x.nome for x in categorias" ng-init="verificaCookiesCategoria();">
                            </select>                            
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
        <!-- left column -->
        <div class="col-md-12">        
            <!-- general form elements -->
            <div class="box box-default">
                <div class="table-responsive">  
                    <table class="table table-striped table-hover">
                        <tr id='cabecalho-da-pagina'>
                            <th ng-click="defineFiltro('codigo')" class='sortable' width="100px">
                                Código
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'codigo') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'codigo') }"></span>
                            </th>
                            <th ng-click="defineFiltro('data_order')" class='sortable'  width="150px">
                                Data
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'data_order') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'data_order') }"></span>
                            </th>
                             <th ng-click="defineFiltro('departamento_nome')" class='sortable'>
                                Departamento
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'departamento_nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'departamento_nome') }"></span>
                            </th>
                            <th ng-click="defineFiltro('assunto')" class='sortable' width="400px">
                                Assunto
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'assunto') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'assunto') }"></span>
                            </th>
                            <th ng-click="defineFiltro('categoria_nome_pai')" class='sortable'>
                                Categoria
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'categoria_nome_pai') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'categoria_nome_pai') }"></span>
                            </th>
                            <th ng-click="defineFiltro('usuario_responsavel_nome')" class='sortable'>
                                Responsável
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'usuario_responsavel_nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'usuario_responsavel_nome') }"></span>
                            </th>
                            <th ng-click="defineFiltro('status_ordem')" class="text-center sortable" width="100px">
                                Status
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'status_ordem') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'status_ordem') }"></span>
                            </th>
                            <th ng-click="defineFiltro('prioridade_ordem')" class="text-center sortable" width="120px">
                                Prioridade
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'prioridade_ordem') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'prioridade_ordem') }"></span>
                            </th>
                            <th class="text-center" width="100px">Ações</th>                            
                        </tr>       
                 
                        <tr ng-repeat="ticket in lista">

                            <td><a href="/ticket/proprio/@{{ ticket.id }}" title="Visualizar" style="color:#333;">#@{{ ticket.codigo }}</a></td>
                            
                            <td>@{{ ticket.data }}</td>

                            <td>@{{ ticket.departamento_nome }}</td>

                            <td>@{{ ticket.assunto }}</td>

                            <td>@{{ ticket.categoria_nome_pai }}</td>

                            <td>@{{ ticket.funcionario_responsavel_nome }}</td>

                            <td class="text-center">
                                <small class="label" style="background-color: @{{ ticket.status_cor }};">
                                    @{{ ticket.status_nome }}
                                </small>
                            </td>

                            <td class="text-center">
                                <small class="label" style="background-color: @{{ ticket.prioridade_cor }};">
                                    @{{ ticket.prioridade_nome }}
                                </small>
                            </td>

                            <td class="text-center">
                                <a href="/ticket/proprio/@{{ ticket.id }}" title="Visualizar"><i class="fa fa-file-text-o"></i></a>
                            </td>

                        </tr>                    
                        <tr>
                            <td colspan="9" ng-show="lista.length == 0">Nenhuma informação encontrada!!!</td>
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

                <div class="overlay" ng-show="carregando">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>

            </div>
            <!-- /.box -->            
        </div>
        <!-- /.left colum -->
    </div>

   <modal-confirm></modal-confirm>

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>
        
</section>

<script>    
    var _token = '{!! csrf_token() !!}';
    var _filtro = '{!! $filtro !!}';
    var _migalhas = <?=json_encode($migalhas);?>;
    var _departamentos = <?=json_encode($departamentos);?>;
    var _usuarios = <?=json_encode($usuarios);?>;
 
</script>

@endsection
