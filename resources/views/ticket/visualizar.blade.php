@extends('core.app')

@section('titulo', $titulo)

@section('content')

<migalha titulo="{{$titulo}}" descricao="Visualizar ticket"></migalha>

<style type="text/css">
.margin:hover { 
    opacity: .5;
    transition: opacity .5s ease-out;
    -moz-transition: opacity .5s ease-out;
    -webkit-transition: opacity .5s ease-out;
    -o-transition: opacity .5s ease-out;
    cursor: pointer;
}
</style>

<section class="content" ng-controller="visualizarTicketCtrl">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-xs-12">

            <div class="box">
                <div class="box-body box-profile">
                    <div class="row">
                        <div class="col-md-4 col-xs-6">
                            <h3 class="profile-username">#{{ $codigo}}</h3>
                        </div>
                        <div class="col-md-8 col-xs-6">
                            <h5 class="pull-right">     
                                <span class="label" style="background-color: {{  $prioridade_cor  }}">{{ $prioridade }}</span>
                                <span class="label" style="background-color: {{  $status_cor  }}">{{ $status }}</span>
                            </h5>
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-unbordered box-comments">

                        <li class="list-group-item">
                            <div class="box-comment">
                                <img class="img-circle img-bordered-sm" src="/configuracao/usuario/avatar-pequeno/{{$funcionario_solicitante_avatar}}">
                                <div class="comment-text">
                                    <span class="username">
                                        Solicitante
                                        <span class="text-muted pull-right margin-r-5" data-toggle="tooltip" data-placement="bottom" data-original-title="Criado à {{ $title_criado }}">
                                            <i class="fa fa-clock-o"></i>
                                            {{$criado}}
                                        </span>
                                    </span>
                                    <span class="truncate">{{ $solicitante }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="box-comment">
                                <img class="img-circle img-bordered-sm" src="/configuracao/usuario/avatar-pequeno/{{ $funcionario_responsavel_avatar }}">
                                <div class="comment-text">
                                    <span class="username">
                                        Responsável
                                        @if( !is_null( $atualizado) )
                                            <span class="text-muted pull-right margin-r-5" data-toggle="tooltip" data-placement="bottom" data-original-title="Atualizado à {{ $atualizado }}">
                                                <i class="fa fa-clock-o"></i>
                                                {{ $atualizado }}
                                            </span>                                        
                                        @endif
                                    </span>
                                    <span class="truncate">{{ $responsavel }}</span>
                                </div>
                            </div>
                        </li>
                        
                        <li class="list-group-item">
                            
                            <label>Departamento:</label>
                            <p class="truncate">{{ $departamento }}</p>                            
                            
                            <label>Assunto:</label>
                            <p class="truncate">{!! $assunto !!}</p>
                            
                            <label>Categoria:</label>
                            <p>{{ $categoria_pai }}</p>
                            
                            <label>Subcategoria:</label>
                            <p>{{ $categoria_filho }}</p>
                        </li>
                        <li class="list-group-item" style="word-wrap: break-word;">
                            <label>Descrição:</label>
                            <p class="text-muted">
                               {!! $descricao !!}
                            </p>
                        </li>

                        @if( !$campos_adicionais->isEmpty() )
                        <li class="list-group-item">
                            @foreach ($campos_adicionais as $campo)
                                <label>{{ $campo->nome }}:</label>
                                <p class="text-muted">
                                    @if( is_null($campo->resposta) )
                                        -
                                    @else
                                        {!! $campo->resposta !!}
                                    @endif
                                </p>
                            @endforeach
                        </li>
                        @endif

                        <li class="list-group-item">

                            <div class="row">
                                <div class="col-md-3">
                                    <label>Abertura:</label><br>
                                    <span class="text-muted" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ $title_abertura }}">{{ $abertura }}</span>
                                </div>
                                <div class="col-md-3">
                                    <label>Atualização:</label><br>
                                    <span class="text-muted" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ $title_atualizacao }}">{{ $atualizacao }}</span>
                                </div>
                                @if( $ticket_fechado )
                                    <div class="col-md-3">
                                        <label>Resolução:</label><br>
                                        <span class="text-muted" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ $title_resolucao }}">{{ $resolucao }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Fechamento:</label><br>
                                        <span class="text-muted" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ $title_fechamento }}">{{ $fechamento }}</span>
                                    </div>
                                @else
                                    <div class="col-md-3">
                                        <label>Previsão:</label><br>
                                        <span class="text-muted" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ $title_previsao }}">{{ $previsao }}</span>
                                    </div>
                                    @if(!$proprio)
                                    <div class="col-md-3">
                                        <label>Notificação:</label><br>
                                        <span class="text-muted" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ $title_notificacao }}">{{ $notificacao }}</span>
                                    </div>
                                    @endif
                                @endif
                            </div>

                        </li>

                    </ul>
                    
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-8 col-xs-12">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" style="cursor: pointer">
                    <li class="uib-tab nav-item" ng-class="{'active' : tab==1}">
                        <a href="#interacao" ng-click="tab=1" class="nav-link" data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-file-text-o margin-r-5"></i> Interações                            
                        </a>
                    </li>
                    
                    <li class="uib-tab nav-item" ng-class="{'active' : tab==2}">
                        <a href="#imagem" ng-click="tab=2" class="nav-link" ng-class="{'active' : tab=='2'}"  data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-file-image-o margin-r-5"></i> Imagens
                                @if( count( $imagens ) > 0 ) 
                                    <span class="label label-default" style="margin-left: 10px;float: right; line-height: 15px;">{{ count( $imagens ) }}</span>
                                @endif

                        </a>
                    </li>
                    
                    <li class="uib-tab nav-item" ng-class="{'active' : tab==3}">
                        <a href="#historico" ng-click="tab=3" class="nav-link" data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-history margin-r-5"></i> Histórico
                        </a>
                    </li>

                    @if(!$proprio && $status_aberto)
                        @can( 'getPermissaoTicketEditar', $ticket )
                            <li class="pull-right ng-scope">
                                <a href="{{$ticket_id}}/edit"><i class="fa fa-edit margin-r-5"></i>Editar</a>
                            </li>
                        @endcan
                    @elseif( $status_aberto )
                        @can( 'getPermissaoTicketResponder', $ticket )
                            <li class="pull-right ng-scope">
                                <a href="/ticket/{{$ticket_id}}"><i class="fa fa-reply-all margin-r-5"></i>Tratar</a>
                            </li>
                        @endcan

                    @endif

                    <li class="dropdown pull-right" ng-class="{'active' : tab==4}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="false">
                           <i class="fa fa-reply-all margin-r-5"></i> Ações <span class="caret"></span>
                         </a>
                         <ul class="dropdown-menu">
                            @forelse ($acoes as $acao)
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#acao" ng-click="acaoMacro( {{ $acao->id }} )"><i class="fa {{ $acao->icone }} margin-r-5"></i> {{ $acao->nome }}</a></li>
                            @empty
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0);">Nenhuma ação.</a></li>
                            @endforelse
                        </ul>
                    </li>
                </ul>
  
                <div class="tab-content">
                    <div ng-show="tab == 1">
                        
                        <form role="form" data-toggle="validator" name="form">

                            @if ( $status_aberto == true )
                                <div class="row form-group">
                                    <div class="col-lg-10 col-md-8 col-sm-12">
                                        <textarea rows="4" class="form-control input-sm" style="text-transform: uppercase; resize: none;" required="" ng-model="ticket.mensagem"></textarea>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-12">
                                        <button type="button" class="btn btn-sm btn-block btn-primary" ng-click="interacao();" ng-disabled="!form.$valid">
                                            <i class="fa fa-comment-o margin-r-5"></i>Interação
                                        </button>
                                        @if(!$proprio)
                                        @can( 'getPermissaoTicketResponder', $ticket )
                                        <button type="button" class="btn btn-sm btn-block btn-primary" ng-click="notaInterna();" ng-disabled="!form.$valid">
                                            <i class="fa fa-lock margin-r-5"></i>Nota interna
                                        </button>
                                        @endcan
                                        @endif
                                    </div>
                                </div>                            
                                
                                <hr>

                            @endif

                            @forelse ($interacoes as $interacao)
                                
                                <div class="post">
                                    <div class="user-block">

                                        <img class="img-circle img-bordered-sm" src="/configuracao/usuario/avatar-pequeno/{{ $interacao->avatar }}">
                                        <span class="username">
                                            <a href="#">{{ $interacao->usuarios->nome }}</a>
                                        </span>
                                        <span class="description">
                                            <i class="fa fa-{{ $interacao->icone }} margin-r-5"></i>
                                            @if( $interacao->interno)
                                            Nota Interna - 
                                            @else
                                            Interação - 
                                            @endif
                                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{ $interacao->data_interacao }}" data-delay="100">
                                                {{ $interacao->tempo_interacao }}
                                            </span>
                                        </span>
                                    </div>
                                    <p>
                                        {!! $interacao->mensagem !!}
                                    </p>
                                </div>

                            @empty

                                <div class="text-center">
                                    <p class="text-muted">Este ticket ainda não possui interação!!!</p>
                                </div>

                            @endforelse

                        </form>
                    </div>
                    
                    <div ng-show="tab == 2">                      
                        <form role="form" name="formImagem" data-toggle="validator" class="form-horizontal" ng-submit="confirmarImagem()">
                                
                            @can('getPermissaoSubirImagemTickettFechado', $ticket)
                            <input type="hidden" name="ticket_id" ng-model="imagem.ticket_id" ng-init="imagem.ticket_id='{{ $ticket_id }}'">
                            <div class="box-body">

                                <div class="form-group">
                                    
                                    <label for="" class="col-sm-2 control-label">Imagem:</label>
                                    <div class="col-sm-8">  
                                        <button 
                                            class="btn btn-sm btn-primary select-image"
                                            ngf-select="escondeErro();"
                                            ng-model="imagem.file"
                                            name="file"
                                            ngf-pattern="'image/*'"
                                            ngf-accept="'image/*'"
                                            ngf-max-size="4MB" 
                                            style="margin-top: 0"
                                            >
                                            Escolher nova imagem...
                                        </button>    
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary" ng-model="imagem.btn_submit" ng-disabled="!formImagem.$valid">
                                            Salvar
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label ng-model="imagem.texto" class="col-sm-2 control-label">Descrição:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control input-sm" ng-model="imagem.texto" placeholder="Descrição" required="required" maxlength="100" ng-keypress="keyPressDescricaoImagem($event);">
                                    </div>                                        
                                </div>
                                <div class="form-group" ng-show="imagem.file">
                                    
                                    <label class="col-sm-2 control-label">Preview:</label>
                                    <div class="col-sm-8">                                            
                                        <div class="new-image-holder" style="border: none; text-align: left">
                                            <img ngf-thumbnail="imagem.file" style="width: 150px;">
                                        </div>
                                    </div>
                                </div>                                

                                <!-- Erros -->        
                                <msg-error ng-show="errors"></msg-error>  
                                <!-- Sucesso -->
                                <msg-success ng-show="success"></msg-success>        
                            </div>

                            <hr>
                            @endcan                            
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row col-sm-12">
                                        <div class="overlay block row" ng-if="carregandoModalImagemDados" style="text-align: center; margin-left: 1px;">
                                            <i class="fa fa-5x fa-refresh fa-spin" style="margin-top: 1%;"></i>
                                        </div>
                                    <div class="timeline-item">                
                                        <div class="timeline-body">
                                            @forelse( $imagens as $imagem )
                                                <img src="data:image/jpeg;base64,{{ $imagem->imagem_miniatura }}" class="margin" ng-click="showModal('{{ $imagem->id }}')">
                                            @empty

                                                <div class="text-center">
                                                    <p class="text-muted">Este ticket ainda não possui imagens!!!</p>
                                                </div>

                                            @endforelse
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div ng-show="tab == 3">

                        <div class="tab-pane" id="timeline">

                            <ul class="timeline timeline-inverse">

                                <?php $data_historico = null;  ?>    
                                @foreach ($historicos as $historico)

                                    <?php if( $data_historico != \Carbon\Carbon::parse($historico->created_at)->format('d/m/Y') )
                                        { ?>

                                        <li class="time-label">
                                            <span class="bg-light-blue">                
                                            {{ \Carbon\Carbon::parse($historico->created_at)->format('d/m/Y')}}
                                            </span>
                                        </li>


                                    <?php } ?>

                                    <?php  $data_historico = \Carbon\Carbon::parse($historico->created_at)->format('d/m/Y');  ?>

                                    <li>
                                        <i class="fa fa-{{ $historico->icone }}" style="background-color: {{ $historico->cor  }}; color: white"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i>
                                            {{ \Carbon\Carbon::parse($historico->created_at)->format('H:i') }} 
                                            </span>
                                            <h3 class="timeline-header no-border">
                                                <a href="#">{{ $historico->usuarios->nome }}</a> 
                                                {{ $historico->mensagem }}
                                            </h3>
                                        </div>
                                    </li>
                                
                                @endforeach
                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div ng-show="tab == 4">
                        
                        <form role="form" data-toggle="validator" name="formMacro">
                           
                            <div ng-if="campos_macro.length != ''">

                                <div class="row form-group" style="margin-bottom: 0px;">

                                    <div class="form-group col-md-4" ng-if="acao_status.length > 1">
                                        <label>Status:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <select class="form-control input-sm" ng-model="acao.status" name="acao_status" required>
                                                    <option ng-repeat="item in acao_status" value="@{{item.id}}">@{{item.nome}}</option>
                                                </select>
                                                <div class="help-block with-errors" ></div>
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4" ng-if="acao_solicitante.length > 0">
                                        <label for="nome_completo">Solicitante:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <select class="form-control input-sm" ng-model="acao.solicitante" id="solicitante" ng-options="x.nome for x in acao_solicitante track by x.id" required="required">
                                                </select>
                                                <div class="help-block with-errors" ></div>
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4" ng-if="acao_prioridade.length > 0">
                                        <label for="email">Prioridade:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <select class="form-control input-sm" ng-model="acao.prioridade" ng-options="x.nome for x in acao_prioridade track by x.id" required="required">
                                                </select>
                                                <div class="help-block with-errors" ></div>
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row form-group" style="margin-bottom: 0px;">
                                    
                                    <div class="col-md-8" ng-if="campos_macro.campos.indexOf('categoria') > 0" style="padding:0px;">
                                        <div class="form-group col-md-6">
                                            <label for="nome">Categoria:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <select class="form-control input-sm" ng-options="item.nome for item in acao_categoria track by item.id" ng-model="acao.categoria" ng-change="mostrarSubcategoria( acao.categoria.id )" required>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                    <i class="fa fa-asterisk"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="nome">Subcategoria:</label>
                                             <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <select class="form-control input-sm" ng-options="item.nome for item in acao_subcategoria track by item.id" ng-model="acao.subcategoria" required>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                    <i class="fa fa-asterisk"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4" ng-if="campos_macro.campos.indexOf('assunto') > 0">
                                        <label for="nome">Assunto:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <input type="text" class="form-control input-sm" id="assunto" name="assunto" ng-model="acao.assunto" data-error="Este campo é obrigatório." required style="text-transform: uppercase;">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row form-group" style="margin-bottom: 0px;">

                                    <div class="form-group col-md-4" ng-if="acao_responsavel.length > 0">
                                        <label>Responsável:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <select class="form-control input-sm" ng-model="acao.responsavel" ng-options="x.nome for x in acao_responsavel track by x.id" required>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4" ng-if="campos_macro.campos.indexOf('data_notificacao') > 0">
                                        <label>Data de notificação:</label>
                                        <div class="input-group" moment-picker="acao.dt_notificacao" format="DD/MM/YYYY HH:mm" locale="pt-br" start-view="month" min-view="month" max-view="hour" today="true" min-date="date_min">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input class="form-control input-sm" name="dt_notificacao" onpaste="return false;" mask="39/19/x999 29:59" restrict="reject" clean="true" ng-model="acao.dt_notificacao" ng-model-options="{ updateOn: 'blur' }" ng-keypress="keyPress($event)" maxlength="16">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-4" ng-if="campos_macro.campos.indexOf('data_previsao') > 0">
                                        <label>Data de previsão:</label>
                                        <div class="input-group" moment-picker="acao.dt_previsao" format="DD/MM/YYYY" locale="pt-br" start-view="month" today="true" >
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input class="form-control input-sm" name="dt_previsao" onpaste="return false;" mask="39/19/x999" restrict="reject" clean="true" ng-model="acao.dt_previsao" ng-model-options="{ updateOn: 'blur' }" ng-keypress="keyPress($event)" maxlength="10">
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row form-group" style="margin-bottom: 0px;">

                                    <div ng-if="campos_macro.campos.indexOf('campos_adicionais') > 0">
                                        @foreach ($campos_departamento as $campo)
                                            <div class="form-group col-md-4" @if( $campo->obrigatorio ) style="margin-bottom: 0px;" @endif>
                                                <label for="nome">{{ $campo->nome }}:</label>
                                                @if( $campo->obrigatorio )
                                                    <div class="row">
                                                        <div class="col-md-11 col-xs-10">
                                                            {!! $campo->html !!}
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                            <i class="fa fa-asterisk"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    {!! $campo->html !!}
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                </div>

                                <div class="row form-group" style="margin-bottom: 0px;">

                                    <div class="form-group col-md-12" ng-if="campos_macro.campos.indexOf('avaliacao') > 0">
                                        <label>Avaliação:</label>
                                        <div ng-repeat="rating in ratings">
                                            <div star-rating rating-value="rating.current" max="rating.max"></div>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="row form-group" ng-if="campos_macro.interacao == true || campos_macro.nota_interna == true">
                                    
                                    <div class="col-lg-10 col-md-8 col-sm-12">
                                        <label>Mensagem:</label>
                                        <textarea rows="4" class="form-control input-sm" style="text-transform: uppercase; resize: none;" ng-model="acao.texto_interacao" required="required"></textarea>
                                    </div>
                                    
                                    <div class="form-group col-lg-2 col-md-4">
                                        <div class="row">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10" ng-if="campos_macro.interacao == true">
                                                <label>
                                                    <input type="radio" value="false" ng-value="false" name="interacao" ng-model="acao.interacao"> Interação 
                                                </label>
                                            </div>
                                            @if(!$proprio)
                                            @can( 'getPermissaoTicketResponder', $ticket )
                                            <div class="col-md-11 col-xs-10" ng-if="campos_macro.nota_interna == true">
                                                <label>
                                                    <input type="radio" value="true" ng-value="true" name="nota_interna" ng-model="acao.interacao"> Nota Interna
                                                </label>
                                            </div>
                                            @endcan
                                            @endif 
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row form-group">
                                    <div class="form-group col-md-3">
                                        <button type="button" class="btn btn-primary" ng-disabled="!formMacro.$valid" ng-click="executaMacro();">Salvar</button>  
                                    </div>
                                </div>

                            </div>

                            <!-- Erros -->        
                            <msg-error ng-show="errors"></msg-error>
                            <!-- Sucesso -->
                            <msg-success ng-show="success"></msg-success>
                            
                            <span ng-if="campos_macro.length == 0">
                                <hr>
                                <div class="text-center">
                                    <p class="text-muted">Selecione uma ação para ser executada.</p>
                                </div>
                            </span>
                        </form>
                    </div>
                </div>
            </div>      
        </div>
    </div>
</section>

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
    var _ticket_id = {{$ticket_id}};
</script>

<script type="text/ng-template" id="imagemModal.html">    
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">                
            <span class="center">@{{ descricao }}</span>
            <button type="button" class="close" data-dismiss="modal" ng-click="closeModal()">
                   <span aria-hidden="true">&times;</span>
                   <span class="sr-only">Close</span>
            </button>                
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <div class="overlay" ng-if="carregandoModalImagem" style="margin-left: 5px;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <img class="img-responsive" ng-if="!carregandoModalImagem" ng-src="@{{'data:image/jpeg;base64,'+imagem}}" width="1280" style="margin: 0 auto;">
                    <div ng-if='usuario'>
                        <div class="text-muted" style="margin-top: 10px">
                            @{{ data }}
                            <span>
                                (@{{ usuario }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
            <div class="row">                    
                <div class="col-sm-12">
                    <span ng-if="   permissaoImagemTicketExcluir">                    
                        <button class="btn btn-warning pull-left" type="button" ng-click="deletarImagem()" data-toggle="modal"><i class="fa fa-trash" aria-hidden="true"></i> &nbsp; Excluir imagem</button>
                        <a href="@{{'data:image/jpeg;base64,'+imagem}}" class="btn btn-primary pull-right" download="@{{nome_imagem}}"><i class="fa fa-download"></i> &nbsp;Download</a>
                        <button class="btn btn-danger pull-right" type="button" ng-click="closeModal()">Fechar</button>                     
                    </span>
                    <span ng-if="!permissaoImagemTicketExcluir">
                        <a href="@{{'data:image/jpeg;base64,'+imagem}}" class="btn btn-primary pull-right" download="@{{nome_imagem}}"><i class="fa fa-download"></i> &nbsp;Download</a>
                        <button class="btn btn-danger pull-right" type="button" ng-click="closeModal()" style="margin-right: 5px">Fechar</button>
                    </span>                   

                </div>                    
            </div>                 
        </div>
    </div>
</script>


<style>
    .overlay.block{
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 10;
        background-color: rgba(0,0,0,0.5); /*dim the background*/
    }
    .nav-tabs-custom > .nav-tabs > li.active, .box {
        border-top-color: {{  $prioridade_cor  }};
    }
    .list-group-item label {
        margin-bottom: 0px;
    }
    .list-group-item p {
        margin-bottom: 5px;
        color: #777;
    }
    .list-group-item .box-comment {
        padding-bottom: 0px;
    }
    .box-comments .box-comment img {
        width: 40px !important;
        height: 40px !important;
    }
    .box-comments .comment-text {
        margin-left: 50px;
    }
    .box-comments .username {
        display: block;
        font-weight: 600;
    }
    .post .user-block{
        margin-bottom: 5px;
    }
    .post:last-of-type{
        border-bottom: 1px solid #d2d6de;
        margin-bottom: 15px;
        padding-bottom: 15px;
    }
    .timeline > li > .timeline-item > .timeline-header {
        font-size: 14px;
    }
</style>

@endsection
