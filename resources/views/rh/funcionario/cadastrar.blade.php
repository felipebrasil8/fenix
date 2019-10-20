@extends('rh.funcionario.app')

@section('pagina')
    <vc-migalha titulo="Funcionários" descricao="Cadastrar funcionário" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
    
    <section class="content">
        <vc-cadastrar gestores="{{ $gestores }}" cargos="{{ $cargos }}" can="{{ json_encode($can) }}" ></vc-cadastrar>
    </section>

@endsection



