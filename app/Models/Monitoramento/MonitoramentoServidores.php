<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Sistema\Parametro;
use Carbon\Carbon;
use App\Util\Date;
use App\Util\FormatString;
use App\Models\Monitoramento\MonitoramentoServidoresItens;
use App\Models\Monitoramento\MonitoramentoServidoresParadaProgramada;

class MonitoramentoServidores extends Model
{
    protected $table = 'monitoramento_servidores';

    protected $fillable = array(
        'id',
        'id_projeto',
        'tipo',
        'porta_api',
        'ip',
        'dns',
        'versao',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'status',
        'plano',
        'grupo',
        'plano_tipo',
        'cliente',
        'razao_social',
        'dt_status',
        'configuracao',
        'mensagem',
        'chamado_vinculado',
        'usuario_inclusao_chamado_id',
        'executa_porta',
        'executa_ping'
    );
    
    public function getDtStatusAttribute( $value )
    {
        if( !is_null($value)){     
            $data = new Date;
            $date1 = Carbon::now();
            $segundos = $date1->diffInSeconds( Carbon::parse( $value) );
            return  $data->dataIntervaloSegundoString( $segundos );        
        }
        return $value;
    }

    public function updateProjetoMonitorServidores($crm)
    {
        if( !is_null( $crm->projeto_pai) )
        {
            $update = [
                'id_projeto' => $crm->projeto
            ];

            $projetos = explode(',', $crm->projeto_pai);

            $itemObj = MonitoramentoServidores::
                        whereIn('id_projeto', $projetos )
                        ->update($update);
        }
    }

    /**
     * [createOrUpdateMonitorServidores Insere na tabela monitoramento servidores e verifa qual o produto para definir a porta de acesso a api.]
     * @param  [int] $projeto [Projeto CRM]
     * @param  [string] $tipo    [Tipo de porjeto - PRINCIPAL OU REDUNDANTE]
     * @param  [string] $grupo   [PRODUTO - NXT3000, NXT-URA, NXT-DISCADOR  ]
     */
    public function createOrUpdateMonitorServidores($crm)
    {
        $update = [
            'id_projeto' => $crm->projeto,
            'tipo' => $crm->tipo,
            'ip' => $crm->ip,
            'dns' => $crm->dns,
            'versao' => $crm->versao,
            'endereco' => $crm->endereco,
            'bairro' => $crm->bairro,
            'cidade' => $crm->cidade,
            'estado' => $crm->estado,
            'status' => $crm->status,
            'plano' => $crm->plano,
            'grupo' => $crm->grupo,
            'plano_tipo' => $crm->plano_tipo,
            'cliente' => $crm->cliente,
            'razao_social' => $crm->razao_social
        ];

        $itemObj = MonitoramentoServidores::updateOrCreate(['id_projeto' => $crm->projeto, 'tipo' => $crm->tipo], $update);

     	return $itemObj;

    }

    /**
     * [deleteMonitorServidores Deleta o registro do servidor do Banco de dados do FENIX ]
     * @param  [type] $id [ID da tabela MONITORAMENTO SERVIDOR]
     */
    public function deleteMonitorServidores($id)
    {
       	MonitoramentoServidores::find($id)->delete();
    }

    /**
     * [getMonitoramentoServidoresServico Metodo que retorna as informações dos servidores para o serviço de monitoramento]
     * @return [Object] [Objeto com ip, porta, id_projeto e tipo]
     */
    public function getMonitoramentoServidoresServico () 
    {
        return MonitoramentoServidores::select('id', 'porta_api', 'tipo', 'id_projeto', 'contador_coletas', 'ip', 'dns' )->orderBy('id')->get();
    }

    public function getItensServidores($filtro){

        $string = new FormatString;

        $alarmaPersistente = $this->getParametroAlarmePersistente();

        $servidoresItenStatus = '';
        if( !empty( $filtro->status ) || !empty( $filtro->item ) || $filtro->mensagem != '' || $filtro->chamado != '' || !empty( $filtro->alarme_persistente ) || ( !empty( $filtro->chamado_vinculado ) && count($filtro->chamado_vinculado) < 2 ) )
        {    
            $monitoramentoServidoresItens = new MonitoramentoServidoresItens(  );
            $servidoresItenStatus = $monitoramentoServidoresItens->getServidoresItenStatus( $filtro->status, $filtro->item, $filtro->mensagem, $filtro->alarme_persistente, $filtro->chamado_vinculado, $filtro->chamado )->pluck('monitoramento_servidores_id');
        }

        $servidores_nao_buscar = ''; 
        $servidores_buscar = '';
        
        if( !empty( $filtro->parada_programada ) && count($filtro->parada_programada) < 3 ){
            $monitoramentoServidoresParadaProgramada = new MonitoramentoServidoresParadaProgramada();
        
            if ( in_array( 'NÃO POSSUI' , $filtro->parada_programada) )
            {
                $servidores_nao_buscar = $monitoramentoServidoresParadaProgramada->getServidoresParadasProgramadas( array( "AGENDADA", "NO MOMENTO" ) )->pluck('monitoramento_servidores_id');
            }
            if( in_array( 'AGENDADA' , $filtro->parada_programada) || in_array( 'NO MOMENTO' , $filtro->parada_programada))
            {
                $servidores_buscar = $monitoramentoServidoresParadaProgramada->getServidoresParadasProgramadas( $filtro->parada_programada )->pluck('monitoramento_servidores_id');
            }
        }
        
        return MonitoramentoServidores::
                    with([
                       'paradasProgramadas' => function ($query) use ($filtro, $string) {
                            $query->selectRaw('
                                        monitoramento_servidores_parada_programada.id, 
                                        monitoramento_servidores_parada_programada.dt_inicio, 
                                        monitoramento_servidores_parada_programada.dt_fim, 
                                        monitoramento_servidores_parada_programada.observacao, 
                                        monitoramento_servidores_parada_programada.monitoramento_servidores_id, 
                                        usuarios.nome,
                                        ua.nome as nome_alteracao,
                                        monitoramento_servidores_parada_programada.dt_inicio < NOW() as parada_programada'
                                    );

                            $query->where('monitoramento_servidores_parada_programada.ativo', true);
                            $query->whereRaw('monitoramento_servidores_parada_programada.dt_fim > NOW()');
                            $query->leftJoin('usuarios', 'usuarios.id', '=', 'monitoramento_servidores_parada_programada.usuario_inclusao_id');
                            $query->leftjoin('usuarios AS ua', 'ua.id', '=', 'monitoramento_servidores_parada_programada.usuario_alteracao_id');

                            if( !empty( $filtro->parada_programada ) && count($filtro->parada_programada) < 3 )
                            {   
                                $query->where(function ($query) use ($filtro)
                                {
                                    if( in_array('NÃO POSSUI', $filtro->parada_programada) )
                                    {
                                        $query->orWhereRaw('monitoramento_servidores_parada_programada.dt_fim < NOW()');
                                    }
                                    
                                    if( in_array('AGENDADA', $filtro->parada_programada) )
                                    {
                                        $query->orWhereRaw('monitoramento_servidores_parada_programada.dt_inicio > NOW()');
                                    }

                                    if( in_array('NO MOMENTO', $filtro->parada_programada) )
                                    {
                                        $query->orWhereRaw('(monitoramento_servidores_parada_programada.dt_inicio < NOW() AND 
                                                            monitoramento_servidores_parada_programada.dt_fim > NOW())');
                                    }
                                });
                            }
                       },
                       'itens' => function ($query) use ($filtro, $string, $alarmaPersistente) {
                            $query->selectRaw('
                                    monitoramento_servidores_itens.nome, 
                                    monitoramento_servidores_itens.dt_status, 
                                    monitoramento_servidores_itens.monitoramento_servidores_id, 
                                    monitoramento_servidores_itens.mensagem,
                                    monitoramento_servidores_status.nome as status, 
                                    monitoramento_servidores_status.cor, 
                                    monitoramento_servidores_status.icone,
                                    monitoramento_servidores_itens.contador_falhas,
                                    monitoramento_servidores_status.alarme,
                                    monitoramento_servidores_itens.id,
                                    monitoramento_servidores_itens.chamado_vinculado,
                                    usuarios.nome as usuario_inclusao_chamado_id
                                    ');
                            
                            $query->selectRaw('( TO_CHAR(monitoramento_servidores_itens.updated_at , \'dd/mm/yyyy hh24:mi:ss\') ) as ultima_coleta');
                            $query->selectRaw('( TO_CHAR(monitoramento_servidores_itens.chamado_vinculado_at , \'dd/mm/yyyy hh24:mi:ss\') ) as chamado_vinculado_at');
                            
                            $query->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores_itens.monitoramento_servidores_status_id');
                            $query->leftJoin('usuarios', 'usuarios.id', '=', 'monitoramento_servidores_itens.usuario_inclusao_chamado_id');
                            $query->orderBy('monitoramento_servidores_itens.nome');

                            if( !empty( $filtro->status ) )
                            {   
                                $query->whereIn('monitoramento_servidores_itens.monitoramento_servidores_status_id', collect($filtro->status)->pluck('id'));
                            }

                            if( !empty( $filtro->item ) )
                            {
                                $query->whereIn('monitoramento_servidores_itens.identificador', collect($filtro->item)->pluck('identificador'));
                            }
                            
                            if( $filtro->mensagem != '' )
                            {
                                $query->whereRaw('UPPER(monitoramento_servidores_itens.mensagem) LIKE \'%'.$string->strToUpperSemAcento($filtro->mensagem).'%\'');
                            }

                            if( $filtro->chamado != '' )
                            {
                                $query->whereRaw('UPPER(monitoramento_servidores_itens.chamado_vinculado) LIKE \'%'.$string->strToUpperSemAcento($filtro->chamado).'%\'');
                            }    

                            if( !empty( $filtro->alarme_persistente ) && count($filtro->alarme_persistente) < 2  )
                            {   
                                $query->where('monitoramento_servidores_status.alarme', true);

                                if ( in_array('SIM', $filtro->alarme_persistente) )
                                {
                                    $query->whereRaw('monitoramento_servidores_itens.contador_falhas > '.$alarmaPersistente);
                                }
                                else
                                {
                                    $query->whereRaw('monitoramento_servidores_itens.contador_falhas < '.$alarmaPersistente );
                                }
                            }

                            if( !empty( $filtro->chamado_vinculado ) && count($filtro->chamado_vinculado) < 2  )
                            {
                                if ( in_array('SIM', $filtro->chamado_vinculado) ){
                                    $query->whereNotNull('monitoramento_servidores_itens.chamado_vinculado');
                                }else{
                                    $query->whereNull('monitoramento_servidores_itens.chamado_vinculado');
                                }
                            }
                        } 
                    ])
                    ->selectRaw( '
                         monitoramento_servidores.cliente, 
                         monitoramento_servidores.grupo, 
                         monitoramento_servidores.tipo, 
                         monitoramento_servidores.dt_status,  
                         monitoramento_servidores_status.nome, 
                         monitoramento_servidores_status.cor, 
                         monitoramento_servidores_status.icone, 
                         monitoramento_servidores.ip,
                         monitoramento_servidores.dns,
                         monitoramento_servidores.porta_api,
                         monitoramento_servidores.id_projeto,
                         monitoramento_servidores_status.peso,
                         ( TO_CHAR(monitoramento_servidores.dt_ultima_coleta , \'dd/mm/yyyy hh24:mi:ss\') ) as ultima_coleta,
                         monitoramento_servidores.id,
                         monitoramento_servidores.mensagem,
                         monitoramento_servidores.contador_falhas,
                         monitoramento_servidores_status.alarme,
                         monitoramento_servidores.status,
                         (SELECT COUNT(chamado_vinculado) FROM monitoramento_servidores_itens WHERE monitoramento_servidores_id = monitoramento_servidores.id AND chamado_vinculado IS NOT NULL) AS has_chamado
                     ')
                ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores.monitoramento_servidores_status_id'  )
                // ->leftJoin('monitoramento_servidores_parada_programada', 'monitoramento_servidores_parada_programada.monitoramento_servidores_id', '=' , 'monitoramento_servidores.id' )
                ->queryWhereCliente($filtro->cliente)
                ->queryWhereProjeto($filtro->projeto)
                ->queryWhereEndereco($filtro->endereco)
                ->queryWhereTipo($filtro->tipo)
                ->queryWhereIP($filtro->ip)
                ->queryWhereDNS($filtro->dns)
                ->queryWhereProduto($filtro->produto)
                ->queryWhereStatusServidores($filtro->status_servidor) 
                ->queryWhereInServidoresStatusItens( $servidoresItenStatus , $filtro->status, $filtro->status_servidor, $filtro->alarmes) 
                ->queryWhereParadaProgramada( $filtro->parada_programada, $servidores_nao_buscar, $servidores_buscar  ) 
                ->queryOrderBy($filtro->sort)
                ->paginate($filtro->por_pagina);

    }

    public function getStatusServidores($filtro)
    {

        $string = new FormatString;
        
        $alarmaPersistente = $this->getParametroAlarmePersistente();

        $servidoresItenStatus = '';
        if( !empty( $filtro->status ) || !empty( $filtro->item ) || $filtro->mensagem != '' || $filtro->chamado != '' || !empty( $filtro->alarme_persistente ) || ( !empty( $filtro->chamado_vinculado ) && count($filtro->chamado_vinculado) < 2 ) )
        {    
            $monitoramentoServidoresItens = new MonitoramentoServidoresItens(  );
            $servidoresItenStatus = $monitoramentoServidoresItens->getServidoresItenStatus( $filtro->status, $filtro->item, $filtro->mensagem, $filtro->alarme_persistente, $filtro->chamado_vinculado, $filtro->chamado )->pluck('monitoramento_servidores_id');
        }

        $servidores_nao_buscar = ''; 
        $servidores_buscar = '';
        
        if( !empty( $filtro->parada_programada ) && count($filtro->parada_programada) < 3 ){
            $monitoramentoServidoresParadaProgramada = new MonitoramentoServidoresParadaProgramada();
        
            if ( in_array( 'NÃO POSSUI' , $filtro->parada_programada) )
            {
                $servidores_nao_buscar = $monitoramentoServidoresParadaProgramada->getServidoresParadasProgramadas( array( "AGENDADA", "NO MOMENTO" ) )->pluck('monitoramento_servidores_id');
            }
            if( in_array( 'AGENDADA' , $filtro->parada_programada) || in_array( 'NO MOMENTO' , $filtro->parada_programada))
            {
                $servidores_buscar = $monitoramentoServidoresParadaProgramada->getServidoresParadasProgramadas( $filtro->parada_programada )->pluck('monitoramento_servidores_id');
            }
        }

        return MonitoramentoServidoresStatus::
                    select(
                        'monitoramento_servidores_status.id', 
                        'monitoramento_servidores_status.nome', 
                        'monitoramento_servidores_status.cor', 
                        'monitoramento_servidores_status.icone',
                        'monitoramento_servidores_status.alarme'
                    )
                    ->selectRaw('count(monitoramento_servidores.id) as total')
                    // ->leftJoin('monitoramento_servidores', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores.monitoramento_servidores_status_id')
                    ->leftJoin('monitoramento_servidores', function ($query) use ($filtro, $servidoresItenStatus,  $string, $servidores_nao_buscar,  $servidores_buscar )
                    {
                        $query->on('monitoramento_servidores_status.id', '=', 'monitoramento_servidores.monitoramento_servidores_status_id');
    
                        // $query->queryWhereCliente($filtro->cliente);
                        if ( !empty( $filtro->cliente ) ){
                            $query->whereIn('monitoramento_servidores.cliente', $filtro->cliente);
                        }

                        // ->queryWhereProjeto($filtro->projeto)
                        if ( !empty( $filtro->projeto ) ){
                            $query->whereIn('monitoramento_servidores.id_projeto', $filtro->projeto);
                        }
                        
                        // ->queryWhereEndereco($filtro->endereco)
                        if ($filtro->endereco != ''){
                            $query->whereRaw('sem_acento( monitoramento_servidores.endereco||\' \'||monitoramento_servidores.bairro||\' \'||monitoramento_servidores.cidade) LIKE \'%'. $string->strToUpperCustom($string->strToUpperSemAcento($filtro->endereco)).'%\'');
                        }

                        // ->queryWhereTipo($filtro->tipo)
                        if ( !empty( $filtro->tipo ) ){
                            $query->whereIn('monitoramento_servidores.tipo', $filtro->tipo);
                        }

                        // ->queryWhereIP($filtro->ip)
                        if ($filtro->ip != ''){
                            $query->where('monitoramento_servidores.ip', $filtro->ip );
                        }
                        // ->queryWhereDNS($filtro->dns)
                       if ($filtro->dns != ''){
                            $query->where('monitoramento_servidores.dns', 'LIKE', '%'.$string->strToLowerCustom($filtro->dns).'%');
                        }

                        // ->queryWhereProduto($filtro->produto)
                        if ( !empty( $filtro->produto ) ) {
                            $query->whereIn('monitoramento_servidores.grupo', $filtro->produto);
                        }

                        // ->queryWhereStatusServidores($filtro->status_servidor) 
                        if( !empty( $filtro->status_servidor ) ){
                                $query->whereIn('monitoramento_servidores.monitoramento_servidores_status_id', collect($filtro->status_servidor)->pluck('id'));
                        }   

                        // ->queryWhereInServidoresStatusItens( $servidoresItenStatus , $filtro->status )
                        if( $servidoresItenStatus != '' ){
                            $query->whereIn('monitoramento_servidores.id', $servidoresItenStatus);
                        }

                        if( !empty( $filtro->parada_programada ) && count($filtro->parada_programada) < 3 )
                        {
                            return $query->where(function ($query) use ($servidores_buscar , $servidores_nao_buscar)
                            {
                                if ( $servidores_buscar != '' )
                                {
                                    $query->whereIn('monitoramento_servidores.id', $servidores_buscar);
                                }

                                if ( $servidores_nao_buscar != '' )
                                {
                                    $query->orWhere(function ($query) use ($servidores_buscar , $servidores_nao_buscar) {
                                        $query->whereNotIn('monitoramento_servidores.id', $servidores_nao_buscar);
                                    });   
                                }
                            });
                        }   
                    })
                    ->where('monitoramento_servidores_status.filtro_servidor', true)
                    ->groupBy('monitoramento_servidores_status.id')
                    ->orderBy('peso', 'desc')
                    ->get(); 
    }

    public function getStatusItens($filtro)
    {
        $string = new FormatString;
        
        $alarmaPersistente = $this->getParametroAlarmePersistente();
        
        $servidores_nao_buscar = ''; 
        $servidores_buscar = '';
        
        if( !empty( $filtro->parada_programada ) && count($filtro->parada_programada) < 3 )
        {
            $monitoramentoServidoresParadaProgramada = new MonitoramentoServidoresParadaProgramada();
        
            if ( in_array( 'NÃO POSSUI' , $filtro->parada_programada) )
            {
                $servidores_nao_buscar = $monitoramentoServidoresParadaProgramada->getServidoresParadasProgramadas( array( "PARADA AGENDADA", "NO MOMENTO" ) )->pluck('monitoramento_servidores_id');
            }
            if( in_array( 'PARADA AGENDADA' , $filtro->parada_programada) || in_array( 'NO MOMENTO' , $filtro->parada_programada))
            {
                $servidores_buscar = $monitoramentoServidoresParadaProgramada->getServidoresParadasProgramadas( $filtro->parada_programada )->pluck('monitoramento_servidores_id');
            }
        }

        $idsServidores = MonitoramentoServidores::
                    selectRaw('monitoramento_servidores.id')
                    ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores.monitoramento_servidores_status_id'  )
                    ->queryWhereCliente($filtro->cliente)
                    ->queryWhereProjeto($filtro->projeto)
                    ->queryWhereEndereco($filtro->endereco)
                    ->queryWhereTipo($filtro->tipo)
                    ->queryWhereIP($filtro->ip)
                    ->queryWhereDNS($filtro->dns)
                    ->queryWhereProduto($filtro->produto)
                    ->queryWhereStatusServidores($filtro->status_servidor) 
                    ->queryWhereParadaProgramada( $filtro->parada_programada, $servidores_nao_buscar, $servidores_buscar  )                    
                    ->get()->pluck('id');

        return MonitoramentoServidoresStatus::
                    select(
                        'monitoramento_servidores_status.id', 
                        'monitoramento_servidores_status.nome',  
                        'monitoramento_servidores_status.cor', 
                        'monitoramento_servidores_status.icone',
                        'monitoramento_servidores_status.alarme'
                    )
                    ->selectRaw('COALESCE(count(monitoramento_servidores_itens.id) ,0 ) as total')
                    ->where('monitoramento_servidores_status.filtro_item', true)
                    ->leftjoin('monitoramento_servidores_itens', function ($query) use ($filtro, $string, $idsServidores, $alarmaPersistente) {

                        $query->on('monitoramento_servidores_status.id', '=', 'monitoramento_servidores_itens.monitoramento_servidores_status_id');
                           
                        $query->leftJoin('usuarios', 'usuarios.id', '=', 'monitoramento_servidores_itens.usuario_inclusao_chamado_id');
                        $query->orderBy('monitoramento_servidores_itens.nome');

                        if( !empty( $filtro->status ) )
                        {   
                            $query->whereIn('monitoramento_servidores_itens.monitoramento_servidores_status_id', collect($filtro->status)->pluck('id'));
                        }

                        if( !empty( $filtro->item ) )
                        {
                            $query->whereIn('monitoramento_servidores_itens.identificador', collect($filtro->item)->pluck('identificador'));
                        }
                        
                        if( $filtro->mensagem != '' )
                        {
                            $query->whereRaw('UPPER(monitoramento_servidores_itens.mensagem) LIKE \'%'.$string->strToUpperSemAcento($filtro->mensagem).'%\'');
                        }

                        if( $filtro->chamado != '' )
                        {
                            $query->whereRaw('UPPER(monitoramento_servidores_itens.chamado_vinculado) LIKE \'%'.$string->strToUpperSemAcento($filtro->chamado).'%\'');
                        }    

                        if( !empty( $filtro->alarme_persistente ) && count($filtro->alarme_persistente) < 2  )
                        {   
                            $query->where('monitoramento_servidores_status.alarme', true);

                            if ( in_array('SIM', $filtro->alarme_persistente) )
                            {
                                $query->whereRaw('monitoramento_servidores_itens.contador_falhas > '.$alarmaPersistente);
                            }
                            else
                            {
                                $query->whereRaw('monitoramento_servidores_itens.contador_falhas < '.$alarmaPersistente );
                            }
                        }

                        if( !empty( $filtro->chamado_vinculado ) && count($filtro->chamado_vinculado) < 2  )
                        {
                            if ( in_array('SIM', $filtro->chamado_vinculado) ){
                                $query->whereNotNull('monitoramento_servidores_itens.chamado_vinculado');
                            }else{
                                $query->whereNull('monitoramento_servidores_itens.chamado_vinculado');
                            }
                        }
                    })
                    ->groupBy('monitoramento_servidores_status.id')
                    ->orderBy('peso', 'desc')
                    ->get(); 
    }

    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
        }
    }

    public function scopeQueryWhereCliente( $query, $value )
    {
        if ( !empty( $value ) ){
            return $query->whereIn('monitoramento_servidores.cliente', $value);
        }
    }

    public function scopeQueryWhereParadaProgramada( $query, $value, $servidores_nao_buscar, $servidores_buscar )
    {
        if( !empty( $value ) && count($value) < 3 )
        {
            return $query->where(function ($query) use ($servidores_buscar , $servidores_nao_buscar) {

                if ( $servidores_buscar != '' ){
                    
                    $query->whereIn('monitoramento_servidores.id', $servidores_buscar);
                }

                if ( $servidores_nao_buscar != '' ){
                    $query->orWhere(function ($query) use ($servidores_buscar , $servidores_nao_buscar) {
                        $query->whereNotIn('monitoramento_servidores.id', $servidores_nao_buscar);
                    });   
                }
            });
        }
    }

    public function scopeQueryWhereEndereco ( $query, $value )
    {
        if ($value != ''){
             $string = new FormatString;
            return $query->whereRaw('sem_acento( monitoramento_servidores.endereco||\' \'||monitoramento_servidores.bairro||\' \'||monitoramento_servidores.cidade) LIKE \'%'. $string->strToUpperCustom($string->strToUpperSemAcento($value)).'%\'');
        }
    }

    public function scopeQueryWhereProjeto( $query, $value )
    {
        if ( !empty( $value ) ){
            return $query->whereIn('monitoramento_servidores.id_projeto', $value);
        }
    }

     public function scopeQueryWhereTipo( $query, $value )
    {
        if ( !empty($value ) ){
            return $query->whereIn('monitoramento_servidores.tipo', $value);
        }
    }

    public function scopeQueryWhereIP( $query, $value )
    {
        if ($value != ''){
            return $query->where('monitoramento_servidores.ip', $value );
        }
    }

    public function scopeQueryWhereDNS( $query, $value )
    {
        if ($value != ''){
            $string = new FormatString;
            return $query->where('monitoramento_servidores.dns', 'LIKE', '%'.$string->strToLowerCustom($value).'%');
        }
    }

    public function scopeQueryWhereProduto( $query, $value )
    {
        if ( !empty( $value ) ) {
            return $query->whereIn('monitoramento_servidores.grupo', $value);
        }
    }

    public function scopeQueryWhereStatusServidores( $query, $value )
    {
        if( !empty( $value ) ){
             return $query->whereIn('monitoramento_servidores.monitoramento_servidores_status_id', collect($value)->pluck('id'));
        }                                    
    }

    public function scopeQueryWhereInServidoresStatusItens( $query, $value, $value_status, $value_servidor, $alarme )
    {
        if( $value != '' && $alarme == false ){
           return $query->whereIn('monitoramento_servidores.id', $value);
                 
        }
        else if ( $alarme == true && !empty( $value_status ) ) {
            return $query->whereIn('monitoramento_servidores.id', $value)
                    ->orWhereIn('monitoramento_servidores.monitoramento_servidores_status_id', collect($value_status)->pluck('id'));
        }                                    
    }

    public function itens()
    {
        return $this->hasMany('App\Models\Monitoramento\MonitoramentoServidoresItens', 'monitoramento_servidores_id', 'id');
    }

    public function paradasProgramadas()
    {
        return $this->hasMany('App\Models\Monitoramento\MonitoramentoServidoresParadaProgramada', 'monitoramento_servidores_id', 'id');
    }

    public function getParametroAlarmePersistente() {
        return  Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_QUANTIDADE_COLETA_FALHA_PERSISTENTE')->first()->valor_numero;
    }

    public function tableMonitoramentoServidores( $filtro )
    {
        return MonitoramentoServidores::selectRaw(
                   'monitoramento_servidores.cliente,
                    monitoramento_servidores.grupo,
                    monitoramento_servidores.id_projeto,
                    monitoramento_servidores.status,
                    monitoramento_servidores.versao,
                    monitoramento_servidores.tipo,
                    monitoramento_servidores.ip,
                    monitoramento_servidores.dns,
                    monitoramento_servidores_status.nome,
                    monitoramento_servidores_status.cor, 
                    monitoramento_servidores_status.icone,
                    monitoramento_servidores.id
                    '
                )
                ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores.monitoramento_servidores_status_id'  )
                ->queryWhereCliente($filtro->cliente)
                ->queryWhereProjeto($filtro->projeto)
                ->queryWhereTipo($filtro->tipo)
                ->queryWhereIP($filtro->ip)
                ->queryWhereDNS($filtro->dns)
                ->queryWhereProduto($filtro->produto)
                ->queryWhereVersao($filtro->versao)
                ->queryWhereStatusInstalacao($filtro->status_instalacao)
                ->queryWhereStatusServidores($filtro->status_servidor) 
                ->queryWhereEndereco($filtro->endereco)
                ->queryOrderBy($filtro->sort)
                ->paginate($filtro->por_pagina);    
    }

    public function scopeQueryWhereVersao( $query, $value )
    {
        if ( !empty( $value ) ) {
            return $query->whereIn('monitoramento_servidores.versao', $value);
        }
    }

    public function scopeQueryWhereStatusInstalacao( $query, $value )
    {
        if ( !empty( $value ) ) {
            return $query->whereIn('monitoramento_servidores.status', $value);
        }
    }

    public function scopeQueryWhereStatusServidoresNome( $query, $value )
    {
        if ( !empty( $value ) ) {
            return $query->whereIn('monitoramento_servidores_status.nome', $value);
        }
    }

    public function exportarMonitoramentoServidores($filtro)
    {

        return MonitoramentoServidores::
                    selectRaw(
                    'monitoramento_servidores.created_at AS data, 
                    monitoramento_servidores.created_at AS hora,
                    monitoramento_servidores.cliente,
                    monitoramento_servidores.grupo,
                    monitoramento_servidores.id_projeto,
                    monitoramento_servidores.status,
                    monitoramento_servidores.tipo,
                    monitoramento_servidores.ip,
                    monitoramento_servidores.dns,
                    monitoramento_servidores.versao,
                    monitoramento_servidores.configuracao,
                    monitoramento_servidores_status.nome 
                    '
                    )
                ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores.monitoramento_servidores_status_id'  )
                ->queryWhereCliente( (isset($filtro->cliente) ? explode(',', $filtro->cliente ) : '' ) )
                ->queryWhereProjeto( (isset($filtro->projeto) ? explode(',', $filtro->projeto ) : '' ) )
                ->queryWhereTipo( (isset($filtro->tipo) ? explode(',', $filtro->tipo ) : '' ) )
                ->queryWhereIP( $filtro->ip )
                ->queryWhereDNS( $filtro->dns )
                ->queryWhereProduto( (isset($filtro->produto) ? explode(',', $filtro->produto ) : '' ) )
                ->queryWhereVersao( (isset($filtro->versao) ? explode(',', $filtro->versao ) : '' ) )
                ->queryWhereStatusInstalacao( (isset($filtro->status_instalacao) ? explode(',', $filtro->status_instalacao ) : '' ) )
                ->queryWhereStatusServidores( json_decode($filtro->status_servidor) ) 
                ->queryWhereEndereco( $filtro->endereco )
                ->get();
    }

    public function getDtUltimaColetaAttribute( $value )
    {
        if( !is_null($value)){     
            $formata = new Date();            
            return $formata->getFormatDate( $value );
        }
        return $value;
    }

    public function abaDados( $id )
    {
        $servidor =  MonitoramentoServidores::
            select(
                'monitoramento_servidores.id', 
                'monitoramento_servidores.cliente', 
                'monitoramento_servidores.razao_social', 

                'monitoramento_servidores.endereco', 
                'monitoramento_servidores.bairro', 
                'monitoramento_servidores.cidade', 
                'monitoramento_servidores.estado', 

                'monitoramento_servidores.status', 
                'monitoramento_servidores.grupo', 
                'monitoramento_servidores.plano', 
                'monitoramento_servidores.plano_tipo', 

                'monitoramento_servidores.ip', 
                'monitoramento_servidores.porta_api', 
                'monitoramento_servidores.dns', 
                'monitoramento_servidores.executa_ping', 
                'monitoramento_servidores.executa_porta', 
                'monitoramento_servidores.versao')
        
        ->where('monitoramento_servidores.id', $id)->first();        

        return $servidor;
    }

    public function abaConfiguracao( $id )
    {
        $dados =  MonitoramentoServidores::select('monitoramento_servidores.configuracao')
            ->where('monitoramento_servidores.id', $id)->first();             

        return $dados;
    }

    /**
     * [getDistinctInfo ]
     * @param  [type] $variavel [description]
     * @return [type]           [description]
     */
    public function getDistinctInfo ( $variavel ) 
    {
        return MonitoramentoServidores::select($variavel)->distinct($variavel)->orderBy($variavel)->get()->pluck($variavel);
    }


}

