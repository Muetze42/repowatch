<?php

namespace App\Providers;

use App\Models\Repository;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        RouteFacade::bind('repository', function (string $value, Route $route) {
            $vendor = $route->parameter('vendor');
            $name = $route->parameter('name');

            abort_if(! is_string($vendor) || ! is_string($name), Response::HTTP_NOT_FOUND);

            return Repository::where('package_name', $vendor . '/' . $name)->firstOrFail();
        });
    }
}
