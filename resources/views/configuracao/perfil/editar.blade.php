@extends('configuracao.perfil.app')

@section('pagina')

    <vc-migalha titulo="Perfis" descricao="Editar perfil" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>

    <section class="content">
        <vc-editar acessos="{{ $acessos }}" can="{{ json_encode($can) }}" perfil="{{ $perfil }}" acesso-perfil="{{ $acesso_perfil }}"></vc-cadastrar>
    </section>


@endsection