<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuloConfiguracaoPerfilTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/configuracao/perfil';

    // Arquivos de view
    private $view = 'configuracao.perfil';

    // Variavies de dados
    private $dados = [
        'nome' => 'TESTE AUTOMATIZADO - CADASTRO',
        'acessos' => [11],
        'configuracao_de_rede' => '192.168.9.0/24',  
        'horario_final' => '23:59:59',
        'horario_inicial' => '00:00:00',
        'todos_dias' => true,
    ];

    private $dadosUpdate = ['nome' => 'TESTE AUTOMATIZADO - UPDATE'];
    private $idInvalido = '100000';

    public function testNoLogin()
    {

    	// Variaveis de pesquisa
    	$search = $this->searchNew;
    	$search['nome'] = '';

    	// Id utilizado
    	$id = '1';

        // Abrir página de pesquisa
        $response = $this->get($this->dir);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Resgatar dados da pesquisa
        $response = $this->json('POST',$this->dir.'/search',$search);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Abrir página de cadastro
        $response = $this->get($this->dir.'/create');
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Inserir novo registro com sucesso
        $response = $this->json('POST',$this->dir,$this->dados);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Abrir página de visualizar
        $response = $this->get($this->dir.'/'.$id);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Abrir página de edição
        $response = $this->get($this->dir.'/'.$id.'/edit');
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Atualizar dados do registro
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id;
        $response = $this->json('PUT',$this->dir.'/'.$id,$dadosUpdate);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Abrir página de cópia
        $response = $this->get($this->dir.'/'.$id.'/copy');
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Remover registros
        $response = $this->delete($this->dir.'/'.$id);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

    }

    public function testLoginBasic()
    {

    	// Variaveis de pesquisa
    	$search = $this->searchNew;
    	$search['nome'] = '';

    	// Id utilizado
    	$id = '1';

        // Abrir página de pesquisa
        $response = $this->getUserBasic()->get($this->dir);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Resgatar dados da pesquisa
        $response = $this->getUserBasic()->json('POST',$this->dir.'/search',$search);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Abrir página de cadastro
        $response = $this->getUserBasic()->get($this->dir.'/create');
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Inserir novo registro com sucesso
        $response = $this->getUserBasic()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Abrir página de visualizar
        $response = $this->getUserBasic()->get($this->dir.'/'.$id);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Abrir página de edição
        $response = $this->getUserBasic()->get($this->dir.'/'.$id.'/edit');
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Atualizar dados do registro
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id;
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$id,$dadosUpdate);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Abrir página de cópia
        $response = $this->getUserBasic()->get($this->dir.'/'.$id.'/copy');
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Remover registros
        $response = $this->getUserBasic()->delete($this->dir.'/'.$id);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);


    }

    public function testLoginAdmin()
    {

    	// Variaveis de pesquisa
    	$search = $this->searchNew;
    	$search['nome'] = '';
    	$searchVazio = $this->searchNew;
    	$searchVazio['nome'] = 'XXXXXXXXXXXXXXXXX';

        // Abrir página de pesquisa
        $response = $this->getUserAdmin()->get($this->dir);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewIndex);

        // Resgatar dados da pesquisa
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$search);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->perfis->data);

        // Resgatar dados da pesquisa sem resultado
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$searchVazio);
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->perfis->data);

        // Abrir página de cadastro
        $response = $this->getUserAdmin()->get($this->dir.'/create');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewCreate);

        // Inserir novo registro com sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(200)->assertJson(['mensagem' => "Perfil cadastrado com sucesso!"]);
        $id = $response->getData()->id;

        // Inserir novo registro com nome existente
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

        // Inserir novo registro sem dados obrigatorios
        $response = $this->getUserAdmin()->json('POST',$this->dir);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['O campo nome é obrigatório.'] ] ]);

        // Abrir página de visualizar
        $response = $this->getUserAdmin()->get($this->dir.'/'.$id);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewView);

        // Abrir página de visualizar invalida
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idInvalido);
        $response->assertStatus(200)->assertViewIs($this->viewNotFound);

        // Abrir página de edição
        $response = $this->getUserAdmin()->get($this->dir.'/'.$id.'/edit');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewEdit);

        // Abrir página de edição invalida
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idInvalido.'/edit');
        $response->assertStatus(200)->assertViewIs($this->viewNotFound);

        // Atualizar dados do registro
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id,$dadosUpdate);
        $response->assertStatus(200)->assertJson(['mensagem' => 'Perfil atualizado com sucesso!']);

        // Atualizar dados de um registro inexistente
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idInvalido,$this->dadosUpdate);
        $response->assertStatus(500);

        // Inserir mais um novo registro com sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(200)->assertJson(['mensagem' => 'Perfil cadastrado com sucesso!']);
        $id2 = $response->getData()->id;

        // Atualizar dados do registro com nome existente
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id2;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id2,$dadosUpdate);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

        // Abrir página de cópia
        $response = $this->getUserAdmin()->get($this->dir.'/'.$id.'/copy');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewCopy);

        // Abrir página de cópia de um id inexistente
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idInvalido.'/copy');
        $response->assertStatus(200)->assertViewIs($this->viewNotFound);

        // Remover registros
        $response = $this->getUserAdmin()->delete($this->dir.'/'.$id);
        $response->assertStatus(200);
        $response = $this->getUserAdmin()->delete($this->dir.'/'.$id2);
        $response->assertStatus(200);

        // Remover registro inexistente
        $response = $this->getUserAdmin()->delete($this->dir.'/'.$this->idInvalido);
        $response->assertStatus(404);

    }
    
}
