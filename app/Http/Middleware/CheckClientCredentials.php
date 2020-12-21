<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laravel\Passport\Http\Middleware\CheckCredentials;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
class CheckClientCredentials extends CheckCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

            $psr = (new PsrHttpFactory(
                new ServerRequestFactory,
                new StreamFactory,
                new UploadedFileFactory,
                new ResponseFactory
            ))->createRequest($request);
    
            try {
                $psr = $this->server->validateAuthenticatedRequest($psr);
            } catch (OAuthServerException $e) {
                return response()->json($e->getPayload(), $e->getHttpStatusCode()); 
            }
    
            return $next($request);
        }
    
        protected function validateCredentials($token) {}
        protected function validateScopes($token, $scopes) {}
    
        protected $middlewarePriority = [
            \App\Http\Middleware\CheckForMaintenanceMode::class, #changed
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authorize::class
        ];
}
