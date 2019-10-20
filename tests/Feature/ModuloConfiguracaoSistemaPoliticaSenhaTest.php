<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;

class ModuloConfiguracaoSistemaPoliticaSenhaTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/configuracao/sistema/politica_senhas';

    // Arquivos de view
    private $view = 'configuracao.sistema.politica_senhas';

    // Variavies de dados
    private $dados = ['tamanho_minimo' => 8, 'qtde_minima_letras' => 1, 'qtde_minima_numeros' => 1, 'qtde_minima_especial' => 0, 'caractere_especial' => '!@#$%&*?+', 'maiusculo_minusculo' => false];
    private $dadosUpdate = ['tamanho_minimo' => 9, 'qtde_minima_letras' => 3, 'qtde_minima_numeros' => 2, 'qtde_minima_especial' => 1, 'caractere_especial' => '!@#$%&*?+=', 'maiusculo_minusculo' => true];
    private $idInvalido = '100000';

    public function testNoLogin()
    {

    	// Id utilizado
    	$id = '1';

        // Abrir página principal
        $response = $this->get($this->dir);
        $response->assertStatus(401)->assertViewIs($this->viewLogin);

        // // Inserir novo registro com sucesso
        // $response = $this->json('POST',$this->dir,$this->dados);
        // $response->assertStatus(401)->assertJson(['status' => false]);

        // Atualizar dados do registro
        $response = $this->json('PUT',$this->dir.'/'.$id,$this->dadosUpdate);
        $response->assertStatus(401)->assertJson(['status' => false]);

    }

    public function testLoginBasic()
    {

    	// Id utilizado
    	$id = '1';

        // Abrir página principal
        $response = $this->getUserBasic()->get($this->dir);
        $response->assertStatus(403)->assertViewIs($this->viewAutenticacao);

        // // Inserir novo registro com sucesso
        // $response = $this->getUserBasic()->json('POST',$this->dir,$this->dados);
        // $response->assertStatus(403)->assertJson(['errors' => [ 'Falha na autenticação' ] ]);

        // Atualizar dados do registro
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$id,$this->dadosUpdate);
        $response->assertStatus(403)->assertJson(['errors' => ['errors' => [ 'Falha na autenticação' ] ] ]);

    }

    public function testLoginAdmin()
    {

        // Abrir página principal
        $response = $this->getUserAdmin()->get($this->dir);
        $response->assertStatus(200)->assertViewIs($this->view.'.'.$this->viewIndex);

        // // Inserir novo registro com sucesso
        // $response = $this->getUserAdmin()->json('POST',$this->dir,$this->dados);
        // $response->assertStatus(200)->assertJson(['status' => true]);
        // $id = $response->getData()->senha->id;

        // Atualizar dados do registro
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/1',$this->dadosUpdate);
        $response->assertStatus(200)->assertJson(['id' => 2]);

        // Atualizar dados de um registro inexistente
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$this->idInvalido,$this->dadosUpdate);
        $response->assertStatus(404)->assertJson(['errors' => ['errors' => ['Não foi possível editar Política de senha']]]);

    }
    
}
