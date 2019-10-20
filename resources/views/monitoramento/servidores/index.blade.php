@extends('monitoramento.servidores.app')

@section('pagina')

    <vc-migalha titulo="Servidores" descricao="Pesquisar servidores" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
	<section class="content">
 		<vc-index 
 			produtos="{{$produtos}}" 
 			itens="{{$itens}}"  
 			status_servidores="{{ json_encode($statusServidores) }}" 
 			clientes="{{$clientes}}" 
 			tipos="{{$tipos}}" 
 			status_instalacao="{{ $statusInstalacao }}"
			versao="{{ $versao }}" 
			can="{{ json_encode($can) }}"
		> </vc-index>

	</section>

@endsection

