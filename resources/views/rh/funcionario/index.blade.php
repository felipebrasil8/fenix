@extends('rh.funcionario.app')

@section('pagina')
    <vc-migalha titulo="Funcionários" descricao="Pesquisar funcionários" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
    <section class="content">
 		<vc-index gestores="{{ $gestores }}" cargos="{{ $cargos }}" can="{{ json_encode($can) }}" ></vc-index>
	</section>
  
@endsection



