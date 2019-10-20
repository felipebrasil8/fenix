<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Configuracao\Sistema\Parametro;
use App\Models\Monitoramento\ServidoresClientesView;
use App\Models\Monitoramento\MonitoramentoServidores;
use App\Models\Monitoramento\LogMonitoramentoServidores;
use App\Models\Monitoramento\MonitoramentoServidoresItens;
use App\Models\Monitoramento\MonitoramentoServidoresStatus;
use App\Models\Monitoramento\MonitoramentoServidoresColetas;
use App\Models\Monitoramento\MonitoramentoServidoresChamados;
use App\Models\Monitoramento\MonitoramentoServidoresItensHistoricos;

class MonitoramentoServidoresClientesService
{    

    private $parametroTimeout = 10;
    private $parametroAlarme;
    private $parametroQuantidadeAlarme;

    private $ip;
    private $porta;
    private $id;

    private $contador_coletas = 0;

    private $statusOptions;
    private $coleta_manual = false;
    private $usuario_inclusao = null;
    
    private $executa_ping;
    private $executa_porta;

    
    /**
     * [chamaApiAtualizaAlarmesServidorCliente Metodo chamado pelo Cron do laravel, caso serviço de monitoramento esteje ativo, lista os servidores e envia um get para a api do laravel realizar a coleta dos clientes]
     */
    public function chamaApiAtualizaAlarmesServidorCliente()
    {
        $servClientes = new ServidoresClientesView();
        $servClientes->atualizaFenix();
        
        $parametroStatus = Parametro::select('valor_booleano')->where('nome', '=', 'MONITORAMENTO_SERVICO_ATIVO')->first()->valor_booleano;
        
        if( $parametroStatus ){
            $inicio = now();
            $parametroSimultaneas = Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_REQUISICOES_SIMULTANEAS')->first()->valor_numero;
            $parametroIntervalo = Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_INTERVALO_REQUISICOES')->first()->valor_numero;
            $parametroTimeout = Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_TIMEOUT_API_PRODUTOS')->first()->valor_numero;
            
            $monitoramentoServidores = new MonitoramentoServidores();
            $servidores = $monitoramentoServidores->getMonitoramentoServidoresServico();            
            
         
            $x = 0;        
            foreach ($servidores as $key => $value) 
            {

                // $servidor = [ 
                //     'ip' => $value->ip,
                //     'porta' => $value->porta_api,
                //     'id' => $value->id,
                //     'contador_coletas' => $value->contador_coletas
                // ];
                
                // \Log::info('FOR - '.$value->ip);

                $url = config('app.url').'/api/atualizarAlarmesServidoresCliente?ip='.$value->ip.'&porta='.$value->porta_api.'&id='.$value->id.'&contador_coletas='.$value->contador_coletas;
              
                exec('curl "'.$url.'" >> /dev/null &');
        
                // \Log::info('SIMULTANEAS - '.$parametroSimultaneas.' /  INTERVALO - '.$parametroIntervalo);
                        
                $x++;
                if($x == $parametroSimultaneas)
                {
                    sleep($parametroIntervalo);
                    $x = 0;
                }

            }

            $final = now();

            LogMonitoramentoServidores::insert([
                'inicio_coleta' => $inicio,
                'fim_coleta' => $final,
                'duracao' => strtotime($final)-strtotime($inicio),
                'requisicoes_simultaneas' => $parametroSimultaneas,
                'intervalo_requisicoes' => $parametroIntervalo,
                'qtde_servidores' => count($servidores),
                'timeout' => $parametroTimeout
            ]);

        }
        return 1;
    }
       
    /**
     * [atualizarAlarmesServidoresCliente 
     * Metodo responsavel pela coleta da api nos produtos e inserção dos retornos no banco de dados.]
     * @param  Request $request [IP, Porta, Contador de coletas, ID]
     */
    // public function atualizarAlarmesServidoresCliente($servidorConf)
    public function apiAlarmesServidoresCliente(Request $request)
    {
        // \Log::info('evento '.$servidorConf['ip']);

        $ip_acesso = $_SERVER['REMOTE_ADDR'];

        if( $ip_acesso != '::1' && $ip_acesso != '127.0.0.1'){
            return 0;
        }

        $this->ip = $request->ip;
        $this->porta = $request->porta;
        $this->id = $request->id;
        $this->contador_coletas = $request->contador_coletas;
        
        $this->atualizarAlarmesServidoresCliente();
    }

    /**
     * Undocumented function
     *
     * @param [Int] $id - Id do servidor
     * @return void
     */
    public function coletaManual( $id ) {

        $servidor = MonitoramentoServidores::select('ip', 'porta_api')->where('id', $id)->first();

        $this->ip = $servidor->ip;
        $this->porta = $servidor->porta_api;
        $this->id = $id;
        $this->coleta_manual = true;
        $this->usuario_inclusao = \Auth::user()->id;                
        $this->atualizarAlarmesServidoresCliente();       
       
        return 1;
    }
    
    public function atualizarAlarmesServidoresCliente()
    {        
        try{

            $this->setStatusId();
            $this->setParametros();
            $this->setExecutaPingPorta();
            
            $configuracoes = NULL;
            $itens = NULL;
            $idItensNotDelete = [];
            $itenStatusAlarme = 0;

            $url = 'http://'.$this->ip.':'.$this->porta.'/api/monitoramento/'.( $this->isColetaCompleta()  ? '' : $this->parametroAlarme . '' );
            
            $options = array( 
                CURLOPT_URL => $url, 
                CURLOPT_HEADER => 0, 
                CURLOPT_RETURNTRANSFER => TRUE, 
                CURLOPT_TIMEOUT => $this->parametroTimeout, 
                CURLOPT_FOLLOWLOCATION => TRUE 
            ); 
            $ch = curl_init();
            curl_setopt_array($ch, $options); 

            $result = (object)[];
            $result = json_decode(curl_exec($ch));
            $info = curl_getinfo($ch);

            if( $this->isColetaCompleta() )
            {
                if( $result && $info['http_code'] == 200 )
                {
                    if( $this->executa_ping ){
                        $result->alarme->ping = $this->verificaPingServidor( $this->ip );                        
                    }
                }
                else
                {
                    if( $this->executa_ping ){
                        $itemPing = $this->verificaPingServidor( $this->ip );
                        $result = (object)( ['alarme' => (object)[ 'ping' => $itemPing ] ]  );
                    }
                }

                if( $info['http_code'] == 200 )
                {
                    if( $this->executa_porta ){
                        // VERIFICA AS PORTAS
                        $portas = $result->configuracao->portas;
                        array_push( $portas, $this->porta.'/TCP' );
                        foreach ($portas as $porta)
                        {
                            $nome = 'porta_'.$porta;
                            $result->alarme->$nome = $this->verificaPortaServidor($this->ip, $porta);
                        }
                    }
                    else
                    {
                        $porta = $this->porta.'/TCP';
                        $nome = 'porta_'.$porta;
                        $result->alarme->$nome = $this->verificaPortaServidor($this->ip, $porta);
                    }
                    $configuracoes = json_encode($result->configuracao); 
                }
                else
                {
                    $porta = $this->porta.'/TCP';
                    $nome = 'porta_'.$porta;
                    $result = (object)( ['alarme' => (object)[ $nome => $this->verificaPortaServidor($this->ip, $porta) ] ] );
                }

            }

        
            if( $info['http_code'] == 200 )
            {
                $servidorStatus = 'OK';
                $servidorMensagem = NULL;            
            }
            else if( $info['http_code'] == 0 )
            {
                $servidorStatus = 'FORA';
                $servidorMensagem = 'TIME OUT';
            }
            else
            {
                $servidorStatus = 'CRITICO';
                $servidorMensagem = 'NÃO ENCONTRADO';
            }

            if( $result )
            {
                $itens = json_encode($result->alarme);
            }

            \DB::beginTransaction();
            
            // SALVAR RETORNO TABELA MONITORAMENTO COLETAS SERVIDORES
            MonitoramentoServidoresColetas::insert([
                'monitoramento_servidores_id' => $this->id,
                'itens' => $itens,
                'configuracoes' => $configuracoes,
                'monitoramento_servidores_status_id' => $this->getStatusId($servidorStatus), 
                'tempo_de_resposta' => $info['total_time'],
                'coleta_manual' => $this->coleta_manual,
                'usuario_inclusao_id' => $this->usuario_inclusao
            ]);

            if ( $info['http_code'] == 200 || $this->isColetaCompleta() )
            {
                foreach ( $result->alarme as $key => $item )
                {
                 

                    $status = $this->getStatusId( $item->estado );

                    if ( $info['http_code'] != 200 && $key == 'porta_'.$this->porta.'/TCP' ){
                        $status = $this->getStatusId( $servidorStatus );
                    }

                    $updateItem = [
                        'updated_at' => now(),
                        'monitoramento_servidores_id' => $this->id,
                        'monitoramento_servidores_status_id' => $status, 
                        'nome' => $item->nome,
                        'mensagem' => $item->mensagem,
                        'valores' => json_encode($item->valores)
                    ];

                    $itemObj = MonitoramentoServidoresItens::updateOrCreate(['monitoramento_servidores_id' => $this->id, 'identificador' => $key], $updateItem);
            
                    $itenStatusAlarme = $item->estado != 'OK'? $itenStatusAlarme+1:$itenStatusAlarme;
                    array_push($idItensNotDelete, $itemObj->id);

                
                }

                if( $this->isColetaCompleta() && $info['http_code'] == 200 )
                {
                    MonitoramentoServidoresChamados::whereNotIn('monitoramento_servidores_itens_id', $idItensNotDelete)
                                                ->where('monitoramento_servidores_id', $this->id)
                                                ->delete();
                    MonitoramentoServidoresItensHistoricos::whereNotIn('servidores_itens_id', $idItensNotDelete)
                                                ->where('servidores_id', $this->id)
                                                ->delete();
                    MonitoramentoServidoresItens::whereNotIn('id', $idItensNotDelete)
                                                ->where('monitoramento_servidores_id', $this->id)
                                                ->delete();
                }
            }

            $updateServidor = [
                'monitoramento_servidores_status_id' =>  $this->getStatusId( $servidorStatus ),                    
                'mensagem' => $servidorMensagem,
                'dt_ultima_coleta' => now(),
            ];
            
            if ( $itenStatusAlarme == 0 && $servidorStatus == 'OK' && $this->contador_coletas < $this->parametroQuantidadeAlarme ) 
            {
                $updateServidor['contador_coletas'] = $this->contador_coletas + 1;
            }
            else
            {
                $updateServidor['contador_coletas'] = 0;            
            }

            if( $this->isColetaCompleta() && $info['http_code'] == 200 )
            {
                $updateServidor['configuracao'] = $configuracoes;
            }

            if ( $this->isColetaCompleta() && $info['http_code'] != 200 ) 
            {
                MonitoramentoServidoresItens::
                                            where('monitoramento_servidores_id', $this->id)
                                            ->where('identificador', '<>', 'ping')
                                            ->where('identificador', '<>', 'porta_'.$this->porta.'/TCP' )
                                            ->update(
                                            [
                                                'monitoramento_servidores_status_id' => $this->getStatusId( $servidorStatus ),
                                                'mensagem' => NULL,
                                                'dt_status' => NULL,
                                                'valores' => NULL
                                            ]);
            }

            MonitoramentoServidores::where('id', $this->id)->update($updateServidor);

            \DB::commit();

            return 1;
       
        }
        catch(\Exception $e)
        {
            \DB::rollback();

            return  0;
        }


    }   

    /**
     * [verificaPingCliente description]
     * @return [type] [description]
     */
    private function verificaPingServidor( $ip ){
        
        $parametroPingAlarme = Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_ALARME_PING')->first()->valor_numero;
        
        exec('ping -c 4 -i 0.2 '.$ip.' | tail -n 2', $ping);

        $estado = 'OK';
        $min = $max = $med = 0;
        if( isset($ping) && count($ping) > 0 && strpos($ping[1], 'ms') !== false )
        {

            list($enviado,$recebido,$perdido) = explode(',', $ping[0]);
            list($perdido) = explode(' ', trim($perdido));

            list($x, $valores) = explode('=', trim($ping[1]));
            list($min, $med, $max) = explode('/',  trim(rtrim($valores, 'ms')));

            if( $med >= $parametroPingAlarme )
            {
                $estado = 'ALERTA';
            }
        }
        else
        {

            $estado = 'FORA';

            list($enviado,$recebido,$perdido) = explode(',', $ping[0]);
            list($perdido) = explode(' ', trim($perdido));

        }

        $ping = (object)[
            'nome' => 'PING',
            'estado' => $estado,

            'mensagem' => 'MINIMO='.$min.' / MAXIMO='.$max.' / MEDIA='.$med.' / PERDA='.$perdido,

            'valores' => (object)[ 
                'media' => (object)[
                    'atual' => $med,
                    'alerta'=>$parametroPingAlarme,
                    'critico'=>'0',
                ],
                'minimo' => (object)[
                    'atual' => $min,
                    'alerta'=>'',
                    'critico'=>'',
                ],
                'maximo' => (object)[
                    'atual' => $max,
                    'alerta'=>'',
                    'critico'=>'',
                ],
                'pacotes' => (object)[
                    'atual' => $perdido,
                    'alerta'=>'',
                    'critico'=>'',
                ],
            ]
        ];

        return $ping;

    }

    /**
     * [verificaPortasServidor description]
     * @param  [type] $ip    [description]
     * @param  [type] $porta [description]
     * @return [type]        [description]
     */
    public function verificaPortaServidor( $ip, $porta ){
        $parametroPortaTimeout = Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_ALARME_PORTA')->first()->valor_numero;
       
        if ( @fsockopen($ip, $porta, $error, $errorstr, (float)($parametroPortaTimeout/1000) ) )
        {
            $estado = 'OK';
            $atual = 'ABERTA';
            $erro = '';
        }
        else
        {
            $estado = 'CRITICO';
            $atual = 'FECHADA';
            $erro = ' / ERRO='.($error == 110?'TIMEOUT / TEMPO TIMEOUT='.$parametroPortaTimeout.'ms':'OUTRO') ;
        }
        $nome = 'PORTA '.$porta;
        $portaObj = (object)[
            'nome' => $nome,
            'estado' => $estado,
            'mensagem' => 'PORTA='.$porta.' / STATUS='.$atual.$erro,
            'valores' => (object)[ 
                $porta => (object)[
                    'atual' => $atual,
                    'alerta'=> '',
                    'critico'=> 'FECHADA',
                ]
            ]
        ];
            
        return $portaObj; 

    }

    public function isColetaCompleta( ) {
        return $this->contador_coletas == 0 ? true : false;
    }

    public function setStatusId () 
    {
        $this->statusOptions = MonitoramentoServidoresStatus::select('id', 'identificador')->get()->pluck('id', 'identificador');
    }

    public function getStatusId ($status) 
    {
        if ( $status == 'ALARME' ){
            $status = 'ALERTA';
        }
        return $this->statusOptions[$status];
    }

    public function setParametros() 
    {
        $this->parametroTimeout = Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_TIMEOUT_API_PRODUTOS')->first()->valor_numero;
        $this->parametroAlarme = Parametro::select('valor_texto')->where('nome', '=', 'NOME_ITEM_REQUISICOES_UNICAS')->first()->valor_texto;
        $this->parametroQuantidadeAlarme = Parametro::select('valor_numero')->where('nome', '=', 'QUANTIDADE_REQUISICOES_UNICAS')->first()->valor_numero;
    }

    public function setExecutaPingPorta(){

        $monitoramentoServidores = monitoramentoServidores::select('executa_ping', 'executa_porta')->where( 'id', $this->id )->first();

        $this->executa_ping = $monitoramentoServidores->executa_ping;
        $this->executa_porta = $monitoramentoServidores->executa_porta;

    }
}



