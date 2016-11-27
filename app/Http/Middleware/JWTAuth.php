<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class JWTAuth extends BaseMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, \Closure $next)
  {
    if (! $token = $this->auth->setRequest($request)->getToken()) {
      return $next($request);
    }

    try {
      $user = $this->auth->authenticate($token);
    } catch (TokenExpiredException $e) {
      return $next($request);
    } catch (JWTException $e) {
      return $next($request);
    }

    if (! $user) {
      return $next($request);
    }

    $this->events->fire('tymon.jwt.valid', $user);

    return $next($request);
  }
}
