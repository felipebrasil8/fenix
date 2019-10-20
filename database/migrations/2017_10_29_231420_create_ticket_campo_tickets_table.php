<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCampoTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_campo_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->integer('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->integer('ticket_campo_id');
            $table->foreign('ticket_campo_id')->references('id')->on('tickets_campo');
            $table->text('resposta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_campo_ticket');
    }
}
