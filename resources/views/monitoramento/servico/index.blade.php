@extends('monitoramento.servico.app')

@section('pagina')

    <vc-index :parametros="{{json_encode($parametros)}}" :migalha="{{ json_encode($migalhas) }}" timeout="{{$timeout}}"></vc-index>

@endsection

