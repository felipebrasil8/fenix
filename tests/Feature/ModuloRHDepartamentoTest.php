<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;

class ModuloRHDepartamentoTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/rh/departamento';

    // Arquivos de view
    private $view = 'rh.departamento';

    // Variavies de dados
    private $dados = ['nome' => 'TESTE AUTOMATIZADO - CADASTRO', 'descricao' => 'DESCRIÇÃO DO DEPARTAMENTO', 'gestor' => 1, 'area' => 1, 'ticket' => true ];
    private $dadosUpdate = ['nome' => 'TESTE AUTOMATIZADO - UPDATE', 'descricao' => 'DESCRIÇÃO DO DEPARTAMENTO', 'gestor' => ['id' => 1 ], 'area' => ['id' => 1 ], 'ticket' => false ];
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
        $response = $this->json('POST',$this->dir.'/search',$this->search);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->json('POST',$this->dir.'/search',$this->search);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->search);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->departamentos->data);

        // Login Admin - nenhum resultado
    	$this->search['nome'] = 'XXXXXXXXXXXXXXXXX';
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->search);
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->departamentos->data);

    }

    // Página de cadastro
    public function testCreate(){

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

        // Login Admin - falha (falta dados obrigatórios)
        $response = $this->getUserAdmin()->json('POST',$this->dir);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['O campo nome é obrigatório.'] ] ]);

    }

    // Página de visualizar
    public function testShow(){

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
        $response->assertStatus(404)->assertViewIs($this->viewNotFound);

    }

    // Página de edição
    public function testEdit(){

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
        $response->assertStatus(404)->assertViewIs($this->viewNotFound);

    }

    // Atualizar registro
    public function testUpdate(){

        // Sem login - falha de autenticação
        $response = $this->json('PUT',$this->dir.'/'.$this->idValido,$this->dadosUpdate);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Login Basic - falha de autorização
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$this->idValido,$this->dadosUpdate);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

    	// Regata ID do registro cadastrado
    	$id = $this->getId($this->dados['nome']);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id,$this->dadosUpdate);
        $response->assertStatus(200)->assertJson(['status' => true]);

        // Login Admin - falha (não encontrado)
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idInvalido,$this->dadosUpdate);
        $response->assertStatus(404);

        // Login Admin - falha (nome existente)
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idValido,$this->dadosUpdate);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

    }

    // Inativar registro
    public function testDestroy(){

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

    // Busca um registro pelo nome
    private function getId($nome){
    	$this->search['nome'] = $nome;
        return $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->search)->getData()->departamentos->data[0]->id;
    }

}
