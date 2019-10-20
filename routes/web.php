<?php


Route::prefix('base-de-conhecimento')->group(function(){
	//GRUPO DE ROTAS PUBLICAÇÔES
	Route::prefix('publicacoes')->group(function(){
		Route::get('img-conteudo-miniatura/{id}', 'BaseDeConhecimento\ConteudoController@getImagemConteudoMiniatura');
		Route::get('img-conteudo-original/{id}', 'BaseDeConhecimento\ConteudoController@getImagemConteudoOriginal');
		Route::get('img-publicacao/{id}', 'BaseDeConhecimento\PublicacaoController@getImagemPublicacao');
	});
});
// GRUPO DE ROTAS - RH
Route::prefix('rh')->group(function(){
	// GRUPO DE ROTAS - FUNCIONARIO
	Route::prefix('funcionario')->group(function(){
		Route::get('avatar-pequeno/{id}', 'RH\FuncionarioController@getAvatarPequeno');			
		Route::get('avatar-grande/{id}', 'RH\FuncionarioController@getAvatarGrande');			
		Route::get('avatar/{id}', 'RH\FuncionarioAvatarController@getAvatarGrande');
	});
});
Route::prefix('configuracao')->group(function(){
	// GRUPO DE ROTAS - USUARIOS
	Route::prefix('usuario')->group(function(){    
		Route::get('avatar-pequeno/{id}', 'Configuracao\UsuarioController@getAvatarPequeno');			
		Route::get('avatar-grande/{id}', 'Configuracao\UsuarioController@getAvatarGrande');			
	});
});


Auth::routes(); 

Route::group(['middleware' => ['web', 'validacao.login' ]], function()
{
	Route::get('/', 'Home\HomeController@index');

	Route::get('/token', 'Api\ApiTokenController@autorizaUrl');

	//Rotas padrão do Auth

	// ROTAS CORE DO SISTEMA
	Route::get('home', 'Home\HomeController@index');
	Route::get('logout', 'Home\HomeController@logoutUser');

	// GRUPO DE ROTAS - CORE
	Route::prefix('core')->group(function(){

		// GRUPO DE ROTAS - NOTIFICAÇÕES
		Route::prefix('notificacao')->group(function(){ 
			Route::post('getNotificacoes', 'Core\NotificacaoController@getNotificacoes'); 
			Route::put('{id}/setNotificaoVisualizada', 'Core\NotificacaoController@setNotificaoVisualizada'); 
		});

		Route::post('buscaMenu', 'Core\BuscaMenuController@search');

	}); 

	// GRUPO DE ROTAS - CONFIGURACAO
	Route::prefix('configuracao')->group(function(){

		// GRUPO DE ROTAS - ACESSO
		Route::resource('acesso', 'Configuracao\AcessoController');
		Route::prefix('acesso')->group(function(){
			Route::post('search', 'Configuracao\AcessoController@search');
			Route::get('{id}/copy', 'Configuracao\AcessoController@copy');
		});

		// GRUPO DE ROTAS - PERFIL
		Route::resource('perfil', 'Configuracao\PerfilController');
		Route::prefix('perfil')->group(function(){
			Route::post('search', 'Configuracao\PerfilController@search');
			Route::get('{id}/copy', 'Configuracao\PerfilController@copy');
		});

		// GRUPO DE ROTAS - USUARIOS
		Route::resource('usuario', 'Configuracao\UsuarioController');
		Route::prefix('usuario')->group(function(){    
			Route::post('search', 'Configuracao\UsuarioController@search');
			Route::post('{id}/password', 'Configuracao\UsuarioController@changePassword');
			Route::put('{id}/solicitarNovaSenha', 'Configuracao\UsuarioController@solicitarNovaSenha');
			Route::put('{id}/novaSenha', 'Configuracao\UsuarioController@novaSenha');
			// Route::get('avatar-pequeno/{id}', 'Configuracao\UsuarioController@getAvatarPequeno');			
			// Route::get('avatar-grande/{id}', 'Configuracao\UsuarioController@getAvatarGrande');			
		});

		// GRUPO DE ROTAS - SISTEMA
		Route::prefix('sistema')->group(function(){  
		
			// GRUPO DE ROTAS - PARAMETROS
			Route::resource('parametro', 'Configuracao\Sistema\ParametroController');
			Route::prefix('parametro')->group(function(){
				Route::post('search', 'Configuracao\Sistema\ParametroController@search');
			});

			// GRUPO DE ROTAS - POLITICA SENHA
			Route::resource('politica_senhas', 'Configuracao\Sistema\PoliticaSenhaController', ['only' => [ 'index', 'update' ]]);

		});

		// GRUPO DE ROTAS - TICKETS
		Route::get('ticket', 'Configuracao\Ticket\TicketController@index');		
		Route::prefix('ticket')->group(function(){    

			// GRUPO DE ROTAS - CATEGORIA
			Route::prefix('categoria')->group(function(){    
				Route::get('/',                  'Configuracao\Ticket\CategoriaController@index');
				Route::get('/departamento/{id}', 'Configuracao\Ticket\CategoriaController@edit');
				Route::post('/store',            'Configuracao\Ticket\CategoriaController@store');
				Route::delete('destroy/{id}',    'Configuracao\Ticket\CategoriaController@destroy');
				Route::put('/update/{id}',       'Configuracao\Ticket\CategoriaController@update');
			});

			// GRUPO DE ROTAS - CAMPO ADICIONAL
			Route::prefix('campo_adicional')->group(function(){

				Route::prefix('departamento/')->group(function(){
					Route::get('{id}',         'Configuracao\Ticket\CampoAdicionalController@getCamposPadroes');
					Route::post('{id}/status', 'Configuracao\Ticket\CampoAdicionalController@showStatus');
				});

				Route::prefix('status')->group(function(){
					Route::post('/',      'Configuracao\Ticket\CampoAdicionalController@storeStatus');
					Route::put('{id}',    'Configuracao\Ticket\CampoAdicionalController@updateStatus');
					Route::delete('{id}', 'Configuracao\Ticket\CampoAdicionalController@destroyStatus');
				});

				Route::prefix('prioridade/')->group(function(){
					Route::post('',      'Configuracao\Ticket\CampoAdicionalController@storePrioridade');
					Route::put('{id}',    'Configuracao\Ticket\CampoAdicionalController@updatePrioridade');
					Route::delete('{id}', 'Configuracao\Ticket\CampoAdicionalController@destroyPrioridade');
				});

				Route::get('',        'Configuracao\Ticket\CampoAdicionalController@index'); 
				Route::post('',       'Configuracao\Ticket\CampoAdicionalController@store');
				Route::put('{id}',    'Configuracao\Ticket\CampoAdicionalController@update');
				Route::delete('{id}', 'Configuracao\Ticket\CampoAdicionalController@destroy');
				Route::post('{id}',   'Configuracao\Ticket\CampoAdicionalController@getCampoAdicional');

			});

			// GRUPO DE ROTAS - Acao
			Route::prefix('acao')->group(function(){    
				Route::get('/',                  'Configuracao\Ticket\AcaoController@index');
				Route::get('/departamento/{id}', 'Configuracao\Ticket\AcaoController@edit');
				Route::post('/store',            'Configuracao\Ticket\AcaoController@store');
				Route::delete('destroy/{id}',    'Configuracao\Ticket\AcaoController@destroy');
				Route::put('/update/{id}',       'Configuracao\Ticket\AcaoController@update');
				Route::post('/storeMultiplo',    'Configuracao\Ticket\AcaoController@storeMultiplo');
			});

			// GRUPO DE ROTAS - GATILHOS
			Route::prefix('gatilho')->group(function(){    
				Route::get('/',                  'Configuracao\Ticket\GatilhoController@index');
				Route::get('/departamento/{id}', 'Configuracao\Ticket\GatilhoController@edit');
				Route::post('/store',            'Configuracao\Ticket\GatilhoController@store');
				Route::delete('destroy/{id}',    'Configuracao\Ticket\GatilhoController@destroy');
				Route::put('/update/{id}',       'Configuracao\Ticket\GatilhoController@update');
				Route::post('/storeMultiplo',    'Configuracao\Ticket\GatilhoController@storeMultiplo');
			});

		});

	});

	// GRUPO DE ROTAS - RH
	Route::prefix('rh')->group(function(){

		// GRUPO DE ROTAS - ÁREAS
		Route::resource('area', 'RH\AreaController');
		Route::prefix('area')->group(function(){
			Route::post('search', 'RH\AreaController@search');
		});

		// GRUPO DE ROTAS - DEPARTAMENTOS
		Route::resource('departamento', 'RH\DepartamentoController');
		Route::prefix('departamento')->group(function(){
			Route::post('search', 'RH\DepartamentoController@search');
		});

		// GRUPO DE ROTAS - CARGOS
		Route::resource('cargo', 'RH\CargoController');
		Route::prefix('cargo')->group(function(){
			Route::post('search', 'RH\CargoController@search');
		});

		// GRUPO DE ROTAS - FUNCIONARIO
		Route::resource('funcionario', 'RH\FuncionarioController');
		Route::prefix('funcionario')->group(function(){
			Route::post('search', 'RH\FuncionarioController@search');
			Route::post('{id}/avatar', 'RH\FuncionarioController@changeAvatar');
			Route::post('getFuncionariosAniversario', 'RH\FuncionarioController@getFuncionariosAniversario');
			Route::post('download/{type}', 'RH\FuncionarioController@downloadExcel');
			// Route::get('avatar-pequeno/{id}', 'RH\FuncionarioController@getAvatarPequeno');			
			// Route::get('avatar-grande/{id}', 'RH\FuncionarioController@getAvatarGrande');			
			Route::get('{modulo}/{publicacaoId}/{funcionario}', 'RH\FuncionarioController@getFuncionariosComPermissaoNaPublicacaoLikeNome');
			
			// GRUPO DE ROTAS - HISTÓRICO FUNCIONARIO AVATAR
			Route::get('avatar/{id}', 'RH\FuncionarioAvatarController@getAvatarGrande');
			// Route::delete('avatar/{id}', 'RH\FuncionarioAvatarController@destroy');

		});

	});

	// GRUPO DE ROTAS - lOG
	Route::prefix('log')->group(function(){

		// GRUPO DE ROTAS - LOG ACESSOS
		Route::resource('acessos', 'Log\LogsLoginController', ['only' => ['index']]);
		Route::prefix('acessos')->group(function(){ 
			Route::post('search', 'Log\LogsLoginController@search');    
			Route::post('download/{type}', 'Log\LogsLoginController@downloadExcel'); 
		});

	});

	// GRUPO DE ROTAS - BASE DE CONHECIMENTO
	Route::prefix('base-de-conhecimento')->group(function(){
		
		Route::resource('categoria', 'BaseDeConhecimento\CategoriaController', ['only' => [ 'index', 'show', 'store', 'update' ]]);
		Route::post('conteudo/conteudo_tipo', 'BaseDeConhecimento\ConteudoController@setConteudoTipo');
		Route::put('conteudo/ordem', 'BaseDeConhecimento\ConteudoController@setOrdem');
		Route::post('conteudo/{id}/rascunho', 'BaseDeConhecimento\ConteudoController@getRascunho');
		Route::delete('conteudo/{id}/confirmRascunho', 'BaseDeConhecimento\RascunhoController@confirmar');
		Route::delete('conteudo/{id}/deleteRascunho', 'BaseDeConhecimento\RascunhoController@destroy');
		Route::resource('conteudo', 'BaseDeConhecimento\ConteudoController',['only' => [ 'store', 'show', 'destroy', 'update' ]]);
		Route::get('tags/{id}/rascunho', 'BaseDeConhecimento\TagController@getRascunho');
		Route::resource('tags', 'BaseDeConhecimento\TagController', ['only' => [ 'store', 'show', 'destroy' ]]);	
		Route::resource('colaboradores', 'BaseDeConhecimento\ColaboradorController', ['only' => [ 'store', 'destroy' ]]);
		Route::resource('restricao', 'BaseDeConhecimento\RestricaoController', ['only' => [ 'store', 'destroy' ]]);
		Route::resource('mensagens', 'BaseDeConhecimento\MensagemController', ['only' => [ 'index', 'show' ]]);		
		Route::post('mensagens/getMensagens', 'BaseDeConhecimento\MensagemController@getMensagens');
		Route::post('mensagens/getMensagens/{id}', 'BaseDeConhecimento\MensagemController@getMensagem');
		Route::post('mensagens/setResposta', 'BaseDeConhecimento\MensagemController@setResposta');

		Route::get('dashboard', 'BaseDeConhecimento\DashboardController@index');
		Route::post('dashboard_ajax', 'BaseDeConhecimento\DashboardController@ajax_conteudo');
		Route::post('dashboardDownload/', 'BaseDeConhecimento\DashboardController@dashboardDownload');

		Route::get('exportacoes', 'BaseDeConhecimento\PublicacaoExportacaoController@index');
		Route::post('exportacoes/download', 'BaseDeConhecimento\PublicacaoExportacaoController@downloadExcel');
		
		Route::prefix('publicacao')->group(function(){
			Route::post('favoritos', 'BaseDeConhecimento\PublicacaoFavoritosController@store');
			Route::delete('favoritos/{id}', 'BaseDeConhecimento\PublicacaoFavoritosController@destroy');
			Route::post('mensagem', 'BaseDeConhecimento\MensagemController@store');

			//RECOMENDACOES			
			Route::post('recomendacao', 'BaseDeConhecimento\PublicacaoRecomendacaoController@store');
			Route::get('recomendacaoVisualizada/{id}', 'BaseDeConhecimento\PublicacaoRecomendacaoController@recomendacaoVisualizada');
			Route::get('{id}/recomendados', 'BaseDeConhecimento\PublicacaoRecomendacaoController@showRecomendados');

		});

		Route::get('publicacao/{id}', 'BaseDeConhecimento\PublicacaoController@getPublicacao');
		Route::post('publicacao/{id}/imagem_capa', 'BaseDeConhecimento\PublicacaoController@changeImagemCapa');
		Route::post('publicacao/{id}/datas', 'BaseDeConhecimento\PublicacaoController@setPublicacaoDatas');		
		
		//GRUPO DE ROTAS PUBLICAÇÔES
		Route::prefix('publicacoes')->group(function(){
			Route::get('categoria/{id}', 'BaseDeConhecimento\PublicacaoController@getPublicacoes');
			Route::get('categoria_ajax/{id}', 'BaseDeConhecimento\PublicacaoController@getPublicacoesCategoria');			
			Route::get('novas', 'BaseDeConhecimento\PublicacaoController@chamaViewNovasPublicacoes');
			Route::post('novas', 'BaseDeConhecimento\PublicacaoController@getNovasPublicacoes');
			Route::post('busca_ajax', 'BaseDeConhecimento\PublicacoesBuscaController@getBuscaPublicacoes');
			Route::post('setBuscaTratada', 'BaseDeConhecimento\PublicacoesBuscaController@setBuscaTratada');
			Route::get('tag/{tag}', 'BaseDeConhecimento\PublicacoesBuscaController@tag');
			Route::get('busca/{busca}', 'BaseDeConhecimento\PublicacoesBuscaController@busca');
			Route::get('colaborador/{busca}', 'BaseDeConhecimento\PublicacoesBuscaController@colaborador');
			// Route::get('img-conteudo-miniatura/{id}', 'BaseDeConhecimento\ConteudoController@getImagemConteudoMiniatura');
			// Route::get('img-conteudo-original/{id}', 'BaseDeConhecimento\ConteudoController@getImagemConteudoOriginal');
			// Route::get('img-publicacao/{id}', 'BaseDeConhecimento\PublicacaoController@getImagemPublicacao');
			Route::get('favoritos', 'BaseDeConhecimento\PublicacaoFavoritosController@index');
			Route::post('favoritos', 'BaseDeConhecimento\PublicacaoFavoritosController@showAll');
			Route::get('proximas', 'BaseDeConhecimento\PublicacaoProximasPublicacoesController@index');
			Route::post('proximas', 'BaseDeConhecimento\PublicacaoProximasPublicacoesController@showAll');
			Route::delete('historico-delete/{id}', 'BaseDeConhecimento\PublicacaoHistoricoController@destroy');			
			Route::get('{id}/historico', 'BaseDeConhecimento\PublicacaoHistoricoController@show');
			Route::post('{id}/historico', 'BaseDeConhecimento\PublicacaoHistoricoController@getDadosPublicacaoHistorico');
			Route::get('{id}/rascunho', 'BaseDeConhecimento\PublicacaoController@rascunho');
			Route::post('{id}/rascunho/novo', 'BaseDeConhecimento\PublicacaoController@setRascunho');

		});
		Route::resource('publicacoes', 'BaseDeConhecimento\PublicacaoController',['only' => [ 'index', 'store', 'edit', 'show', 'update' ]]);
		
	});

	// ROTAS DE TICKETS
	Route::prefix('ticket')->group(function(){
		Route::post('search', 'Ticket\TicketController@search');    
		Route::post('searchProprio', 'Ticket\TicketController@searchProprio');    
		Route::post('pesquisaSubcategoria', 'Ticket\TicketController@pesquisaSubcategoria');
		Route::post('download/{type}', 'Ticket\TicketController@downloadExcel');
		Route::post('interacao', 'Ticket\TicketController@setInteracao');
		Route::post('prioridade/{id}', 'Ticket\TicketController@getPrioridades');
		Route::post('status/{id}', 'Ticket\TicketController@getStatus');
		Route::post('solicitante/{id}', 'Ticket\TicketController@getSolicitantes');
		Route::post('categoria/{id}', 'Ticket\TicketController@getCategorias');
		Route::post('campos_adicionais/{id}', 'Ticket\TicketController@getCamposAdicionais');
		Route::get('proprio', 'Ticket\TicketController@indexProprio');
		Route::get('proprio/{id}', 'Ticket\TicketController@showProprio');
		Route::post('acaoMacro/{id}', 'Ticket\TicketController@getAcaoMacro');
		Route::post('executaMacro', 'Ticket\TicketController@executaMacro');
		Route::resource('imagem', 'Ticket\TicketImagemController', ['only' => [ 'store', 'show', 'destroy' ]]);
		Route::get('imagemDados/{id}', 'Ticket\TicketImagemController@imagemDados');
		Route::get('dashboard', 'Ticket\TicketDashboardController@index');
		Route::post('dashboard/{id}', 'Ticket\TicketDashboardController@show');
		Route::post('dashboard/download/{grafico}', 'Ticket\TicketDashboardController@downloadExcel'); 
	});
	Route::resource('ticket', 'Ticket\TicketController');



	Route::prefix('monitoramento')->group(function(){
		
		Route::post('servidores/search', 'Monitoramento\ServidorController@search');    
		Route::get('servidores', 'Monitoramento\ServidorController@index');
		Route::get('servico', 'Monitoramento\ServicoController@index');
		Route::get('servico/status/{status}', 'Monitoramento\ServicoController@setStatus');
		
		Route::post('itens/search', 'Monitoramento\ItemController@search');    
		Route::get('itens', 'Monitoramento\ItemController@index');
		Route::post('itens/coleta-manual', 'Monitoramento\ItemController@coletaManual');
		
		Route::get('itens/chamados-crm/{projeto}', 'Monitoramento\ItemController@getChamados');
		Route::get('itens/chamados-crm-info/{chamado}', 'Monitoramento\ItemController@getInfoChamado');
		Route::post('itens/chamados/', 'Monitoramento\ItemController@setChamado');
		Route::delete('itens/chamados/{id}', 'Monitoramento\ItemController@unsetChamado');

		Route::post('servico/servico_ajax', 'Monitoramento\ServicoController@getServico');			

		Route::resource('parada-programada', 'Monitoramento\ParadaProgramadaController', ['only' => [ 'store', 'update', 'destroy' ]]);
				
		Route::post('servidores/download/{type}', 'Monitoramento\ServidorController@exportar');

		Route::get('servidores/dados_ajax/{id}', 'Monitoramento\ServidorController@abaDados');
		Route::get('servidores/configuracao_ajax/{id}', 'Monitoramento\ServidorController@abaConfiguracao');
		Route::get('servidores/itens_monitorados_ajax/{id}', 'Monitoramento\ServidorController@abaItensMonitorados');
		Route::resource('servidores', 'Monitoramento\ServidorController', ['only' => [ 'edit', 'update' ]]);
		Route::get('servidores/{id}/{aba?}', 'Monitoramento\ServidorController@show');

		Route::post('servidores/alerta_ajax/{id}/search', 'Monitoramento\ServidorController@abaAlerta');
		Route::post('servidores/chamado_vinculado_ajax/{id}/search', 'Monitoramento\ServidorController@abaVinculoChamados');
		Route::post('servidores/aba_alerta_dados/{id}', 'Monitoramento\ServidorController@abaAlertaDados');
		Route::post('servidores/aba_chamados_vinculados_dados/{id}', 'Monitoramento\ServidorController@abaVinculoChamadosDados');
	    Route::post('servidores/parada_ajax/{id}/search', 'Monitoramento\ServidorController@abaParadaProgramada');
		Route::post('servidores/aba_parada_dados/{id}', 'Monitoramento\ServidorController@abaParadaDados');

	});



});




