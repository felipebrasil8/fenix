<!-- Notificações Menu -->
<li class="dropdown messages-menu tooltip-delay" data-toggle="tooltip" data-placement="left" title="" data-original-title="Notificações">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell"></i>
        <span class="label bg-red" ng-show="count_nao_lidas_int > 0">@{{count_nao_lidas_int}}</span>
    </a>

    <ul class="dropdown-menu">
        <li class="header" ng-if="count_nao_lidas_int == 0 && notificacoes.length == 0">
            <div style="margin: 0; padding: 3px 10px;">
                <h4 style="padding: 0; margin: 0 0 0 45px; color: #3c8dbc; font-size: 15px; position: relative;">
                    @{{count_nao_lidas_str}}
                </h4>
            </div>
        </li>
        <li class="header" ng-if="count_nao_lidas_int > 0">
            @{{count_nao_lidas_str}}
        </li>
        <li>
            <ul class="menu" style="max-height: 270px;">
                <li ng-repeat="notificacao in notificacoes" ng-if="$index < 10">
                    <div style="@{{notificacao.color}}">
                        <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )">
                            <div class="pull-left @{{notificacao.text_muted}}">
                                <img class="img-circle" ng-src="@{{notificacao.imagem}}" height="40" alt="Avatar">
                            </div>
                        </a>

                        <h4 class="@{{notificacao.text_light_blue}}">
                            <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )">
                                <span class="@{{notificacao.text_muted}}">
                                    @{{notificacao.titulo | strLimit: 23 }}
                                </span>
                            </a>
                            <small class="@{{notificacao.text_light_blue}}" data-original-title="@{{notificacao.data_notificacao}}" data-toggle="tooltip" data-placement="top">
                                <i class="fa fa-clock-o @{{notificacao.text_light_blue}}"></i> @{{notificacao.data}}
                            </small>
                        </h4>

                        <p class="@{{notificacao.text_light_blue}}" class="a-ajuste-display-mozila-p">
                            <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )" style="display: table-cell; width: 205px;">
                                <span class="@{{notificacao.text_muted}}">
                                    @{{notificacao.mensagem | strLimit: 31 }}
                                </span>
                            </a>
                            <a href="#" ng-click="marcarNotificacaoLida( notificacao )" style="display: table-cell;">
                                <small class="pull-right @{{notificacao.text_muted}}">
                                    <i style="text-align: right;" class="fa @{{notificacao.icone_status}} @{{notificacao.text_light_blue}}" title="@{{notificacao.title}}"></i>
                                </small>
                            </a>
                        </p>
                    </div>
                </li>
            </ul>
        </li>
        <li class="footer"><a ng-click="modalTodasNotificacoes()" data-toggle="modal" class="cursor-pointer">Visualizar todas as notificações</a></li>
    </ul>

</li>

<script type="text/ng-template" id="todasNotificacoesModal.html">
    <div class="modal-header">
        <button type="button" class="close" ng-click="modalFecharTodasNotificacoes()" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modal-title">Notificações</h4>
    </div>
    <div class="" style="width: 300px;">
        <ul class="menu" style="margin: 0; padding: 0; list-style: none; overflow-x: hidden; max-height: 600px; height: 400px;">
            <li class="header" ng-if="count_nao_lidas_int == 0 && notificacoes.length == 0">
                <div style="margin: 0; padding: 10px 10px;">
                    <h4 style="padding: 0; margin: 0 0 0 10px; color: #3c8dbc; font-size: 15px; position: relative;">
                        @{{count_nao_lidas_str}}
                    </h4>
                </div>
            </li>
            <li class="header" ng-if="count_nao_lidas_int > 0" style="margin: 0; padding: 10px 10px;">
                @{{count_nao_lidas_str}}
            </li>
            <li ng-repeat="notificacao in notificacoes">
                <!-- Notificação visualizada FALSE -->
                <div style="background-color: #edf2fa;" class="notificacao-modal-div" ng-if="notificacao.visualizada == false">
                    
                    <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )">
                        <div class="pull-left">
                            <img class="img-circle" ng-src="@{{notificacao.imagem}}" height="40" alt="Avatar">
                        </div>
                    </a>

                    <h4 class="text-light-blue notificacao-modal-h4">
                        <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )">
                            @{{notificacao.titulo | strLimit: 23 }}
                        </a>
                        <a class="a-ajuste-display-mozila-modal">
                            <small class="text-light-blue pull-right a-ajuste-display-mozila-modal-data" data-original-title="@{{notificacao.data_notificacao}}" data-toggle="tooltip" data-placement="top">
                                <i class="fa fa-clock-o text-light-blue"></i> @{{notificacao.data}}
                            </small>
                        </a>
                    </h4>

                    <p class="text-light-blue notificacao-modal a-ajuste-display-mozila-p">
                        <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )" class="a-ajuste-display-mozila-modal" style="width: 205px;">
                            @{{notificacao.mensagem | strLimit: 31 }}
                        </a>
                        <a href="#" ng-click="marcarNotificacaoLida( notificacao )" class="a-ajuste-display-mozila-modal ">
                            <small class="pull-right">
                                <i style="text-align: right;" class="fa fa-circle text-light-blue" title="Marcar como lida"></i>
                            </small>
                        </a>
                    </p>
                </div>

                <!-- Notificação visualizada TRUE -->
                <div style="background-color: ;" class="notificacao-modal-div" ng-if="notificacao.visualizada == true">
                    <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )">
                        <div class="pull-left text-muted">
                            <img class="img-circle" ng-src="@{{notificacao.imagem}}" height="40" alt="Avatar">
                        </div>
                    </a>

                    <h4 class="notificacao-modal-h4">
                        <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )">
                            <span class="text-muted">
                                @{{notificacao.titulo | strLimit: 23 }}
                            </span>
                        </a>
                        <a class="a-ajuste-display-mozila-modal">
                            <small class="pull-right a-ajuste-display-mozila-modal-data" data-original-title="@{{notificacao.data_notificacao}}" data-toggle="tooltip" data-placement="top">
                                <i class="fa fa-clock-o"></i> @{{notificacao.data}}
                            </small>
                        </a>
                    </h4>
        
                    <p class="notificacao-modal a-ajuste-display-mozila-p">
                        <a href="@{{notificacao.url}}" ng-click="abrirNotificacao( notificacao )" class="a-ajuste-display-mozila-modal" style="width: 205px;">
                            <span class="text-muted">
                                @{{notificacao.mensagem | strLimit: 31 }}
                            </span>
                        </a>
                        <a href="#" ng-click="marcarNotificacaoLida( notificacao )" class="a-ajuste-display-mozila-modal">
                            <small class="pull-right text-muted">
                                <i style="text-align: right;" class="fa fa-circle-o" title="Marcar como não lida"></i>
                            </small>
                        </a>
                    </p>
                </div>

            </li>
        </ul>
    </div>
</script>
