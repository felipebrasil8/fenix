<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;

class MonitoramentoServidoresColetas extends Model
{
    protected $table = 'monitoramento_servidores_coletas';

    protected $fillable = array(
        'monitoramento_servidores_id',
        'itens',
        'configuracoes',
        'monitoramento_servidores_status_id',
        'tempo_de_resposta',
        'coleta_manual'
    );

    /**
     * [deleteMonitorServidoresIColetas Deleta o registro do servidor do Banco de dados do FENIX ]
     * @param  [type] $id [ID da tabela MONITORAMENTO SERVIDOR COLETAS]
     */
    public function deleteMonitorServidoresColetas( $id ){
		  MonitoramentoServidoresColetas::where( 'monitoramento_servidores_id' , $id)->delete();
    }
}
