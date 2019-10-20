(function(){

    'use strict';

    angular.module('app')
    .service('serviceHeaderHomeCtrl', ['$filter', function( $filter ) 
    {
        /*
         * Variáveis
         */
        var notificacoes;

        var notificacoes_nao_lidas;

        /*
         * Métodos set
         */
        this.setNotificacoes = function ( notificacoes )
        {
            this.notificacoes = notificacoes;
        }

        this.setNotificacoesNaoLidas = function ( notificacoes_nao_lidas )
        {
            this.notificacoes_nao_lidas = notificacoes_nao_lidas;
        }

        /*
         * Métodos get
         */
        this.getNotificacoes = function ( )
        {
            return this.notificacoes;
        }

        this.getNotificacoesNaoLidas = function ( )
        {
            return this.notificacoes_nao_lidas;
        }

        this.getNotificacoesNaoLidasStr = function ( )
        {
            var count_nao_lidas_str = '';

            if( this.notificacoes_nao_lidas == 0 )
            {
                count_nao_lidas_str = 'Nenhuma notificação.';
            }
            else if( this.notificacoes_nao_lidas == 1 )
            {
                count_nao_lidas_str = 'Você possui ' + this.notificacoes_nao_lidas + ' notificação não lida.';
            }
            else
            {
                count_nao_lidas_str = 'Você possui ' + this.notificacoes_nao_lidas + ' notificações não lidas.';
            }

            return count_nao_lidas_str;
        }

        /*
         * Função que aplica o estilo e formata a data de todos funcionarios e aniversariantes
         */
        this.getFormataAniversariantes = function ( funcionarios )
        {
            angular.forEach(funcionarios, function(value) 
            {
                value.imagem = 'margin: 0 auto; margin-top: 12px; height: 84px; width: 70px;';
                value.text_class = '';
                value.dt_nascimento = $filter('date')(value.dt_nascimento, 'dd/MM');

                if( value.aniversariante == true )
                {
                    value.dt_nascimento = 'PARABÉNS !';
                    value.imagem = 'margin: 0 auto; margin-top: 0px; height: 96px; width: 80;';
                    value.text_class = 'text-orange';
                }
            });

            return funcionarios;
        }
    }]);

})();