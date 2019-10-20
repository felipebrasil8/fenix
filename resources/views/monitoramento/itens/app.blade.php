@extends('core.app')

@section('titulo', 'Itens monitorados')

@section('content')

<div id="app_itens">

	@yield('pagina')

</div>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/itens.js') }}"></script>
 @endpush
@endsection