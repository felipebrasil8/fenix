<?php

namespace App\Models\Configuracao\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acao extends Model
{
    use SoftDeletes;
    
    protected $table = 'tickets_acao';
    
    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = array(
        'nome',
        'descricao',
        'departamento_id',
        'solicitante_executa',
        'responsavel_executa',
        'trata_executa',
        'status_atual',
        'status_novo',
        'icone',
        'campos',
        'nota_interna',
        'interacao',
        'usuario_alteracao_id',
    );
}