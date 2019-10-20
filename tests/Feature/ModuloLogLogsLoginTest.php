<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;
use App\Models\Core\Login as LoginModel;
use App\Models\Configuracao\Usuario;
use App\Models\Configuracao\Perfil;

class ModuloLogLogsLoginTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/log/acessos';

    // Arquivos de view
    private $view = 'log.acessos';

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

        // Gera registro
        $this->gerarLogin($this->userAdmin);

		// Dados de pesquisa
		$this->search['coluna'] = 'created_at';
		$this->search['ordem'] = 'desc';
		$this->search['de'] = date('d/m/Y');
		$this->search['ate'] = date('d/m/Y');

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->search);
        $response->assertStatus(200);
        $this->assertNotEmpty($response->getData()->logs->data);

        // Login Admin - nenhum resultado
    	$this->search['de'] = '11/09/2001';
    	$this->search['ate'] = '11/09/2001';
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/search',$this->search);
        $response->assertStatus(200);
        $this->assertEmpty($response->getData()->logs->data);

    }

    // Insere um registro de login
    private function gerarLogin($user){

    	// Resgata dados do usuario
        $usuario = Usuario::find($user);
        $perfil = Perfil::find( $usuario->perfil_id );

        // Registra Login
        $login = new LoginModel;
        $login->usuario_id = $usuario->id;
        $login->usuario = $usuario->nome;
        $login->perfil_id = $perfil->id;
        $login->perfil = $perfil->nome;
        $login->credencial = json_encode( [ "usuario"  => $usuario->usuario, "password" => $usuario->password ] );
        $login->ip = '127.0.0.1';
        $login->tipo = 'LOGIN';
        $login->mensagem = 'LOGADO COM SUCESSO';
        $login->save();

    }

}
