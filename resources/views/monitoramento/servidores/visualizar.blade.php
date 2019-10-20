@extends('monitoramento.servidores.app')

@section('pagina')
    <vc-migalha titulo="Servidores" descricao="Visualizar servidor" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
    <section class="content">
    	<vc-visualizar :can="{{ json_encode($can) }}" :servidor="{{ json_encode($servidor) }}" :abas="{{ json_encode($abas) }}" :aba="{{ json_encode($aba) }}"></vc-visualizar>
	</section>
	
@endsection
