<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewTicketsSprint27 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS tickets_view");
        DB::statement("CREATE OR REPLACE VIEW tickets_view AS
            SELECT 
                t.id, t.codigo, t.created_at, t.updated_at, t.assunto, t.avaliacao, t.descricao, t.dt_resolucao, t.dt_fechamento, t.dt_previsao, 
                t.dt_notificacao, ui.nome AS usuario_inclusao, ua.nome AS usuario_alteracao, us.id AS usuario_solicitante_id, us.nome AS usuario_solicitante_nome, fs.nome AS funcionario_solicitante_nome, 
                fs.avatar AS funcionario_solicitante_avatar, ur.id AS usuario_responsavel_id, ur.nome AS usuario_responsavel_nome, fr.nome AS funcionario_responsavel_nome, fr.avatar AS funcionario_responsavel_avatar, 
                tcp.nome AS categoria_nome_pai, tcp.descricao AS categoria_descricao_pai, tcp.id AS categoria_id_pai,tcf.id AS categoria_id_filho, tcf.nome AS categoria_nome_filho, tcf.descricao AS categoria_descricao_filho, ts.id AS status_id,
                ts.nome AS status_nome, ts.cor AS status_cor, ts.ordem AS status_ordem, ts.aberto AS status_aberto, tp.id AS prioridade_id, tp.nome AS prioridade_nome, tp.cor AS prioridade_cor, tp.prioridade AS prioridade_ordem, d.id AS departamento_id, d.nome AS departamento_nome
            FROM tickets AS t
            INNER JOIN usuarios AS ui ON ui.id = t.usuario_inclusao_id
            LEFT JOIN usuarios AS ua ON ua.id = t.usuario_alteracao_id
            INNER JOIN usuarios AS us ON us.id = t.usuario_solicitante_id
            INNER JOIN funcionarios AS fs ON fs.id = us.funcionario_id
            LEFT JOIN usuarios AS ur ON ur.id = t.usuario_responsavel_id
            LEFT JOIN funcionarios AS fr ON fr.id = ur.funcionario_id
            INNER JOIN departamentos AS d ON d.id = t.departamento_id
            INNER JOIN tickets_categoria AS tcf ON tcf.id = t.tickets_categoria_id
            INNER JOIN tickets_categoria AS tcp ON tcp.id = tcf.ticket_categoria_id
            INNER JOIN tickets_status AS ts ON ts.id = t.tickets_status_id
            INNER JOIN tickets_prioridade AS tp ON tp.id = t.tickets_prioridade_id
            ORDER BY id;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
