@extends('core.app')

@section('titulo', 'Dashboard')

@section('content')


<span ng-controller="dashboardTicketCtrl">

<section class="content-header">
	<h1>
		Dashboard de tickets
		<small>
			<span ng-if="visualizar_todos">
				<span ng-show="!viewDepartamento" style="display: inline-block;">
					<select class="form-control input-sm" ng-change="setDepartamentoCookie( departamento )" ng-model="departamento" ng-options="item.nome for item in departamentos track by item.id"></select>
				</span>
				<span ng-show="viewDepartamento" ng-mouseover="hoverInDepartamento()">@{{departamento.nome}}</span>
			</span>
			<span ng-if="!visualizar_todos">
				<span>@{{departamento.nome}}</span>
			</span>
			 - 
			<span ng-show="!viewData" style="display: inline-block;">
				<select class="form-control input-sm" ng-change="setDataCookie( data )" ng-model="data" ng-options="item.nome for item in datas track by item.value"></select>
			</span>
			<span ng-show="viewData" ng-mouseover="hoverInData()">@{{stringData}}</span>
			<span class="overlay" ng-if="carregando" style="margin-left: 5px;">
            	<i class="fa fa-refresh fa-spin"></i>
            </span>
		</small>
	</h1>
	<ol class="breadcrumb">
		<li class="@{{ migalha.class }}" ng-repeat="migalha in migalhas">
			<a href="@{{ migalha.url }}">
				<i class="fa fa-@{{ migalha.icone }}"></i>@{{ migalha.nome }}
			</a>
		</li>		
	</ol>
</section>

<section class="content" ng-show="viewTela" ng-mouseover="hoverOutDepartamento();hoverOutData();">
	<div class="row">
		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="info-box">
				<a ng-click="modalTodasNotificacoes()" data-toggle="modal" style="color: #333;" >
					<span class="info-box-icon bg-red"><i class="fa fa-snapchat-ghost"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Tickets Novos</span>
						<span class="info-box-number"><span style="font-size:40px;">							
				            <span>
				            	@{{ ticketsNovos }}
				            </span>
				        </span></span>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="info-box">
				<a ng-click="modalTodasNotificacoes()" data-toggle="modal" style="color: #333;">
					<span class="info-box-icon bg-blue"><i class="fa fa-user"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Total de tickets abertos</span>
						<span class="info-box-number"><span style="font-size:40px;">							
				            <span>
				            	@{{ totalDeTicketsAbertos }}
				            </span>
				        </span></span>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="info-box">
				<a ng-click="modalTodasNotificacoes()" data-toggle="modal" style="color: #333;">
					<span class="info-box-icon bg-green"><i class="fa fa-flag-checkered"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Tickets fechados no período</span>
						<span class="info-box-number"><span style="font-size:40px;">							
				            <span>
				            	@{{ ticketsFechadosNoPeriodo }}
				            </span>
				        </span></span>
					</div>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="info-box">
				<a ng-click="modalTodasNotificacoes()" data-toggle="modal" style="color: #333;">
					<span class="info-box-icon bg-yellow"><i class="fa fa-star"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">
							Avaliação
							<i style="font-size: 12px;" ng-click="porAvaliacao()" class="fa fa-download pull-right text-muted btn btn-box-tool posicaoDownload" ></i>
						</span>

						<span class="info-box-number">
							<span style="font-size:40px; color: #333;">
								@{{ avaliacao }}
							</span>
							<span class="pull-right visible-lg" style="font-size:30px; margin-top: 12px;">
								@{{ avaliacaoQtde }} <small style="font-size: 20px;">@{{ avaliacaoQtdeTexto }}</small>
							</span>
						</span>
					</div>
				</a>
			</div>
		</div>	

		<div class="col-lg-12 col-md-6 col-xs-12 hidden-lg">
			<div class="info-box">
				<a ng-click="modalTodasNotificacoes()" data-toggle="modal" style="color: #333;">
					<span class="info-box-icon bg-default"><i class="fa fa-clock-o"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Tempo médio de atendimento</span>
						<span class="info-box-number"><span style="font-size:40px;">									
				            <span>
				            	@{{ tempoMedioDeAtendimento }} 
				            </span>
				        </span></span>
					</div>
				</a>
			</div>
		</div>
	</div>
	

	<div class="row box-wrap">
		
		<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
			
			<div class="col_1_class">
				<div class="box col_1_class">
					<div class="box-header with-border">
						<h3 class="box-title">
							Tickets abertos
						</h3>
					</div>
					<div  class="">

						<ul class="list-group list-group-unbordered box-comments ticketsdepartamento" style="background: #fff;"> 

							<li ng-repeat="ticket in ticketsAbertosPorDepartamento" class="list-group-item" style="padding-left:10px; padding-right:15px;padding-bottom: 0px;padding-top: 8px;" data-toggle="tooltip" data-placement="left">
								<div class="box-comment" style="padding-bottom: 0px !important;margin-bottom: -3px; ">

									<img class="img-circle img-bordered-sm" style="width: 40px !important; height: 40px !important;" ng-src="/configuracao/usuario/avatar-pequeno/@{{ ticket.funcionario_responsavel_avatar ? ticket.funcionario_responsavel_avatar : 'fantasma' }}">

									<div class="comment-text" style="margin-left: 50px;">
										<span class="username" style="color:#72afd2; margin-bottom: -5px;">

											<a class="truncate" href="{{ url('ticket') }}/@{{ticket.id}}" style="width: 30%;" data-toggle="tooltip" data-placement="top" data-original-title="@{{ ticket.assunto }}"> 
												#@{{ ticket.codigo }}
											</a>

											<span class="label" style="background-color: @{{  ticket.status_cor  }}; float: right; line-height: 9px; margin-top: 4px;" >@{{ ticket.status_nome }}</span>
											<span class="label" style="background-color: @{{  ticket.prioridade_cor  }}; float: right; line-height: 9px; margin-top: 4px; margin-right: 5px;" >@{{ ticket.prioridade_nome }}</span>

										</span>
										<p style="color:#999;"> @{{ ticket.funcionario_solicitante_nome }}
											<span class="text-muted" style="float: right; margin-top: 2px;" data-toggle="tooltip" data-placement="bottom" data-original-title="Aberto em @{{ ticket.criado }}">
												<i class="fa fa-clock-o"></i> @{{ticket.tempo_interacao}}
											</span>
										</p>
									</div>
								</div>
							</li>

						</ul>

					</div>
					<div class="box-footer text-center">
						<a class="uppercase cursor-pointer" href="{{ url('ticket') }}" >Visualizar todos os Tickets</a>
					</div>
				</div>
				<!--/.box -->
			</div>
			
		</div>
				
		<div class="col-lg-6 hidden-md hidden-sm hidden-xs">
			
			<div class="row">
				<div class="col-lg-6 col-md-12 col-xs-12">
					<!-- DONUT CHART -->
					<div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title">Tickets abertos por prioridade</h3>

							<div class="box-tools pull-right">
								<button type="button" ng-click="porPrioridade()" class="btn btn-box-tool"><i class="fa fa-download"></i></button>								
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="chart-responsive">
										<div class="chart" id="ticketsAbertosPorPrioridadeCtrl"></div>
										<span class="chart text-center" id="idNoDataPrioridades" style="line-height: 140px; display: none">Nenhum ticket.</span>
									</div>									
								</div>								
							</div>
						</div>
						<!-- /.box-body -->

						<div class="box-footer" style="border-top: none">
							<div style="text-align: center;">
								<nav id="menu">									
									<ul class="chart-legend clearfix">
									    <li ng-repeat=" x in prioridades">
									    	<i class="fa fa-circle" ng-style="{'color': x.cor}"></i>
									        @{{ x.nome }}
									    </li>
								    </ul>
								</nav>
							</div>
						</div>

					</div>
					<!-- /.box -->
				</div>
				<div class="col-lg-6 col-md-12 col-xs-12">
					<!-- DONUT CHART -->
					<div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title">Tickets abertos por status</h3>

							<div class="box-tools pull-right">
								<button type="button" ng-click="porStatus()" class="btn btn-box-tool"><i class="fa fa-download"></i>
								</button>								
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12">
									<div class="chart-responsive">
										<div class="chart" id="ticketsPorStatusCtrl"></div>
										<span class="chart text-center" id="idNoDataStatus" style="line-height: 140px; display: none">Nenhum ticket.</span>
									</div>									
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer" style="border-top: none">
							<div style="text-align: center;">
								<nav id="menu">
									<ul class="chart-legend clearfix">
									    <li ng-repeat=" x in status">
									    	<i class="fa fa-circle" ng-style="{'color': x.cor}"></i>
									        @{{ x.nome }}
									    </li>
								    </ul>
								</nav>
							</div>
						</div>
					</div>
					<!-- /.box -->
				</div>
				
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12 col-xs-12">
					<!-- Bar chart -->
					<div class="box box-default">
						<div class="box-header with-border">
							<!-- <i class="fa fa-bar-chart-o"></i> -->
							<h3 class="box-title">Tickets por categoria fechados no período</h3>
							<div class="box-tools pull-right">
								<button type="button" ng-click="porCategoria()" class="btn btn-box-tool" ><i class="fa fa-download"></i>
								</button>								
							</div>
						</div>
						<div class="box-body">							
							<div id="columnchart_values" style="width: auto; height: 450px; padding-left: 0px;"></div>
						</div>
						<!-- /.box-body-->
					</div>
					<!-- /.box -->
				</div>
			</div>
			
		</div>

		<div class="col-lg-3 hidden-md hidden-sm hidden-xs">

			<div class="row" id="col_3_class">
				<div class="col-lg-12 col-md-6 col-xs-12">
					<div class="info-box" id="tempoMedioAtendimentoId">
						<a data-toggle="modal" style="color: #333;">
							<span class="info-box-icon bg-default"><i class="fa fa-clock-o"></i></span>
							<div class="info-box-content">
								<span class="info-box-text">Tempo médio de atendimento</span>
								<span class="info-box-number"><span style="font-size:40px;">									
						            <span>
						            	@{{ tempoMedioDeAtendimento }}
						            </span>
						        </span></span>
							</div>
						</a>
					</div>
				</div>
			
				<div class="col-lg-12 col-md-12 col-xs-12" style="height: 100%">
					<div class="box box-default" style="height: 100%">
						<div class="box-header with-border">
							<h3 class="box-title">Tickets por responsável</h3>
							<div class="box-tools pull-right">
								<button type="button" ng-click="porResponsavel()" class="btn btn-box-tool" ><i class="fa fa-download"></i>
								</button>								
							</div>
						</div>
						<div class="box-body">							

							<div class="row">
								<div class="col-lg-12 col-md-6">
									<h5 style="text-align: center;">TICKETS ABERTOS</h5>
									<div class="chart-responsive">										
										<div class="chart" id="idTicketsPorResponsavelAbertos"></div>
										<span class="chart text-center" id="idNoDataAbertos" style="line-height: 140px;display: none">Nenhum ticket.</span>
									</div>									
								</div>
							</div>
							<div class="row" style="margin-top: 20px;">
								<div class="col-lg-12 col-md-6">
									<h5 style="text-align: center;">TICKETS FECHADOS NO PERÍODO</h5>
									<div class="chart-responsive">
										<div class="chart" id="idTicketsPorResponsavelFechados"></div>
										<span class="chart text-center" id="idNoDataFechados" style="line-height: 140px;display: none">Nenhum ticket.</span>
									</div>									
								</div>
							</div>
							<div class="row" style="width:100%; padding: 20px;position: absolute;bottom: 0;">
								<div class="col-lg-12 col-md-12" style="text-align: center;">
									<span class="col-lg-6" ng-repeat="x in ticketResponsaveis">
										<img ng-src="/configuracao/usuario/@{{x.id}}" class="img-circle" title="@{{x.nome}}" style="width:75px;padding:10px 20px 0 20px">
										<div style="padding: 5px 10px;" ng-style="{'background-color':x.cor}"><i>@{{ x.nome }}</i></div>
									</span>												
								</div>								
							</div>

						</div>
						<div class="container">
							<div class="row" style="width:100%; padding: 20px;position: absolute;bottom: 0;">
								<div class="col-lg-12 col-md-12" style="text-align: center;">
									<span class="col-lg-6" ng-repeat="x in responsaveis">
										<img ng-src="@{{x.url}}" class="img-circle" title="@{{x.nome}}" style="width:75px;padding:10px 20px 0 20px">
											<div style="padding: 5px 10px;color: #333333;" ng-style="{'background-color':x.cor}"><i>@{{ x.nome }}</i></div>
									</span>												
								</div>								
							</div>
						</div>

					</div>						
				</div>
			</div>
		</div>

 <!-- Formulario com trigger em angularjs -->
    <form name="exportar" method="post">
        <input type="hidden" value="@{{data.value}}" name="data">
        <input type="hidden" value="@{{data.data_de}}" name="data_de">
        <input type="hidden" value="@{{data.data_ate}}" name="data_ate">
        <input type="hidden" value="@{{departamento.id}}" name="departamento_id">
    </form>




	</div>

</section>

</span>

<script>
	var _migalhas =  <?=json_encode($migalhas);?>;
	var _departamentos = <?=json_encode($departamentos);?>;
	var _visualizar_todos = <?=json_encode($visualizar_todos);?>;
	var _timeout = <?=json_encode($timeout);?>;
</script>

<script type="text/javascript">	
	$(function(){
    	$('.ticketsdepartamento').slimScroll({
	       	height: '710px'
	    });
	});
</script>

<style type="text/css">
	.moment-picker {
		z-index: 1060 !important;
	}
</style>

<script type="text/ng-template" id="periodoModal.html">
    <div class="modal-header">            
        <h4 class="modal-title" id="modal-title">Selecione um período</h4>
    </div>
    <div class="modal-body">
        <div class="box-body row">
        	<form role="form" data-toggle="validator" name="modal">
	            <div class="form-group col-md-6">
	                <label>De:</label>
	                <div class="row">
	                    <div class="col-md-12 col-xs-12">
	                        <div class="input-group" moment-picker="data_de" format="DD/MM/YYYY" locale="pt-br" start-view="year" today="true" >
	                            <input class="form-control input-sm" readonly="" onpaste="return false;" mask="39/19/x999" restrict="reject"" clean="true" ng-model="data_de" ng-model-options="{ updateOn: 'blur' }"  maxlength="10">
	                            <span class="input-group-addon">
	                                <i class="fa fa-calendar"></i>
	                            </span>
	                        </div>                                    
	                    </div>                                
	                </div>
	            </div>

	            <div class="form-group col-md-6">
	                <label>Até:</label>
	                <div class="row">
	                    <div class="col-md-12 col-xs-12">
	                        <div class="input-group" moment-picker="data_ate" format="DD/MM/YYYY" locale="pt-br" start-view="year" today="true" min-date="data_de">
	                            <input class="form-control input-sm" readonly="" onpaste="return false;" mask="39/19/x999" restrict="reject"" clean="true" ng-model="data_ate" ng-model-options="{ updateOn: 'blur' }"  maxlength="10">
	                            <span class="input-group-addon">
	                                <i class="fa fa-calendar"></i>
	                            </span>
	                        </div>
	                    </div>                                
	                </div>
	            </div>
	        </form>
        </div>    
        <!-- /.box-body -->
    </div>
    <!-- form-horizontal -->  
    <div class="modal-footer">
        <button class="btn btn-danger"  type="button" ng-click="modalFecharCustomizado();">Cancelar</button>
        <button class="btn btn-primary" type="button" ng-click="modalData()" ng-disabled="!(data_ate.length > 0 && data_ate.length > 0)">Salvar</button>
	</div>
</script>

@push('css')
	<style type="text/css">
		.box-wrap {
		  display: -webkit-box;           /* OLD - iOS 6-, Safari 3.1-6 */
		  display: -moz-box;              /* OLD - Firefox 19- (doesn't work very well) */
		  display: -ms-flexbox;           /* TWEENER - IE 10 */
		  display: -webkit-flex;          /* NEW - Chrome */
		  display: flex;                  /* NEW, Spec - Opera 12.1, Firefox 20+ */
		}

		#col_3_class {
			height: calc(100% - 125px);
		}

		.col_1_class{
			height: calc(100% - 10px);
		}

		#menu ul {
		    padding:0px;
		    margin:0px;
		    list-style:none;
		}

		#menu ul li {
			display: inline; 
			padding-right: 10px;
		}
		.chart {
	  		width: 100%;   		
	  		min-height: 179px;
		}	
	</style>
@endpush


@push('scripts')
	<script type="text/javascript" src="{{ asset('js/charts.min.js') }}"></script>
@endpush

@endsection
