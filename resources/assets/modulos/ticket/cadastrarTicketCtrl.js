(function(){

	'use strict';

	angular.module('app')
	.controller('cadastrarTicketCtrl', ['$scope', '$uibModal', '$window', 'TicketFactory', '$sce', 'serviceTicketCtrl',function($scope, $uibModal, $window, TicketFactory, $sce, serviceTicketCtrl)
	{			
		var departamento = _departamentos;
		var init = function()
		{
			$scope.success = "";
			$scope.subcategoria = '';
			$scope.departamentos = departamento;
			$scope.oldValue ='';
		};

		/**
		 * Irá carregar o campo solicitante conforme o departamento escolhido
		 */
		$scope.mostraSolicitante = function(){
			
			if( $scope.ticket.departamento ){
				
				TicketFactory.getSolicitante($scope.ticket.departamento.id)
				.then(function mySuccess(response)
				{ 	
					var selecionado =  response.data.filter(function(selecionado) {
						return selecionado.selected == 'selected';
					});

					$scope.solicitantes = response.data;
					
					var index = $scope.solicitantes.map(function (item) {
						return item.id;
					}).indexOf(selecionado[0].id);
					
					$scope.ticket.solicitante = $scope.solicitantes[index];					
					
				}, function(error){
					$scope.solicitantes = null;
				}).finally(function() {
					//
				});
			}
		}

		/**
		 * Irá carregar o campo prioridade conforme o departamento escolhido
		 */
		$scope.mostraPrioridade = function(){

			if( $scope.ticket.departamento ){
				
				TicketFactory.getPrioridade($scope.ticket.departamento.id)
				.then(function mySuccess(response)
		        { 	
					var selecionado =  response.data.filter(function(selecionado) {
						return selecionado.selected == 'selected';
					});

					$scope.prioridades = response.data;

					var index = $scope.prioridades.map(function (item) {
						return item.id;
					}).indexOf(selecionado[0].id);
					
					$scope.ticket.prioridade = $scope.prioridades[index];
		        	
		        }, function(error){
					$scope.prioridades = null;
		        }).finally(function() {
					//
			    });
			}
		}

		$scope.verificaDescricaoPrioridade = function(id){

			var obj = $scope.prioridades.filter( function( obj ) {
  	 			return obj.id == id;
			});

			if( !(obj[0].descricao == null || obj[0].descricao == '') ){
				$scope.dicaModalPrioridades( $scope, obj[0].descricao );
			}
		}

		/**
		 * Irá carregar o campo categoria conforme o departamento escolhido
		 */
		$scope.mostraCategoria = function(){

			if( $scope.ticket.departamento ){

				TicketFactory.getCategoria($scope.ticket.departamento.id)
				.then(function mySuccess(response)
				{ 						
					$scope.categorias = response.data;
					
				}, function(error){					
					$scope.categorias = null;
				}).finally(function() {
					//
				});
			}
		}

		$scope.renderHtml = function(html_code)
		{
			return $sce.trustAsHtml(html_code);
		};

		/**
		 * Irá carregar o campo adicional conforme o departamento escolhido
		 */
		$scope.mostraCamposAdicionais = function(){

			if( $scope.ticket.departamento ){

				TicketFactory.getCamposAdicionais($scope.ticket.departamento.id)
				.then(function mySuccess(response)
				{ 	
					$scope.campos_adicionais = response.data;
					
				}, function(error){
					$scope.campos_adicionais = null;
				}).finally(function() {
					//
				});
			}
		}

		$scope.mostrarSubcategoria = function ( id_categoria )
		{
			if( id_categoria != undefined )
			{
				TicketFactory.pesquisaSubcategoria( id_categoria )     			
		        .then(function mySuccess(response)
		        { 	
					$scope.dicaModal = response.data.categoriaDica[0].dicas;
		        	$scope.subcategoria = response.data.subcategoria;
		        	
		        	if( $scope.dicaModal != '' )
		        	{
		        		$scope.abreModalDica( $scope.dicaModal );
		        	}

		        }, function(error){
					$scope.errors = error.data;
		        }).finally(function() {
		        	//
			    });
			}
			else
			{
				$scope.subcategoria = '';
			}
		}

		$scope.dicaSubcategoria = function ( subcategoria )
		{
			if( subcategoria != undefined && subcategoria.dicas != '' )
			{
				$scope.abreModalDica( subcategoria.dicas );
			}
		}

		$scope.alterarDepartamento = function(oldValue){

			if( ( $scope.ticket.solicitante || $scope.ticket.prioridade || $scope.ticket.assunto || $scope.ticket.categoria || $scope.ticket.descricao) && oldValue != undefined ){
				serviceTicketCtrl.modal( oldValue, $scope);
			}else{
				$scope.mostraSolicitante();
				$scope.mostraPrioridade();
				$scope.mostraCategoria();
				$scope.mostraCamposAdicionais();
			}			
		}

		$scope.cadastrar = function( ticket )
		{			
			$scope.form.$valid = false;			
			$scope.success = false;
            $scope.errors = false;
			/*
			 * Pegas os campos via javascript e passa para o angular
			 * para enviar via ajax
			 */			
			var campos_adicionais = angular.element( document.getElementsByName("campo_adicional") );			
			ticket.campo_adicional = [];			

			angular.forEach(campos_adicionais, function(campo) {                
                var obj = { 'id': campo.attributes['data-campo-id'].value, 'value': campo.value };
                ticket.campo_adicional.push( obj );
            });
			
			TicketFactory.cadastrarTicket( ticket )
	        .then(function mySuccess(response) {
				$scope.success = response.data.mensagem;
	        	$scope.url = ""+response.data.id+"";
	        	$window.location.href = response.data.id;
	        }, function(error){
				$scope.errors = error.data;				
	        }).finally(function() {
		    	//
			});			
		};

		$scope.abreModalDica = function ( dicaModal )
		{
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'todasDicaModal.html',
                controller: function ( $scope )
                {
                	$scope.dicaModal = nl2br(dicaModal);

                    $scope.continuarTicket = function () {
                        modalInstance.close();
                    }

                    $scope.pesquisarTicket = function () {
                        $window.location.href = '/ticket';
                    }
                }
            });
        }        

        $scope.dicaModalPrioridades = function( $scope, dicaModal ){

        	var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                templateUrl: 'dicaModalPrioridades.html',
                scope: $scope,
                controller: function ( $scope )
                {
                	$scope.dicaModal = nl2br(dicaModal);

                    $scope.continuarTicket = function () {
                        modalInstance.close();                        
                    }

                    $scope.checarMenorPrioridade = function () {
                        $scope.mostraPrioridade();
                        modalInstance.close();                        
                    }
                }
            });	
        }

        function nl2br (str, is_xhtml)
        {
		    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
		    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
		}
		
		//inicializa controller cadastrarAcessoCtrl
		init();	        
	}]);
})();