<?php

namespace Core;

class Router {
    private array $routes = [];
    private string $toolsPath;

    public function __construct() {
        $this->toolsPath = dirname(__DIR__, 2) . '/tools';
    }
    
    public function add(string $path, callable|array $handler): void {
        $path = trim($path, '/');
        $this->routes[$path] = $handler;
    }

    public function dispatch(string $uri): mixed {
        // Remove /web-tools prefix if present
        $uri = str_replace('/web-tools/', '', $uri);
        
        // Normalize URI
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        
        // Debug current route
        error_log('Current route: ' . $uri);
        error_log('Available routes: ' . implode(', ', array_keys($this->routes)));
        
        // Check for exact route match
        if (isset($this->routes[$uri])) {
            return $this->executeHandler($this->routes[$uri]);
        }
        
        // Check for tool route
        if (preg_match('~^tool/([^/]+)$~', $uri, $matches)) {
            return $this->handleTool($matches[1]);
        }
        
        // Handle 404
        http_response_code(404);
        return (new Template)->render('errors/404', [
            'pageTitle' => '404 - Page Not Found'
        ]);
    }

    private function executeHandler(callable|array $handler): mixed {
        return is_array($handler) ? 
            (new $handler[0])->{$handler[1]}() : 
            $handler();
    }

    private function handleTool(string $toolName): string {
        $toolDir = $this->toolsPath . '/' . $toolName;
        $indexFile = $toolDir . '/index.php';

        if (!is_dir($toolDir) || !file_exists($indexFile)) {
            http_response_code(404);
            return (new Template)->render('errors/404', [
                'pageTitle' => '404 - Tool Not Found'
            ]);
        }

        return (new Template)->render($indexFile, [
            'pageTitle' => ucwords(str_replace('-', ' ', $toolName)) . ' - Mike Zino Web Dev',
            'toolName' => $toolName
        ]);
    }
}