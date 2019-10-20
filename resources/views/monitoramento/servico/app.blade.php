@extends('core.app')

@section('titulo', 'Monitoramento')

@section('content')

<section id="app_servico">

	@yield('pagina')

</section>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/servico.js') }}"></script>
 @endpush
@endsection