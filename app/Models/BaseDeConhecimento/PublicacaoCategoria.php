<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseDeConhecimento\Publicacao;

class PublicacaoCategoria extends Publicacao
{
    use SoftDeletes;

	protected $table = 'publicacoes_categorias';

	protected $fillable = array(
        'codigo',
        'created_at',
        'updated_at',
        'usuario_inclusao_id',
        'usuario_alteracao_id',
        'publicacao_categoria_id',
        'nome',
        'descricao',
        'icone',
        'ordem',
    );
 	
 	protected $hidden = array(
        'usuario_inclusao_id',
        'usuario_alteracao_id',
        'created_at',
        'updated_at',
    );

    protected $softDelete = true;

	private $orderBy = 'ordem';

	private function getCamposSelect(){
		return ['id' ,'nome', 'descricao', 'publicacao_categoria_id', 'icone', 'ordem'];
	}

	private function getCategoria(){
    	return PublicacaoCategoria::select( $this->getCamposSelect() )
            ->orderBy( $this->getOrderBy() )
			->orderBy( 'nome' )
			->get();	
    }
	
    
 	public function setOrderBy($orderBy){
 		$this->orderBy = $orderBy;
 	}
	private function getOrderBy(){
		return $this->orderBy;
	}	

 	public function getAtivo(){
    	return 	$this->getCategoria();
    }
 
    public function getCategoriaNome($id){
        return PublicacaoCategoria::whereId( $id ) 
            ->select( $this->getCamposSelect() )
            ->orderBy( $this->getOrderBy() )
            ->first();
    }
 
    public function categoriaAtiva()
    {
        $categorias = $this->getCategoria()->where('publicacao_categoria_id', '=', '');
        $subCategorias = $this->getCategoria()->where('publicacao_categoria_id', '!=', '');

        $categorias = $categorias->map(function ($categoria) use ($subCategorias){
            $categoria->filho = $subCategorias->where('publicacao_categoria_id', '=', $categoria->id)->sortBy('ordem');
            return $categoria;
        });

        return $categorias;
    }


}
