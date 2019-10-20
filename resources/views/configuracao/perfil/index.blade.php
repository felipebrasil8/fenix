@extends('configuracao.perfil.app')

@section('pagina')

    <vc-migalha titulo="Perfis" descricao="Pesquisar perfis" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
	<section class="content">
 		<vc-index acessos="{{ $acessos }}" can="{{ json_encode($can) }}" ></vc-index>
	</section>

@endsection



