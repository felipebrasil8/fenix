<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMonitoramentoServidoresStatusSprint30 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $status = DB::table('monitoramento_servidores_status')->where('identificador', 'FORA')->first();
        
        if(!$status){
            DB::table('monitoramento_servidores_status')->insert(
              [ 
                    'nome' => 'FORA',
                    'identificador' => 'FORA',
                    'alarme' => true,
                    'peso' => 0,
                    'cor' => '#941708',
                    'icone' => 'fa-ban',
                    'filtro_servidor' => true,
                    'filtro_item' => true,
                ]
            );
            
        }

        
        DB::table('monitoramento_servidores_status')->update(
            [
                'filtro_servidor' => true,
                'filtro_item' => true,
            ]
        );

        DB::table('monitoramento_servidores_status')->where('identificador', '=', 'ALERTA')->update(
            [
                'filtro_servidor' => false,
            ]
        );
    



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        
    }
}
