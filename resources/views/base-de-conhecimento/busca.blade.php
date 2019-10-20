@extends('core.app')

@section('titulo', 'Base de conhecimento')

@section('content')

<migalha titulo="Base de conhecimento" descricao="COMPARTILHE!"></migalha>

<section class="content" id="app_base_de_conhecimento">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12 margin-top" id="categoria_menu">
              <vc-categoria categoria_id="{{ $categoria_id ?? 0 }}" permissao_botao="{{$can ? 'true' : 'false'}}" busca_usr="{{ $busca }}" can_categoria="{{ $can_categoria ? $can_categoria : '' }}"></vc-categoria>
        </div>

        <div class="col-lg-9 col-md-12 margin-top" style="float: right; padding-left: 0px;">
            <vc-busca-publicacao busca="{{ $busca }}" ></vc-busca-publicacao>
        </div>
    </div>

@if(Gate::check('BASE_PUBLICACOES_CATEGORIA_CADASTRAR') || Gate::check('BASE_PUBLICACOES_CATEGORIA_EDITAR'))
    <vc-categoria-modal categorias="{{$categorias}}" icones="{{$icones}}" can_categoria="{{$can_categoria ? $can_categoria : ''}}"></vc-categoria-modal>
@endif
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
