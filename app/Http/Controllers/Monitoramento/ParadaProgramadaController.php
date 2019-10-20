<?php

namespace App\Http\Controllers\Monitoramento;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Monitoramento\MonitoramentoServidoresParadaProgramada;

class ParadaProgramadaController extends Controller
{
	private $paradaProgramada;

    private $entidade = 'Parada Programada';  
     
    public function __construct( MonitoramentoServidoresParadaProgramada $paradaProgramada )
    {
        $this->paradaProgramada = $paradaProgramada;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$this->autorizacao( 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR' );

        $paradaProgramada = $this->paradaProgramada::where('monitoramento_servidores_id', '=', $request->id)->where('ativo', true)->whereRaw('dt_fim >= NOW()')->first();

        if( isset($paradaProgramada) )
        {
            return \Response::json(['errors' =>  ['errors' => [ 'Não é possivel cadastrar mais de uma parada ao mesmo tempo.' ]]] ,404);
        }

    	DB::beginTransaction();

    	try{

            $dt_fim = $this->validaParada($request->parada);

	    	$this->paradaProgramada->insert([
	    		'dt_inicio' => now(),
	    		'dt_fim'    => $dt_fim,
	    		'usuario_inclusao_id' => \Auth::user()->id,
                'monitoramento_servidores_id' => $request->id,
				'observacao' => $this->formatString()->strToUpperCustom($request->observacao)
	    	]);

	    	DB::commit();

        }catch(\Exception $e){
            DB::rollback();
            return \Response::json(['errors' => ['errors' =>[ $this->errors()->msgStore( $this->entidade ) ] ] ] ,404);
        }

        return ['mensagem' => $this->entidade.' cadastrada com sucesso.'];
    }

    public function destroy( Request $request, $id )
    {
        $this->autorizacao( 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR' );

        $paradaProgramada = $this->paradaProgramada::where('id', $id)->first();

        if( isset($paradaProgramada) )
        {
            if ( $request->old_de == $paradaProgramada->dt_inicio && 
                    $request->old_ate == $paradaProgramada->dt_fim && 
                        $request->old_observacao == $paradaProgramada->observacao &&
                        $paradaProgramada->ativo )
            {
                $this->paradaProgramada->where('id', '=',$id)
                    ->update([
                    'usuario_alteracao_id' => \Auth::user()->id,
                    'ativo' => false,
                    ]);

                return \Response::json(['mensagem' => $this->success()->msgDestroy( $this->entidade ), 'id' => $id], 200);
            }
            else
            {
                return \Response::json(['errors' =>  ['errors' => [ 'Não foi possível alterar pois, já existe uma alteração antes.' ]]] ,404);
            }  

        }else{
            return \Response::json(['errors' =>  ['errors' => [ $this->errors()->msgDestroy( $this->entidade ) ]]] ,404);
        }
    }

    /**
     * Atualiza dados do Perfil
     */
    public function update( Request $request, $id )
    {
        $this->autorizacao('MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR');

        \DB::beginTransaction();

        try
        {
            $paradaProgramada = $this->paradaProgramada::where('id', $id)->first();

            if( isset($paradaProgramada) )
            {
                if ( $request->old_de == $paradaProgramada->dt_inicio && 
                        $request->old_ate == $paradaProgramada->dt_fim && 
                            $request->old_observacao == $paradaProgramada->observacao &&
                            $paradaProgramada->ativo )
                {
                    $dt_fim = $this->validaParada($request->parada);
                    $this->paradaProgramada->where('id', '=', $id)->update([
                        'usuario_alteracao_id' => \Auth::user()->id,
                        'dt_inicio' => now(),
                        'dt_fim'    => $dt_fim,
                        'observacao' => $this->formatString()->strToUpperCustom($request->observacao)
                    ]);
                }
                else
                {
                    return \Response::json(['errors' =>  ['errors' => [ 'Não foi possível alterar pois, já existe uma alteração antes.' ]]] ,404);
                }  
            }
            else
            {
                return \Response::json(['errors' =>  ['errors' => [ $this->errors()->msgUpdate( $this->entidade ) ]]] ,404);
            }  

            \DB::commit();
        }
        catch(\Exception $e)
        {
            \DB::rollback();
            return \Response::json(['errors' => ['errors' => [ $this->errors()->msgUpdate( $this->entidade.$e ) ] ] ] ,404) ;
        }

        return ['mensagem' => $this->success()->msgUpdate( $this->entidade ), 'id' => $id ];
    }

    private function validaParada($parada){

        $now = Carbon::now();
        switch ($parada) {
            case '10':
                $data_fim = $now->addMinutes(10);
                break;
            case '30':
                $data_fim = $now->addMinutes(30);
                break;
            case '1':
                $data_fim = $now->addHours(1);
                break;
            case '2':
                $data_fim = $now->addHours(2);
                break;
            case '4':
                $data_fim = $now->addHours(4);
                break;
            case '8':
                $data_fim = $now->addHours(8);
                break;
            default:
                $data_fim = $now->addMinutes(30);
                break;
        }

        return $data_fim;

    }
}
