@extends('configuracao.ticket.index')

@section('ticket')

<div class="box box-default" ng-controller="camposAdicionaisCtrl">
    <div class="box-header with-border">
        <h3 class="box-title">CAMPOS ADICIONAIS</h3>
    </div>
    <!-- /.box-header --> 
 
    <!-- form start -->
    <form class="form-horizontal" name="departamento" method="get">        
        <div class="box-header with-border">
            <div class="form-group">
                <div class="row">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Departamento</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm" ng-change="update()" ng-model="campo_adicional.departamento" ng-options="item.nome for item in departamentos track by item.id">                            
                        </select>
                    </div>                
                    <div class="col-sm-4" ng-show="departamentos_selected">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" style="pointer-events: none;">Adicionar campo</button>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">                            
                                <li><a href="#" ng-click="modalAddCamposAdicionais('lista')">LISTA</a></li>
                                <li><a href="#" ng-click="modalAddCamposAdicionais('texto')">TEXTO</a></li>
                                <li><a href="#" ng-click="modalAddCamposAdicionais('longo')">TEXTO LONGO</a></li>
                            </ul>
                        </div>    
                    </div>
                </div>
                
            </div>
            <!-- form-group -->
        </div>
        <!-- box-body with-border -->
    </form>

    @if( $departamento_id )
    <input type="hidden" ng-model="departamento_id" ng-init="departamento_id = '{{$departamento_id}}'">
    @endif

    <form class="form-horizontal">
        <div class="box-body">

            @if( $status )
            <div class="form-group" ng-show="departamentos_selected">
                <div class="row">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Status:</label>
                    <div class="col-sm-4">                    
                        <select class="form-control" name="status">
                            @foreach($status as $statu)
                                <option value="{{$statu->id}}">{{$statu->nome}}</option>
                            @endforeach
                        </select>
                    </div>

                    

                    <div class="col-sm-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" 
                            @if( count($status) > 0 ) ng-click="modalEditarCampoAdicionalStatus()" @endif >
                                <span class="fa fa-pencil"></span>
                            </button>  
                            <button type="button" class="btn btn-default" ng-click="modalAddCampoAdicionalStatus()">
                                <span class="fa fa-plus-square"></span>
                            </button>  
                        </div>
                    </div>
                </div>
            </div>            
            @endif 
        </div>
    </form>

    <form class="form-horizontal">
        <div class="box-body">
            @if( $prioridades )
            <div class="form-group" ng-show="departamentos_selected">
                <div class="row">                    
                    <label for="inputEmail3" class="col-sm-2 control-label">Prioridade:</label>
                    <div class="col-sm-4">                      
                        <select class="form-control" name="prioridade">
                            @foreach($prioridades as $prioridade)
                                <option value="{{$prioridade->id}}">{{$prioridade->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" 
                            @if( count($prioridades) > 0 ) ng-click="modalEditarCampoAdicionalPrioridade()" @endif >
                                <span class="fa fa-pencil"></span>
                            </button>  
                            <button type="button" class="btn btn-default" ng-click="modalAddCampoAdicionalPrioridade()">
                                <span class="fa fa-plus-square"></span>
                            </button>  
                        </div>
                    </div>                
                </div>
            </div>
            <!-- form-group -->
            @endif            

        </div>
        <!-- /.box-body -->
    </form>

<!-- ------------------------------------------------------- -->
<!-- ------------------------------------------------------- -->
<!-- -----CAMPOS ADICIONAIS - ira ser mostrado na index----- -->    
<!-- ------------------------------------------------------- -->
<!-- ------------------------------------------------------- -->
    <span ng-repeat="item in campos_adicionais_lista">       
        <form class="form-horizontal">    
            <div class="box-body">        
                <div class="form-group">
                    <div class="row">
                        <label for="" class="col-sm-2 control-label" title="@{{item.descricao}}">@{{item.nome}}</label>
                        <div class="col-sm-4">                            
                            <select class="form-control"  
                            ng-options="x.valor for x in item.dados" 
                            ng-model="statusSelected" 
                            ng-init="statusSelected=item.padrao"
                            >                            
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="btn-group" role="group">                    
                                <button type="button" class="btn btn-default" ng-click="modalEditarCamposAdicionais(item.id)" data-toggle="modal">
                                    <span class="fa fa-pencil"></span>
                                </button>

                                <button type="button" class="btn btn-default" ng-click="modalExcluirCamposAdicionais(item.id)" data-toggle="modal">
                                    <span class="fa fa-trash"></span>
                                </button>                                
                            </div>                    
                        </div>            
                    </div>
                </div>
            </div>
        </form>
    </span>

    <span ng-repeat="item in campos_adicionais_longo">
        <form class="form-horizontal">            
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <label for="" class="col-sm-2 control-label" title="@{{item.descricao}}">@{{item.nome}}</label>
                        <div class="col-sm-4">
                            <textarea class="form-control" readonly="readonly" rows="5" ng-model="item.padrao" style="text-transform: uppercase; resize:none"></textarea>
                        </div>
                        <div class="col-sm-4">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-default" ng-click="modalEditarCamposAdicionais(item.id)" data-toggle="modal">
                                    <span class="fa fa-pencil"></span>
                                </button>
                                
                                <button type="button" class="btn btn-default" ng-click="modalExcluirCamposAdicionais(item.id)" data-toggle="modal">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </span>

    <span ng-repeat="item in campos_adicionais_texto">
        <form class="form-horizontal">    
            <input name="_method" type="hidden" value="DELETE">
            <div class="box-body">        
                <div class="form-group">
                    <div class="row">
                        <label for="" class="col-sm-2 control-label" title="@{{item.descricao}}">@{{item.nome}}</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" readonly="readonly" ng-model="item.padrao">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="btn-group" role="group">                    
                                <button type="button" class="btn btn-default" ng-click="modalEditarCamposAdicionais(item.id)" data-toggle="modal">
                                    <span class="fa fa-pencil"></span>
                                </button>                                
                                
                                <button type="button" class="btn btn-default" ng-click="modalExcluirCamposAdicionais(item.id)" data-toggle="modal">
                                    <span class="fa fa-trash"></span>
                                </button>
                            </div>                    
                        </div>            
                    </div>
                </div>
            </div>
        </form>
    </span>

<!-- ------------------------------------- -->
<!-- ------------------------------------- -->
<!-- -----CADASTRAR CAMPOS ADICIONAIS----- -->
<!-- ------------------------------------- -->
<!-- ------------------------------------- -->
    <script type="text/ng-template" id="addCamposAdicionaisModal.html">
        <section class="content">
            <div class="row">        
                <div class="col-md-12">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                            <span aria-hidden="true">×</span>
                        </button>            
                        <h4 class="modal-title" id="modal-title">Cadastrar campo adicional</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form" action="/configuracao/ticket/campo_adicional" method="post">
                            <input type="text" ng-hide="true" name="departamento_id" ng-model="departamento_id">
                            <input type="text"   name="tipo" ng-model="tipo" ng-hide="true">
                            <input type="hidden" name="_method" value="POST">
                            <div class="box-body">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Nome:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <input type="text" class="form-control" name="nome" placeholder="NOME DO CAMPO" required="required" style="text-transform: uppercase; resize:none">                                                    
                                                </div>
                                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                    <i class="fa fa-asterisk"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- radio -->
                                        <div class="form-group">
                                        <label>Obrigatório:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <label class="radio-inline">                                    
                                                        <input type="radio" name="obrigatorio" value="true" checked="checked">
                                                        <b>SIM</b>
                                                    </label>
                                                    <label class="radio-inline">                                    
                                                        <input type="radio" name="obrigatorio" value="false">
                                                        <b>NÃO</b>
                                                    </label>                                                    
                                                </div>
                                            </div>

                                        </div>                            
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Descrição:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <input type="text" class="form-control" name="descricao" placeholder="DESCRIÇÃO DO CAMPO" required="required" style="text-transform: uppercase;">                                                    
                                                </div>
                                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                    <i class="fa fa-asterisk"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- radio -->
                                        <div class="form-group">
                                            <label>Vísivel para o usuário solicitante:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <label class="radio-inline">                                    
                                                        <input type="radio" name="visivel" value="true" checked="checked">
                                                        <b>SIM</b>
                                                    </label>
                                                    <label class="radio-inline">                                    
                                                        <input type="radio" name="visivel" value="false">
                                                        <b>NÃO</b>
                                                    </label>                                                    
                                                </div>
                                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                    
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div> 

                                <!-- Campos Adicionais -->
                                <!--tipo lista-->
                                <div class="row" ng-show="mostrar_lista">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="" style="margin-right: 15px;">Itens da lista:</label>
                                                        <button type="button" class="btn btn-default" ng-click="addItem()" >
                                                            <span class="fa fa-plus-square"></span>
                                                        </button>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" ng-model="lista_input" name="lista" ng-hide="true">
                                            <span ng-repeat="item in lista track by $index">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="form-group">

                                                            <input type="radio" name="lista_radio[]" style="margin-right: 15px" ng-click="atualiza(item.id)">
                                                            
                                                            <input type="text" name="lista_texto_cadastrar[]" style="margin-right: 15px; text-transform: uppercase;" >

                                                            <button type="button" class="btn btn-default" ng-show="$last" ng-click="removeItem()">
                                                                <span class="fa fa-trash"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>                                        
                                    </div>
                                </div>
                                <!--tipo texto longo-->
                                <div class="row" ng-show="longo">                                    
                                    <div class="col-sm-6">                                        
                                        <div class="form-group">
                                            <label for="">Padrão:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <textarea class="form-control" name="texto_longo" rows="5" placeholder="campo padrão" style="text-transform: uppercase; resize:none"></textarea>                                                    
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <!--tipo texto-->
                                <div class="row" ng-show="texto">                                    
                                    <div class="col-sm-6">                                        
                                        <div class="form-group">
                                            <label for="">Padrão:</label>
                                            <div class="row">
                                                <div class="col-md-11 col-xs-10">
                                                    <input type="text" class="form-control" placeholder="campo padrão" name="texto" style="text-transform: uppercase;">
                                                </div>                                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.box-body -->

                            <!-- form-horizontal -->  
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" ng-click="close();" style="margin-right: 10px;">Cancelar</button>
                                <button type="submit" class="btn btn-primary" autofocus>Salvar</button>
                            </div>          
                        </form>
                    </div>
                </div>
            </div>                    
        </section>
    </script>

<!-- ---------------------------------- -->
<!-- ---------------------------------- -->
<!-- -----EDITAR CAMPOS ADICIONAIS----- -->    
<!-- ---------------------------------- -->
<!-- ---------------------------------- -->
    <script type="text/ng-template" id="editCamposAdicionaisModal.html">
        <section class="content">
            <div class="row">        
                <div class="col-md-12">
                    <form class="form" action="/configuracao/ticket/campo_adicional/{{$departamento_id}}" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                                <span aria-hidden="true">×</span>
                            </button>           
                            <h4 class="modal-title" id="modal-title">Editar campo adicional@{{campo.id}}</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="text"   name="campo_id" ng-model="campo.campo_id" ng-hide="true">
                            <input type="text"   name="departamento_id" ng-model="campo.departamento_id" ng-hide="true">
                            <input type="text"   name="tipo" ng-model="campo.tipo" ng-hide="true">                           

                            <div class="row">
                                <div class="col-sm-6">
                                    
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <input type="text" ng-model="campo.nome" class="form-control" name="nome" placeholder="NOME DO CAMPO" required="required" style="text-transform: uppercase; resize:none">                                                    
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                    <label>Obrigatório:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <label class="radio-inline">                                    
                                                    <input type="radio" name="obrigatorio" ng-model="campo.obrigatorio" value="true">
                                                    <b>SIM</b>
                                                </label>
                                                <label class="radio-inline">                                    
                                                    <input type="radio" name="obrigatorio" ng-model="campo.obrigatorio" value="false">
                                                    <b>NÃO</b>
                                                </label>                                                    
                                            </div>
                                        </div>

                                    </div>                            
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Descrição:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <input type="text" class="form-control" name="descricao" ng-model="campo.descricao" placeholder="DESCRIÇÃO DO CAMPO" required="required" style="text-transform: uppercase;">                                                    
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Vísivel para o usuário solicitante:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <label class="radio-inline">                                    
                                                    <input type="radio" name="visivel" ng-model="campo.visivel" value="true">
                                                    <b>SIM</b>
                                                </label>
                                                <label class="radio-inline">                                    
                                                    <input type="radio" name="visivel" ng-model="campo.visivel" value="false">
                                                    <b>NÃO</b>
                                                </label>                                                    
                                            </div>
                                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">                                                    
                                            </div>
                                        </div>
                                    </div>                                        
                                </div>
                            </div>                                   

                            <!-- Campos Adicionais -->
                            <div class="row" ng-show="mostrar_lista_edit">
                                <div class="col-sm-6">
                                    <div class="form-group">                                            
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="" style="margin-right: 15px;">Itens da lista:</label>
                                                    <button type="button" class="btn btn-default" ng-click="addItem()" >
                                                        <span class="fa fa-plus-square"></span>
                                                    </button>                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <input type="text" ng-model="lista_input" name="lista" ng-hide="true">

                                        <span ng-repeat="item in campo.lista track by $index">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        
                                                        <input type="radio" name="lista_radio[]" value="true" ng-model="item.padrao" style="margin-right: 15px" ng-click="atualiza(item.id)">
                                                        
                                                        <input type="text" name="lista_texto_editar[]" ng-model="item.valor" style="margin-right: 15px; text-transform: uppercase;">

                                                        <button type="button" class="btn btn-default" ng-show="$last" ng-click="removeItem()">
                                                            <span class="fa fa-trash"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </span>
                                    </div>                                        
                                </div>
                            </div>

                            <div class="row" ng-show="longo_edit">                                    
                                <div class="col-sm-6">                                        
                                    <div class="form-group">
                                        <label for="">Padrão:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <textarea class="form-control" name="texto_longo" rows="5" ng-model="campo.padrao" style="text-transform: uppercase; resize:none"></textarea>                                                    
                                            </div>
                                        </div>
                                    </div>                                        
                                </div>
                            </div>

                            <div class="row" ng-show="texto_edit">                                    
                                <div class="col-sm-6">                                        
                                    <div class="form-group">
                                        <label for="">Padrão:</label>
                                        <div class="row">
                                            <div class="col-md-11 col-xs-10">
                                                <input type="text" ng-model="campo.padrao" class="form-control" name="texto" style="text-transform: uppercase;">
                                            </div>                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- form-horizontal -->  
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" ng-click="close();" style="margin-right: 10px;">Cancelar</button>
                            <button type="submit" class="btn btn-primary" autofocus>Salvar</button>
                        </div>          
                    </form>
                </div>
            </div>                    
        </section>
    </script>

<!--------------------------------------- -->
<!-------------------------------------- --->
<!-- -----EXCLUIR CAMPOS ADICIONAIS-----  -->
<!-------------------------------------- --->
<!--------------------------------------- -->
    <script type="text/ng-template" id="excluirCamposAdicionaisModal.html">
        <section>
            <div class="row">        
                <div class="col-md-12">
                    <div class="modal-header">
                        <button type="button" class="close" aria-label="Fechar" ng-click="modalCancelarAlterarStatus()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Confirma a exclusão do campo?</h4>
                    </div>                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" ng-click="modalCancelarAlterarStatus()" style="margin-right: 10px;">NÃO</button>
                        <button type="button" class="btn btn-primary" ng-click="alterarStatus({{$departamento_id}})" autofocus>SIM</button>
                    </div>
                </div>
            </div>
        </section>
    </script>

<!---------------------------------------  -->
<!---------------------------------------  -->
<!-- -----CADASTRAR STATUS----- -->
<!---------------------------------------  -->
<!---------------------------------------  -->
    @if( $status )    
    <script type="text/ng-template" id="addCampoAdicionalStatusModal.html">
        <section class="content">
            <div class="row">        
                <div class="col-md-12">                                          
                    <form class="form" ng-submit="cadastrar(status)">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                                <span aria-hidden="true">×</span>
                            </button>           
                            <h4 class="modal-title" id="modal-title">Cadastrar campo status</h4>
                        </div>
                        <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-3">                                        
                                        <label>Nome:</label>
                                        <input type="text" class="form-control" ng-model="status.nome" placeholder="NOME DO STATUS" required="required" style="text-transform: uppercase;">
                                                                 
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Descrição:</label>         
                                        <input type="text" class="form-control" ng-model="status.descricao" placeholder="DESCRIÇÃO DO STATUS " required="required" style="text-transform: uppercase;">
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="">Prioridade</label>                                    
                                            <input type="number" class="form-control" ng-model="status.ordem" required="required">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <label>Cor:</label>                                
                                        <div class="input-group">
                                            <input type="text" class="form-control" ng-model="status.cor" ng-init="status.cor = '#000000' " required="required" style="text-transform: uppercase; width: 70%;" readonly="readonly">
                                            <input type="color" class="form-control" ng-model="status.cor" style="width: 30%;">                                                                                
                                        </div>                        
                                    </div>

                                    <div class="col-sm-1" style="text-align: center">                                        
                                        <label for="">Aberto</label>
                                        <div class="checkbox">                                                                                   
                                            <input type="checkbox" ng-model="status.aberto" ng-true-value="true" ng-true-false="false"> 
                                        </div>                                            
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">                
                            <button type="button" class="btn btn-danger" ng-click="close();" style="margin-right: 10px;">Cancelar</button>
                            <button type="submit"  class="btn btn-primary" autofocus>Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Erros -->        
            <div class="row" ng-show="errors">
                <div class="col-md-12">
                    <div class="callout no-margin-bottom callout-danger" style="margin-top: 15px;">
                        <div>@{{ errors | filterJSON | filterDataNascimento }}</div>        
                    </div>
                </div>
            </div>   
            <!-- Sucesso -->
            <div class="row callout no-margin-bottom callout-success" ng-show="success"> 
                @{{ success }} &nbsp;         
            </div>
        </section>  
    </script>
    @endif

<!-- ------------------------------------ -->
<!-- ------------------------------------ -->
<!-- -----EDITAR CAMPO STATUS MODAL-----  -->
<!-- ------------------------------------ -->
<!-- ------------------------------------ -->
    @if( $status )    
    <script type="text/ng-template" id="editarCampoAdicionalStatusModal.html">
        <section class="content">
            <div class="row">        
                <div class="col-md-12">
                    <form class="form" action="/configuracao/ticket/campo_adicional/status/{{$departamento_id}}" method="post" name="form_editar_status">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                                <span aria-hidden="true">×</span>
                            </button>     
                            <h4 class="modal-title" id="modal-title">Editar campo status</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_method" value="PUT">                            
                            <div class="row">
                                <div class="col-sm-3">
                                        <label>Nome:</label>                                        
                                </div>
                                <div class="col-sm-3">
                                        <label>Descrição:</label>                                        
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Prioridade</label>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <label>Cor:</label>
                                </div>
                                <div class="col-sm-1" style="text-align: center">
                                    <label style="">Aberto</label>
                                    
                                </div>
                                <div class="col-sm-1">
                                    <label for="">&nbsp;</label>
                                </div>
                            </div>

                                                           
                            @foreach($status as $statu)                    
                            <input type="hidden" name="status_id[]" value="{{$statu->id}}">
                            <input type="hidden" name="departamento_id[]" value="{{$statu->departamento_id}}">
                            <div class="row" ng-hide="status{{$statu->id}}">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="nome[]" value="{{$statu->nome}}" placeholder="NOME DO CAMPO" required="required" style="text-transform: uppercase;">
                                    </div>                            
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="descricao[]" value="{{$statu->descricao}}" placeholder="DESCRIÇÃO DO STATUS " required="required" style="text-transform: uppercase;">
                                    </div>                            
                                </div>

                                <div class="col-sm-2">
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="ordem[]" value="{{$statu->ordem}}" required="required">
                                        </div>
                                    </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="input-group">                                    
                                            <input type="text" class="form-control" name="cor[]" ng-model="cor{{$statu->id}}" ng-init="cor{{$statu->id}} = '{{ $statu->cor }}'" style="width: 70%;" readonly="readonly">
                                            <input type="color" class="form-control" ng-model="cor{{$statu->id}}" value="{{ $statu->cor }}" style="width: 30%;">                                    
                                        </div>                                
                                    </div>                            
                                </div>

                                <div class="col-sm-1">                                    
                                    <div class="checkbox" style="text-align: center;">
                                        <input type="checkbox" name="aberto_[]" ng-model="myVar{{$statu->id}}"  @if( $statu->aberto ) ng-checked="1" @endif />
                                        <input type="text" ng-hide="true" name="aberto[]" ng-model="myVar{{$statu->id}}" ng-init="myVar{{$statu->id}} = @if( $statu->aberto ) true @else false @endif">
                                    </div>                                        
                                </div>                       

                                <div class="col-sm-1">                            
                                    <div class="form-group">                                            
                                        <div class="input-group">                                    
                                            <button type="button" class="btn btn-default" data-toggle="modal" ng-click="setExcluirCampoAdicionalStatus({{$statu->id}}); status{{$statu->id}} = true">
                                                <span class="fa fa-trash"></span>
                                            </button>                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach  
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" ng-click="close()" style="margin-right: 10px;">Cancelar</button>
                            <button type="button" class="btn btn-primary" ng-click="modalExcluirCampoAdicionalStatus()" autofocus>Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </script>
    @endif

<!-- ------------------------------------ -->
<!-- ------------------------------------ -->
<!-- -----CADASTRAR PRIORIDADE-----  -->
<!-- ------------------------------------ -->
<!-- ------------------------------------ -->
    @if( $prioridades )    
    <script type="text/ng-template" id="addCampoAdicionalPrioridadeModal.html">
        <section class="content">
            <div class="row">        
                <div class="col-md-12">
                    <form class="form" ng-submit="cadastrar(prioridade)">
                        <div class="modal-header">            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                                <span aria-hidden="true">×</span>
                            </button> 
                            <h4 class="modal-title" id="modal-title">Cadastrar campo prioridade</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-3">                                    
                                    <div class="form-group">
                                        <label>Nome:</label>
                                        <input type="text" class="form-control" ng-model="prioridade.nome" placeholder="NOME DO CAMPO" required="required" style="text-transform: uppercase;">
                                    </div>                            
                                </div>
                                <div class="col-sm-4">
                                    <label>Descrição:</label>
                                    <input type="text" class="form-control" ng-model="prioridade.descricao" placeholder="DESCRIÇÃO DE CAMPO" style="text-transform: uppercase;">
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Cor:</label>                                
                                        <div class="input-group">                                    
                                            <input type="text" class="form-control" ng-model="prioridade.cor" style="width: 70%;" readonly="readonly" ng-init="prioridade.cor = '#000000' ">
                                            <input type="color" class="form-control" ng-model="prioridade.cor"  style="width: 30%;">
                                        </div>                                
                                    </div>                            
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Prioridade</label>                                    
                                        <input type="number" class="form-control" ng-model="prioridade.prioridade" required="required">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">                
                            <button type="button" class="btn btn-danger" ng-click="close();" style="margin-right: 10px;">Cancelar</button>
                            <button type="submit" class="btn btn-primary" autofocus>Salvar</button>
                        </div>          
                    </form>
                </div>
            </div>
            <!-- Erros -->        
            <div class="row" ng-show="errors">
                <div class="col-md-12">
                    <div class="callout no-margin-bottom callout-danger" style="margin-top: 15px;">
                        <div>@{{ errors | filterJSON | filterDataNascimento }}</div>        
                    </div>
                </div>
            </div>   
            <!-- Sucesso -->
            <div class="row callout no-margin-bottom callout-success" ng-show="success"> 
                @{{ success }} &nbsp;         
            </div>
        </section>  
    </script>
    @endif

<!-- ------------------------------------ -->
<!-- ------------------------------------ -->
<!-- -----EDITAR PRIORIDADE-----  -->
<!-- ------------------------------------ -->
<!-- ------------------------------------ -->
    @if( $prioridades )    
    <script type="text/ng-template" id="editarCampoAdicionalPrioridadeModal.html">        
        <section class="content">
            <div class="row">        
                <div class="col-md-12">
                    <form class="form" action="/configuracao/ticket/campo_adicional/prioridade/{{$departamento_id}}" name="form_editar_prioridade" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close();">
                                <span aria-hidden="true">×</span>
                            </button>            
                            <h4 class="modal-title" id="modal-title">Editar campo prioridade</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="_method" value="PUT">
                                <div class="row">
                                    <div class="col-sm-3">
                                            <label>Nome:</label>
                                    </div>
                                    <div class="col-sm-3">
                                            <label>Descrição:</label>
                                    </div>
                                    <div class="col-sm-3">
                                            <label>Cor:</label>                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Prioridade:</label>
                                    </div>                                    
                                    <div class="col-sm-1">
                                        <label for="">&nbsp;</label>
                                    </div>
                                </div>

                                @foreach($prioridades as $prioridade)
                                <input type="hidden" name="prioridade_id[]" value="{{$prioridade->id}}">
                                <input type="hidden" name="departamento_id[]" value="{{$prioridade->departamento_id}}">
                                <div class="row" ng-hide="prioridade{{$prioridade->id}}">
                                    
                                    <div class="col-sm-3">
                                        <div class="form-group">                                            
                                            <input type="text" class="form-control" name="nome[]" value="{{$prioridade->nome}}" placeholder="NOME DO CAMPO" required="required" style="text-transform: uppercase; resize:none">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="descricao[]" value="{{$prioridade->descricao}}" placeholder="DESCRIÇÃO DO CAMPO" style="text-transform: uppercase; resize:none">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">                                            
                                            <div class="input-group">                                    
                                                <input type="text" class="form-control" name="cor[]" ng-model="cor{{$prioridade->id}}" ng-init="cor{{$prioridade->id}} = '{{ $prioridade->cor }}'" style="width: 70%;" readonly="readonly">
                                                <input type="color" class="form-control" ng-model="cor{{$prioridade->id}}" value="{{ $prioridade->cor }}" style="width: 30%;">
                                            </div>
                                        </div>                            
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">                                            
                                            <input type="number" class="form-control" name="prioridade[]" value="{{$prioridade->prioridade}}" required="required">                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default" data-toggle="modal" ng-click="setExcluirCampoAdicionalPrioridade({{$prioridade->id}}); prioridade{{$prioridade->id}} = true">
                                                <span class="fa fa-trash"></span>
                                            </button>                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach                            

                            <!-- form-horizontal -->  
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" ng-click="close()" style="margin-right: 10px;">Cancelar</button>
                            <button type="button" class="btn btn-primary" ng-click="modalExcluirCampoAdicionalPrioridade()" autofocus>Salvar</button>
                        </div>          
                    </form>
                </div>
            </div> 
        </section>
    </script>
    @endif

</div>        

<script>
    var _departamentos = {!!$departamentos!!};
    var _departamentos_selected = {!!$departamentos_selected!!};
    var campos_adicionais =  {!!$campos_adicionais!!};
</script>

@endsection
