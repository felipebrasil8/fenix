<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;

use App\Models\Configuracao\Usuario;
use App\Models\BaseDeConhecimento\Publicacao;

class PublicacaoCargo extends Model
{
    //
    protected $table = 'publicacoes_cargos';

    protected $fillable = array(
        'id',
        'created_at',
        'usuario_inclusao_id',
        'publicacao_id',
        'cargo_id',
    );

    protected $hidden = array(
        'usuario_inclusao_id',
        'created_at',
    );

    public function insertPublicacaoCargo( $dadosRestricao )
    {
        if( $dadosRestricao->restricaoAcesso == "true" )
        {
            $dados = array();
           
            foreach ($dadosRestricao->cargos as $value)
            {
                array_push($dados, 
                [
                    'usuario_inclusao_id' => \Auth::user()->id,
                    'cargo_id' => trim($value),
                    'publicacao_id' => $dadosRestricao->publicacao_id 
                ]); 
            }
    
            PublicacaoCargo::insert($dados);
        }

        $pub = new Publicacao();
        $pub->updateRestricaoAcesso($dadosRestricao->publicacao_id, $dadosRestricao->restricaoAcesso);
    }

    public function getIdPublicacoesBloqueadas()
    {
        $idPublicacoesBloqueadas = $this->permissaoCargo();

        if( isset($idPublicacoesBloqueadas) && count($idPublicacoesBloqueadas) > 0 )
        {
            return $idPublicacoesBloqueadas->values();
        }

        return collect(['publicacao_id' => 0]);
    }

    public function permissaoCargo()
    {
        $usuario = new Usuario();
        $cargoUsuario = $usuario->getCargo(\Auth::user()->id);

        if(isset($cargoUsuario))
        {
            return PublicacaoCargo::select('publicacoes_cargos.publicacao_id')
                                    ->join('publicacoes', 'publicacoes.id', '=', 'publicacoes_cargos.publicacao_id')
                                    ->where('publicacoes_cargos.cargo_id', '<>', $cargoUsuario->id)
                                    ->where('publicacoes.restricao_acesso', '=', 'true')
                                    ->whereNotIn('publicacoes_cargos.publicacao_id', function( $query ) use ($cargoUsuario){
                                        return $query->select('publicacao_id')
                                                ->from('publicacoes_cargos')
                                                ->whereRaw('publicacao_id = publicacoes_cargos.publicacao_id')
                                                ->where('cargo_id', '=', $cargoUsuario->id);
                                    })  
                                    ->groupBy('publicacoes_cargos.publicacao_id')
                                    ->get();
        }

        return null;
    }
}
