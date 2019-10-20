@extends('core.app')

@section('titulo', 'Tickets')

@section('content')

<migalha titulo="Tickets" descricao="Abrir ticket"></migalha>

<section class="content" ng-controller="cadastrarTicketCtrl">

    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">
            
                <form role="form" data-toggle="validator" name="form" ng-submit="cadastrar(ticket)">
                    
                    <div class="box-header with-border">
                        <i class="fa fa-info-circle"></i>
                        <h3 class="box-title">Informações do ticket</h3>
                    </div>
                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-3">
                            <label for="nome">Departamento:</label>                            
                            <div class="row">
                                <div class="col-md-11 col-xs-10">                                    
                                    <select class="form-control input-sm" name="departamento" id="departamento" ng-model="ticket.departamento" 
                                        ng-options="x.nome for x in departamentos track by x.id" 
                                        ng-change="alterarDepartamento(oldValue)" 
                                        ng-focus="oldValue=ticket.departamento"                                         
                                        required>
                                        <option value=""></option>
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>    
                                                                    
                        <div class="form-group col-md-3" ng-show="ticket.departamento">
                            <label for="nome_completo">Solicitante:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" name="solicitante" id="solicitante"                                     
                                        ng-model="ticket.solicitante" 
                                        ng-options="x.nome for x in solicitantes" required>
                                        <option></option>
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3" ng-show="ticket.departamento">
                            <label for="email">Prioridade:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" name="prioridade" id="prioridade" 
                                        ng-model="ticket.prioridade" 
                                        ng-change="verificaDescricaoPrioridade( ticket.prioridade.id )" 
                                        ng-options="x.nome for x in prioridades" required>
                                        <option></option>
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    
                    <span ng-show="ticket.departamento">
                        <div class="box-header with-border">
                            <i class="fa fa-ticket"></i>
                            <h3 class="box-title">Detalhes do ticket</h3>
                        </div>                        
                        <div class="box-body row" style="padding-bottom: 0;">
                            <div class="form-group col-md-3">
                                <label for="nome">Assunto:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-10">
                                        <input type="text" class="form-control input-sm" name="assunto" id="" data-error="Este campo é obrigatório." ng-model="ticket.assunto" required style="text-transform: uppercase;">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="nome">Categoria:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-10">
                                        <select class="form-control input-sm" name="categoria" required 
                                            ng-model="ticket.categoria" 
                                            ng-change="mostrarSubcategoria( ticket.categoria.id )" 
                                            ng-options="x.nome for x in categorias"
                                            required>
                                            <option value=""></option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3" ng-hide="subcategoria.length == 0">
                                <label for="nome">Subcategoria:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-10">
                                        <select class="form-control input-sm" ng-options="item.nome for item in subcategoria track by item.id" ng-model="ticket.subcategoria" ng-change="dicaSubcategoria( ticket.subcategoria )" required>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="box-body row" style="padding-bottom: 0; padding-top: 0;">
                            <div class="form-group col-md-12">
                                <label>Descrição:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-10">
                                        <textarea rows="4" class="form-control input-sm" ng-model="ticket.descricao" style="text-transform: uppercase; resize:none;" required></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="box-header with-border" ng-show="campos_adicionais.length">
                            <i class="fa fa-plus-circle"></i>
                            <h3 class="box-title">Dados adicionais</h3>
                        </div>
                        <div class="box-body row" style="padding-bottom: 0;">
                            
                            <div ng-repeat="x in campos_adicionais">

                                <div class="form-group col-md-3">
                                    <label for="nome">@{{ x.nome }}:</label>
                                    <div class="row">
                                        <div class="col-md-11 col-xs-10">                                            
                                            <p ng-bind-html="renderHtml(x.html)"></p>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                            <i class="fa fa-asterisk"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>                            
                    
                        </div>
                        
                        <div class="box-footer">                            
                            <button type="submit" class="btn btn-primary" ng-disabled="!form.$valid">Salvar</button>
                        </div>
                    </span>

                </form>

            </div>
            <!-- /.box -->
            
        </div>
        <!-- /.left colum -->
    </div>

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>     
        
    <!-- Sucesso -->
    <msg-success ng-show="success"></msg-success>     

</section>

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
    var _departamentos = <?=json_encode($departamento);?>;
</script>

<script type="text/ng-template" id="todasDicaModal.html">
    <div class="modal-header">
        <button type="button" class="close" aria-label="Fechar" ng-click="continuarTicket()">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Deseja continuar a abertura de ticket?</h4>
    </div>
    <div class="modal-body">   
        <div class="row">
            <div class="col-md-12">
                <span ng-bind-html="dicaModal"></span>
            </div>
        </div>
    </div>      
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" ng-click="pesquisarTicket()" style="margin-right: 10px;">NÃO</button>
      <button type="button" class="btn btn-primary" ng-click="continuarTicket()" autofocus>SIM</button>
    </div>
</script>

<script type="text/ng-template" id="confirmaAlterarDepartamento.html">
    <section>
        <div class="row">        
            <div class="col-md-12">
                <div class="modal-header">
                    <button type="button" class="close" aria-label="Fechar" ng-click="cancelarModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">As informações do ticket na tela serão perdidos<br>Deseja continuar?</h4>

                </div>                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" ng-click="cancelarModal()" style="margin-right: 10px;">NÃO</button>
                    <button type="button" class="btn btn-primary" ng-click="confirmaModal()" autofocus>SIM</button>
                </div>
            </div>
        </div>
    </section>
</script>

<script type="text/ng-template" id="dicaModalPrioridades.html">
    <div class="modal-header">
        <button type="button" class="close" aria-label="Fechar" ng-click="continuarTicket()">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Deseja continuar a abertura de ticket nesta prioridade?</h4>
    </div>
    <div class="modal-body">   
        <div class="row">
            <div class="col-md-12">
                <span ng-bind-html="dicaModal"></span>
            </div>
        </div>
    </div>      
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" ng-click="checarMenorPrioridade()" style="margin-right: 10px;">NÃO</button>
      <button type="button" class="btn btn-primary" ng-click="continuarTicket()" autofocus>SIM</button>
    </div>
</script>

@endsection
