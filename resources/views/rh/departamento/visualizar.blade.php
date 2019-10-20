

@extends('core.app')

@section('titulo', 'Departamento')

@section('content')

<migalha titulo="Departamento" descricao="Visualizar departamento"></migalha>

<!-- Main content -->
<section class="content">

    <!-- first row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <form role="form">

                    <div class="box-body row" style="padding-bottom: 0;">
                        <div class="col-md-3">
                            <label >Nome:</label>
                            <p class="text-muted">{{ $departamento->nome }}</p>
                        </div>
                        <div class="form-group col-md-3">
                            <label >Gestor:</label>
                            <p class="text-muted">{{ $departamento->gestor }}</p>
                        </div>
                         <div class="form-group col-md-3">
                            <label >Área:</label>
                            <p class="text-muted">{{ $departamento->area_departamento }}</p>
                        </div>
                         <div class="form-group col-md-3">
                            <label >Ticket:</label>
                            <p class="text-muted">{{ $departamento->ticket }}</p>
                        </div>
                    </div>




                    <div class="box-body row" style="padding-top: 0; padding-bottom: 0;">
                         <div class="form-group col-md-12">
                            <label >Descrição:</label>
                            <p class="text-muted"> {!! nl2br(e($departamento->descricao)) !!}</p>
                        </div>
                    </div>

                   <div class="box-body row" style="padding-top: 0;">
                        <div class="col-md-4">
                            <strong>Data de inclusão:</strong>
                            <p class="text-muted">
                                {{ $departamento->data_inclusao }}
                                {{ $departamento->usuario_inclusao }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <strong>Data de alteração:</strong>
                            <p class="text-muted">
                                {{ $departamento->data_alteracao }}
                                {{ $departamento->usuario_alteracao }}
                            </p>
                        </div>
                    </div>

                    @can( 'RH_DEPARTAMENTO_EDITAR' )
                    <div class="box-footer">
                        <a href="/rh/departamento/{{ $departamento->id }}/edit" title="Editar">
                            <button type="button" class="btn btn-primary">Editar</button>
                        </a>
                    </div>
                    @endcan
                </form>
               
            </div>
            <!-- /.box -->
           
        </div>
        <!-- /.left colum -->
    </div>
    <!-- /.first row -->

</section>
<!-- /.content -->

<script>     
    var _migalhas =  <?=json_encode($migalhas);?>;
</script>

@endsection
