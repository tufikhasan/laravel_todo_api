<?php

namespace App\Http\Middleware;

use App\Helper\JWT_TOKEN;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJwtToken {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle( Request $request, Closure $next ): Response{
        $token = $request->header( 'token' );
        $verify = JWT_TOKEN::verify_token( $token );

        if ( 'unauthorized' == $verify ) {
            return response()->json( ['status' => 'Failed', 'message' => 'Unauthorized'], 500 );
        } else {
            $request->headers->set( 'email', $verify->user_email );
            $request->headers->set( 'user_id', $verify->user_id );
            return $next( $request );
        }
    }
}
