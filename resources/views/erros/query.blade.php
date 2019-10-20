@extends('core.app')

@section('titulo', 'Erro Query')

@section('pagina-titulo', 'Atenção')
@section('pagina-descricao', 'Falha na execução')

@section('pagina-migalha-pao')
@endsection

@section('content')
<section class="content">

	<div class="error-page" style="margin: 0 auto;">
		<div class="error-content" style="margin-left: 0;">
			<h3 style=" padding-top: 38px; margin-bottom: 5px;">
				<i class="fa fa-exclamation-circle text-red"></i> Ops! Algo está errado.
			</h3>
			<h3 style="font-size: 16px;">
				Falha na execução.
				<br>
				{{$mensagem[0]}}
			</h3>
		</div>
		<!-- /.error-content -->
	</div>
	<!-- /.error-page -->
	
	@if( count($mensagem) > 1 )
	<pre>
{{$mensagem[1]}}
	</pre>
	@endif
</section>
@endsection
