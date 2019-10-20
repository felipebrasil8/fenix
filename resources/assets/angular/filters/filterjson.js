(function(){

    'use strict';

    angular.module('app')
    .filter('filterJSON', function () {
        return function(value){
            return ''+value+'';
        };
    });

    angular.module('app')
    .filter('filterDataNascimento', function () {
        return function(value){
            return value.replace('dt nascimento', 'Data de nascimento');
        };
    });

    angular.module('app')
    .filter('range', function() {
  		return function(input, total) {
    		total = parseInt(total);

    		for (var i=0; i<total; i++) {
      			input.push(i);
    		}

    		return input;
    	};
    });

    angular.module('app')
    .filter('ucfirst', function() {
        return function(value){
            var ignore = ['de', 'da', 'das', 'do', 'dos'];
            value = value.toLowerCase();
            value = value.split(' ');
    
            for (var i in value) {
                if (ignore.indexOf(value[i]) === -1) {
                    value[i] = value[i].charAt(0).toUpperCase() + value[i].slice(1);
                }
            }
            
            return value.join(' ');
        };
    });

    angular.module('app')
    .filter('mask', ['MaskService', function(MaskService) {
        return function(text, mask) {
            if( text != '' && text != null && text != undefined )
            {
                var maskService = MaskService.create();

                if (!angular.isObject(mask)) {
                  mask = { mask: mask }
                }

                maskService.generateRegex(mask);

                return maskService.getViewValue(text).withDivisors();
            }
            else
            {
                return '';
            }    
        };
    }]);

    angular.module('app')
    .filter('filterBooleano', function () {
        return function(value){
            if( value == true )
            {
                return 'VERDADEIRO';
            }
            else if( value == false )
            {
                return 'FALSO';
            }
        };
    });


    angular.module('app')
    .filter('strLimit', ['$filter', function($filter) {
        return function(input, limit)
        {
            if (! input) return;
            if (input.length <= limit)
            {
                return input;
            }

            return $filter('limitTo')(input, limit) + '...';
        };
    }]);

})();