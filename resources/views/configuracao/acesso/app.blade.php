@extends('core.app')

@section('titulo', 'Acesso')

@section('content')

<div id="app_acesso">

	@yield('pagina')

</div>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/acesso.js') }}"></script>
 @endpush
@endsection