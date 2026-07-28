<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login'); 
            //→ If the user is not logged in, redirect them to the login page.
        }

        if (in_array(Auth::user()->role, $roles, true)) {
            //→ If the user's role matches an allowed role, continue to the requested page.
            return $next($request);
            //→ Pass the request to the next middleware or the controller.
            
        }

        abort(403, 'Unauthorized access.');
        //→ Stop the request and show a 403 Unauthorized error.
    }
}

//string ...$roles : This is called a variadic parameter.It means:Accept one or more role values

//Closure $next: This is a callback function that represents the next middleware in the request pipeline. It allows the request to proceed to the next middleware or controller if the current middleware's conditions are met.

//Request $request: This is an instance of the Illuminate\Http\Request class, which represents the incoming HTTP request. It contains information about the request, such as headers, parameters, and the authenticated user.