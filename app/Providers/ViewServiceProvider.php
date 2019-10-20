<?php

namespace App\Providers;

use App\Models\Core\Menu;
use App\Models\Configuracao\Sistema\PoliticaSenha;
use App\Models\Configuracao\Sistema\Parametro;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        \View()->composer('auth.login', function ($view){
            $complementoLogo = Parametro::select('valor_texto')
                ->where( [['ativo', '=', true], ['nome', '=', 'COMPLEMENTO_LOGO']])
                ->get()
                ->first();
            $complementoLogo = ( ( empty($complementoLogo) || empty($complementoLogo->valor_texto) ) ? '' : Str::lower('-'.$complementoLogo->valor_texto) );

            $view->with([
                'logoLogin' => '/img/logo-login'.$complementoLogo.'.png',
            ]);
        });
        
        \View()->composer('core.menu', function ($view){
            $menus = new Menu();
            $view->with(['new_menu_usuario' => json_encode($menus->listarMenu())]);
      
        });

        \View()->composer('core.header', function ($view){
            $politicaSenha = PoliticaSenha::select('*')
                    ->where('ativo', '=', true)
                    ->get();

            $view->with(['politicaSenha' => json_encode($politicaSenha[0]->getInformacoes())]);
        });

         \View()->composer(['core.header', 'home.home'], function ($view){
            
            $tempo = Parametro::select('valor_numero')
                ->where( [['ativo', '=', true], ['nome', '=', 'TEMPO_COLETA_NOTIFICACAO']])
                ->get()
                ->first()->valor_numero;
            
            $can = \Auth::user()->can( 'NOTIFICACAO_VISUALIZAR' )? 'true':'false';
            
            $view->with(['notificacao_tempo' => $tempo, 'notificacao_can' => $can]);
        });


        \View()->composer('core.header', function ($view){
            $complementoLogo = Parametro::select('valor_texto')
                ->where( [['ativo', '=', true], ['nome', '=', 'COMPLEMENTO_LOGO']])
                ->get()
                ->first();
        
            $complementoLogo = ( ( empty($complementoLogo) || empty($complementoLogo->valor_texto) ) ? '' : Str::lower('-'.$complementoLogo->valor_texto) );

            $view->with([
                'logoMini' => '/img/logo-mini'.$complementoLogo.'.png',
                'logoLg' => '/img/logo-lg'.$complementoLogo.'.png',
            ]);
        });

        \View()->composer('core.footer', function ($view){
            $versaoSistema = Parametro::select('valor_texto')
                ->where( [['ativo', '=', true], ['nome', '=', 'VERSAO']])
                ->get();
    
            $view->with(['versaoSistema' => $versaoSistema->first(), 'copyright_data' => date('Y')]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
