<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Models\Core\Notificacao;

use App\Repositories\Core\NotificacaoRepositoryInterface;

use Carbon\Carbon;

class NotificacaoController extends Controller
{
   
    protected $repository;
    private $errors;

    public function __construct( NotificacaoRepositoryInterface $repository, Errors $errors )
    {
        $this->repository = $repository;
        $this->errors = $errors;
    }

    public function getNotificacoes()
    {
        try
        {
            if( !is_null(\Auth::user()) )
            {
                /*
                 * Consulta todas as notificações do usuário logado
                 */
                $notificaoes = $this->repository->scopeQuery(function($query){
                    return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
                })->findWhere(
                    [ 
                        ['usuario_id', '=', \Auth::user()->id], 
                        ['created_at', '<=', Carbon::now('America/Sao_Paulo')] 
                    ]);

                /*
                 * Retorna a quantidade de notificacoes não notificadas
                 */
                $notificaoes_nao_notificadas = $this->notificacaoesNaoNotificadas();

                /*
                 * Altera o status e a data de notificada (utilizada no webNotification)
                 */
                $this->alterarNotificada( $notificaoes );

                /*
                 * Cria o campo data em notificacao representando a diferença entre a data de criação e a data de agora
                 */
                $this->dataNotificacao( $notificaoes );

                /*
                 * Retorna a quantidade de notificacoes não lidas
                 */
                $notificaoes_nao_lidas = $this->notificacaoesNaoLidas( $notificaoes );
                
                return [
                    'notificacoes' => $notificaoes, 
                    'notificaoes_nao_lidas' => $notificaoes_nao_lidas, 
                    'notificaoes_nao_notificadas' => $notificaoes_nao_notificadas,
                ];
            }
            else
            {
                return response()->json([ 'errors' => 'Usuário inválido.' ], 422);
            }
        }
        catch(\Exception $e)
        {    
            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }
    }

    private function dataNotificacao( $notificaoes )
    {
        foreach ($notificaoes as $key => $value)
        {
            $date1 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create($value->created_at), 'd/m/Y H:i:s'));
            $date2 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create(Carbon::now('America/Sao_Paulo')), 'd/m/Y H:i:s'));

            if( $date2->diffInMinutes($date1) <= 1 )
            {
                $value->data = $date2->diffInMinutes($date1).' min';
            }
            else if( $date2->diffInMinutes($date1) <= 59 )
            {
                $value->data = $date2->diffInMinutes($date1).' mins';
            }
            else if( $date2->diffInHours($date1) == 1 )
            {
                $value->data = '1 hora';   
            }
            else if( $date2->diffInHours($date1) <= 23 )
            {
                $value->data = $date2->diffInHours($date1).' horas';   
            }
            else
            {
                $value->data = date_format(date_create($value->created_at), 'd/m');
            }
            $value->data_notificacao = date_format(date_create($value->created_at), 'd/m/Y H:i:s');
        }
    }

    private function notificacaoesNaoNotificadas()
    {
        $notificaoes_nao_notificadas = $this->repository->scopeQuery(function($query){
            return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        })->findWhere(
            [ 
                ['usuario_id', '=', \Auth::user()->id], 
                ['created_at', '<=', Carbon::now('America/Sao_Paulo')], 
                ['notificada', '=', false], 
            ]);

        return $notificaoes_nao_notificadas;
    }

    private function notificacaoesNaoLidas( $notificaoes )
    {
        $count = 0;

        foreach ($notificaoes as $notificacao)
        {
            if( $notificacao->visualizada == false )
            {
                $count++;
            }
        }

        return $count;
    }

    private function alterarNotificada( $notificaoes )
    {
        $count = 0;
        $limitWebNotification = 3;

        foreach ($notificaoes as $notificacao)
        {
            if( $notificacao->notificada == false && $count < $limitWebNotification )
            {
                $notificacaoAlteracao = $this->repository->find( $notificacao->id );
                $notificacaoAlteracao->notificada = true;
                $notificacaoAlteracao->dt_notificada = Carbon::now('America/Sao_Paulo');
                $this->repository->update($notificacaoAlteracao->toArray(), $notificacaoAlteracao->id, false);

                $count++;
            }
        }
    }

    public function setNotificaoVisualizada( Request $request, $id )
    {
        try
        {
            if( !is_null(\Auth::user()) && \Auth::user()->id == $request->usuario_id )
            {
                $notificacao = $this->repository->find( $id );

                if( \Auth::user()->id == $notificacao->usuario_id )
                {

                    if( $notificacao->dt_visualizada == '' && !$request->visualizada )
                    {
                        $notificacao->dt_visualizada = Carbon::now('America/Sao_Paulo');
                    }

                    $notificacao->visualizada = !$request->visualizada;
                    $notificacao->save();

                    return ['status' => true];

                }

            }
            
            return response()->json([ 'errors' => 'Usuário inválido.' ], 422);

        }
        catch(\Exception $e)
        {
            return \Response::json( ['errors' => 'Não foi possível alterar a notificação.'], 422);
        }
    }
}
