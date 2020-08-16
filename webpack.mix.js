const { mix } = require('laravel-mix');


/* ======================================================================================== */
/* 	ARQUIVOS JAVA SCRIPT 																	*/
/* ======================================================================================== */

/* JavaScript base para o sistema */
mix.combine([
	'resources/assets/lib/jquery.min.js',
	'resources/assets/lib/bootstrap.min.js',
	'resources/assets/lib/admin-lte/js/admin-lte.min.js',
], 'public/js/base.js' );

/* JavaScript para funcionamento do Angular */
mix.combine([
	'resources/assets/lib/angularjs.min.js',
	'resources/assets/lib/angular-animate.min.js',
	'resources/assets/lib/angular-sanitize.min.js',
	'resources/assets/lib/angular-ui-bootstrap.min.js',
	'resources/assets/lib/angular-cookies.min.js',
	'resources/assets/lib/angular-messages.min.js',
], 'public/js/angular.js' );

/* JavaScript do sistema */
mix.js([
	'resources/assets/app-angular.js',
], 'public/js/app-angular.js' );

/* JavaScript das bibliotecas */
mix.combine([
	'resources/assets/lib/datapicker/moment-with-locales.min.js',
	'resources/assets/lib/datapicker/angular-moment-picker.min.js',
	'resources/assets/lib/validator.min.js',
	'resources/assets/lib/pace.min.js',
	'resources/assets/lib/ngMask.min.js',
	'resources/assets/lib/slick-carousel/slick-carousel/slick/slick.min.js',
	'resources/assets/lib/slick-carousel/angular-slick/slick.min.js',
	'resources/assets/lib/ng-file-upload/dist/ng-file-upload-shim.min.js',
	'resources/assets/lib/ng-file-upload/dist/ng-file-upload.min.js',
	'resources/assets/lib/web-notification/simple-web-notification/web-notification.js',
	'resources/assets/lib/web-notification/angular-web-notification/angular-web-notification.js',
	'resources/assets/lib/angular-drag-and-drop-lists/angular-drag-and-drop-lists.js',	
], 'public/js/libs.js' );



/* ======================================================================================== */
/* 	ARQUIVOS ANGULAR PADRÃO PARA TODAS PÁGINAS												*/
/* ======================================================================================== */

mix.js([
	'resources/assets/angular/directives/modalconfirm.js',
	'resources/assets/angular/directives/msgsuccess.js',
	'resources/assets/angular/directives/msgerror.js',
	'resources/assets/angular/directives/checklist-model.js',
	'resources/assets/angular/directives/migalhas-de-pao.js',
	'resources/assets/angular/directives/customChange.js',
	'resources/assets/angular/directives/acesso-permissao.js',
	'resources/assets/angular/directives/starRating.js',
], 'public/js/angular/directives.js' );

mix.js([
	'resources/assets/angular/controllers/modalConfirmCtrl.js',
	'resources/assets/angular/controllers/migalhaDePaoCtrl.js',
	'resources/assets/angular/controllers/acessoPermissaoCtrl.js',
], 'public/js/angular/controllers.js' );

mix.js([
	'resources/assets/angular/filters/filterjson.js',
], 'public/js/angular/filters.js' );

mix.js([
	'resources/assets/modulos/core/listarMenuCtrl.js',
	'resources/assets/modulos/core/headerfactory.js',
	'resources/assets/modulos/core/headerCtrl.js',
	'resources/assets/modulos/core/serviceHeaderHomeCtrl.js',
	'resources/assets/modulos/core/serviceModalAniversarioCtrl.js',
	'resources/assets/modulos/core/serviceModalNotificacaoCtrl.js',
	'resources/assets/modulos/ticket/serviceTicketCtrl.js',	
], 'public/js/modulos/core.js' );



/* ======================================================================================== */
/* 	ARQUIVOS ANGULAR ESPECÍFICO DE CADA MÓDULO												*/
/* ======================================================================================== */

mix.js([
	'resources/assets/modulos/home/homeCtrl.js',
], 'public/js/modulos/home.js' );

mix.js([
	'resources/assets/modulos/configuracao/usuario/usuariofactory.js',
	'resources/assets/modulos/configuracao/usuario/cadastrarUsuarioCtrl.js',
	'resources/assets/modulos/configuracao/usuario/visualizarUsuarioCtrl.js',
	'resources/assets/modulos/configuracao/usuario/listarUsuarioCtrl.js',
	'resources/assets/modulos/configuracao/usuario/editarUsuarioCtrl.js',
	'resources/assets/modulos/configuracao/sistema/parametro/parametrofactory.js',
	'resources/assets/modulos/configuracao/sistema/parametro/cadastrarParametroCtrl.js',
	'resources/assets/modulos/configuracao/sistema/parametro/listarParametroCtrl.js',
	'resources/assets/modulos/configuracao/sistema/parametro/editarParametroCtrl.js',
	'resources/assets/modulos/configuracao/ticket/categoriaFactory.js',
	'resources/assets/modulos/configuracao/ticket/categoriaCtrl.js',
	'resources/assets/modulos/configuracao/ticket/serviceCategoriasCtrl.js',
	'resources/assets/modulos/configuracao/ticket/acaoCtrl.js',
	'resources/assets/modulos/configuracao/ticket/acaoFactory.js',
	'resources/assets/modulos/configuracao/ticket/serviceAcaoCtrl.js',
	'resources/assets/modulos/configuracao/ticket/gatilhoCtrl.js',
	'resources/assets/modulos/configuracao/ticket/gatilhoFactory.js',
	'resources/assets/modulos/configuracao/ticket/serviceGatilhoCtrl.js',
	'resources/assets/modulos/configuracao/ticket/camposAdicionaisFactory.js',
	'resources/assets/modulos/configuracao/ticket/camposAdicionaisCtrl.js',
	'resources/assets/modulos/configuracao/ticket/serviceCamposAdicionaisCtrl.js',
	'resources/assets/modulos/configuracao/ticket/serviceExcluirCamposAdicionaisCtrl.js',
	'resources/assets/modulos/configuracao/ticket/serviceModalCampoAdicionalPrioridadeCtrl.js',
	'resources/assets/modulos/configuracao/ticket/serviceModalCampoAdicionalStatusCtrl.js',
], 'public/js/modulos/configuracao.js' );

mix.js([
	'resources/assets/modulos/rh/area/areafactory.js',
	'resources/assets/modulos/rh/area/cadastrarAreaCtrl.js',
	'resources/assets/modulos/rh/area/listarAreaCtrl.js',
	'resources/assets/modulos/rh/area/editarAreaCtrl.js',
	'resources/assets/modulos/rh/cargo/cargofactory.js',
	'resources/assets/modulos/rh/cargo/cadastrarCargoCtrl.js',
	'resources/assets/modulos/rh/cargo/listarCargoCtrl.js',
	'resources/assets/modulos/rh/cargo/editarCargoCtrl.js',
	'resources/assets/modulos/rh/departamento/departamentofactory.js',
	'resources/assets/modulos/rh/departamento/cadastrarDepartamentoCtrl.js',
	'resources/assets/modulos/rh/departamento/listarDepartamentoCtrl.js',
	'resources/assets/modulos/rh/departamento/editarDepartamentoCtrl.js',	
], 'public/js/modulos/rh.js' );

mix.js([
	'resources/assets/modulos/logs/logfactory.js',
	'resources/assets/modulos/logs/listarLogsCtrl.js',
], 'public/js/modulos/logs.js' );

mix.js([
	'resources/assets/modulos/ticket/ticketfactory.js',
	'resources/assets/modulos/ticket/cadastrarTicketCtrl.js',
	'resources/assets/modulos/ticket/visualizarTicketCtrl.js',
	'resources/assets/modulos/ticket/listarTicketCtrl.js',
	'resources/assets/modulos/ticket/editarTicketCtrl.js',
	'resources/assets/modulos/ticket/listarProprioTicketCtrl.js',
	'resources/assets/modulos/ticket/dashboardTicketCtrl.js',
	'resources/assets/modulos/ticket/dashboardfactory.js',
], 'public/js/modulos/ticket.js' );


/* ======================================================================================== */
/* 	ARQUIVOS VUE ESPECÍFICO DE CADA MÓDULO													*/
/* ======================================================================================== */

 mix.js('resources/assets/vue/modulos/core/menu/menu.js', 'public/js/menu.js')
 	.js('resources/assets/vue/modulos/basedeconhecimento/base_de_conhecimento.js', 'public/js/base_de_conhecimento.js')
	.js('resources/assets/vue/modulos/configuracao/acesso/acesso.js', 'public/js/acesso.js')
	.js('resources/assets/vue/modulos/rh/funcionario/funcionario.js', 'public/js/funcionario.js')
	.js('resources/assets/vue/modulos/configuracao/perfil/perfil.js', 'public/js/perfil.js')
	.js('resources/assets/vue/modulos/configuracao/sistema/politica_senha/politicaSenha.js', 'public/js/politicaSenha.js')
	.js('resources/assets/vue/modulos/monitoramento/servidores/servidores.js', 'public/js/servidores.js')
	.js('resources/assets/vue/modulos/monitoramento/servico/servico.js', 'public/js/servico.js')
	.js('resources/assets/vue/modulos/monitoramento/itens/itens.js', 'public/js/itens.js')
	.extract(['vue']);

/* ======================================================================================== */
/* 	ARQUIVOS CSS 																			*/
/* ======================================================================================== */

/* CSS base para o sistema */
mix.styles([
	'resources/assets/css/bootstrap.min.css',
	'resources/assets/lib/admin-lte/css/admin-lte.min.css',
	'resources/assets/lib/admin-lte/css/skin-blue.min.css',
	'resources/assets/lib/admin-lte/css/skin-blue-custom.css',
], 'public/css/base.css');

/* CSS para bibliotecas */
mix.styles([
	'resources/assets/css/pace.min.css',
	'resources/assets/lib/datapicker/angular-moment-picker.min.css',
	'resources/assets/lib/slick-carousel/slick-carousel/slick/slick.css',
	'resources/assets/lib/slick-carousel/slick-carousel/slick/slick-theme.css',
], 'public/css/libs.css');

/* CSS personalizado do sistema */
mix.styles([
	'resources/assets/css/core.css',
], 'public/css/core.css');



/* ======================================================================================== */
/* 	JUNÇÃO DE ARVIVOS JS E CSS																*/
/* ======================================================================================== */

mix.combine([
	'public/js/base.js',
	'public/js/angular.js',
	'public/js/app-angular.js',
	'public/js/libs.js',
	'public/js/angular/directives.js',
	'public/js/angular/controllers.js',
	'public/js/angular/filters.js',
	'public/js/modulos/core.js',
], 'public/js/all.js' );

mix.combine([
	'public/css/base.css',
	'public/css/libs.css',
	'public/css/core.css',
], 'public/css/all.css' );



/* ======================================================================================== */
/* 	CÓPIA DE DEMAIS ARQUIVOS (FONT, IMAGEM, HTML)											*/
/* ======================================================================================== */

mix.copyDirectory('resources/assets/fonts/', 'public/fonts/');
mix.copyDirectory('resources/assets/angular/templates/', 'public/templates/');
mix.copy('resources/assets/lib/slick-carousel/slick-carousel/slick/fonts/slick.woff', 'public/css/fonts/slick.woff');
mix.copy('resources/assets/lib/slick-carousel/slick-carousel/slick/ajax-loader.gif', 'public/css/ajax-loader.gif');
mix.copy('resources/assets/js/jquery.slimscroll.min.js', 'public/js/jquery.slimscroll.min.js');
mix.copy('resources/assets/js/charts.min.js', 'public/js/charts.min.js');

/* ======================================================================================== */
/* 	FUNCIONAMENTO DO BROWSERSYNC															*/
/* ======================================================================================== */

// webpack.mix.js
mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery']
});

//mix.browserSync(process.env.MIX_SENTRY_DSN_PUBLIC);

