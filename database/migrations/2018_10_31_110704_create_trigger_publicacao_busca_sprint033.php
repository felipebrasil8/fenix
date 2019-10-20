<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerPublicacaoBuscaSprint033 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE TRIGGER atualiza_busca_publicacao AFTER INSERT OR UPDATE ON publicacoes FOR EACH ROW EXECUTE PROCEDURE function_atualiza_busca_publicacao();");
        DB::statement("CREATE TRIGGER atualiza_busca_publicacao AFTER INSERT OR UPDATE ON publicacoes_tags FOR EACH ROW EXECUTE PROCEDURE function_atualiza_busca_publicacao();");
        DB::statement("CREATE TRIGGER atualiza_busca_publicacao AFTER INSERT OR UPDATE ON publicacoes_conteudos FOR EACH ROW EXECUTE PROCEDURE function_atualiza_busca_publicacao();");
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS atualiza_busca_publicacao ON publicacoes CASCADE;");
        DB::statement("DROP TRIGGER IF EXISTS atualiza_busca_publicacao ON publicacoes_tags CASCADE;");
        DB::statement("DROP TRIGGER IF EXISTS atualiza_busca_publicacao ON publicacoes_conteudos CASCADE;");
        
    }
}



