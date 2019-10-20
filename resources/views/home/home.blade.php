@extends('core.app')

@section('titulo', 'Home')

@section('content')
<migalha titulo="Home" descricao="Página inicial"></migalha>

<section class="content" ng-controller="homeCtrl">
	
	<div class="row">
		@can('NOTIFICACAO_VISUALIZAR')
			<div class="col-lg-3 col-md-6 col-xs-12">
				<div class="info-box">
					<a ng-click="modalTodasNotificacoes()" data-toggle="modal" style="color: #333;" class="cursor-pointer">
						<span class="info-box-icon bg-red"><i class="fa fa-bell"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Notificações</span>
							<span class="info-box-number"><span style="font-size:40px;">
								<div class="overlay" ng-if="notificacoes_nao_lidas == undefined">
					            	<i class="fa fa-refresh fa-spin"></i>
					            </div>
					            <span ng-if="notificacoes_nao_lidas != undefined">
					            	@{{notificacoes_nao_lidas}}
					            </span>
					        </span></span>
						</div>
					</a>
				</div>
			</div>
		@endcan
 		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="info-box">
				<a style="color: #333;" href="{{ url('ticket/proprio') }}" class="cursor-pointer">
					<span class="info-box-icon bg-green"><i class="fa fa-ticket"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">
							Meus Tickets Abertos
							
							
						</span>
							<div>
								<a href="{{ url('ticket/create') }}"  style="color: #333;" >
									<i style="margin-top: -15px;" class="fa fa-plus-square pull-right text-muted" data-toggle="tooltip" data-original-title="Abrir ticket"></i>
								<a>	
							</div>
						<a style="color: #333;" href="{{ url('ticket/proprio') }}" class="cursor-pointer">
						<span class="info-box-number"><span style="font-size:40px;" >
							<span style="color: #333;" >
				            	{{$meusTicketsAbertos}}
				            </span>
				        </span></span>
				    </a>
					</div>
				</a>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3 col-md-6 col-xs-12">
			<div class="box box-warning">
				<div class="box-header with-border">
					<i class="fa fa-birthday-cake text-yellow" style=""></i>
					<h3 class="box-title">Próximos aniversários</h3>
				</div>
				<div class="box-body no-padding">
					<div class="alturaAniversarios">
				  		<div class="usuario-aniversario" ng-repeat="funcionario in funcionarios">
				  			<img ng-src="/rh/funcionario/avatar-grande/@{{funcionario.id}}" alt="@{{funcionario.nome}}" style="@{{funcionario.imagem}}">
							<a class="users-list-name" href="/rh/funcionario/@{{funcionario.id}}">@{{funcionario.nome | ucfirst}}</a>
							<span class="users-list-date @{{funcionario.text_class}}">@{{funcionario.dt_nascimento}}</span>
				  		</div>					
					</div>
				</div>

				<div class="box-footer text-center">
					<a ng-click="modalTodosAniversarios()" data-toggle="modal" class="uppercase cursor-pointer">Visualizar todos</a>
				</div>
			</div>
			<!--/.box -->
		</div>

	</div>

</section>

<script>
	var _migalhas =  <?=json_encode($migalhas);?>;
    var _funcionarios_modal = {!!$funcionarios!!};
    var _notificacao_tempo = {!! $notificacao_tempo !!};
    var _notificacao_can = {!! $notificacao_can !!};
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.alturaAniversarios').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 3
		});
	});


</script>

@endsection
