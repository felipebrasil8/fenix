<?php

namespace App\Models\RH;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\RH\Areas;
use App\Models\RH\Departamentos;
use App\Models\RH\Cargos;
use Illuminate\Support\Str;
use App\Models\BaseDeConhecimento\PublicacaoCargo;
use App\Models\BaseDeConhecimento\Publicacao;

use App\Util\FormatString;

/**
 *Model para as aplicações do sistema, responsável pela modelagem dos módulos.
 *      @author Gustavo Lopes <gustavo.lopes@novaxtelecom.com.br>
 *      @version v 1.0
 *      @copyright Novax Telecom (c) 2016
 *      @access public
 *      @package app\Models\RH\Funcionario
 *      @example Classe Funcionario
 */
class Funcionario extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'funcionarios';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
		'nome',
        'nome_completo',
		'email',
		'dt_nascimento',
        'avatar',
        'avatar_grande',
        'avatar_pequeno',
        'celular_pessoal',
        'celular_corporativo',
        'telefone_comercial',
        'ramal',
		'ativo',
        'gestor_id',
        'cargo_id'
	);

    public function area()
    {
        return $this->hasOne(Areas::class);
    }

    public function departamentos()
    {
        return $this->hasOne(Departamentos::class);
    }  


	public function proximosAniversarios()
    {
         // Funcionários que fazem aniversário antes da data atual
         // Funcionários que fazem aniversário na data atual ou depois, até o fim do ano corrente
         
        $funcionariosAnt = $this->funcionarioAniversario( '<', '<' );
        $funcionariosProx = $this->funcionarioAniversario( '>', '>=' );

        
         // Mesca os funcionários que fazem aniversário antes da data atual para o final da Colletion 
         
        return $funcionariosProx->merge($funcionariosAnt);
    }

    public function aniversariantes()
    {
        $funcionarios = $this->proximosAniversarios();
        return $funcionarios->filter(
            function( $func )
            {
                return $func->aniversariante == true;
            });
    }


    /**
     * User_id inteiro Id do usuario
     * @access  public
     * @return int
    */
    public function departamentoFuncionario($user_id)
    {
        $departamento = Funcionario::where('funcionarios.id', '=', $user_id )
           ->leftJoin('cargos', 'cargos.id', '=', 'funcionarios.cargo_id')
           ->select('cargos.departamento_id AS departamento_id')
           ->first();

        
            return $departamento->departamento_id;
    
    }


    /**
     * Retorna os funcionarios que tem aniversario antes ou depois de hoje
     * @access private
     * @param  string $mes
     * @param  string $dia 
     * @return object
     */
    
    private function funcionarioAniversario($mes,$dia)
    {
        return Funcionario::where('funcionarios.ativo', true)
            ->whereMonth('funcionarios.dt_nascimento', ''.$mes, date('m'))
            ->orWhere(function ($query) use ($dia){
                $query->whereMonth('funcionarios.dt_nascimento', '=', date('m'))
                      ->whereDay('funcionarios.dt_nascimento', ''.$dia, date('d'))
                      ->where('funcionarios.ativo', true);
            })
            ->leftJoin('usuarios', 'funcionarios.id', '=', 'usuarios.funcionario_id')
          
            ->select('funcionarios.id', 'funcionarios.nome','funcionarios.dt_nascimento', 'funcionarios.avatar' , 'usuarios.usuario', 'funcionarios.email', 'funcionarios.ramal',
                DB::raw('(CASE WHEN TO_CHAR(funcionarios.dt_nascimento, \'DD-MM\') = TO_CHAR(NOW(), \'DD-MM\') THEN TRUE ELSE FALSE END) AS aniversariante')
            )
            ->orderByRaw("(Extract('Month' from  funcionarios.dt_nascimento)) asc")
            ->orderByRaw("(Extract('Day' from  funcionarios.dt_nascimento)) asc")
            ->orderByRaw("funcionarios.nome")
            ->get();
    }    

    /**
     * [getFuncionarios Retorna os usuarios ativos do sistema]
     * @return [collection] [Lista de usuarios com ID, AVATAR e NOME, Ordenado por nome]
     */
    public function getFuncionariosAtivos(){

        return Funcionario::where('ativo', '=', true)
            ->select('id', 'nome')
            ->orderBy('nome')
            ->get();

    }

    public function getAvatarPequenoBase( $id )
    {
        return Funcionario::where('id','=', $id)
            ->select('avatar_pequeno')
            ->first()
            ->avatar_pequeno;
    }

    public function getAvatarGrandeBase( $id )
    {
        return Funcionario::where('id','=', $id)
            ->select('avatar_grande')
            ->first()
            ->avatar_grande;
    }    

    public function getFuncionariosComPermissaoNaPublicacaoLikeNome( $funcionario, $publicacaoId ){

        $busca = Str::upper($funcionario);

        $restricaoAcessoPublicacao = Publicacao::select( 'restricao_acesso' )->where('id', $publicacaoId)->first();

        if( !$restricaoAcessoPublicacao['restricao_acesso'] ){

            $funcionarios = Funcionario::selectRaw('funcionarios.id AS id, funcionarios.nome AS nome')
            ->selectRaw(' \'/rh/funcionario/avatar-pequeno/\'||funcionarios.id AS avatar ' )
            ->where('funcionarios.ativo', true)
            ->where(\DB::raw("sem_acento(funcionarios.nome)"), 'LIKE', \DB::raw("sem_acento('%".$busca."%')"))
            ->where('funcionarios.id', '!=', \Auth::user()->funcionario_id)
            ->orderBy('funcionarios.nome')->get();

        }else{            

            $funcionarios = PublicacaoCargo::selectRaw('funcionarios.id AS id, funcionarios.nome AS nome')
            ->selectRaw(' \'/rh/funcionario/avatar-pequeno/\'||funcionarios.id AS avatar ' )
            ->Join('cargos', 'publicacoes_cargos.cargo_id', '=', 'cargos.id')
            ->Join('funcionarios', 'funcionarios.cargo_id', '=', 'cargos.id')
            ->where('publicacoes_cargos.publicacao_id', $publicacaoId)
            ->where(\DB::raw("sem_acento(funcionarios.nome)"), 'LIKE', \DB::raw("sem_acento('%".$busca."%')"))
            ->where('funcionarios.ativo', true)
            ->where('funcionarios.id', '!=', \Auth::user()->funcionario_id)
            ->orderBy('funcionarios.nome')->get();

        }

        return $funcionarios;

    }

    public function validaFuncionariosComPermissaoNaPublicacao( $publicacaoId, $funcionarioId ){

      $regra = PublicacaoCargo::selectRaw('funcionarios.id' )        
            ->Join('cargos', 'publicacoes_cargos.cargo_id', '=', 'cargos.id')
            ->Join('funcionarios', 'funcionarios.cargo_id', '=', 'cargos.id')            
            ->where('publicacoes_cargos.publicacao_id', $publicacaoId)
            ->where('funcionarios.ativo', true)
            ->where('funcionarios.id', $funcionarioId )
            ->orderBy('funcionarios.nome')->get();

      if( count( $regra ) > 0 ){

          return true;
      }
      return false;
    }

    public function getFuncionarios($filtro)
    {
        return Funcionario::select('funcionarios.id', 'funcionarios.ativo', 'funcionarios.nome', 'funcionarios.email')
            ->queryWhereNome($filtro->nome)
            ->queryWhereEmail($filtro->email)
            ->queryWhereStatus($filtro->status)
            ->queryWhereGestor($filtro->gestor)
            ->queryWhereCargo($filtro->cargo)
            ->queryOrderBy($filtro->sort)
            ->paginate($filtro->por_pagina);
    }

    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
        }
    }

    public function scopeQueryWhereNome( $query, $nome )
    {
        if ($nome != ''){
            return $query->whereRaw('sem_acento(funcionarios.nome) LIKE UPPER(sem_acento(\'%'.$nome.'%\'))');
        }
    }

    public function scopeQueryWhereStatus( $query, $status )
    {
        if ($status != 'todos'){
          $query->where('funcionarios.ativo', '=', $status);
        }
    }

    public function scopeQueryWhereEmail( $query, $email )
    {
        if ($email != ''){
            return $query->whereRaw('sem_acento(funcionarios.email) LIKE LOWER(sem_acento(\'%'.$email.'%\'))');
        }
    }

    public function scopeQueryWhereCargo( $query, $cargo )
    {
        if (isset($cargo) && $cargo != ''){
          $query->where('funcionarios.cargo_id', '=', $cargo);
        }
    }

    public function scopeQueryWhereGestor( $query, $gestor )
    {
        if (isset($gestor) && $gestor != ''){
          $query->where('funcionarios.gestor_id', '=', $gestor);
        }
    }

    /**
     * Método criado para retornar o funcionario, conforme sua permissao 
     *
     * @param [int] $id
     * @return Collection
     */
    public function getFuncionario($id)
    {
        return Funcionario::select('funcionarios.id', 'funcionarios.nome' , 'funcionarios.avatar', 'ua.nome AS usuario_alteracao', 'ui.nome AS usuario_inclusao')
                ->querySelectDadosPessoais()
                ->querySelectContato()
                ->querySelectDadosFuncionario()
                ->querySelectDadosCadastro()                
                ->leftJoin('usuarios as ui', 'funcionarios.usuario_inclusao_id', '=', 'ui.funcionario_id')
                ->leftJoin('usuarios as ua', 'funcionarios.usuario_alteracao_id', '=', 'ua.funcionario_id')
            ->where('funcionarios.id', '=', $id)->get();
    }

    public function scopeQuerySelectDadosPessoais( $query )
    {
        if( \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_PESSOAIS' ) != false )
        {
            return $query->selectRaw('funcionarios.dt_nascimento, funcionarios.nome_completo'); 
        }
    }

    public function scopeQuerySelectContato( $query )
    {
        if( \Auth::user()->can( 'RH_FUNCIONARIO_ABA_CONTATO' ) != false )
        {
            return $query->selectRaw('funcionarios.email, funcionarios.celular_corporativo, funcionarios.celular_pessoal,funcionarios.telefone_comercial, funcionarios.ramal'); 
        }
    }

    public function scopeQuerySelectDadosFuncionario( $query )
    {
        if( \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_FUNCIONARIO' ) != false )
        {
            return $query->selectRaw('funcionarios.gestor_id, funcionarios.cargo_id'); 
        }
    }

    public function scopeQuerySelectDadosCadastro( $query )
    {
        if( \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_DE_CADASTRO' ) != false )
        {
            return $query->selectRaw('funcionarios.created_at, funcionarios.updated_at'); 
        }
    }

    public function insert( $request )
    {
        $fs = new FormatString();
  
        $id = Funcionario::insertGetId([
            'usuario_inclusao_id' => \Auth::user()->id, 
            'nome' => $fs->strToUpperCustom( $request->nome ),
            'nome_completo' => $fs->strToUpperCustom($request->nome_completo), 
            'email' => $fs->strToLowerCustom($request->email), 
            'dt_nascimento' => $request->dt_nascimento, 
            'celular_pessoal' => $request->celular_pessoal, 
            'celular_corporativo' => $request->celular_corporativo, 
            'telefone_comercial' => $request->telefone_comercial, 
            'ramal' => $request->ramal, 
            'cargo_id' => $request->cargo_id, 
            'gestor_id' => $request->gestor_id
        ]);

        return $id;
    }

    public function updates( $request, $id )
    {
        $fs = new FormatString();
  
        Funcionario::where('id', '=', $id)->update([
            'usuario_alteracao_id' => \Auth::user()->id, 
            'nome' => $fs->strToUpperCustom( $request->nome ),
            'nome_completo' => $fs->strToUpperCustom($request->nome_completo), 
            'email' => $fs->strToLowerCustom($request->email), 
            'dt_nascimento' => $request->dt_nascimento, 
            'celular_pessoal' => $request->celular_pessoal, 
            'celular_corporativo' => $request->celular_corporativo, 
            'telefone_comercial' => $request->telefone_comercial, 
            'ramal' => $request->ramal, 
            'cargo_id' => $request->cargo_id, 
            'gestor_id' => $request->gestor_id
        ]);

        return $id;    
    }   
}
