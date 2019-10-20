@extends('configuracao.perfil.app')

@section('pagina')

    <vc-migalha titulo="Perfis" descricao="Cadastrar perfil" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
    
    <section class="content">
        <vc-cadastrar acessos="{{ $acessos }}" can="{{ json_encode($can) }}"></vc-cadastrar>
    </section>

@endsection