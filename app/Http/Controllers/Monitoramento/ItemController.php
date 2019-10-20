<?php

namespace App\Http\Controllers\Monitoramento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Monitoramento\ChamadosAbertosView;
use App\Models\Monitoramento\MonitoramentoServidores;
use App\Services\MonitoramentoServidoresClientesService;
use App\Models\Monitoramento\MonitoramentoServidoresItens;
use App\Models\Monitoramento\MonitoramentoServidoresStatus;
use App\Models\Monitoramento\MonitoramentoServidoresChamados;

class ItemController extends Controller
{

    private $monitoramentoServidores;
    private $monitoramentoServidoresStatus;
    private $monitoramentoServidoresChamados;
    private $entidade = 'Monitoramento Item';
    private $entidadechamado = 'Chamado';

    public function __construct( 
                                MonitoramentoServidores $monitoramentoServidores,
                                MonitoramentoServidoresChamados $monitoramentoServidoresChamados,
                                MonitoramentoServidoresStatus $monitoramentoServidoresStatus 
                            )
    {

        $this->monitoramentoServidores = $monitoramentoServidores;
        $this->monitoramentoServidoresChamados = $monitoramentoServidoresChamados;
        $this->monitoramentoServidoresStatus = $monitoramentoServidoresStatus;
    }


    public function index()
    {
        $this->autorizacao( 'MONITORAMENTO_ITENS_VISUALIZAR' );

        $produtos = $this->monitoramentoServidores->getDistinctInfo( 'grupo' );

        $clientes = $this->monitoramentoServidores->getDistinctInfo( 'cliente' );

        $tipos = $this->monitoramentoServidores->getDistinctInfo( 'tipo' );

        $statusServidores = $this->monitoramentoServidoresStatus->getStatusServidor();

        $statusItens = $this->monitoramentoServidoresStatus->getStatusIten();

        $itens = MonitoramentoServidoresItens::select('identificador', 'nome')->distinct('identificador')->orderBy('nome')->get();
        

        return view('monitoramento.itens.index', [
            'ativo' => 'true', 
            'filtro' => false,
            'migalhas' => $this->migalhaDePao( 'INDEX' ),
            'itens' => $itens,
            'produtos' => $produtos,
            'clientes' => $clientes,
            'statusServidores' => $statusServidores,
            'statusItens' => $statusItens,
            'tipos' => $tipos
        ] );
    }

    public function search(Request $request)
    {
        $this->autorizacao( 'MONITORAMENTO_ITENS_VISUALIZAR' );

        $alarmePersistente = $this->monitoramentoServidores->getParametroAlarmePersistente();
        
        $servidores = $this->monitoramentoServidores->getItensServidores($request);

        $statusServidores = $this->monitoramentoServidores->getStatusServidores($request);
        
        $statusItens = $this->monitoramentoServidores->getStatusItens($request);

        return [
            'servidores' => $servidores,
            'statusServidores' => $statusServidores,
            'statusItens' => $statusItens,
            'alarmePersistente' => $alarmePersistente,
            'can' => [ 
                    'monitoramento_visualizar' => \Auth::user()->can( 'MONITORAMENTO_PARADA_PROGRAMADA_VISUALIZAR' ),
                    'monitoramento_cadastrar' => \Auth::user()->can( 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR' ),
                    'monitoramento_coleta_manual' => \Auth::user()->can( 'MONITORAMENTO_COLETA_MANUAL_EXECUTAR' ),
                    'chamado_vinculados_visualizar' => \Auth::user()->can( 'MONITORAMENTO_CHAMADOS_VINCULADOS_VISUALIZAR' ),
                    'chamado_vinculados_cadastrar' => \Auth::user()->can( 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR' ),
                ]
        ];

    }

    public function coletaManual( Request $request ) {

        $this->autorizacao( 'MONITORAMENTO_COLETA_MANUAL_EXECUTAR' );

        $monitoramentoServidoresClientesService = new MonitoramentoServidoresClientesService();
        $monitoramentoServidoresClientesService->coletaManual( $request->servidorId );     
    }

    public function getChamados( $projeto ){

        $this->autorizacao( 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR' );

        $chamadosAbertosView = new ChamadosAbertosView;
        $chamados = $chamadosAbertosView->getChamadosProjeto( $projeto );
        
        return ['chamados' => $chamados];

    }

    public function setChamado( Request $request ){

        $this->autorizacao( 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR' );

        if ( $request->id_item == false ) {

            $itemServidor = MonitoramentoServidoresItens::where( 'monitoramento_servidores_id', $request->id_servidor )->whereNotNull('chamado_vinculado')->get();

            
            if( count($itemServidor) > 0 )
            {
                return \Response::json(['errors' =>  ['errors' => [ 'Não é possivel cadastrar, servidor já possui chamado.' ]]] ,404);
            }

            \DB::beginTransaction();

            try
            {

                $status = MonitoramentoServidores::select('monitoramento_servidores_status_id')->where('id',$request->id_servidor)->first()->monitoramento_servidores_status_id;
                
                MonitoramentoServidoresItens::where( 'monitoramento_servidores_id', $request->id_servidor )
                    ->where('monitoramento_servidores_status_id' , $status)
                    ->update([
                        'chamado_vinculado' => $request->chamado[0]['numero'],
                        'chamado_vinculado_at' => now(),
                        'usuario_inclusao_chamado_id' => \Auth::user()->id
                    ]);
                
                $this->monitoramentoServidoresChamados->setHistoricoInsertChamado($request->chamado[0]['numero'], $request->id_servidor, false );

                \DB::commit();
            }
            catch(\Exception $e)
            {
             
                \DB::rollback();
                return \Response::json(['errors' => ['errors' =>[ $this->errors()->msgStore( $this->entidadechamado ) ] ] ] ,404);

            }

            return ['mensagem' => $this->entidadechamado.' cadastrada com sucesso.'];
        

        }
        else
        {

            $itemServidor = MonitoramentoServidoresItens::where( 'id', $request->id_item )->whereNotNull('chamado_vinculado')->first();
            
            if( isset($itemServidor) )
            {
                return \Response::json(['errors' =>  ['errors' => [ 'Não é possivel cadastrar, item já possui chamado.' ]]] ,404);
            }

            \DB::beginTransaction();

            try{

                MonitoramentoServidoresItens::where( 'id', $request->id_item )
                    ->update([
                        'chamado_vinculado' => $request->chamado[0]['numero'],
                        'chamado_vinculado_at' => now(),
                        'usuario_inclusao_chamado_id' => \Auth::user()->id
                    ]);
                
                $this->monitoramentoServidoresChamados->setHistoricoInsertChamado( $request->chamado[0]['numero'], $request->id_servidor, $request->id_item );
                
                \DB::commit();

            }catch(\Exception $e){
                \DB::rollback();
                return \Response::json(['errors' => ['errors' =>[ $this->errors()->msgStore( $this->entidadechamado ) ] ] ] ,404);
            }

            return ['mensagem' => $this->entidadechamado.' cadastrada com sucesso.'];
        }        
    }

    public function unsetChamado( Request $request, $id ){

        $this->autorizacao( 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR' );

        if ( $request->id_item == false ) {

            $itemServidor = MonitoramentoServidoresItens::where( 'monitoramento_servidores_id', $request->id_servidor )
                                ->where('chamado_vinculado', '=', $request->chamado['numero'] )
                                ->first();


            if( !isset($itemServidor) )
            {
                return \Response::json(['errors' =>  ['errors' => [ 'Não é possivel excluir, chamado alterado.' ]]] ,404);
            }

            \DB::beginTransaction();

            try
            {
                   
                MonitoramentoServidoresItens::where( 'monitoramento_servidores_id', $request->id_servidor )
                    ->update([
                        'chamado_vinculado' => NULL,
                        'chamado_vinculado_at' => NULL,
                        'usuario_inclusao_chamado_id' => NULL
                    ]);

                $this->monitoramentoServidoresChamados->setHistoricoDeleteChamado($request->chamado['numero'], $request->id_servidor, false);

                

                \DB::commit();
            }
            catch(\Exception $e)
            {

                \DB::rollback();
                 return \Response::json(['errors' => ['errors' =>[ $this->errors()->msgStore( $this->entidadechamado ) ] ] ] ,404);

            }

            return ['mensagem' => $this->entidadechamado.' excluido com sucesso.'];


        }
        else
        {

            $itemServidor = MonitoramentoServidoresItens::where( 'id', $request->id_item )
                                                        ->where('chamado_vinculado', '=', $request->chamado['numero'] )
                                                        ->first();

  
            if( !isset($itemServidor) )
            {
                return \Response::json(['errors' =>  ['errors' => [ 'Não é possivel excluir, chamado alterado.' ]]] ,404);
            }

            \DB::beginTransaction();

            try{

                MonitoramentoServidoresItens::where( 'monitoramento_servidores_id', $request->id_servidor )
                    ->where( 'id', $request->id_item )
                    ->update([
                        'chamado_vinculado' => NULL,
                        'chamado_vinculado_at' => NULL,
                        'usuario_inclusao_chamado_id' => NULL
                    ]);

                $this->monitoramentoServidoresChamados->setHistoricoDeleteChamado( $request->chamado['numero'], $request->id_servidor, $request->id_item);
                    
                
                \DB::commit();

            }catch(\Exception $e){
                \DB::rollback();
                return \Response::json(['errors' => ['errors' =>[ $this->errors()->msgStore( $this->entidadechamado ) ] ] ] ,404);
            }

            return ['mensagem' => $this->entidadechamado.' excluido com sucesso.'];
        
        }
    }

    public function getInfoChamado( $chamado ){

        $this->autorizacao( 'MONITORAMENTO_CHAMADOS_VINCULADOS_VISUALIZAR' );

        $chamadosAbertosView = new ChamadosAbertosView;
        $chamado = $chamadosAbertosView->getInfoChamadoProjeto( $chamado );
        
        return ['chamado' => $chamado];

    }

}
