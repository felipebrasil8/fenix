<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use App\Models\RH\Funcionario;
use App\Models\RH\Area;
use App\Models\Configuracao\Usuario;

/**
 *      Model para representar o módulo Parâmetros
 *  
 */
class Departamento extends Model
{

    protected $table = 'departamentos';
    
    protected $fillable = array(
		'nome',
		'descricao',
        'area_id',
        'funcionario_id',
        'ticket'
		);

   
	
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

	public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function cargos()
    {
        return $this->belongsTo(Cargos::class);
    }

    public function usuarioInclusao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_inclusao_id');
    }

    public function usuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_alteracao_id');
    }




}