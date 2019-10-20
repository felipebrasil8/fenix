@extends('core.app')

@section('titulo', 'Tickets')

@section('content')

<migalha titulo="Configurações de tickets" descricao=""></migalha>

<!-- Main content -->
<section class="content">

    <div class="row">

        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">

                        @can( 'CONFIGURACAO_TICKET_CAMPOS_VISUALIZAR' )
                        <li @if($page == 'campos') class="active" @endif ><a href="/configuracao/ticket/campo_adicional"><i class="fa fa-list-alt"></i> CAMPOS ADICIONAIS</a></li>
                        @endcan

                        @can( 'CONFIGURACAO_TICKET_CATEGORIAS_VISUALIZAR' )
                        <li  @if($page == 'categoria') class="active" @endif ><a href="/configuracao/ticket/categoria"><i class="fa fa-list-ul"></i>CATEGORIAS</a></li>
                        @endcan

                        @can( 'CONFIGURACAO_TICKET_ACOES_VISUALIZAR' )
                        <li  @if($page == 'acao') class="active" @endif ><a href="/configuracao/ticket/acao"><i class="fa fa-reply-all"></i>AÇÕES</a></li>
                        @endcan

                        @can( 'CONFIGURACAO_TICKET_GATILHOS_VISUALIZAR' )
                        <li  @if($page == 'gatilho') class="active" @endif ><a href="/configuracao/ticket/gatilho"><i class="fa fa-magic"></i>GATILHOS</a></li>
                        @endcan
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <div class="col-md-9">
            @yield('ticket')
        </div>
</section>
<!-- /.content -->

<script>
    var _migalhas = <?=json_encode($migalhas);?>;
</script>
@endsection