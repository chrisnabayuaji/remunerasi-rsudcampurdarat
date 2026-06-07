<?php

use App\Http\Middlewares\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'app/auth/login', 301);

$modulePath = app_path('Modules');

if (is_dir($modulePath)) {
  foreach (scandir($modulePath) as $moduleName) {
    if ($moduleName === '.' || $moduleName === '..') {
      continue;
    }

    $controllerPath = $modulePath . '/' . $moduleName . '/Controllers';

    if (is_dir($controllerPath)) {
      // Tentukan middleware berdasarkan nama modul dan controller
      $middlewares = ['web'];
      if (strtolower($moduleName) !== 'app') {
        $middlewares[] = AuthMiddleware::class;
      }

      Route::prefix(strtolower($moduleName))->middleware($middlewares)->group(function () use ($controllerPath, $moduleName) {
        foreach (glob($controllerPath . '/*.php') as $controllerFile) {
          $controllerName = pathinfo($controllerFile, PATHINFO_FILENAME);
          $controllerClass = "App\\Modules\\$moduleName\\Controllers\\$controllerName";

          if (class_exists($controllerClass)) {
            $methods = (new ReflectionClass($controllerClass))->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
              $methodName = $method->name;

              if ($methodName === '__construct') {
                continue;
              }

              // Tentukan apakah perlu menambahkan Auth middleware
              $methodMiddlewares = [];
              if (strtolower($controllerName) !== 'auth' && strtolower($controllerName) !== 'app') {
                $methodMiddlewares[] = AuthMiddleware::class;
              }

              // Cek apakah controller memiliki nama yang sama dengan modul
              $isMainController = strtolower($controllerName) === strtolower($moduleName);
              $routeBase = $isMainController ? '' : strtolower($controllerName) . '/';
              $routeBaseSnake = $isMainController ? '' : strtolower(camelToSnake($controllerName)) . '/';
              $routeBaseKebab = $isMainController ? '' : str_replace('_', '-', strtolower(camelToSnake($controllerName))) . '/';

              // Cek paremeter method
              $parameters = $method->getParameters();
              $paramString = '';
              $optionalParams = [];

              if (count($parameters) ?? 0 > 0 && count($parameters) ?? 0 <= 5) {
                foreach ($parameters as $param) {
                  $paramName = $param->name;
                  $optionalParams[] = $param->isOptional() ? '{' . $paramName . '?}' : '{' . $paramName . '}';
                }
                $paramString = '/' . implode('/', $optionalParams);
              }

              // Buat route dengan GET dan POST untuk setiap method
              if ($methodName === 'index') {
                Route::get($routeBase . $paramString, [$controllerClass, $methodName])
                  ->name(strtolower($moduleName) . '.' . strtolower($controllerName) . '.' . strtolower($methodName));
                Route::get($routeBaseSnake . $paramString, [$controllerClass, $methodName])
                  ->name(strtolower($moduleName) . '.' . strtolower(camelToSnake($controllerName)) . '.' . strtolower($methodName));
                Route::get($routeBaseKebab . $paramString, [$controllerClass, $methodName])
                  ->name(strtolower($moduleName) . '.' . str_replace('_', '-', strtolower(camelToSnake($controllerName))) . '.' . strtolower($methodName));

                Route::post($routeBase . $paramString, [$controllerClass, $methodName])
                  ->name(strtolower($moduleName) . '.' . strtolower($controllerName) . '.' . strtolower($methodName));
                Route::post($routeBaseSnake . $paramString, [$controllerClass, $methodName])
                  ->name(strtolower($moduleName) . '.' . strtolower(camelToSnake($controllerName)) . '.' . strtolower($methodName));
                Route::post($routeBaseKebab . $paramString, [$controllerClass, $methodName])
                  ->name(strtolower($moduleName) . '.' . str_replace('_', '-', strtolower(camelToSnake($controllerName))) . '.' . strtolower($methodName));
              }

              // Route umum untuk method selain index
              $route1 = Route::get($routeBase . strtolower($methodName) . $paramString, [$controllerClass, $methodName])
                ->name(strtolower($moduleName) . '.' . strtolower($controllerName) . '.' . strtolower($methodName));
              $route2 = Route::get($routeBaseSnake . strtolower($methodName) . $paramString, [$controllerClass, $methodName])
                ->name(strtolower($moduleName) . '.' . strtolower(camelToSnake($controllerName)) . '.' . strtolower($methodName));
              $route3 = Route::get($routeBaseKebab . strtolower($methodName) . $paramString, [$controllerClass, $methodName])
                ->name(strtolower($moduleName) . '.' . str_replace('_', '-', strtolower(camelToSnake($controllerName))) . '.' . strtolower($methodName));

              $route4 = Route::post($routeBase . strtolower($methodName) . $paramString, [$controllerClass, $methodName])
                ->name(strtolower($moduleName) . '.' . strtolower($controllerName) . '.' . strtolower($methodName));
              $route5 = Route::post($routeBaseSnake . strtolower($methodName) . $paramString, [$controllerClass, $methodName])
                ->name(strtolower($moduleName) . '.' . strtolower(camelToSnake($controllerName)) . '.' . strtolower($methodName));
              $route6 = Route::post($routeBaseKebab . strtolower($methodName) . $paramString, [$controllerClass, $methodName])
                ->name(strtolower($moduleName) . '.' . str_replace('_', '-', strtolower(camelToSnake($controllerName))) . '.' . strtolower($methodName));

              // Terapkan middleware jika ada
              if (!empty($methodMiddlewares)) {
                $route1->middleware($methodMiddlewares);
                $route2->middleware($methodMiddlewares);
                $route3->middleware($methodMiddlewares);
                $route4->middleware($methodMiddlewares);
                $route5->middleware($methodMiddlewares);
                $route6->middleware($methodMiddlewares);
              }
            }
          }
        }
      });
    }
  }
}
