<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;
use App\Models\BaseDeConhecimento\PublicacaoTag;
use App\Models\BaseDeConhecimento\PublicacaoConteudo;
use App\Models\BaseDeConhecimento\PublicacaoHistorico;
use App\Models\BaseDeConhecimento\PublicacaoCargo;

use Illuminate\Support\Facades\DB;

class RascunhoController extends Controller
{
    private $publicacaoColaborador;
    private $publicacaoHistorico;
    private $publicacaoCargo;
    private $publicacao;
    private $publicacaoTag;
    private $publicacaoConteudo;

    private $errors;
    private $success;
    private $entidade = 'Rascunho';    

    public function __construct( Errors $errors,
                                    Success $success,
                                        Publicacao $publicacao,
                                            PublicacaoColaborador $publicacaoColaborador,
                                                PublicacaoHistorico $publicacaoHistorico,
                                                    PublicacaoCargo $publicacaoCargo,
                                                        PublicacaoTag $publicacaoTag,
                                                            PublicacaoConteudo $publicacaoConteudo )
    {
        $this->publicacao = $publicacao;
        $this->publicacaoColaborador = $publicacaoColaborador;
        $this->publicacaoHistorico = $publicacaoHistorico;
        $this->publicacaoCargo = $publicacaoCargo;
        $this->publicacaoTag = $publicacaoTag;
        $this->publicacaoConteudo = $publicacaoConteudo;

        $this->errors = $errors;
        $this->success = $success;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmar($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_RASCUNHO' );
        
        DB::beginTransaction();

        try
        {   
            $rascunho = [];
            $publicacao = [];

            // Qtd. de rascunhos para o histórico
            $rascunho = [
                'tags' => $this->publicacaoTag->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->count(),
                'colaboradores' => $this->publicacaoColaborador->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->count(),
                'conteudos' => $this->publicacaoConteudo->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->count()
            ];

            // Qtd. de conteudos para o histórico
            $publicacao = [
                'tags' => $this->publicacaoTag->where('publicacao_id', '=', $id)->where('rascunho', '=', false)->count(),
                'colaboradores' => $this->publicacaoColaborador->where('publicacao_id', '=', $id)->where('rascunho', '=', false)->count(),
                'conteudos' => $this->publicacaoConteudo->where('publicacao_id', '=', $id)->where('rascunho', '=', false)->count()
            ];

            $this->publicacaoColaborador->where('publicacao_id', '=', $id)->where('rascunho', '=', false)->delete();
            $this->publicacaoTag->where('publicacao_id', '=', $id)->where('rascunho', '=', false)->delete();
            $this->publicacaoConteudo->where('publicacao_id', '=', $id)->where('rascunho', '=', false)->forceDelete();

            $this->publicacaoColaborador->where('publicacao_id', '=', $id)->update(['rascunho' => false]);
            $this->publicacaoTag->where('publicacao_id', '=', $id)->update(['rascunho' => false]);
            $this->publicacaoConteudo->where('publicacao_id', '=', $id)->update(['rascunho' => false]);

            $this->publicacaoHistorico->setHistoricoPublicacaoConfirmRascunho( $id, $rascunho, $publicacao );

            DB::commit();

            return [
                'mensagem' => $this->success->msgUpdate( $this->entidade )
            ];
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return  $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_RASCUNHO' );

        DB::beginTransaction();

        try
        {   
            $rascunho = [];

            // Qtd. de rascunhos para o histórico
            $rascunho = [
                'tags' => $this->publicacaoTag->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->count(),
                'colaboradores' => $this->publicacaoColaborador->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->count(),
                'conteudos' => $this->publicacaoConteudo->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->count()
            ];

            $this->publicacaoColaborador->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->delete();
            $this->publicacaoTag->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->delete();
            $this->publicacaoConteudo->where('publicacao_id', '=', $id)->where('rascunho', '=', true)->delete();

            $this->publicacaoHistorico->setHistoricoPublicacaoDeleteRascunho( $id, $rascunho );

            DB::commit();

            return [
                'mensagem' => $this->success->msgDestroy( $this->entidade )
            ];
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return  $e;
        }
    }
}
