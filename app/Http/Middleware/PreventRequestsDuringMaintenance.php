<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;

class PreventRequestsDuringMaintenance
{
    public function handle($request, \Closure $next)
    {
        if (app()->isDownForMaintenance()) {
            return response('Service is under maintenance', Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return $next($request);
    }
}
