@extends('core.app')

@section('titulo', 'Usuário')

@section('content')

<migalha titulo="Usuário" descricao="Pesquisar usuário"></migalha>                

<!-- Main content -->
<section class="content" ng-controller="listarUsuarioCtrl">

    <div class="row">

        <div class="col-md-12">

            <!-- general form elements | fechar: collapsed-box -->
            <div class="box box-default @{{ filtro == true ? '' : 'collapsed-box'}}">
                <form role="form" ng-submit="pesquisaUsuario(filter)">
                   
                    <div class="box-header with-border cursor-pointer" ng-click="abrePesquisa()" data-widget="collapse" >
                        
                        <i class="fa fa-search cursor-pointer" style="display: none;"> </i>                        
                        <i class="fa fa-search cursor-pointer"> </i>                        

                        <h3 class="box-title cursor-pointer" data-widget="collapse">Filtrar resultado</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa @{{ filtro == true ? 'fa-minus' : 'fa-plus'}}"></i>
                            </button>
                        </div>
                    </div>
                       
                    <div class="box-body row">

                        <div class="form-group col-md-3">
                            <label for="nome">Nome:</label>                           
                            <input type="text" class="form-control input-sm" ng-model="filter.nome" placeholder="Nome" style="text-transform: uppercase;">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="nome">Usuário:</label>                           
                            <input type="text" class="form-control input-sm" ng-model="filter.usuario" placeholder="Usuário" style="text-transform: lowercase;">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Perfil:</label>
                            <select class="form-control input-sm" ng-model="filter.perfil" ng-options="x.nome for x in perfis" ng-init="setSelect();">
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
                            <th ng-click="defineFiltro('nome')" class='sortable'>
                                Nome
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'nome') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'nome') }"></span>

                            </th>
                            <th ng-click="defineFiltro('usuario')" class='sortable'>
                                Usuário
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'usuario') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'usuario') }"></span>
                            </th>
                            <th ng-click="defineFiltro('nome_perfil')" class='sortable'>
                                Perfil
                                <span class='fa fa-caret-up' ng-class="{'selecionado': (filter.ordem == 'asc' && filter.coluna == 'nome_perfil') }"></span>
                                <span class='fa fa-caret-down' ng-class="{'selecionado': (filter.ordem == 'desc' && filter.coluna == 'nome_perfil') }"></span>
                            </th>
                            <th class="text-center" width="100px">Senha</th>
                            <th class="text-center" width="200px">Status</th>
                            <th class="text-center" width="100px">Ações</th>                           
                        </tr>                   
                        <tr ng-repeat="usuario in lista">
                            <td>@{{ usuario.nome }}</td>
                            <td>@{{ usuario.usuario }}</td>
                            <td>@{{ usuario.nome_perfil }}</td>
                            <td class="text-center">
                                @can( 'CONFIGURACAO_USUARIO_ALTERAR_SENHA' )
                                    <a href="#" title="Alterar senha" ng-click="abreModalNovaSenha(usuario, politicaSenha)">
                                        <i class="fa fa-key"></i>
                                    </a>
                                @else
                                    <i class="fa fa-key"></i>
                                @endcan
                                &nbsp;
                                @can( 'CONFIGURACAO_USUARIO_SOLICITAR_SENHA' )
                                    <a href="#" title="@{{usuario.solicitar_senha_texto}}" ng-click="abreModalSolicitarSenha(usuario)">
                                        <i class="fa fa-@{{usuario.solicitar_senha_icone}}"></i>
                                    </a>
                                @else
                                    <i class="fa fa-@{{usuario.solicitar_senha_icone}}"></i>
                                @endcan
                            </td>
                            <td class="text-center">
                                @can( 'CONFIGURACAO_USUARIO_STATUS' )
                                <a href="#" class="alterarStatus" title="@{{ usuario.ativo == true ? 'Ativo, clique para desativar.' : 'Inativo, clique para ativar.'}}"  ng-click="modalConfirm(usuario, lista, 'configuracao/usuario')" data-toggle="modal">
                                @endcan                                                           
                                    <i ng-class="{true:'fa fa-check-square-o', false:'fa fa-square-o'}[usuario.ativo]"></i>
                                @can( 'CONFIGURACAO_USUARIO_STATUS' )
                                </a>
                                @endcan
                            </td>
                            <td class="text-center">
                                @can( 'CONFIGURACAO_USUARIO_EDITAR' )                           
                                <a href="/configuracao/usuario/@{{ usuario.id }}/edit" title="Editar"><i class="fa fa-pencil-square-o"></i></a>
                                @endcan
                                &nbsp;
                                @can( 'CONFIGURACAO_USUARIO_VISUALIZAR' )
                                <a href="/configuracao/usuario/@{{ usuario.id }}" title="Visualizar"><i class="fa fa-file-text-o"></i></a>
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
           
            @can( 'CONFIGURACAO_USUARIO_CADASTRAR' )
            <div class="">
                <a href="/configuracao/usuario/create" title="Cadastrar">
                    <button type="button" class="btn btn-primary">Cadastrar</button>
                </a>
            </div>
            @endcan

        </div>
        <!-- /.left colum -->

    </div>  
    <!-- /.row -->
   
    <!-- modal-->
    <modal-confirm></modal-confirm>
   
    <!-- Erros -->       
    <msg-error ng-show="errors"></msg-error>

    <script type="text/ng-template" id="solicitarSenhaModal.html">
        <div class="modal-header">
            <button type="button" class="close" ng-click="modalCancelarSolicitarSenha()" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modal-title">Deseja realmente alterar a solicitação de alteração de senha?</h4>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" type="button" ng-click="modalCancelarSolicitarSenha()">NÃO</button>
            <button class="btn btn-primary" type="button" ng-click="modalConfirmarSolicitarSenha()" autofocus>SIM</button>
        </div>
    </script>

    <script type="text/ng-template" id="novaSenhaModal.html">
        <div class="modal-header">
            <button type="button" class="close" ng-click="modalCancelarNovaSenha()" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modal-title">Alterar senha</h4>
        </div>
        <div class="modal-body" id="modal-body">
            <div class="form-group">
                <label >Usuário:</label>
                <p class="text-muted">@{{ usuario.usuario }}</p>
            </div>

            <div class="form-group">
                <label for="new-password">Nova senha
                    <span data-toggle="tooltip" data-placement="right" data-original-title="@{{politicaSenha}}" data-html="true">
                        <i class="fa fa-info-circle"></i>
                    </span>
                </label>
                <input type="password" class="form-control" id="new-password" ng-model='usuario.newPassword'>
            </div>

            <div class="form-group">
                <label for="repeat-password">Repetir nova senha</label>
                <input type="password" class="form-control" id="repeat-password" ng-model='usuario.newPasswordConfirmation'>
            </div>

            <div class="form-group">
                <label style="font-weight: normal;">
                    <input type="checkbox" ng-model="usuario.solicitar_alteracao" ng-init="usuario.solicitar_alteracao = false;">
                    <span class=""> Solicitar alteração da senha no próximo acesso.</span>
                </label>
            </div>

            <msg-success ng-show="modal.sucesso"></msg-success>
            <msg-error ng-show="modal.erro"></msg-error>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" type="button" ng-click="modalCancelarNovaSenha()">Cancelar</button>
            <button class="btn btn-primary" type="button" ng-click="modalConfirmarNovaSenha()">Confirmar</button>
        </div>
    </script>

    <!-- Erros -->
    <msg-error ng-show="erro"></msg-error>

</section>
<!-- /.content -->

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
    var _filtro = '{!! $filtro !!}';
    var _ativo =  '{!! $ativo !!}';
    var _perfis = <?=json_encode($perfis);?>;
</script>

@endsection
