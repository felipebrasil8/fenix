(function(){            

    'use strict';

    //factories
    angular.module('app')
    .factory('HeaderFactory', ['$http', function($http) {

        var _mudarSenhaFunc = function(param){
            return $http.post("/configuracao/usuario/" + param.id + "/password", param);
        };

        var _atualizarVisualizarSenha = function(param){                
            return $http.put("/configuracao/usuario/" + param.id + "/solicitarNovaSenha", param)        
        };

        var _pegafuncionarios = function(){                
            return $http.post("/rh/funcionario/getFuncionariosAniversario")        
        };

        var _notificacoes = function(){                
            return $http.post("/core/notificacao/getNotificacoes")        
        };

        var _setNotificaoVisualizada = function(param){                
            return $http.put("/core/notificacao/" + param.id + "/setNotificaoVisualizada", param)        
        };
    
        return {
            mudarSenhaFunc: _mudarSenhaFunc,
            atualizarVisualizarSenha: _atualizarVisualizarSenha,
            pegafuncionarios: _pegafuncionarios,
            notificacoes: _notificacoes,
            setNotificaoVisualizada: _setNotificaoVisualizada,
        };
    }]);
})();