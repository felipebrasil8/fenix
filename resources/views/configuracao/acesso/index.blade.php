@extends('configuracao.acesso.app')

@section('pagina')

    <vc-migalha titulo="Acessos" descricao="Pesquisar acessos" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
	<section class="content">
 		<vc-index can="{{ json_encode($can) }}"> </vc-index>
	</section>

@endsection

