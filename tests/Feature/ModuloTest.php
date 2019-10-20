<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Core\LoginUsuario;

class ModuloTest extends TestCase
{
    
    protected $search = ['coluna' => 'nome', 'ordem' => 'asc','pagina' => '1','limite' => '15','ativo' => true];
    protected $searchNew = ['sort'=> '3 asc','pagina' => '1','por_pagina' => '15','status' => true];
    protected $viewIndex = 'index';
    protected $viewEdit = 'editar';
    protected $viewCreate = 'cadastrar';
    protected $viewView = 'visualizar';
    protected $viewCopy = 'copiar';
    protected $viewNotFound = 'erros.naoEncontrado';
    protected $viewAutenticacao = 'erros.403';
    protected $viewLogin = 'auth.login';
    protected $userAdmin = 1;
    //protected $userBasic = 8;
    protected $userBasicLogin = 'usuario.comum';

    protected function getUser($user)
    {
        return $this->actingAs(LoginUsuario::find($user));
    }

    protected function getUserByLogin($login)
    {
        return $this->actingAs(LoginUsuario::where('usuario', $login)->first());
    }

    protected function getUserAdmin()
    {
        return $this->getUser($this->userAdmin);
    }

    protected function getUserBasic()
    {
        return $this->getUserByLogin($this->userBasicLogin);
    }

    protected function getUserBasicID()
    {
        return LoginUsuario::where('usuario', $this->userBasicLogin)->first()->id;
    }

    public function testSuccess()
    {
        $this->assertTrue(true);
    }

}
