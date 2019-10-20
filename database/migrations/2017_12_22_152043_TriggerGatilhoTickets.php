<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerGatilhoTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::statement("
            CREATE TRIGGER triger_gatilho_tickets AFTER INSERT OR UPDATE OF tickets_status_id, usuario_responsavel_id, dt_notificacao ON tickets FOR EACH ROW EXECUTE PROCEDURE gatilho_tickets();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            DROP TRIGGER IF EXISTS triger_gatilho_tickets ON tickets CASCADE;
        ");
    }
}
