@extends('configuracao.ticket.index')

@section('ticket')

<!-- Main content -->
<div class="box box-default" ng-controller="acaoCtrl">
    <div class="box-header with-border">
        <h3 class="box-title">AÇÕES</h3>
    </div>
    <!-- /.box-header -->

    
    <!-- form start -->
    <form action="/configuracao/ticket/acao/departamento/{{$departamento}}" name="departamento_acao"  method="get">
        <div class="box-header with-border">
            <div class="form-group">
                 <label for="" class="col-sm-2 control-label">Departamento:</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm" ng-change="update()" ng-model="acao_departamento" ng-options="item.nome for item in departamentos track by item.id">
                        </select>
                    </div>  
                    
                    @if( $edit)
                        @can( 'CONFIGURACAO_TICKET_ACOES_CADASTRAR' )
                    <div class="col-sm-4">
                        <div class="btn-group">
                            <button type="button" ng-click="abrirModal( 'cadastrar', '' )" class="btn btn-primary">Adicionar ação</button> 
                        </div>    
                    </div>
                        @endcan
                    @endif
            </div>
            <!-- form-group -->
        </div>
        <!-- box-body with-border -->
    </form>
    
    @if( $edit )        
    <ul class="nav nav-pills nav-stacked" dnd-list="list">             
        <li ng-repeat="item in list"
            dnd-draggable="item"
            dnd-moved="list.splice($index, 1)"
            dnd-effect-allowed="move"
            dnd-selected="models.selected = item" 
            dnd-dragend="atualizaAcoes()"
            ng-class="{'selected': models.selected === item}" 
            >
            <a ng-click="abrirModal( 'editar', item )" style="cursor: pointer;"> 
                <i class="fa" ng-class="item.icone"></i> @{{ item.nome }}
            </a>
        </li>
    </ul>    
    @endif
            
    <modal-confirm></modal-confirm>

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>
</div>

<script>
    var _departamentos = {!!$departamentos!!};
    var _departamento = {!!$departamento!!};
    var _icones = '';
    var _status = '';
    var _acoes = {!!$acoes!!};
</script>

@if( $edit )

    <script>
        var _icones = {!!$icones!!};
        var _status = {!!$status!!};
    </script>

    <script type="text/ng-template" id="acaoModal.html">
        <div class="modal-header">            
            <h4 class="modal-title" id="modal-title">@{{modal_title}}</h4>
        </div>
        <div class="modal-body">
            <form  role="form" data-toggle="validator" name="form_modal" action="@{{modal_action}}" method="post">
                <div class="box-body row">
                    <div class="form-group col-md-6">
                        <label >Nome:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                <input type="text" class="form-control input-sm ng-pristine ng-empty ng-invalid ng-invalid-required ng-touched" name="nome" ng-model="nome" required="required" style="text-transform: uppercase;">
                                <div class="help-block with-errors" ></div>
                            </div>
                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <input type="hidden" name="modal_icone" value="@{{modal_icone}}">
                        <label for="nome">Icone: <div style="display: inline; padding-left: 5px; padding-right: 0;"><i class="fa @{{icone_view}}"></i></div></label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                <div class="input-group">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="display: table-row-group;">
                                        <input type="text" class="form-control input-sm" ng-model="modal_icone_view" name="modal_icone_view" readonly="" required="required">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-default input-sm">
                                                <span class="fa fa-caret-down"></span>
                                            </button>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu" style="height: 150px; overflow: auto; left: auto; right: 0; width: 100%;">
                                        <li role="presentation" ng-repeat="item in icones">
                                            <a role="menuitem" tabindex="-1" ng-click="setIcone( item );"><i class="fa @{{item.icone}} margin-r-5"></i> @{{item.nome}}</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>                   
                    
                    <div class="form-group col-md-12">
                        <label >Descrição: </label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                              <textarea rows="4" class="form-control input-sm ng-pristine ng-empty ng-invalid ng-invalid-required ng-touched" name="descricao" ng-model="descricao" required="required" style="text-transform: uppercase; resize: none;"></textarea>
                                <div class="help-block with-errors" ></div>
                            </div>
                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4 col-xs-12">
                        <label >Solicitante executa: </label>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input type="radio" ng-value="true" ng-model="solicitante_executa" name="solicitante_executa"> SIM
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio" ng-value="false" ng-model="solicitante_executa" name="solicitante_executa"> NÃO
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4 col-xs-12">
                        <label >Reponsável executa: </label>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input type="radio" ng-value="true" ng-model="responsavel_executa" name="responsavel_executa"> SIM
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio" ng-value="false" ng-model="responsavel_executa" name="responsavel_executa"> NÃO
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4 col-xs-12">
                        <label >Quem trata executa: </label>
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <input type="radio" ng-value="true" ng-model="trata_executa" name="trata_executa"> SIM
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio" ng-value="false" ng-model="trata_executa" name="trata_executa"> NÃO
                                </label>
                            </div>
                        </div>
                    </div>
                    <style type="text/css">.checkbox-aling{vertical-align: middle; margin-top: -2px !important; margin-right: 3px !important;}</style>
                    <div class="form-group col-md-12">
                        <label >Campos:</label>
                        <div class="row">
                            <div class="col-md-4 col-xs-4" ng-repeat="item in campos">
                                <label style="font-weight: normal;">
                                    <input type="checkbox" checklist-model="campos_adicionais.campos" checklist-value="item" name="campos[]" value="@{{item.value}}" class="checkbox-aling">
                                    <span class=""> @{{item.nome}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label >Mensagem:</label>
                        <div class="row">
                            <div class="col-md-2 col-xs-11">
                                <label style="font-weight: normal;">
                                    <input type="checkbox" ng-model="mensagem.tipo_mensagem.interacao" name="mensagem[]" value="interacao" class="checkbox-aling">
                                    <span class=""> INTERAÇÃO</span>
                                </label>
                            </div>
                            <div class="col-md-2 col-xs-11">
                                <label style="font-weight: normal;">
                                    <input type="checkbox" ng-model="mensagem.tipo_mensagem.nota_interna" name="mensagem[]" value="nota_interna" class="checkbox-aling">
                                    <span class=""> NOTA INTERNA</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label >Status atual: <i style="margin-left: 5px;" class="fa fa-asterisk"></i></label>
                        <div class="row">
                            <div class="col-md-4 col-xs-4" ng-repeat="item in status">
                                <label style="font-weight: normal;">
                                    <input type="checkbox" checklist-model="status_atual.status" checklist-value="item" name="status_atual[]" value="@{{item.id}}" class="checkbox-aling">
                                    <span class=""> @{{item.nome}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label >Status futuro:</label>
                        <div class="row">
                            <div class="col-md-4 col-xs-4" ng-repeat="item in status">
                                <label style="font-weight: normal;">
                                    <input type="checkbox" checklist-model="status_novo.status" checklist-value="item" name="status_novo[]" value="@{{item.id}}" class="checkbox-aling">
                                    <span class=""> @{{item.nome}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                        
                    <input type="hidden" name="departamento_id" value="{{$departamento}}">

                </div>    
                <!-- /.box-body -->
               
                <!-- form-horizontal -->  
                <div class="modal-footer">
                    <span ng-if="modal_tipo == 'cadastrar'">
                        <button class="btn btn-danger" type="button" ng-click="modalCancelarAcao();">Cancelar</button>
                        @can( 'CONFIGURACAO_TICKET_ACOES_CADASTRAR' )
                        <button class="btn btn-primary" type="submit" ng-disabled="!((form_modal.$valid) && (status_atual.status.length > 0))">Salvar</button>
                        @endcan
                    </span>

                    <span ng-if="modal_tipo == 'editar'">
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="acao_id" value="@{{acao_id}}">

                        @can( 'CONFIGURACAO_TICKET_ACOES_EXCLUIR' )
                        <button class="btn btn-warning pull-left" type="button" ng-click="modalConfirmAcao()" data-toggle="modal"><i class="fa fa-trash" aria-hidden="true"></i> &nbsp; Excluir ação</button>
                        @endcan
                     
                        <button class="btn btn-danger" type="button" ng-click="modalCancelarAcao();">Cancelar</button>

                        @can( 'CONFIGURACAO_TICKET_ACOES_EDITAR' )
                        <button class="btn btn-primary" type="submit" ng-disabled="!((form_modal.$valid) && (status_atual.status.length > 0))">Salvar</button>
                        @endcan
                    </span>
                </div>
            </form>
        </div>
    </script>

@endif

@endsection
