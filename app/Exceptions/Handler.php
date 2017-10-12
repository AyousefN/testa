<?php

namespace App\Exceptions;

use App\Http\Controllers\UserServices;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
	    if ($exception instanceof MethodNotAllowedHttpException) {
		    if ($request->expectsJson()) {
			    return $this->respondWithError ('Method Not Allowed',405,UserServices::fail);
		    }
		    return response()->view('404', [], 404);
	    }
	/*    if($request->is('api/*')){
		    return response()->json([
			    'error_message' => $exception->getMessage(),
			    'status' => Response::HTTP_BAD_REQUEST
		    ]);
	    }*/

	if($exception instanceof NotFoundHttpException)
		if ($request->expectsJson()) {
			return $this->respondWithError ('invalid url',404,UserServices::fail);
		}
        return parent::render($request, $exception);
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
//            return response()->json(['error' => 'Unautheasdasdasdasdanticated.'], 401);
	        return $this->respondWithError ('bye bye',401,UserServices::fail);
        }

        return redirect()->guest(route('login'));
    }

	public function respondWithError($massage,$code,$status)
	{
		return $this-> respond([

			'massage'=> $massage,
			'code'=>$code
			,'status'=>$status

		]);
	}
	public function respond($data,$headers=[])
	{
		return Response::json($data);
	}

}
