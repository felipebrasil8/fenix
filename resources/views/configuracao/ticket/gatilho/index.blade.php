@extends('configuracao.ticket.index')

@section('ticket')

<!-- Main content -->

<div class="box box-default" ng-controller="gatilhoCtrl">
    <div class="box-header with-border">
        <h3 class="box-title">GATILHOS</h3>
    </div>
    <!-- /.box-header -->

    
    <!-- form start -->
    <form action="/configuracao/ticket/gatilho/departamento/{{$departamento}}" name="departamento_gatilho"  method="get">
        <div class="box-header with-border">
            <div class="form-group">
                 <label for="inputEmail3" class="col-sm-2 control-label">Departamento:</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm" 
                                    ng-change="update()"
                                    ng-model="gatilho_departamento" 
                                    ng-options="item.nome for item in departamentos track by item.id">
                        </select>
                    </div>  
                    
                    @if($edit)
                             @can( 'CONFIGURACAO_TICKET_GATILHOS_CADASTRAR' )
                    <div class="col-sm-4">
                        <div class="btn-group">
                            <button type="button" 
                                        ng-click="modalGatilho( 'cadastrar', '')" 
                                        class="btn btn-primary">Adicionar gatilho</button>
                                                   
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
                dnd-dragend="atualizaGatilhos()"
                ng-class="{'selected': models.selected === item}" 
                >
                <a ng-click="modalGatilho( 'editar', item )" style="cursor: pointer;"> 
                    @{{ item.nome }}
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
 
    var _todosdep = {!!$todosdep!!};

    var _responsaveis = {!! $responsaveis !!};
    var _cargos = {!! $cargos !!};
    var _usuarios = {!! $usuarios !!};
    var _status = {!! $status !!};
    var _gatilhos = {!! $gatilhos !!};
</script>
  @if( $edit )

  
    <script type="text/ng-template" id="gatilhoModal.html">
        <div class="modal-header">            
            <h4 class="modal-title" id="modal-title">Adicionar gatilho</h4>
        </div>
        <div class="modal-body">
           
            <form role="form" data-toggle="validator" name="form" action="@{{ url }}" method="POST">

                <div class="box-body">
                    <div class="col-md-6 col-xs-6" style="padding-left: 0;">
                        <div class="form-group">
                            <label >Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" 
                                            MAXLENGTH=50
                                            class="form-control input-sm"  
                                            name="nome" 
                                            ng-model="nomeGatilho"
                                            placeholder="NOME DO GATILHO" 
                                            style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-6" style="padding-left: 0;">
                        <div class="form-group"> 
                            <label >Descrição:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input 
                                        type="text" 
                                        class="form-control input-sm" 
                                        name="descricao" 
                                        ng-model="decricaoGatilho"
                                        placeholder="DESCRIÇÃO DO GATILHO" 
                                        required="required"  style="text-transform: uppercase;" > 
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="form-group">
                        <label>Quando executar:</label>
                        <div class="row">
                              <div class="col-md-11 col-xs-11" style="padding-left: 0;">
                          

                                <div class="col-md-3 col-xs-3">
                                    <label>
                                        <input type="radio" 
                                                name="executar" 
                                                value="status" 
                                                ng-model="teste" 
                                                > 
                                        <span style="font-weight: normal;">ALTERAÇÃO DE STATUS</span>
                                    </label>
                                </div>





                                <div class="col-md-4 col-xs-4">
                                     <label>
                                        <input type="radio" 
                                                name="executar" 
                                                value="res" 
                                                ng-model="teste" 
                                               >  
                                            <span style="font-weight: normal;">ALTERAÇÃO DE RESPONSÁVEL</span>
                                    </label>
                                </div>



                                <div class="col-md-3 col-xs-3">
                                     <label>
                                        <input type="radio" 
                                                name="executar" 
                                                value="not" 
                                                ng-model="teste" 
                                               ng-click="funcao = false"
                                                > 
                                            <span style="font-weight: normal;">DATA DE NOTIFICAÇÃO</span>
                                    </label>
                                </div>



                                <div class="help-block with-errors" ></div>
                            </div>                            
                        </div>
                    </div>


                    <div class="col-md-6 col-xs-6" style="padding-left: 0;">
                     


                        <div class="form-group"  ng-if="teste == 'status'">
                            <label >Status:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    
                                    <select class="form-control input-sm" name="status" ng-model="statuscombo" ng-options="item as item.nome for item in statuses track by item.id" required>
                                        <option value=""></option>
                                </select>
                                    
                                    <div class="help-block with-errors" ></div>
                                </div>                        
                                    <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                             </div>
                        </div>







                        <div class="form-group" ng-if="teste == 'res'">
                            <label >Responsável:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                   
                               
                                <select class="form-control input-sm" name="responsavel" ng-model="responsavelcombo" ng-options="item as item.nome for item in responsaveis track by item.id" required>
                                        <option value=""></option>
                                </select>

                                    <div class="help-block with-errors" ></div>
                                </div>                        
                                    <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

      
                        <div class="form-group" ng-if="teste == 'not'">
                            <label >Mensagem:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" class="form-control input-sm" name="mesagemnot" ng-model="notmesagem" placeholder="Mensagem da notificação" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                     
           
                    <div class="form-group" ng-show="teste == 'res' || teste == 'status'">
                        <div class="col-md-12" style="padding-left: 0;">
                            <label >Ação:</label>
                        </div>
                        <div class="row">
                              <div class="col-md-12 col-xs-12" style="padding-left: 0;">
                                     <div class="col-md-3 col-xs-3">
                                    <label>
                                        <input type="radio" 
                                                name="acao" 
                                                value="notif"
                                                ng-model="funcao"
                                            >   
                                        <span style="font-weight: normal;">
                                            GERAR NOTIFICAÇÃO
                                        </span>
                                    </label>
                                  </div>


                                <div class="col-md-3 col-xs-3">
                                    <label>
                                        <input type="radio" 
                                                name="acao" 
                                                value="respon"
                                                ng-model="funcao"
                                           >   
                                             <span style="font-weight: normal;">
                                                MUDAR RESPONSÁVEL
                                            </span>
                                    </label>
                                    </div>


                                <div class="col-md-3 col-xs-3">
                                    <label>
                                        <input type="radio" 
                                                name="acao" 
                                                value="data"  
                                                ng-model="funcao"
                                            >  
                                         <span style="font-weight: normal;">
                                        MUDAR DATA
                                        </span>
                                    </label>
                                    
                                 </div>

                                    <div class="help-block with-errors" ></div>
                            </div>                            
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-6" style="padding-left: 0;"  ng-if="funcao == 'notif'">
                        <div class="form-group">
                            <label >Mensagem:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" class="form-control input-sm" name="mensagem" placeholder="Mensagem da notificação" ng-model="mensagemacao" style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group" ng-if="funcao == 'notif'">
                        <div class="col-md-12" style="padding-left: 0;">
                            <label >Gerar notificação para:</label>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12" style="padding-left: 0;">
                               

                                <div class="col-md-3 col-xs-3">
                                    <label>
                                        <input type="checkbox" name="solicitane" ng-model="solicitantenotificacao"> 
                                        <span style="font-weight: normal;">
                                            SOLICITANTE
                                        </span>
                                    </label>

                                    <label style="margin-right: 10px;">
                                        <input type="checkbox" name="responsavel" ng-model="responsavelnotificacao">  
                                     <span style="font-weight: normal;">
                                            RESPONSÁVEL
                                    </span>
                                    </label>
                                </div>


                                <div class="col-md-3 col-xs-3">
                                    <label style="margin-right: 10px;">
                                        <input type="checkbox" name="departamento" ng-model="departamentonotificacao"> 
                                        <span style="font-weight: normal;">
                                            DEPARTAMENTO:
                                        </span>
                                    </label>

                                    <label>
                                        <select multiple class="form-control input-sm" name="listaDepartamento[]" ng-model="deplist" style="width: 100%;">
                                            @foreach($todosdep as $item) 
                                                <option value="{{$item->id}}">{{$item->nome}}</option>
                                            @endforeach

                                        </select>
                                    </label>
                                </div>
                                

                                <div class="col-md-3 col-xs-3">
                                    <label style="margin-right: 10px;">
                                        <input type="checkbox" name="cargo" ng-model="cargonotificacao">   <span style="font-weight: normal;">CARGO:</span>
                                    </label>
                               <br>
                                    <label>
                                        <select multiple class="form-control input-sm" name="listaCargo[]" ng-model="carglist" style="width: 100%;">
                                          @foreach($cargos as $item) 
                                                <option value="{{$item->id}}">{{$item->nome}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>


                                <div class="col-md-3 col-xs-3">
                                    <label style="margin-right: 10px;">
                                        <input type="checkbox" name="usuario" ng-model="usuarionotificacao">  <span style="font-weight: normal;"> USUÁRIO:</span>
                                    </label>
                                <br>
                                    <label>
                                        <select multiple class="form-control input-sm" ng-model="usualist" name="listaUsuarios[]"  style="width: 100%;">
                                           @foreach($usuarios as $item) 
                                                <option value="{{$item->id}}">{{$item->nome}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                    
                           
                                                    

                                <div class="help-block with-errors" ></div>
                            </div>                        
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-6" style="padding-left: 0;" ng-if="funcao == 'respon'">
                        <div class="form-group"> 
                            <label >Responsável:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                   <select class="form-control input-sm" name="acaoResposavel" ng-model="responsavelacaocombo" ng-options="item as item.nome for item in responsaveis track by item.id" required>
                                        <option value=""></option>
                                </select>

                                    <div class="help-block with-errors" ></div>
                                </div>  
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                      
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-md-6 col-xs-6" style="padding-left: 0;"  ng-if="funcao == 'data'">
                        <div class="form-group"> 
                            <label >Campo:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                   
                                    <select class="form-control input-sm" name="dataCampo" ng-model="datateste" required>
                                        <option value=""></option>
                                        <option value="dt_fechamento"> FECHAMENTO </option>
                                        <option value="dt_notificacao"> NOTIFICAÇÃO </option>
                                        <option value="dt_previsao"> PREVISÃO </option>
                                        <option value="dt_resolucao"> RESOLUÇÃO </option>
                                    </select>


                                    <div class="help-block with-errors" ></div>
                                </div>  
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                      
                            </div>
                        </div>


                        <div class="form-group"> 
                            <label >Valor:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <select class="form-control input-sm" name="valor" ng-model="acaodataaltera" required>
                                        <option value=""></option>
                                        <option value="now()"> AGORA </option>
                                        <option value="null"> VAZIO </option>
                                    </select>


                                    <div class="help-block with-errors" ></div>
                                </div>        
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>                
                            </div>
                        </div>
                    </div>

                          
                    

                    <input type="hidden" name="_method" ng-if="putedit" value="put">
                    <input type="hidden" name="departamento_id" value="{{$departamento}}">

                </div>    
     
                <!-- /.box-body -->

                <!-- form-horizontal -->  
                <div class="modal-footer">

                      @can( 'CONFIGURACAO_TICKET_GATILHOS_EXCLUIR' )
                         <button class="btn btn-warning pull-left" 
                                 type="button" 
                                 ng-show="excluir"
                                ng-click="modalConfirmaGatilho ('configuracao/ticket/gatilho/destroy')"
                                 data-toggle="modal">
                        <i class="fa fa-trash" aria-hidden="true"></i> &nbsp; Excluir gatilho</button>
                        
                   
                        @endcan


                    <button class="btn btn-danger" type="button" ng-click="modalCancelarGatilho()">Cancelar</button> 


                    <button class="btn btn-primary" ng-if="!excluir" type="submit">Salvar</button>

                    <button class="btn btn-primary" ng-if="excluir" type="submit">Salvar</button>
            
                </div>        
                    
            </form>

        
       
      
        </div>
    </script>

   

        @endif

    @endsection
