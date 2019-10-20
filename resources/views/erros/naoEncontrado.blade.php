@extends('core.app')

@section('titulo', $titulo)

@section('content')

<migalha titulo="{{ $titulo }}" descricao="{{ $descricao }}"></migalha> 

<section class="content">

	<div class="error-page" style="margin: 0 auto;">

		<div class="error-content">

			<h3 style=" padding-top: 38px; margin-bottom: 5px;">
				<i class="fa fa-exclamation-triangle text-orange"></i> Ops! Algo está errado.
			</h3>

			<h3 style="font-size: 16px;">{{ $mensagem[0] }}</h3>
		
		</div>
		<!-- /.error-content -->

	</div>
	<!-- /.error-page -->
	
	@if( count($mensagem) > 1 )
	<pre>
{{$mensagem[1]}}
	</pre>
	<?php $queries = DB::getQueryLog(); ?>
    @foreach($queries as $query)
    <pre>
<?php var_dump($query) ?>
    </pre>
    @endforeach
	@endif

</section>

<script>
    var _migalhas =  <?=json_encode($migalhas);?>;
</script>
@endsection
