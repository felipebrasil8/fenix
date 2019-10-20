<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        //dd($e);
        //dd($request->wantsJson());
        //dd($request);
        if ($e instanceof ModelNotFoundException or 
            $e instanceof NotFoundHttpException or 
            $e instanceof HttpException)
        {
            //dd('entrou no if');
            $statusCode = $e->getStatusCode();

            // Verifica se requisição veio via AJAX
            if ($request->wantsJson()){
                switch ($statusCode) 
                {
                    case 401: return response()->json(['status' => false, 'errors' => ['errors' => [ 'Usuário não está logado' ] ] ], $statusCode);
                    case 403: return response()->json(['errors' => ['errors' => ['Falha na autenticação'] ] ], $statusCode);
                    case 404: return response()->json(['status' => false, 'errors' => ['errors' => [ 'A página solicitada não foi encontrada' ] ] ], $statusCode);
                    //case 404: return redirect()->guest(route('login'));
                }
                
            } else {
                switch ($statusCode) 
                {
                    case 401: return response()->view('auth.login',[],$statusCode);
                    case 403: return response()->view('erros.403', ['titulo' => 'Atenção', 'descricao' => 'Falha na autenticação'],$statusCode);
                    case 404: return response()->view('erros.404',[],$statusCode);
                    //case 404: return redirect()->guest(route('login'));
                }
            }
        }
        else if( $e instanceof QueryException )
        {
            if( !empty($e->errorInfo[2]) && isset($e->errorInfo[2]) && $e->errorInfo[2] != '' )
            {
                $mensagem[0] = $e->errorInfo[2];

                if( \Config::get('app.debug') )
                {
                    $mensagem[1] = $e->getSql();
                }
            }
            else
            {
                $mensagem[0] = $e->getMessage();
            }

            // Verifica se requisição veio via AJAX
            if ($request->wantsJson())
                return response()->json(['status' => false, 'errors' => ['errors' => [ 'Erro no comando SQL.' ] ] ], 200);
            else
                return response()->view('erros.query', ['mensagem' => $mensagem], 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
