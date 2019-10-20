        <!-- ====================== -->
        <!--  header.html           -->
        <!-- ====================== -->
<style type="text/css">
    .voltaMenu{
         position: absolute;
        width: 100%;
        top: 0px;
        height: 10px;
        z-index: 1000;"
    }
</style>
    <div ng-controller="headerCtrl" class="fixo index">
        <!-- .main-header -->
        <header class="main-header" id="main-header">

            <!-- Logo -->
            <a href="/" class="logo">

                <!-- Reduzido -->
                <span class="logo-mini">
                    <img src="{{ asset($logoMini) }}" height="40" alt="Fenix">
                </span>
                <!-- Normal -->
                <span class="logo-lg">
                    <img src="{{ asset($logoLg) }}" height="40" alt="Fenix">
                </span>

            </a>
            <!-- /.logo -->            
                
            <!-- Menu superior | .navbar -->
            <nav class="navbar navbar-static-top">
    
            @if( !empty( auth()->user() ) )

                <!-- Icone para alternar visualização do menu -->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" id="sidebar_menu" ng-click="setStatusAtualMenu()">
                    <span class="sr-only">Alterar menu</span>
                </a>

                <!-- Botões da direita | .navbar-custom-menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav" id="menuSuperior">

                        <li>
                            <a href="#" onclick="location.reload();" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Atualizar página">
                                <i class="fa fa-refresh" ng-mouseover="spin=true" ng-mouseout="spin=false" ng-class="{'fa-spin':spin,'':!spin}" ng-init="spin=false" style="padding:15px;margin:-15px;"></i>
                            </a>
                        </li>
                        <li class="esconde-celular">
                            <a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Expandir página">
                                <i class="fa fa-arrows-alt"  ng-click="expandirPagina()" style="padding:15px;margin:-15px;"></i>
                            </a>
                        </li>

                        @if( $notificacao_can == 'true' )
                            @include('core.menu_superior.notificacao')
                        @endif

                        @include('core.menu_superior.aniversario')

                        <!-- Botão: Informações do usuário | .dropdown.user-menu -->
                        <li class="dropdown user user-menu" id="user_menu">

                            <!-- Botão de abertura das informações -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                               
                                <img src="/configuracao/usuario/avatar-pequeno/{{auth()->user()->id}}" alt="{{auth()->user()->nome}}" class='user-image' alt='User Image' style=" background-color: #FFF;">
                                
                                <span class="hidden-xs">{{auth()->user()->nome}}</span>
                            </a>
                            
                            <!-- Informações do usuário | .dropdown-menu -->
                            <ul class="dropdown-menu">
                                
                                <!-- Foto e Dados -->
                                <li class="user-header">
                                    @if( auth()->user()->funcionario_id === null )
                                        <a href="/configuracao/usuario/{{auth()->user()->id}}">
                                    @else
                                        <a href="/rh/funcionario/{{auth()->user()->funcionario_id}}">
                                    @endif
                                        <img src="/configuracao/usuario/avatar-pequeno/{{auth()->user()->id}}" alt="{{auth()->user()->nome}}" class='img-circle' alt='User Image'>
                                        <p>
                                            <strong>{{auth()->user()->nome}}</strong>
                                        </p>
                                        <small>{{auth()->user()->perfil_nome}}</small>
                                        <small>{{auth()->user()->usuario}}</small>
                                    </a>
                                </li>

                                <!-- Botões -->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <div class="btn-group">
                                            <a href="#"
                                                class="btn btn-default btn-flat"
                                                ng-click='abreModalParaTrocarSenha( politicaSenha )'
                                                data-toggle=""
                                                data-target="" 
                                                @if( auth()->user()->senha_alterada == false && auth()->user()->visualizado_senha_alterada == false )
                                                    ng-init="abreModalParaTrocarSenha( politicaSenha ); atualizarVisualizarSenha();"
                                                @endif
                                                >
                                                <i class="fa fa-key" style="margin-right:7px;"></i>
                                                ALTERAR SENHA
                                            </a>                   
                                            <a href="#"
                                                class="btn btn-default btn-flat"
                                                ng-click='abreModalParaCopiarToken()'
                                                data-toggle=""
                                                data-target="" 
                                                >
                                                <i class="fa fa-key" style="margin-right:7px;"></i>
                                                TOKEN
                                            </a>                                            
                                        </div>
                                    </div>
                                </li>
                                
                            </ul>
                            <!-- /.dropdown-menu -->

                        </li>
                        <!-- /.dropdown.user-menu -->

                        <!-- Botão: sair -->
                        <li class="" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Sair">
                            <a href="/logout"><i class="fa fa-sign-out"></i></a>
                        </li>

                    </ul>

                </div>
                <!-- /.navbar-custom-menu -->
            @endif
            </nav>
            <!-- /.navbar -->

        </header>
        <div ng-mousemove="voltaPagina()" class="voltaMenu"></div>    
    </div>
        <!-- /.main-header -->
        
        <!-- ====================== -->
        <!--  /header.html          -->
        <!-- ====================== -->
        @if( !empty( auth()->user() ) )
            <script type="text/javascript">
                var id = {{Auth::user()->id}};
                var _notificacao_tempo = {!! $notificacao_tempo !!};
                var _notificacao_can = {!! $notificacao_can !!};
                var _auth = {!!Auth::user()!!};
                var _politicaSenha = {!!$politicaSenha!!};



            </script>

            <script type="text/ng-template" id="trocarSenhaModal.html">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h3 class="modal-title" id="modal-title">Trocar senha</h3>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="form-group">
                        <label for="old-password">Senha atual</label>
                        <input type="password" class="form-control" id="old-password" ng-model='modal.oldPassword'>
                    </div>

                    <div class="form-group">
                        <label for="new-password">Nova senha
                            <span data-toggle="tooltip" data-placement="right" data-original-title="@{{politicaSenha}}" data-html="true">
                                <i class="fa fa-info-circle"></i>
                            </span>
                        </label>
                        <input type="password" class="form-control" id="new-password" ng-model='modal.newPassword'>
                    </div>

                    <div class="form-group">
                        <label for="repeat-password">Repetir nova senha</label>
                        <input type="password" class="form-control" id="repeat-password" ng-model='modal.newPasswordConfirmation'>
                    </div>

                    <msg-success ng-show="modal.sucesso"></msg-success>
                    <msg-error ng-show="modal.erro"></msg-error>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" ng-click="modalCancelarTrocaDeSenha()">Fechar</button>
                    <button class="btn btn-primary" type="button" ng-click="modalConfirmarTrocaDeSenha()" ng-show="isShowBtnSalvar()">Salvar</button>
                </div>
            </script>

            <script type="text/ng-template" id="copiarTokenModal.html">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">
                            <i class="fa fa-key"></i>
                            Token de acesso
                        </h4>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="form-group">
                        <label for="old-password">Token:</label>
                        @{{token}}
                    </div>                    
                    <msg-success ng-show="success"></msg-success>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" ng-click="close()">Fechar</button>
                    <button class="btn btn-primary" type="button" ng-click="modalCopiarUrl()">Copiar URL</button>
                    <button class="btn btn-primary" type="button" ng-click="modalCopiarToken()">Copiar TOKEN</button>
                </div>
            </script>

        @endif
