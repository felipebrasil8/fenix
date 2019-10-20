@extends('core.app')

@section('titulo', 'Erro 404')

@section('pagina-titulo', 'Atenção')

@section('content')
<section class="content">

	<div class="error-page">

		<h2 class="headline text-orange">404</h2>

		<div class="error-content">

			<h3 style=" padding-top: 38px; margin-bottom: 5px;"><i class="fa fa-exclamation-triangle text-orange"></i> Ops! Algo está errado.</h3>

			<h3 style="font-size: 16px;">A página solicitada não foi encontrada.</h3>
		
		</div>
		<!-- /.error-content -->

	</div>
	<!-- /.error-page -->

</section>

<style type="text/css">
	.content-wrapper
	{
		margin-left: 0;
	}
	.main-footer
	{
		margin-left: 0;	
	}
</style>
@endsection