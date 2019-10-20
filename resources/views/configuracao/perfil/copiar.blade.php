@extends('configuracao.perfil.app')

@section('pagina')

    <vc-migalha titulo="Perfis" descricao="Copiar perfil" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
    <section class="content">        
 		<vc-copiar dados="{{ json_encode($dados) }}" can="{{ json_encode($can) }}"></vc-copiar>        
	</section>

@endsection



