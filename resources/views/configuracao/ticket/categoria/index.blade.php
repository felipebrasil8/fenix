@extends('configuracao.ticket.index')

@section('ticket')

<!-- Main content -->

<div class="box box-default" ng-controller="categoriaCtrl">
    <div class="box-header with-border">
        <h3 class="box-title">CATEGORIAS</h3>
    </div>
    <!-- /.box-header -->

    
    <!-- form start -->
    <form action="/configuracao/ticket/categoria/departamento/{{$departamento}}" name="departamento_categoria"  method="get">
        <div class="box-header with-border">
            <div class="form-group">
                 <label for="inputEmail3" class="col-sm-2 control-label">Departamento:</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm" ng-change="update()" ng-model="categoria_departamento" ng-options="item.nome for item in departamentos track by item.id">
                        </select>
                    </div>  
                    
                    @if( $edit)
                             @can( 'CONFIGURACAO_TICKET_CATEGORIAS_CADASTRAR' )
                    <div class="col-sm-4">
                        <div class="btn-group">
                            <button type="button" ng-click="modalCategoria()" class="btn btn-primary">Adicionar categoria</button>
                                                   
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
            <div>
                <ul class="nav nav-pills nav-stacked ">
                   
                        @foreach($categorias_pai as $pai) 
                            <li>
                            <a  ng-click="modalEditCategoria({{$pai}})"> 
                               
                                  {{$pai->nome}}</a>
                                
                                   
                                    @foreach($categorias_filha as $filha) 
                                    <ul class="nav nav-pills nav-stacked">
                                        @if($filha->ticket_categoria_id == $pai->id  )
                                        <li>
                                            <a style="padding-left: 30px;" ng-click="modalEditCategoria({{$filha}})" > - {{$filha->nome}}</a>
                                        </li>
                                        @endif
                                </ul>
                                    @endforeach
                            </li> 
                        @endforeach
                   

                </ul>   

            </div>
        @endif
    

    <modal-confirm></modal-confirm>

    <!-- Erros -->        
    <msg-error ng-show="errors"></msg-error>        


</div>

<script>
    var _departamentos = {!!$departamentos!!};
    var _departamento = {!!$departamento!!};
    var _categorias_filha = {!!$categorias_filha!!};
    var _categorias_pai = {!!$categorias_pai!!};
        
</script>

    @if( $edit )

    <script type="text/ng-template" id="categoriaModal.html">
        <div class="modal-header">            
            <h4 class="modal-title" id="modal-title">Adicionar categoria</h4>
        </div>
        <div class="modal-body">
           
            <form  role="form" data-toggle="validator" name="form"  action="/configuracao/ticket/categoria/store" method="post">
                <div class="box-body row">

                    @if( count($categorias_pai) > 0 )
                    <label >Pertence a categoria:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                            <select class="form-control" name="ticket_categoria_id">
                                        <option value=""></option>
                                        @foreach($categorias_pai as $pai)
                                            <option value="{{$pai->id}}">{{$pai->nome}}</option>
                                        @endforeach
                                    </select>
                           <div class="help-block with-errors"></div>
                        </div>                        
                    </div>
                    @endif

                    <div class="form-group">
                        <label >Nome:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                <input type="text" class="form-control input-sm" name="nome" placeholder="NOME DA CATEGORIA" style="text-transform: uppercase;" required>
                                <div class="help-block with-errors" ></div>
                            </div>
                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                     
                    <label >Descrição:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                            <input type="text" class="form-control" name="descricao" placeholder="DESCRIÇÃO DA CATEGORIA" required="required"  style="text-transform: uppercase;" > 
                            <div class="help-block with-errors" ></div>
                        </div>
                        <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                     
                
                    <label >Dicas:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                          <textarea rows="4" class="form-control input-sm ng-pristine ng-empty ng-invalid ng-invalid-required ng-touched" name="dicas" style="text-transform: uppercase; resize: none;"></textarea>
                            <div class="help-block with-errors" ></div>
                        </div>
                    </div>
                        
                     <input type="hidden" name="departamento_id" value="{{$departamento}}">

                </div>    
     
                <!-- /.box-body -->

                <!-- form-horizontal -->  
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" ng-click="modalCancelarCategoria()">Cancelar</button> 

                    <button class="btn btn-primary" type="submit" ng-disabled="!form.$valid"  >Salvar</button>
                </div>        
                    
            </form>

        
       
      
        </div>
    </script>


    <script type="text/ng-template" id="editarCategoriaModal.html">
        <div class="modal-header">            
            <h4 class="modal-title" id="modal-title">Editar categoria</h4>
        </div>
        <div class="modal-body">
            <form class="form" action="/configuracao/ticket/categoria/update/{{$departamento}}" method="post">
                <div class="box-body">
                    <div ng-hide="permite_pai == false"> 
                        <label >Pertence a categoria:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                <select class="form-control input-sm" name="ticket_categoria_id" ng-model="modal_pai" ng-options="item as item.nome for item in categorias_pai_new track by item.id">
                                    <option value=""></option>
                                </select>


                                <div class="help-block with-errors" ></div>
                            </div>                        
                        </div>
                    </div>
                    <label >Nome:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                            <input type="text" class="form-control input-sm ng-pristine ng-empty ng-invalid ng-invalid-required ng-touched" name="nome" ng-model="modal_nome" required="required" style="text-transform: uppercase;">
                            <div class="help-block with-errors" ></div>
                        </div>
                        <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                     
                    <label >Descrição:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                            <input type="text" class="form-control" name="descricao" ng-model="modal_descricao" required="required"  style="text-transform: uppercase;" > 
                            <div class="help-block with-errors" ></div>
                        </div>
                        <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                     
                
                    <label >Dicas:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                          <textarea rows="4" class="form-control input-sm ng-pristine ng-empty ng-invalid ng-invalid-required ng-touched"  ng-model="modal_dicas" name="dicas" style="text-transform: uppercase; resize: none;"></textarea>
                            <div class="help-block with-errors" ></div>
                        </div>
                    </div>
                        
                    <input type="text" name="id" ng-model="categoria_id" style="display: none">
                    <input type="hidden" name="_method" value="put">
                 
                    

                </div>    
     
                <!-- /.box-body -->
                    
              
                <!-- form-horizontal -->  
                <div class="modal-footer">

                       @can( 'CONFIGURACAO_TICKET_CATEGORIAS_EXCLUIR' )
                     <button class="btn btn-warning pull-left"  ng-hide="permite_pai == false" type="button" ng-click="modalConfirmCategoria(elemento, 'configuracao/ticket/categoria/destroy')" data-toggle="modal"><i class="fa fa-trash" aria-hidden="true"></i> &nbsp; Excluir categoria</button>
                        @endcan

                     
                     <button class="btn btn-danger" type="button" ng-click="modalCancelarCategoria()">Cancelar</button> 
                    @can( 'CONFIGURACAO_TICKET_CATEGORIAS_EDITAR' )
                    <button class="btn btn-primary" type="submit" >Salvar</button>
                     @endcan
                </div>        
                    
            </form>
                    

        
       
      
        </div>
    </script>




   

        @endif

    @endsection
