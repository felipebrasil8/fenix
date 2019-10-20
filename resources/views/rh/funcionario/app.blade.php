@extends('core.app')

@section('titulo', 'Funcionário')

@section('content')

<div id="app_funcionario">

	@yield('pagina')

</div>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/funcionario.js') }}"></script>
 @endpush
@endsection

