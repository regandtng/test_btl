<?php

namespace Core;

class Router
{
    private $routes = [];
    private $basePath = '';

    public function __construct()
    {
        // Calculate base path for subdirectory hosting
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $this->basePath = $scriptDir === '/' || $scriptDir === '\\' ? '' : $scriptDir;
    }

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute($method, $path, $handler)
    {
        // Convert route parameters {id} to regex capturing groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        // Replace trailing slash if necessary
        $pattern = '#^' . $pattern . '/?$#';
        
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path from URI
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }
        
        // Default to / if empty
        if ($uri === '') {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Call handler
                if (is_callable($route['handler'])) {
                    return call_user_func_array($route['handler'], $params);
                } elseif (is_string($route['handler'])) {
                    list($controllerName, $action) = explode('@', $route['handler']);
                    $controllerClass = "App\\Controllers\\{$controllerName}";
                    
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $action)) {
                            return call_user_func_array([$controller, $action], $params);
                        }
                    }
                }
            }
        }

        // 404 Handler
        $this->handle404();
    }

    private function handle404()
    {
        http_response_code(404);
        $viewFile = ROOT . '/app/Views/errors/404.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo '<h1>404 - Trang không tìm thấy</h1>';
        }
        exit;
    }
}
