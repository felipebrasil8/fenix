(function(){

	'use strict';

	angular.module('app')
	.controller('visualizarTicketCtrl', ['$scope', '$uibModal', '$window', 'TicketFactory', 'Upload', '$location', 'serviceTicketCtrl', '$q', '$filter', 
        function($scope, $uibModal, $window, TicketFactory, Upload, $location, serviceTicketCtrl, $q, $filter)
	{	
        var ticket_id;
        var acao;
        var acao_id;
        var date_min;
    
		var init = function()
		{
			ticket_id = _ticket_id;

            acao_id = '';

            $scope.acao = {
                responsavel: '',
                solicitante: '',
                categoria: '',
                subcategoria: '',
            }

            $scope.rating = 0;
            $scope.ratings = [{
                current: 1,
                max: 5
            }];

            $scope.verificaTab();

            $scope.date_min = $filter('date')(new Date(), 'dd-MM-yyyy HH:mm');

		};

        $scope.interacao = function( )
        {
            var ticket = setTicket();

            ticket.interno = false;

            setInteracao( ticket );          
        }

        $scope.notaInterna = function( )
        {
            var ticket = setTicket();

            ticket.interno = true;

            setInteracao( ticket );            
        }

        var setTicket = function( )
        {
            $scope.form.$valid = false;

            var ticket = {};

            ticket.id = ticket_id;
            ticket.mensagem = $scope.ticket.mensagem.toUpperCase();

            return ticket;
        }

        var setInteracao = function( ticket )
        {
            $scope.success = false;
            $scope.errors = false;
            
            $scope.form.$valid = false;   

            TicketFactory.interacaoTicket( ticket )                 
            .then(function mySuccess(response)
            {
                if( response.data.status == true )
                {
                    $window.location.reload();
                }

            }, function(error){

                $scope.errors = error.data;             

            }).finally(function() {                                        

                $scope.form.$valid = false;   

            });
        }  

        $scope.escondeErro = function(){            
            $scope.errors = '';
        }

        $scope.keyPressDescricaoImagem = function(event){
            if (event.which === 13){
                $scope.confirmarImagem();
                event.preventDefault();
            }
        }

        $scope.confirmarImagem = function(){

            $scope.errors = {};
            $scope.formImagem.$valid = false;

            if ($scope.imagem.file && $scope.imagem.texto) {
                Upload.upload({
                    url: '/ticket/imagem',
                    data: {imagem: $scope.imagem.file, ticket_id: $scope.imagem.ticket_id, texto: $scope.imagem.texto},
                    headers: {'Content-Type': 'multipart/form-data'}
                }).then(function (response) {

                    $scope.success = response.data.mensagem;                    
                    $scope.imagem.file = false;
                    $scope.formImagem.$valid = true;
                    $scope.imagem.texto = '';

                    setTimeout(function(){
                        $window.location.reload();
                    }, 2000);

                }, function (error) {
                    $scope.errors = error.data;                    
                });

            }else if( !$scope.imagem.file ){

                var obj = {
                    'errors' : {
                        '0': 'Por favor selecione uma imagem'
                    }
                };

                $scope.errors = obj;
                
            }else{

                var obj = {
                    'errors' : {
                        '0': 'Campo descrição é obrigatório'
                    }
                };

                $scope.errors = obj;
            }
        }

        $scope.verificaTab = function(){

            var url = $window.location.href;
            var res = url.split("#!#");

            if( res[1] == 'interacao' ){
                $scope.tab = 1;
            }else if( res[1] == 'imagem' ){
                $scope.tab = 2;
            }else if( res[1] == 'historico' ){
                $scope.tab = 3;
            }else if( res[1] == 'acao' ){
                $scope.tab = 4;
                $scope.campos_macro = '';
            }else{
                $scope.tab = 1;
            }

        }

        $scope.showModal = function(ticket_id){

            $scope.carregandoModalImagemDados = true;

            var getImagemTicket = TicketFactory.getImagemTicketDados( ticket_id )
            .then(function mySuccess(response) {
                return response.data;
            });

            $q.all([getImagemTicket]).then(function( result ){
                serviceTicketCtrl.modalImagem( result );
                $scope.carregandoModalImagemDados = false;
            });
        }

        $scope.acaoMacro = function( macro_id )
        {
            $scope.success = false;
            $scope.errors = false;

            acao_id = macro_id;
            
            var obj = {'ticket_id': ticket_id };

            TicketFactory.getAcaoMacro( macro_id, obj )                 
            .then(function mySuccess(response)
            {
                if( response.data.status == true )
                {
                    $scope.tab = 4;

                    $scope.campos_macro = response.data.acao;
    
                    /**
                     * Seleciona os select que veio do banco se houver
                     */
                    $scope.acao_responsavel = response.data.responsavel;
                    $scope.acao.responsavel = $scope.acao_responsavel[setSelect(response.data.responsavel, $scope.acao_responsavel)];

                    $scope.acao_status = response.data.ticket_status;
                    if ( $scope.acao_status != '' ){
                        var index = setSelect(response.data.ticket_status, $scope.acao_status);
                        if( index == 0 )
                        {
                            $scope.acao.status = $scope.acao_status[index].id;
                        }
                        else
                        {
                            $scope.acao.status = $scope.acao_status[index].id.toString();
                        }
                    }
                    
                    $scope.acao.dt_previsao = response.data.dt_previsao;

                    $scope.acao_solicitante = response.data.solicitantes;
                    $scope.acao.solicitante = $scope.acao_solicitante[setSelect(response.data.solicitantes, $scope.acao_solicitante)];

                    $scope.acao_categoria = response.data.categoria;
                    $scope.acao.categoria = $scope.acao_categoria[setSelect(response.data.categoria, $scope.acao_categoria)];

                    $scope.acao_subcategoria = response.data.subcategoria;
                    $scope.acao.subcategoria = $scope.acao_subcategoria[setSelect(response.data.subcategoria, $scope.acao_subcategoria)];
                    
                    $scope.acao.assunto = response.data.assunto;

                    $scope.acao_prioridade = response.data.prioridade;
                    $scope.acao.prioridade = $scope.acao_prioridade[setSelect(response.data.prioridade, $scope.acao_prioridade)];

                    $scope.acao.dt_notificacao = response.data.dt_notificacao;

                    if( $scope.campos_macro.interacao == true && $scope.campos_macro.nota_interna == true ){
                        $scope.acao.interacao = false;
                    }

                    if( $scope.campos_macro.interacao == true && $scope.campos_macro.nota_interna == false ){
                        $scope.acao.interacao = false;
                    }

                    if( $scope.campos_macro.interacao == false && $scope.campos_macro.nota_interna == true ){
                        $scope.acao.interacao = true;
                    }
                }

            }, function(error){

                $scope.tab = 4;
                $scope.errors = error.data;             

            });
        }

        $scope.mostrarSubcategoria = function ( id_categoria )
        {
            if( id_categoria != undefined )
            {
                TicketFactory.pesquisaSubcategoria( id_categoria )              
                .then(function mySuccess(response)
                {
                    $scope.acao_subcategoria = response.data.subcategoria;
                    $scope.acao.subcategoria = $scope.acao_subcategoria[0];
                },
                function(error)
                {
                    $scope.errors = error.data;
                });
            }
            else
            {
                $scope.acao.subcategoria = '';
            }
        }

        $scope.executaMacro = function(  )
        {
            $scope.success = false;
            $scope.errors = false;
            
            $scope.formMacro.$valid = false;

            $scope.acao.ticket_id = ticket_id;
            $scope.acao.acao_id = acao_id;
            //$scope.acao.texto_interacao = $filter('uppercase')($scope.acao.texto_interacao);

            if( $scope.campos_macro.campos.indexOf('campos_adicionais') > 0 )
            {
                inserirCamposAdicionais();
            }

            if( $scope.campos_macro.campos.indexOf('avaliacao') > 0 )
            {
                $scope.acao.avaliacao = $scope.ratings[0].current;
            }
            
            var obj = {'acoes_macro': $scope.acao };

            TicketFactory.executaMacro( obj )
            .then(function mySuccess( response )
            {
                $scope.success = response.data.mensagem;
                $scope.iconeCarregando = true;

                if( response.data.status == true )
                {
                    setTimeout(function(){
                        var url = $window.location.href;
                        var res = url.split("#!#");
                        $window.location.href = res[0]+'#interacao';
                        $window.location.reload();
                    }, 2000);
                }

            }, function(error){

                $scope.errors = error.data;             

            });
        }

        var setSelect = function( response, lista )
        {
            var indexObj = 0;

            angular.forEach(response, function(value) {
                if( value.selected == 'selected' )
                {
                    indexObj = lista.indexOf( value )
                }
            });

            return indexObj;
        }

        var inserirCamposAdicionais = function( acao )
        {
            var campos_adicionais = angular.element( document.getElementsByName("campo_adicional") );
            $scope.acao.campo_adicional = [];
            /* 
             * Percorre o array de campos adicionais
             * Cria um objeto com o id : campo.attributes['data-campo-id'].value
             * e
             * seu valor : campo.value.toUpperCase()
             * para enviar via ajax
             */
            angular.forEach(campos_adicionais, function(campo) {
                var obj_campo = { 'id': campo.attributes['data-campo-id'].value, 'value': campo.value.toUpperCase() };
                $scope.acao.campo_adicional.push( obj_campo );
            });
        }

		//inicializa controller cadastrarAcessoCtrl
		init();	        
	}]);
})();