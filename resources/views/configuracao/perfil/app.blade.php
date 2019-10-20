@extends('core.app')

@section('titulo', 'Perfil')

@section('content')

<div id="app_perfil">

	@yield('pagina')

</div>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/perfil.js') }}"></script>
 @endpush
@endsection

