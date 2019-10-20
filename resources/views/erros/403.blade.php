@extends('core.app')

@section('titulo', 'Erro 403')

@section('content')

<!-- <migalha titulo="{{ $titulo }}" descricao="{{ $descricao }}"></migalha> -->

<section class="content">

	<div class="error-page">

		<h2 class="headline text-red">403</h2>

		<div class="error-content">

			<h3 style=" padding-top: 38px; margin-bottom: 5px;">
				<i class="fa fa-minus-circle text-red"></i> Ops! Algo está errado.
			</h3>

			<h3 style="font-size: 16px;">Falha na autenticação.</h3>
		
		</div>
		<!-- /.error-content -->

	</div>
	<!-- /.error-page -->
	
	<div style="margin-top: 40px;">
	<?php $queries = DB::getQueryLog(); ?>
    @foreach($queries as $query)
    <pre>
<?php print_r($query) ?>
    </pre>
    @endforeach
    </div>

</section>
@endsection
