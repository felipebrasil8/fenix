<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuloConfiguracaoUsuarioTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/configuracao/usuario';

    // Arquivos de view
    private $view = 'configuracao.usuario';

    // Variavies de dados
    private $dados = ['nome' => 'TESTE AUTOMATIZADO - CADASTRO', 'perfil' => ['id' => 4], 'usuario' => 'teste.automatizado', 'senha' => 'Mudar@123', 'campoConfirmarSenha' => 'Mudar@123' ];
    private $dadosUpdate = ['nome' => 'TESTE AUTOMATIZADO - UPDATE', 'perfil' => ['id' => 4], 'usuario' => 'teste.automatizado.update', 'senha' => 'Mudei@123', 'campoConfirmarSenha' => 'Mudei@123' ];
    private $dadosSenha = ['oldPassword' => 'Mudar@123', 'newPassword' => 'MudeiNovamente@123', 'newPasswordConfirmation' => 'MudeiNovamente@123' ];
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

        // Remover registros
        $response = $this->delete($this->dir.'/'.$id);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // Alterar senha
        $response = $this->json('POST',$this->dir.'/'.$id.'/password',$this->dadosSenha);
        $response->assertStatus(400)->assertJson(['status' => false]);

        // Alterar solicitacao de senha
        $dados = ['id' => $id, 'nome' => 'TESTE AUTOMATIZADO - CADASTRO', 'usuario' => 'teste.automatizado'];
        $response = $this->json('PUT',$this->dir.'/'.$id.'/solicitarNovaSenha',$dados);
        $response->assertStatus(400)->assertJson(['status' => false]);

        // Alterar senha pelo adm
        $dadosSenha = $this->dadosSenha;
        $dadosSenha['id'] = $id;
        $response = $this->json('PUT',$this->dir.'/'.$id.'/novaSenha',$dadosSenha);
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

        // Remover registros
        $response = $this->getUserBasic()->delete($this->dir.'/'.$id);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // Alterar senha
        $response = $this->getUserBasic()->json('POST',$this->dir.'/'.$id.'/password',$this->dadosSenha);
        $response->assertStatus(401)->assertJson(['status' => false]);

        // Alterar solicitacao de senha
        $dados = ['id' => $id, 'nome' => 'TESTE AUTOMATIZADO - CADASTRO', 'usuario' => 'teste.automatizado'];
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$id.'/solicitarNovaSenha',$dados);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

        // Alterar senha pelo adm
        $dadosSenha = $this->dadosSenha;
        $dadosSenha['id'] = $id;
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$id.'/novaSenha',$dadosSenha);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

    }

    public function testLoginAdmin()
    {

    	// Variaveis de pesquisa
    	$search = $this->search;
    	$search['nome'] = '';
        $search['usuario'] = '';
        $search['perfil'] = [ 'id' => '' ];
    	$searchVazio = $this->search;
    	$searchVazio['nome'] = 'XXXXXXXXXXXXXXXXX';
        $searchVazio['usuario'] = 'xxxxxxxxxxxxxxxx';
        $searchVazio['perfil'] = [ 'id' => '1000' ];

        // Abrir página de pesquisa
        $response = $this->getUserAdmin()->get($this->dir);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewIndex);

        // Resgatar dados da pesquisa
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$search);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->usuarios->data);

        // Resgatar dados da pesquisa sem resultado
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$searchVazio);
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->usuarios->data);

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
        $response->assertStatus(200)->assertJson(['status' => true]);

        // Atualizar dados de um registro inexistente
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idInvalido,$this->dadosUpdate);
        $response->assertStatus(500);

        // Inserir mais um novo registro com sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        $response->assertStatus(200)->assertJson(['status' => true]);
        $id2 = $response->getData()->id;

        // Atualizar dados do registro com nome existente
        $dadosUpdate = $this->dadosUpdate;
        $dadosUpdate['id'] = $id2;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id2,$dadosUpdate);
        $response->assertStatus(422)->assertJson(['errors' => ['nome' => ['nome já está em uso.'] ] ]);

        // Alterar senha válida com dados errados
        $dadosSenha = $this->dadosSenha;
        $dadosSenha['id'] = $id;
        $dadosSenha['newPasswordConfirmation'] = 'xxxxxxxxx';
        $response = $this->getUser($id)->json('POST',$this->dir.'/'.$id.'/password',$dadosSenha);
        $response->assertStatus(422)->assertJson(['message' => 'The given data was invalid.' ]);

        // Alterar senha válida
        $dadosSenha = $this->dadosSenha;
        $dadosSenha['id'] = $id;
        $response = $this->getUser($id)->json('POST',$this->dir.'/'.$id.'/password',$dadosSenha);
        $response->assertStatus(200)->assertJson(['success' => 'Senha alterada com sucesso.' ]);

        // Alterar senha inválida
        $response = $this->getUser($id)->json('POST',$this->dir.'/'.$id.'/password',$dadosSenha);
        $response->assertStatus(422)->assertJson(['errors' => ['Senha antiga inválida.'] ]);

        // Alterar solicitacao de senha
        $dados = ['id' => $id, 'nome' => 'TESTE AUTOMATIZADO - UPDATE', 'usuario' => 'teste.automatizado.update', 'senha_alterada' => false, 'visualizado_senha_alterada' => false ];
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id.'/solicitarNovaSenha',$dados);
        $response->assertStatus(200)->assertJson(['success' => 'Solicitação de senha alterada com sucesso.' ]);

        // Alterar senha pelo adm com dados errados
        $dadosSenha = ['newPassword' => 'MudeiAdm@123', 'newPasswordConfirmation' => 'xxxxxxxxxxxxx' ];
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id.'/novaSenha',$dadosSenha);
        $response->assertStatus(422)->assertJson(['message' => 'The given data was invalid.' ]);

        // Alterar senha pelo adm válida
        $dadosSenha = $this->dadosSenha;
        $dadosSenha['id'] = $id;
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id.'/novaSenha',$dadosSenha);
        $response->assertStatus(200)->assertJson(['success' => 'Senha alterada com sucesso.' ]);
        
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
