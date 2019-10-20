@extends('core.app')

@section('titulo', 'Base de conhecimento')

@section('content')

<migalha titulo="Base de conhecimento" descricao="COMPARTILHE!"></migalha>

<section class="content" id="app_base_de_conhecimento">

    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-12 margin-top" id="categoria_menu">
            <vc-categoria categoria_id="{{ $categoria_id ?? 0 }}" permissao_botao="{{$can ? 'true' : 'false'}}" can_categoria="{{ $can_categoria ? $can_categoria : '' }}"></vc-categoria>
        </div>

        <div class="col-lg-9 col-md-9 col-sm-12 margin-top" style="float: right; padding-left: 0px;" >
            <vc-conteudo-visualizar publicacao_id="{{ $publicacao_id ?? 0 }}"></vc-conteudo-visualizar>
        </div>

    </div>
    @can('BASE_PUBLICACOES_EDITAR')
        <publicacao-modal categoria_id="{{ $categoria_id }}" publicacao_id="{{ $publicacao_id ?? 0 }}" publicacao="{{ $publicacao }}" categorias="{{ $categorias }}"></publicacao-modal>
        <publicacao-imagem-capa-modal publicacao_id="{{$publicacao_id}}"></publicacao-imagem-capa-modal>    
        <publicacao-datas-modal publicacao_id="{{ $publicacao_id }}" datas="{{ $datas }}"></publicacao-datas-modal>
    @endcan
    
    @can('BASE_PUBLICACOES_RESTRICAO_EDITAR')
        <publicacao-restricao-modal publicacao_id="{{ $publicacao_id }}" cargos="{{ $cargos }}" publicacao_cargos="{{ $publicacao_cargos }}" restricao_acesso="{{ $publicacao->restricao_acesso ?? false }}"></publicacao-restricao-modal>
    @endcan

    @can('BASE_PUBLICACOES_CADASTRAR')
        <publicacao-nova-modal categoria_id="{{ $categoria_id }}" categorias="{{ $categorias }}"></publicacao-nova-modal>
    @endcan

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
