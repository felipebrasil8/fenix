<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;
use Event;
use App\Events\NotificacaoEvent;

class ModuloCoreNotificacaoTest extends ModuloTest
{

    // Diretorio do módulo
    private $dir = '/core/notificacao';

    // Variavies de dados
    private $idInvalido = '100000';

    // Resgatar dados da notificação
    public function testGetNotificacoes()
    {

        // Sem login - falha de autenticação
        $response = $this->json('POST',$this->dir.'/getNotificacoes');
        $response->assertStatus(422)->assertJson(['errors' => 'Usuário inválido.']);

        // Login Admin - sucesso
        $response = $this->getUserAdmin()->json('POST',$this->dir.'/getNotificacoes');
        $response->assertStatus(200)->assertJson(['notificaoes_nao_lidas' => 0]);
        $this->assertEmpty($response->getData()->notificacoes);

        // Gera uma nofificação
        $this->gerarNotificacao($this->getUserBasicID());

        // Verifica se notificação foi criada
        $response = $this->getUserBasic()->json('POST',$this->dir.'/getNotificacoes');
        $response->assertStatus(200)->assertJson(['notificaoes_nao_lidas' => 1]);
        $this->assertNotEmpty($response->getData()->notificacoes);
        $this->assertNotEmpty($response->getData()->notificaoes_nao_notificadas);

        // Verifica se notificação criada e resgatada foi marcada como notificada
        $response = $this->getUserBasic()->json('POST',$this->dir.'/getNotificacoes');
        $response->assertStatus(200)->assertJson(['notificacoes' => [], 'notificaoes_nao_lidas' => 1, 'notificaoes_nao_notificadas' => []]);
        $this->assertEmpty($response->getData()->notificaoes_nao_notificadas);

    }

    // Marcar notificacao como visualizada
    public function testSetNotificaoVisualizada()
    {

        // Armazena ID da notificacao
        $response = $this->getUserBasic()->json('POST',$this->dir.'/getNotificacoes');
        $response->assertStatus(200);
        $id = array_first($response->getData()->notificacoes)->id;

        // Sem login - falha de autenticação
        $response = $this->json('PUT',$this->dir.'/'.$id.'/setNotificaoVisualizada');
        $response->assertStatus(422)->assertJson(['errors' => 'Usuário inválido.']);

        // Login errado - falha de autorizacao
        $response = $this->getUserAdmin()->json('PUT',$this->dir.'/'.$id.'/setNotificaoVisualizada',['usuario_id' => $this->userAdmin]);
        $response->assertStatus(422)->assertJson(['errors' => 'Usuário inválido.']);

        // Login correto - sucesso
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.$id.'/setNotificaoVisualizada',['usuario_id' => $this->getUserBasicID()]);
        $response->assertStatus(200)->assertJson(['status' => true]);

        // Id inválido
        $response = $this->getUserBasic()->json('PUT',$this->dir.'/'.($id+1000).'/setNotificaoVisualizada',['usuario_id' => $this->getUserBasicID()]);
        $response->assertStatus(422)->assertJson(['errors' => 'Não foi possível alterar a notificação.']);

    }

    // Gerar uma notificação para o usuário
    private function gerarNotificacao($user)
    {
        event(new NotificacaoEvent( LoginUsuario::find($user), [
        	'notificacao' => [
				'titulo' => 'Titulo da notificação',
				'mensagem' => 'Notificação de teste',
				'modulo' => 'Test',
				'url' => '/home',
				'usuario' => [$user]
			]
		]));
		sleep(3);
    }

}
