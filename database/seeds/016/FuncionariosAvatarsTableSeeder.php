<?php

use Illuminate\Database\Seeder;

class FuncionariosAvatarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // Busca ID do usuário GUSTAVO LOPES
        $usuario_id = DB::table('usuarios')->where('nome', 'GUSTAVO LOPES')->first()->id;

        // Busca os funcionarios
        $funcionarios = DB::table('funcionarios')->select('id','avatar_grande','avatar_pequeno')->get();

        foreach ($funcionarios as $funcionario) {
            if ( !empty($funcionario->avatar_grande) && !empty($funcionario->avatar_pequeno) ){
                DB::table('funcionarios_avatars')->insert([
                    'funcionario_id' => $funcionario->id,
                    'avatar_grande' => $funcionario->avatar_grande,
                    'avatar_pequeno' => $funcionario->avatar_pequeno,
                    'usuario_inclusao_id' => $usuario_id,
                ]);
            }
        }

    }
}
