@extends('rh.funcionario.app')

@section('pagina')
    <vc-migalha titulo="Funcionários" descricao="Editar funcionário" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
    
    <section class="content">
        <vc-editar gestores="{{ $gestores }}" cargos="{{ $cargos }}" can="{{ json_encode($can) }}" funcionario="{{ $funcionario }}"></vc-editar>
    </section>

</div>            

@endsection



