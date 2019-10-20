<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;

class ModuloConfiguracaoSistemaParametroTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/configuracao/sistema/parametro';

    // Arquivos de view
    private $view = 'configuracao.sistema.parametro';

    // Variavies de dados
    private $dados = ['nome' => 'TESTE AUTOMATIZADO - CADASTRO', 'descricao' => 'DESCRIÇÃO', 'valor' => 'VALOR', 'ordem' => 1000, 'editar' => true, 'grupo' => ['id' => 1], 'tipo' => ['id' => 1, 'nome' => 'TEXTO']];
    private $dadosUpdate = ['nome' => 'TESTE AUTOMATIZADO - UPDATE', 'descricao' => 'DESCRIÇÃO UPDATE', 'valor' => 0, 'ordem' => 1999, 'editar' => false, 'grupo' => ['id' => 1], 'tipo' => ['id' => 2, 'nome' => 'NÚMERO']];
    private $idInvalido = '100000';

    public function testNoLogin()
    {

        // Variaveis de pesquisa
        $search = $this->search;
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

        // Abrir página de edição
        $response = $this->get($this->dir.'/'.$id.'/edit');
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Atualizar dados do registro
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id;
        $response = $this->json('PUT',$this->dir.'/'.$id,$dadosUpdate);
        $response->assertStatus(401)->assertJson(['status' => false]);

    }

    public function testLoginBasic()
    {

        // Variaveis de pesquisa
        $search = $this->search;
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

        // Abrir página de edição
        $response = $this->getUserBasic()->get($this->dir.'/'.$id.'/edit');
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Atualizar dados do registro
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id;
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$id,$dadosUpdate);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

    }

    public function testLoginAdmin()
    {

        // Variaveis de pesquisa
        $search = $this->search;
        $search['nome'] = '';
        $searchVazio = $this->search;
        $searchVazio['nome'] = 'XXXXXXXXXXXXXXXXX';

        // Abrir página de pesquisa
        $response = $this->getUserAdmin()->get($this->dir);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewIndex);

        // Resgatar dados da pesquisa
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$search);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->parametros->data);

        // Resgatar dados da pesquisa sem resultado
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$searchVazio);
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->parametros->data);

        // Abrir página de cadastro
        $response = $this->getUserAdmin()->get($this->dir.'/create');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewCreate);

        // Inserir novo registro com sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(200)->assertJson(['status' => true]);
        $id = $response->getData()->id;

        // Inserir novo registro com nome existente
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

        // Inserir novo registro sem dados obrigatorios
        $response = $this->getUserAdmin()->json('POST',$this->dir);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['O campo nome é obrigatório.'] ] ]);

        // Abrir página de edição
        $response = $this->getUserAdmin()->get($this->dir.'/'.$id.'/edit');
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewEdit);

        // Abrir página de edição invalida
        $response = $this->getUserAdmin()->get($this->dir.'/'.$this->idInvalido.'/edit');
        $response->assertStatus(200)->assertViewIs($this->viewNotFound);

        // Atualizar dados do registro
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$dadosUpdate['id'],$dadosUpdate);
        $response->assertStatus(200)->assertJson(['status' => true]);

        // Atualizar dados de um registro inexistente
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idInvalido,$this->dadosUpdate);
        $response->assertStatus(500);

        // Atualizar registro que não permite edição
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = '1';
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$dadosUpdate['id'],$dadosUpdate);
        $response->assertStatus(422)->assertJson(['message' => 'The given data was invalid.' ]);

        // Inserir mais um novo registro com sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(200)->assertJson(['status' => true]);
        $id2 = $response->getData()->id;

        // Atualizar dados do registro com nome existente
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id2;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$dadosUpdate['id'],$dadosUpdate);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

    }
    
}
