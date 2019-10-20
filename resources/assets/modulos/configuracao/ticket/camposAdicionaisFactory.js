(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('CamposAdicionaisFactory', ['$http', function($http){

        var _addTipoCampo = function( tipo ){
            return $http.post("campo_adicional/tipo/" + tipo);
        };

        var _getCampoAdicional = function( id ){
            return $http.post("/configuracao/ticket/campo_adicional/" + id);
        };

        var _excluirCampoAdicional = function( id ){
            return $http.delete("/configuracao/ticket/campo_adicional/" + id);
        };

        //PRIORIDADE
        var _getPrioridade = function( id ){
            return $http.post("ticket/campo_adicional/departamento/"+id+"/prioridade");
        };

        var _addCampoAdicionalPrioridade = function( param ){//ok            
            return $http.post("/configuracao/ticket/campo_adicional/prioridade", param);            
        };

        var _excluirCampoAdicionalPrioridade = function( id ){
            return $http.delete("/configuracao/ticket/campo_adicional/prioridade/"+id);
        };

        //STATUS
        var _getStatus = function( id ){
            return $http.post("ticket/campo_adicional/departamento/"+id+"/status");
        };

        var _addCampoAdicionalStatus = function( param ){//ok            
            return $http.post("/configuracao/ticket/campo_adicional/status", param);
        };

        var _excluirCampoAdicionalStatus = function( id ){
            return $http.delete("/configuracao/ticket/campo_adicional/status/"+id);
        };
        
        return {
            addTipoCampo: _addTipoCampo,
            excluirCampoAdicional: _excluirCampoAdicional,
            getCampoAdicional: _getCampoAdicional,

            //prioridade
            getPrioridade: _getPrioridade,
            addCampoAdicionalPrioridade: _addCampoAdicionalPrioridade,
            excluirCampoAdicionalPrioridade: _excluirCampoAdicionalPrioridade,

            //status
            getStatus: _getStatus,
            addCampoAdicionalStatus: _addCampoAdicionalStatus,
            excluirCampoAdicionalStatus: _excluirCampoAdicionalStatus

        };

    }]);
    
})();