(function(){

    'use strict';

    angular.module('app')
    .service('serviceFuncionarioCtrl', function() 
    {
        var count_permissoes = 0;
        var permissoes_marcadas = {};

        this.keyPress = function ( event )
        {
            var keys = {
                'backspace': 8, 'del': 46,
                '0': 48, '1': 49, '2': 50, '3': 51, '4': 52, '5': 53, '6': 54, '7': 55, '8': 56, '9': 57
            };

            for (var index in keys)
            {
                if (!keys.hasOwnProperty(index)) continue;
                if (event.charCode == keys[index] || event.keyCode == keys[index])
                {
                    return true;
                }
            }

            return false;
        }
    });

})();