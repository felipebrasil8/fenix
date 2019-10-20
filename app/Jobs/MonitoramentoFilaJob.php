<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Core\Icone;


class MonitoramentoFilaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // private $monitoramentoServidores;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( 
                                    // MonitoramentoServidores $monitoramentoServidores
            )
    {
         // $this->monitoramentoServidores = $monitoramentoServidores;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            
            sleep(5);
            
            Icone::insert([

                'nome'  => 'teste',
                'icone' => 'teste',
                'unicode' => 'teste', 

            ]);

        //$this->monitoramentoServidores->getMonitoramentoServidoresServico()
    }
}
