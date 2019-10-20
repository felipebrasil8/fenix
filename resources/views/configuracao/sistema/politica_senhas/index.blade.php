@extends('core.app')

@section('titulo', 'Política de senhas')

@section('content')

<div id="app_politicaSenha">
    <vc-migalha titulo="Política de senhas" descricao="Configurar política de senhas" :migalha="{{ json_encode($migalhas) }}"></vc-migalha>
    
    <section class="content">
        <vc-index :politica="{{ $politica }}" > </vc-index>
    </section>
 
</div>            

@push('css')
@endpush


@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/politicaSenha.js') }}"></script>
 @endpush
@endsection

