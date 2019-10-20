@extends('rh.funcionario.app')

@section('pagina')
    <vc-migalha titulo="Funcionários" descricao="Visualizar funcionário" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
	
    <section class="content">        
 		<vc-visualizar dados="{{ json_encode($dados) }}" can="{{ json_encode($can) }}"></vc-visualizar>        
	</section>
	
@endsection



