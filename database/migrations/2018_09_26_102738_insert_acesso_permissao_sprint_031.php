<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertAcessoPermissaoSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu_id_itens = DB::table('menus')->where('url', '/monitoramento/itens')->first()->id;
        $menu_id_servidores = DB::table('menus')->where('url', '/monitoramento/servidores')->first()->id;
        
        // CRIA A PERMISSÃO E VÍNCULA AO MENU
        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id_itens,
                'descricao' => 'VISUALIZAR PARADA PROGRAMADA',
                'identificador' => 'MONITORAMENTO_PARADA_PROGRAMADA_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_itens,
                'descricao' => 'CADASTRAR PARADA PROGRAMADA',
                'identificador' => 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR'
            ],
            [
                'menu_id' => $menu_id_itens,
                'descricao' => 'EXECUTAR COLETA MANUAL',
                'identificador' => 'MONITORAMENTO_COLETA_MANUAL_EXECUTAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'EDITAR SERVIDORES',
                'identificador' => 'MONITORAMENTO_SERVIDORES_EDITAR'
            ],
            //ABAS 
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'ABA DE DADOS',
                'identificador' => 'MONITORAMENTO_SERVIDORES_ABA_DADOS_SERVIDORES_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'ABA DE CONFIGURAÇÃO DO SERVIDOR',
                'identificador' => 'MONITORAMENTO_SERVIDORES_ABA_CONFIGURACAO_SERVIDORES_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'ABA DE HISTÓRICO DE ALERTA',
                'identificador' => 'MONITORAMENTO_SERVIDORES_ABA_HISTORICO_ALERTA_SERVIDORES_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'ABA DE ITENS MONITORADOS',
                'identificador' => 'MONITORAMENTO_SERVIDORES_ABA_ITENS_MONITORADOS_SERVIDORES_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'ABA DE CHAMADOS VÍNCULADOS',
                'identificador' => 'MONITORAMENTO_SERVIDORES_ABA_VINCULO_CHAMADOS_SERVIDORES_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'ABA DE PARADAS PROGRAMADAS',
                'identificador' => 'MONITORAMENTO_SERVIDORES_ABA_PARADAS_PROGRAMADAS_SERVIDORES_VISUALIZAR'
            ],
            // EXPORTAR            
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'EXPORTAR DADOS DOS SERVIDORES',
                'identificador' => 'MONITORAMENTO_SERVIDORES_EXPORTAR'
            ],
            // CHAMADOS VINCULADOS
            [
                'menu_id' => $menu_id_itens,
                'descricao' => 'VISUALIZAR CHAMADO VINCULADO',
                'identificador' => 'MONITORAMENTO_CHAMADOS_VINCULADOS_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_itens,
                'descricao' => 'CADASTRAR CHAMADO VINCULADO',
                'identificador' => 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR'
            ]
        ]);

        // CRIA O ACESSO 
        DB::table('acessos')->insert([
            [
                'nome' => 'MONITORAMENTO - CONFIGURAR PARADA PROGRAMADA',
            ],
            [
                'nome' => 'MONITORAMENTO - EXECUTAR COLETA MANUAL',
            ],
            //EXPORTAR SERVIDORES            
            [
                'nome' => 'MONITORAMENTO - EXPORTAR SERVIDORES',
            ],
            // CHAMADOS VINCULADOS
            [
                'nome' => 'MONITORAMENTO - CONFIGURAR CHAMADOS VINCULADOS',
            ],
            //EDITAR SERVIDORES
            [
                'nome' => 'MONITORAMENTO - EDITAR SERVIDORES',
            ]
        ]);



        //ACESSO - PARADA PROGRAMADA   
        $acesso_configurar_parada_programada = DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR PARADA PROGRAMADA')->get()->first()->id;
        //ACESSO - COLETA MANUAL
        $acesso_coleta_manual = DB::table('acessos')->where('nome', 'MONITORAMENTO - EXECUTAR COLETA MANUAL')->get()->first()->id;
        //ACESSO - SERVIDORES
        $acesso_visualizar_servidores = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR MONITORAMENTO DOS SERVIDORES')->get()->first()->id;        
        //ACESSO - SERVIDORES EXPORTAR
        $acesso_exportar_servidores = DB::table('acessos')->where('nome', 'MONITORAMENTO - EXPORTAR SERVIDORES')->get()->first()->id;    
        //ACESSO - CHAMADOS VINCULADOS
        $acesso_chamados_vinculados = DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR CHAMADOS VINCULADOS')->get()->first()->id;             
        //ACESSO - SERVIDORES EDITAR
        $acesso_editar_servidores = DB::table('acessos')->where('nome', 'MONITORAMENTO - EDITAR SERVIDORES')->get()->first()->id;        
        

        //PERMISSAO - PARADA PROGRAMADA   
        $permissao_parada_programada_visualizar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_PARADA_PROGRAMADA_VISUALIZAR')->get()->first()->id;                
        $permissao_parada_programada_cadastrar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR')->get()->first()->id;
        //PERMISSAO - COLETA MANUAL
        $permissao_coleta_manual_executar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_COLETA_MANUAL_EXECUTAR')->get()->first()->id;
        //PERMISSAO - SERVIDORES EDITAR
        $permissao_servidores_editar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_EDITAR')->get()->first()->id;
        //PERMISSAO - ABA
        $permissao_servidores_aba_dados = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_DADOS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_configuracao = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_CONFIGURACAO_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_historico = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_HISTORICO_ALERTA_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_itens_monitorados = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_ITENS_MONITORADOS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_vinculo = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_VINCULO_CHAMADOS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_paradas_programadas = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_PARADAS_PROGRAMADAS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        // PERMISSAO - SERVIDORES EXPORTAR
        $permissao_servidores_exportar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_EXPORTAR')->get()->first()->id;
        //PERMISSAO - CHAMADOS VINCULADOS
        $permissao_chamados_vinculados_visualizar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_CHAMADOS_VINCULADOS_VISUALIZAR')->get()->first()->id;
        $permissao_chamados_vinculados_cadastrar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR')->get()->first()->id;
      
        
        // APÓS ACESSO E PERMISSÃO SEREM CRIADOS, ELES SÃO VÍNCULADOS
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_configurar_parada_programada,
                'permissao_id' => $permissao_parada_programada_visualizar
            ],  
            [
                'acesso_id' => $acesso_configurar_parada_programada,
                'permissao_id' => $permissao_parada_programada_cadastrar
            ],  
            [
                'acesso_id' => $acesso_coleta_manual,
                'permissao_id' => $permissao_coleta_manual_executar
            ],
            //ABAS
            [
                'acesso_id' => $acesso_editar_servidores,
                'permissao_id' => $permissao_servidores_editar
            ],
            [
                'acesso_id' => $acesso_visualizar_servidores,
                'permissao_id' => $permissao_servidores_aba_dados
            ],
            [
                'acesso_id' => $acesso_visualizar_servidores,
                'permissao_id' => $permissao_servidores_aba_configuracao
            ],
            [
                'acesso_id' => $acesso_visualizar_servidores,
                'permissao_id' => $permissao_servidores_aba_historico
            ],
            [
                'acesso_id' => $acesso_visualizar_servidores,
                'permissao_id' => $permissao_servidores_aba_itens_monitorados
            ],
            [
                'acesso_id' => $acesso_visualizar_servidores,
                'permissao_id' => $permissao_servidores_aba_vinculo
            ],
            [
                'acesso_id' => $acesso_visualizar_servidores,
                'permissao_id' => $permissao_servidores_aba_paradas_programadas
            ],
            [
                'acesso_id' => $acesso_exportar_servidores,
                'permissao_id' => $permissao_servidores_exportar
            ],
            [
                'acesso_id' => $acesso_chamados_vinculados,
                'permissao_id' => $permissao_chamados_vinculados_visualizar
            ],
            [
                'acesso_id' => $acesso_chamados_vinculados,
                'permissao_id' => $permissao_chamados_vinculados_cadastrar
            ]
        ]);

        //CRIA PERFIL        
        DB::table('perfis')->insert([
            [
                'nome' => 'SUPORTE AO CLIENTE'
            ],
            [
                'nome' => 'SUPORTE AO PRODUTO'
            ]
        ]);

        $perfil_id_cliente = DB::table('perfis')->where('nome', 'SUPORTE AO CLIENTE')->get()->first()->id;
        $perfil_id_produto = DB::table('perfis')->where('nome', 'SUPORTE AO PRODUTO')->get()->first()->id;
        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        
        // VÍNCULA O ACESSO CRIADO AOS PERFIS 
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_configurar_parada_programada,
                'perfil_id' => $perfil_id_cliente
            ],
            [
                'acesso_id' => $acesso_configurar_parada_programada,
                'perfil_id' => $perfil_id_produto
            ],            
            [
                'acesso_id' => $acesso_configurar_parada_programada,
                'perfil_id' => $perfil_id_admin
            ],
            [
                'acesso_id' => $acesso_coleta_manual,
                'perfil_id' => $perfil_id_cliente
            ],
            [
                'acesso_id' => $acesso_coleta_manual,
                'perfil_id' => $perfil_id_produto
            ],
            [
                'acesso_id' => $acesso_coleta_manual,
                'perfil_id' => $perfil_id_admin                
            ],
            [
                'acesso_id' => $acesso_exportar_servidores,
                'perfil_id' => $perfil_id_admin                
            ],
            [
                'acesso_id' => $acesso_chamados_vinculados,
                'perfil_id' => $perfil_id_admin                
            ],
            [
                'acesso_id' => $acesso_chamados_vinculados,
                'perfil_id' => $perfil_id_produto                
            ],
            [
                'acesso_id' => $acesso_chamados_vinculados,
                'perfil_id' => $perfil_id_cliente                
            ],
            [
                'acesso_id' => $acesso_editar_servidores,
                'perfil_id' => $perfil_id_cliente                
            ],
            [
                'acesso_id' => $acesso_editar_servidores,
                'perfil_id' => $perfil_id_produto                
            ],
            [
                'acesso_id' => $acesso_editar_servidores,
                'perfil_id' => $perfil_id_admin                
            ]
        ]);

        DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR MONITORAMENTO DOS SERVIDORES')->update(['nome' => 'MONITORAMENTO - VISUALIZAR SERVIDORES' ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
        //BUSCO ACESSOS
        $acesso_configurar_parada_programada = DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR PARADA PROGRAMADA')->get()->first()->id;
        $acesso_coleta_manual = DB::table('acessos')->where('nome', 'MONITORAMENTO - EXECUTAR COLETA MANUAL')->get()->first()->id;        

        $acesso_exportar_servidores = DB::table('acessos')->where('nome', 'MONITORAMENTO - EXPORTAR SERVIDORES')->get()->first()->id;        
        //ACESSO - CHAMADOS VINCULADOS
        $acesso_chamados_vinculados = DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR CHAMADOS VINCULADOS')->get()->first()->id;                  
        $acesso_editar_servidores = DB::table('acessos')->where('nome', 'MONITORAMENTO - EDITAR SERVIDORES')->get()->first()->id;              
                
        //DELETA VÍNCULOS CRIADOS COM PERFIS NESTA MIGRATION
        DB::table('acesso_perfil')->where('acesso_id', $acesso_coleta_manual)->delete();
        DB::table('acesso_perfil')->where('acesso_id', $acesso_configurar_parada_programada)->delete();
        DB::table('acesso_perfil')->where('acesso_id', $acesso_exportar_servidores)->delete();
        DB::table('acesso_perfil')->where('acesso_id', $acesso_chamados_vinculados)->delete();
        DB::table('acesso_perfil')->where('acesso_id', $acesso_editar_servidores)->delete();

        //DELETA PERFIS CRIADOS NESTA MIGRATION
        DB::table('perfis')->where('nome', 'SUPORTE AO PRODUTO')->delete();
        DB::table('perfis')->where('nome', 'SUPORTE AO CLIENTE')->delete();

        // DELETA VÍNCULOS DE ACESSO E PERMISSÃO        
        $permissao_parada_programada_visualizar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_PARADA_PROGRAMADA_VISUALIZAR')->get()->first()->id;
        $permissao_parada_programada_cadastrar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR')->get()->first()->id;
        $permissao_coleta_manual_executar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_COLETA_MANUAL_EXECUTAR')->get()->first()->id;
        
        $acesso_configurar_parada_programada = DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR PARADA PROGRAMADA')->get()->first()->id;
        $acesso_coleta_manual = DB::table('acessos')->where('nome', 'MONITORAMENTO - EXECUTAR COLETA MANUAL')->get()->first()->id;
        
        //PERMISSAO - SERVIDORES EDITAR
        $permissao_servidores_editar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_EDITAR')->get()->first()->id;

        //PERMISSAO - ABA
        $permissao_servidores_aba_dados = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_DADOS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_configuracao = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_CONFIGURACAO_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_historico = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_HISTORICO_ALERTA_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_itens_monitorados = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_ITENS_MONITORADOS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_vinculo = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_VINCULO_CHAMADOS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        $permissao_servidores_aba_paradas_programadas = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_PARADAS_PROGRAMADAS_SERVIDORES_VISUALIZAR')->get()->first()->id;
        //PERMISSAO - EXPORTAR
        $permissao_servidores_exportar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_EXPORTAR')->get()->first()->id;
        //PERMISSAO - CHAMADOS VINCULADOS
        $permissao_chamados_vinculados_visualizar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_CHAMADOS_VINCULADOS_VISUALIZAR')->get()->first()->id;
        $permissao_chamados_vinculados_cadastrar = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR')->get()->first()->id;
        
        DB::table('acesso_permissao')->where('acesso_id', $acesso_coleta_manual)->delete();
        DB::table('acesso_permissao')->where('acesso_id', $acesso_configurar_parada_programada)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_editar)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_aba_dados)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_aba_configuracao)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_aba_historico)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_aba_itens_monitorados)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_aba_vinculo)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_aba_paradas_programadas)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidores_exportar)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_chamados_vinculados_visualizar)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_chamados_vinculados_cadastrar)->delete();
        
        //DELETA ACESSOS
        DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR PARADA PROGRAMADA')->delete();
        DB::table('acessos')->where('nome', 'MONITORAMENTO - EXECUTAR COLETA MANUAL')->delete();
        DB::table('acessos')->where('nome', 'MONITORAMENTO - EXPORTAR SERVIDORES')->delete();
        DB::table('acessos')->where('nome', 'MONITORAMENTO - CONFIGURAR CHAMADOS VINCULADOS')->delete();
        DB::table('acessos')->where('nome', 'MONITORAMENTO - EDITAR SERVIDORES')->delete();

        //DELETA PERMISSÕES
        DB::table('permissoes')->where('descricao', 'CADASTRAR PARADA PROGRAMADA')->where('identificador', 'MONITORAMENTO_PARADA_PROGRAMADA_CADASTRAR')->delete();
        DB::table('permissoes')->where('descricao', 'VISUALIZAR PARADA PROGRAMADA')->where('identificador', 'MONITORAMENTO_PARADA_PROGRAMADA_VISUALIZAR')->delete();
        DB::table('permissoes')->where('descricao', 'EXECUTAR COLETA MANUAL')->where('identificador', 'MONITORAMENTO_COLETA_MANUAL_EXECUTAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_EDITAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_DADOS_SERVIDORES_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_CONFIGURACAO_SERVIDORES_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_HISTORICO_ALERTA_SERVIDORES_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_ITENS_MONITORADOS_SERVIDORES_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_VINCULO_CHAMADOS_SERVIDORES_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_ABA_PARADAS_PROGRAMADAS_SERVIDORES_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_EXPORTAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_CHAMADOS_VINCULADOS_VISUALIZAR')->delete();
        DB::table('permissoes')->where('identificador', 'MONITORAMENTO_CHAMADOS_VINCULADOS_CADASTRAR')->delete();

        DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR SERVIDORES')->update(['nome' => 'MONITORAMENTO - VISUALIZAR MONITORAMENTO DOS SERVIDORES' ]);

    }
}
