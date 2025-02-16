<?php

namespace Core;

class Template {
    private static array $variables = [];
    private string $layoutsPath;
    private string $viewsPath;

    public function __construct() {
        $this->layoutsPath = dirname(__DIR__) . '/views/layouts/';
        $this->viewsPath = dirname(__DIR__) . '/views/';
    }

    public static function setVar(string $key, mixed $value): void {
        self::$variables[$key] = $value;
    }

    public function render(string $view, array $data = []): string {
        // Merge static variables with passed data
        $data = array_merge(self::$variables, $data);
        extract($data);

        // Start output buffering
        ob_start();

        // Include header
        require $this->layoutsPath . 'header.php';

        // Check if this is a full path (for tools) or a view name
        $viewFile = str_contains($view, '.php') ? $view : $this->viewsPath . $view . '.php';
        
        // If it's a tool file with complete HTML structure, extract the body content
        if (str_contains($view, '/tools/')) {
            $content = file_get_contents($viewFile);
            if (preg_match('/<body.*?>(.*?)<\/body>/s', $content, $matches)) {
                echo $matches[1];
            } else {
                require $viewFile;
            }
        } else {
            require $viewFile;
        }

        // Include footer
        require $this->layoutsPath . 'footer.php';

        return ob_get_clean();
    }
}