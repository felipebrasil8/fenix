@extends('configuracao.acesso.app')

@section('pagina')
    <vc-migalha titulo="Acesso" descricao="Editar acesso" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>

	<section class="content">
 		<vc-editar :permissoes="{{ json_encode($permissoes) }}" :acesso="{{ json_encode($acesso) }}" :acessopermissao="{{ json_encode($acesso_permissao) }}"></vc-editar>
	</section>
@endsection
