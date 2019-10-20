@extends('configuracao.acesso.app')

@section('pagina')
    <vc-migalha titulo="Acesso" descricao="Cadastrar acesso" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
	<section class="content">
 		<vc-cadastrar :permissoes="{{ json_encode($permissoes) }}"></<vc-></vc->cadastrar>
	</section>
@endsection
