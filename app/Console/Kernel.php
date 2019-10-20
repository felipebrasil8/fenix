<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Configuracao\Sistema\Parametro;
use App\Services\MonitoramentoServidoresClientesService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // CHAMA O SERVIÇO QUE EXECUTA O MONITORAMENTO DOS CLIENTES
        $monitoramentoServidoresClientesService = new MonitoramentoServidoresClientesService;
        $schedule->call(function () use ( $monitoramentoServidoresClientesService ) {
            $monitoramentoServidoresClientesService->chamaApiAtualizaAlarmesServidorCliente();
        })->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
