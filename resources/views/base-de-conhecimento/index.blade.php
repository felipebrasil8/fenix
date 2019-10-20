@extends('core.app')

@section('titulo', 'Base de conhecimento')

@section('content')

<migalha titulo="Base de conhecimento" descricao="COMPARTILHE!"></migalha>

<section class="content" id="app_base_de_conhecimento">
    <div class="row">

        <div class="col-lg-3 col-md-3 col-sm-12 margin-top" id="categoria_menu">
            <vc-categoria categoria_id="{{ $categoria_id ?? 0 }}" permissao_botao="{{$can ? 'true' : 'false'}}" novas_publicacoes="{{ $novas_publicacoes ?? false }}" favoritos="{{ $favoritos ?? false }}" proximas_publicacoes="{{ isset($proximas_publicacoes) && $proximas_publicacoes == true? true : false }}" can_categoria="{{ $can_categoria ? $can_categoria : '' }}"></vc-categoria>            
        </div>

        <div class="col-lg-9 col-md-9 col-sm-12 margin-top" style="float: right; padding-left: 0px;">          
            @if( isset($categoria_id) )
                <vc-publicacao categoria_id="{{ $categoria_id ?? 0 }}" favoritos="{{ isset($favoritos) && $favoritos == true ? true : false }}" proximas_publicacoes="{{ isset($proximas_publicacoes) && $proximas_publicacoes == true? true : false }}"></vc-publicacao>
            @endif
        </div>
    </div>
    
    @can('BASE_PUBLICACOES_CADASTRAR')
        <publicacao-nova-modal categoria_id="{{ $categoria_id }}"  categorias="{{ $categorias }}"></publicacao-nova-modal>
        <publicacao-modal categoria_id="{{ $categoria_id }}" publicacao_id="{{ $publicacao_id ?? 0 }}" categorias="{{ $categorias }}"></publicacao-modal>
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
