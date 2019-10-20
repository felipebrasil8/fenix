<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMonitoramentoServidoresStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('monitoramento_servidores_status')->insert(
            [
                [
                    'nome' => 'OK',
                    'identificador' => 'OK',
                    'alarme' => false,
                    'peso' => 3,
                    'cor' => '#00a65a',
                    'icone' => 'fa-check-circle',
                ],
                [
                    'nome' => 'ALERTA',
                    'identificador' => 'ALERTA',
                    'alarme' => true,
                    'peso' => 2,
                    'cor' => '#f39c12',
                    'icone' => 'fa-exclamation-triangle',
                ],
                [
                    'nome' => 'CRÍTICO',
                    'identificador' => 'CRITICO',
                    'alarme' => true,
                    'peso' => 1,
                    'cor' => '#dd4b39',
                    'icone' => 'fa-times-circle',
                ]
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
        DB::table('monitoramento_servidores_status')->where('identificador', 'OK')->delete();
        DB::table('monitoramento_servidores_status')->where('identificador', 'ALERTA')->delete();
        DB::table('monitoramento_servidores_status')->where('identificador', 'CRITICO')->delete();
    }
}
