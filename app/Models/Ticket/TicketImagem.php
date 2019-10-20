<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketImagem extends Model
{
    use SoftDeletes;

    protected $table = 'tickets_imagem';
    protected $dates = ['deleted_at'];
    protected $fillable = array('ticket_id', 'texto', 'imagem', 'imagem_miniatura', 'usuario_inclusao_id');
    protected $softDelete = true;
    public    $timestamps = false;

    public function getNomeAttribute($value) {
	    return strtoupper($value);
	}

	public function getCreatedAtAttribute($value) {
	    return date_format(date_create( $value ), 'd/m/Y H:i:s');	    
	}
	
}