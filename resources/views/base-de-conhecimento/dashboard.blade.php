@extends('core.app')

@section('titulo', 'Base de conhecimento')

@section('content')



<section id="app_base_de_conhecimento">

    <vc-dashboard 
		:migalha="{{ json_encode($migalhas) }}" timeout="{{$timeout}}"
    >
    	
    </vc-dashboard>
	
    
</section>

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
</script>

@push('css')
@endpush


@push('scripts')
	<script type="text/javascript" src="{{ asset('js/charts.min.js') }}"></script>
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/base_de_conhecimento.js') }}"></script>
    
@endpush

@endsection
