@extends('monitoramento.servidores.app')

@section('pagina')
    <vc-migalha titulo="Servidores" descricao="Editar servidor" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
    
	<section class="content"> 
        <vc-editar can="{{ json_encode($can) }}" servidor="{{ json_encode($servidor) }}"></vc-editar>
    </section>
</div>            

@endsection



