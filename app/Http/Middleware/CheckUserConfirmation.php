<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserConfirmation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty(auth()->user()) || empty(auth()->user()->confirmed)) {
            $obj = [
                'message' => 'You need to confirm email before take an action!',
                'level' => 'danger'
            ];

            return redirect(route('threads.index'))
                ->with('flash', json_encode($obj));
        }

        return $next($request);
    }
}
