@extends('configuracao.acesso.app')

@section('pagina')
    <vc-migalha titulo="Acesso" descricao="Visualizar acesso" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>

	<section class="content">
 		<vc-visualizar :permissoes="{{ json_encode($permissoes) }}" :acesso="{{ json_encode($acesso) }}" :acessopermissao="{{ json_encode($acesso_permissao) }}" can="{{ json_encode($can) }}" ></vc-visualizar>
	</section>

@endsection
