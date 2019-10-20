@extends('core.app')

@section('titulo', 'Servidores')

@section('content')

<div id="app_servidores">

	@yield('pagina')

</div>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/servidores.js') }}"></script>
 @endpush
@endsection