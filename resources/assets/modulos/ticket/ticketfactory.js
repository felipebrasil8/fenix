(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('TicketFactory', ['$http', function($http){

        var _pesquisarTicket = function(param){           
            return $http.post("/ticket/search", param)            
        };
        var _pesquisarTicketProprio = function(param){           
            return $http.post("/ticket/searchProprio", param)            
        };
    
        var _cadastrarTicket = function( param ){
            return $http.post("/ticket", param);
        };

        var _pesquisaSubcategoria = function( param ){
            return $http.post("/ticket/pesquisaSubcategoria", param)      
        };

        var _atualizarTicket = function(param){                
            return $http.put("/ticket/"+param.id, param)        
        };

        var _interacaoTicket = function( param ){
            return $http.post("/ticket/interacao", param);
        };

        var _getStatus = function( id ){
            return $http.post("/ticket/status/"+id);
        };

        var _getSolicitante = function( id ){
            return $http.post("/ticket/solicitante/"+id);
        };

        var _getPrioridade = function( id ){
            return $http.post("/ticket/prioridade/"+id);
        };

        var _getCategoria = function( id ){
            return $http.post("/ticket/categoria/"+id);
        };

        var _getCamposAdicionais = function( id ){
            return $http.post("/ticket/campos_adicionais/"+id);
        };

        //imagem ticket        
        var _getImagemTicketDados = function( id ){
            return $http.get("/ticket/imagemDados/"+id);
        };
        var _getImagemTicket = function( id ){
            return $http.get("/ticket/imagem/"+id);
        };

        var _destroyImagemTicket = function( id ){
            return $http.delete("/ticket/imagem/"+id);
        };

        var _getAcaoMacro = function( id, param ){
            return $http.post("/ticket/acaoMacro/"+id, param);
        };

         var _executaMacro = function( param ){
            return $http.post("/ticket/executaMacro", param);
        };
       
        return {
            cadastrarTicket: _cadastrarTicket,
            pesquisaSubcategoria: _pesquisaSubcategoria,
            atualizarTicket: _atualizarTicket,
            pesquisarTicket: _pesquisarTicket,
            interacaoTicket: _interacaoTicket,
            getStatus: _getStatus,
            getSolicitante: _getSolicitante,
            getPrioridade: _getPrioridade,
            getCategoria: _getCategoria,
            getCamposAdicionais: _getCamposAdicionais,
            pesquisarTicketProprio: _pesquisarTicketProprio,
            getImagemTicket: _getImagemTicket,
            getImagemTicketDados: _getImagemTicketDados,
            destroyImagemTicket: _destroyImagemTicket,
            getAcaoMacro: _getAcaoMacro,
            executaMacro: _executaMacro,
        };

    }]);
})();