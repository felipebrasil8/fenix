@extends('core.app')

@section('titulo', 'Tickets')

@section('content')

<migalha titulo="Tickets" descricao="Editar informações do ticket"></migalha>

<section class="content" ng-controller="editarTicketCtrl">

    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" ng-submit="atualizar(ticket)">
                    
                    <div class="box-header with-border">
                        <i class="fa fa-info-circle"></i>
                        <h3 class="box-title">Informações do ticket</h3>
                    </div>
                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-3">
                            <label for="nome">Status:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" nome="status" id="status" required>
                                        @foreach ($status as $value)
                                            <option value="{{ $value->id }}" {{$value->selected}}>{{ $value->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="nome_completo">Solicitante:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" nome="solicitante" id="solicitante" required>
                                        @foreach ($solicitantes as $value)
                                            <option value="{{ $value->id }}" {{$value->selected}}>{{ $value->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="email">Prioridade:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" nome="prioridade" id="prioridade" required>
                                        @foreach ($prioridades as $value)
                                            <option value="{{ $value->id }}" {{$value->selected}}>{{ $value->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="box-header with-border">
                        <i class="fa fa-ticket"></i>
                        <h3 class="box-title">Detalhes do ticket</h3>
                    </div>
                    <div class="box-body row" style="padding-bottom: 0;">

                        <div class="form-group col-md-3">
                            <label for="nome">Assunto:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" id="assunto" name="assunto" data-error="Este campo é obrigatório." value="{{$ticket->assunto}}" required style="text-transform: uppercase;">
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
                                    <select class="form-control input-sm" id="categoria" name="categoria" required custom-on-change="customChange">
                                        <option value=""></option>
                                        @foreach ($categoria as $value)
                                            <option value="{{ $value->id }}" {{$value->selected}}>{{ $value->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="nome">Subcategoria:</label>
                             <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" ng-options="item.nome for item in subcategoria track by item.id" ng-model="ticket.subcategoria" required>
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="box-header with-border">
                        <i class="fa fa-plus-circle"></i>
                        <h3 class="box-title">Dados adicionais</h3>
                    </div>
                    <div class="box-body" style="padding-bottom: 0;">                        
                        <?php

                            $numberOfColumns = 4;
                            $bootstrapColWidth = 12 / $numberOfColumns ;
                            $chunkArray = array_chunk($campos->toArray(), $numberOfColumns);

                            foreach($chunkArray as $campos) {
                                echo '<div class="row">';
                                foreach($campos as $campo) {
                                    echo '<div class="col-md-'.$bootstrapColWidth.'">';

                                        echo '<div class="form-group">';
                                            echo '<label for="nome">'.$campo['nome'].':</label>';
                                            echo '<div class="row">';
                                                echo '<div class="col-md-11 col-xs-10">';
                                                    echo $campo['html'];
                                                    echo '<div class="help-block with-errors"></div>';
                                                echo '</div>';
                                                if( $campo['obrigatorio'] ){
                                                    echo '<div class="col-md-1 col-xs-2" style="padding-left: 0;">';
                                                        echo '<i class="fa fa-asterisk"></i>';
                                                    echo '</div>';
                                                }
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }  
                        ?>                        
                 
                    </div>                    

                    <div class="box-header with-border">
                        <i class="fa fa-user"></i>
                        <h3 class="box-title">Tratativa do ticket</h3>
                    </div>
                    <div class="box-body row" style="padding-bottom: 0;">
                        
                        <div class="form-group col-md-3">
                            <label for="nome">Reponsável:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <select class="form-control input-sm" nome="responsavel" id="responsavel" required>
                                        @foreach ($responsavel as $value)
                                            <option value="{{ $value->id }}" {{$value->selected}}>{{ $value->nome }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="email">Previsão de término:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <div class="input-group" moment-picker="ticket.dt_previsao" format="DD/MM/YYYY" locale="pt-br" start-view="month" today="true" >
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input class="form-control input-sm" onpaste="return false;" mask="39/19/x999" restrict="reject" clean="true" ng-model="ticket.dt_previsao"  ng-model-options="{ updateOn: 'blur' }" data-error="Este campo é obrigatório." ng-keypress="keyPress($event)" maxlength="10">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                     <div class="box-header with-border">
                        <i class="fa fa-file-text-o"></i>
                        <h3 class="box-title">Interação</h3>
                    </div>
                    <div class="box-body row" style="padding-bottom: 0;">
                        
                        <div class="form-group col-md-6">
                           <div class="row">
                                <div class="col-md-11 col-xs-11">
                                  
                                        <textarea rows="4" 
                                        nome="mensagem" id="mensagem" 
                                        class="form-control input-sm" style="text-transform: uppercase; resize: none;" required ng-model="ticket.mensagem"></textarea>
                    
                                    <div class="help-block with-errors" ></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                           
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <label>
                                        <input type="radio" value="false" ng-value="false" nome="interno" id="interno" ng-model="ticket.interno" ng-init="ticket.interno = false"> Interação 
                                    </label>
                                 
                                </div>
                                <div class="col-md-11 col-xs-10">
                                    <label>
                                        <input type="radio" value="true" ng-value="true" nome="interno" id="interno" ng-model="ticket.interno"> Nota Interna
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" ng-disabled="!form.$valid">Salvar</button>
                        <button type="button" class="btn btn-danger" ng-click="cancelar(ticket)">Cancelar</button>
                    </div>

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
    var _subcategoria = {!!$subcategoria!!};
    var _ticket_id = {!!$ticket->id!!};
    var _ticket_dt_previsao = <?=json_encode($ticket->dt_previsao);?>
</script>

@endsection
