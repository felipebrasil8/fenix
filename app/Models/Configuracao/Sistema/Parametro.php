<?php

namespace App\Models\Configuracao\Sistema;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Sistema\ParametroGrupo;
use App\Models\Configuracao\Sistema\ParametroTipo;

class Parametro extends Model
{
	protected $fillable = array(
		'parametro_grupo_id',
		'parametro_tipo_id',
		'nome',
		'descricao',
		'valor_texto',
		'valor_numero',
		'valor_booleano',
		'ordem',
		'editar',
		'obrigatorio',
		'ativo'
	);
	
	public function parametrosGrupo()
    {
        return $this->hasOne(ParametroGrupo::class, 'id', 'parametro_grupo_id');
    }

	public function parametrosTipo()
    {
        return $this->hasOne(ParametroTipo::class, 'id', 'parametro_tipo_id');
    }    

    /**
     * [getParametrosMonitoramento Resgata todos os parametro do grupo monitoramento]
     * @return [collecion] [descrição, id, valor]
     */
    static public function getParametrosMonitoramento() {
    	return Parametro::selectRaw(' parametros.descricao,
    								  parametros.id, 
    								  CASE WHEN parametros.valor_numero IS NOT NULL THEN parametros.valor_numero::text 
                                          WHEN parametros.valor_booleano IS NOT NULL THEN parametros.valor_booleano::text 
                                          ELSE parametros.valor_texto::text 
                                      END valor')
                            ->join('parametros_grupo',  'parametros_grupo.id', '=', 'parametros.parametro_grupo_id')
                            ->where('parametros_grupo.nome', '=', 'MONITORAMENTO')
                            ->where('parametros.nome', '!=', 'MONITORAMENTO_SERVICO_ATIVO')
                            ->orderBy('parametros.ordem')
                            ->get();
    }
    
}