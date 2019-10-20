@extends('configuracao.acesso.app')

@section('pagina')
    <vc-migalha titulo="Acesso" descricao="Copiar acesso" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>

	<section class="content">
 		<vc-copiar :permissoes="{{ json_encode($permissoes) }}" :acesso="{{ json_encode($acesso) }}" :acessopermissao="{{ json_encode($acesso_permissao) }}"></vc-copiar>
	</section>
@endsection
