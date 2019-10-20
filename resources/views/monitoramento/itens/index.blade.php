@extends('monitoramento.itens.app')

@section('pagina')

    <vc-migalha titulo="Itens Monitorados" descricao="Pesquisar itens monitorados" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
	<section class="content">
 		<vc-index  produtos="{{$produtos}}" itens="{{$itens}}"  status_servidores="{{ json_encode($statusServidores) }}" status_itens="{{$statusItens}}" clientes="{{$clientes}}" tipos="{{$tipos}}" > </vc-index>
	</section>

@endsection

