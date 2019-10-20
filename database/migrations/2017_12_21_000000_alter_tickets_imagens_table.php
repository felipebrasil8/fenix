<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketsImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets_imagem', function($table)
        {            
            $table->softDeletes();
        });        
    }

    public function down()
    {
        Schema::table("tickets_imagem", function ($table) {
            $table->dropSoftDeletes();
        });
    }
    
}
