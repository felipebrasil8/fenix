<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;

class ModuloRHFuncionarioTest extends ModuloTest
{

    //protected $search = ['sort'=> '3 asc','pagina' => '1','por_pagina' => '15','status' => true];

    // Diretorio do módulo
    private $dir = '/rh/funcionario';

    // Arquivos de view
    private $view = 'rh.funcionario';

    // Variavies de dados
    private $dados = [
        'nome' => 'TESTE AUTOMATIZADO - CADASTRO',
        'nome_completo' => 'NOME COMPLETO',
        'email' => 'teste@novaxtelecom.com.br',
        'dt_nascimento' => '01/01/2001',
        'celular_pessoal' => '11988887777',
        'celular_corporativo' => '11988888888',
        'telefone_comercial' => '1133334444',
        'ramal' => '2345',
        'ramal' => '2345',
        'cargo_id' => 1,
        'gestor_id' => 1,
    ];
    private $dadosUpdate = [
        'nome' => 'TESTE AUTOMATIZADO - UPDATE',
        'nome_completo' => 'NOME COMPLETO',
        'email' => 'teste.update@novaxtelecom.com.br',
        'dt_nascimento' => '01/01/2001',
        'celular_pessoal' => '11988887777',
        'celular_corporativo' => '11988888888',
        'telefone_comercial' => '1133334444',
        'ramal' => '2345',
        'ramal' => '2345',
        'cargo_id' => 1,
        'gestor_id' => 1,
    ];

    private $idInvalido = '100000';
    private $idValido = '1';
    private $id = '';

    // Página de pesquisa
    public function testIndex()
    {
       
        // Sem login - falha de autenticação
        $response = $this->get($this->dir);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Login Basic - falha de autorização 
        $response = $this->getUserBasic()->get($this->dir);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->get($this->dir);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewIndex);

    }

    // Dados da página de pesquisa
    public function testSearch()
    {
        // Sem login - falha de autenticação
        $response = $this->json('POST',$this->dir.'/search',$this->searchNew);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->json('POST',$this->dir.'/search',$this->searchNew);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->searchNew);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->funcionarios->data);

        // Login Admin - nenhum resultado
    	$this->searchNew['nome'] = 'XXXXXXXXXXXXXXXXX';
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->searchNew);
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->funcionarios->data);

    }

    // Página de cadastro
    public function testCreate(){
        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $response = $this->get($this->dir.'/create');
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->get($this->dir.'/create');
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->get($this->dir.'/create');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewCreate);

    }

    // Inserir novo registro
    public function testStore(){
        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $response = $this->json('POST',$this->dir,$this->dados);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(200)->assertJson(['status' => true]);
        $this->id = $response->getData()->id;

        // Login Admin - falha (nome existente)
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

        // Login Admin - falha (email existente)
        $dados = $this->dados;
        $dados['nome'] = 'OUTRO NOME';
        $response = $this->getUserAdmin()->json('POST',$this->dir,$dados);
        $response->assertStatus(422)->assertJson(['errors' => ['email' => ['email já está em uso.'] ] ]);

        // Login Admin - falha (falta dados obrigatórios)
        $response = $this->getUserAdmin()->json('POST',$this->dir);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['O campo nome é obrigatório.'] ] ]);

    }

    // Página de visualizar
    public function testShow(){
        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $response = $this->get($this->dir.'/'.$this->idValido);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->get($this->dir.'/'.$this->idValido);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idValido);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewView);

        // Login Admin - falha (não encontrado)
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idInvalido);
        $response->assertStatus(200)->assertViewIs($this->viewNotFound);

    }

    // Página de edição
    public function testEdit(){
        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $response = $this->get($this->dir.'/'.$this->idValido.'/edit');
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->get($this->dir.'/'.$this->idValido.'/edit');
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idValido.'/edit');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewEdit);

        // Login Admin - falha (não encontrado)
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idInvalido.'/edit');
        $response->assertStatus(200)->assertViewIs($this->viewNotFound);

    }

    // Atualizar registro
    public function testUpdate(){
        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $this->dadosUpdate['id'] = $this->idValido;
        $response = $this->json('PUT',$this->dir.'/'.$this->idValido,$this->dadosUpdate);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$this->idValido,$this->dadosUpdate);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

    	// Regata ID do registro cadastrado
    	$id = $this->getId($this->dados['nome']);

        // Login Admin - sucesso
        $this->dadosUpdate['id'] = $id;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id,$this->dadosUpdate);
        $response->assertStatus(200)->assertJson(['mensagem' => 'Funcionário atualizado com sucesso!']);

        // Login Admin - falha (não encontrado)
        $this->dadosUpdate['id'] = $this->idInvalido;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idInvalido,$this->dadosUpdate);
        $response->assertStatus(500);

        // Login Admin - falha (nome existente)
        $this->dadosUpdate['id'] = $this->idValido;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idValido,$this->dadosUpdate);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

    }

    // Inativar registro
    public function testDestroy(){

        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $response = $this->delete($this->dir.'/'.$this->idValido);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->delete($this->dir.'/'.$this->idValido);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

    	// Regata ID do registro cadastrado que foi editado
    	$id = $this->getId($this->dadosUpdate['nome']);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->delete($this->dir.'/'.$id);
        $response->assertStatus(200);

        // Login Admin - falha (não encontrado)
        $response = $this->getUserAdmin()->delete($this->dir.'/'.$this->idInvalido);
        $response->assertStatus(404);

    }

    // Inativar registro
    public function testGetAniversario(){
        $search = $this->searchNew;
        // Sem login - falha de autenticação
        $response = $this->json('POST',$this->dir.'/getFuncionariosAniversario');
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Login Basic - retorna os dados corretamente
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/getFuncionariosAniversario');
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->funcionarios);
        
        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/getFuncionariosAniversario');
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->funcionarios);

    }

    // Busca um registro pelo nome
    private function getId($nome){
        
    	$this->searchNew['nome'] = $nome;
        return $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->searchNew)->getData()->funcionarios->data[0]->id;
    }

}
