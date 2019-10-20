@extends('core.app')

@section('titulo', 'Base de conhecimento')

@section('content')

<migalha titulo="Base de conhecimento" descricao="VISUALIZAR MENSAGENS"></migalha>

<section class="content" id="app_base_de_conhecimento">
    
    <vc-mensagens-conteudo funcionarios="{{ $funcionarios }}" id_mensagem="{{ $id_mensagem ?? 0 }}"></vc-mensagens-conteudo>
</section>

<script>
    var _migalhas = <?=json_encode($migalhas);?>; 
</script>

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/base_de_conhecimento.js') }}"></script>


@endpush

@endsection
