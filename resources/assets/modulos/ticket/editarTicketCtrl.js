(function(){

    'use strict';

    angular.module('app')

    .controller('editarTicketCtrl', ['$scope', '$filter', 'TicketFactory', function($scope, $filter, TicketFactory) {
        
        var subcategoria = _subcategoria;
        var ticket_dt_previsao = _ticket_dt_previsao;

        var init = function()
        {
            $scope.ticket = {};

            $scope.subcategoria = subcategoria;
            $scope.subcategoria.unshift({id: '', nome: '', dicas: '', selected: ''});

            var indexObj = 0;
            angular.forEach($scope.subcategoria, function(value) {
               
                if ( value.selected != '' )
                {
                    indexObj = $scope.subcategoria.indexOf( value );
                }
            });

            $scope.ticket.subcategoria = $scope.subcategoria[indexObj];
            
            if( ticket_dt_previsao != '' )
            {
                $scope.ticket.dt_previsao  = $filter('date')(ticket_dt_previsao, 'dd/MM/yyyy');
            }
        };

        init();

        $scope.customChange = function( event )
        {
            var id_categoria = angular.element( document.querySelector( '#categoria' ) )[0].value;

            if( id_categoria != undefined )
            {
                TicketFactory.pesquisaSubcategoria( id_categoria )              
                .then(function mySuccess(response)
                {   
                    $scope.subcategoria = response.data.subcategoria;
                    $scope.subcategoria.unshift({id: '', nome: '', dicas: ''});
                    $scope.ticket.subcategoria = $scope.subcategoria[0];

                }, function(error){

                    $scope.errors = error.data;

                }).finally(function() {                                        

                    $scope.form.$valid = true;   

                });
            }
            else
            {
                $scope.subcategoria = '';
            }
        };
        
        $scope.atualizar = function( ticket )
        {
            /*
             * Pegas os campos via javascript e passa para o angular
             * para enviar via ajax
             */
            ticket.status = angular.element( document.querySelector( '#status' ) )[0].value;
            ticket.solicitante = angular.element( document.querySelector( '#solicitante' ) )[0].value;
            ticket.prioridade = angular.element( document.querySelector( '#prioridade' ) )[0].value;

            ticket.assunto = angular.element( document.querySelector( '#assunto' ) )[0].value;
            ticket.categoria = angular.element( document.querySelector( '#categoria' ) )[0].value;
            // subcategoria já está no ticket

            var campos_adicionais = angular.element( document.getElementsByName("campo_adicional") );

            ticket.responsavel = angular.element( document.querySelector( '#responsavel' ) )[0].value;

            ticket.campo_adicional = [];
            /* 
             * Percorre o array de campos adicionais
             * Cria um objeto com o id : campo.attributes['data-campo-id'].value
             * e
             * seu valor : campo.value.toUpperCase()
             * para enviar via ajax
             */
            angular.forEach(campos_adicionais, function(campo) {
                var obj = { 'id': campo.attributes['data-campo-id'].value, 'value': campo.value.toUpperCase() };
                ticket.campo_adicional.push( obj );
            });

            ticket.assunto = ticket.assunto.toUpperCase();
            ticket.mensagem =  ticket.mensagem.toUpperCase();

            ticket.id = _ticket_id;
                           
            $scope.success = false;
            $scope.errors = false;

            TicketFactory.atualizarTicket( ticket )
            .then(function mySuccess(response) {    
                
                $scope.ticket.mensagem = "";
                $scope.success = response.data.mensagem;
                $scope.url = "../"+response.data.id+"";
                
            }, function(error){
                
                $scope.errors = error.data;
            });
        };
        
        $scope.cancelar = function( ticket )
        {
            location.href = '../'+_ticket_id;
        }

    }]);

})();