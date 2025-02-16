<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


use Core\Router;
use Core\Template;
use Core\Config;
use Core\Helper; // Add this line

// Initialize router
$router = new Router();

$router->add('', function () {  // This is the home route
    return (new Template)->render('home', [
        'pageTitle' => 'Mike Zino Web Dev Tools'
    ]);
});

$router->add('api/tool-upload', function () {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return json_encode(['error' => 'Method not allowed']);
    }

    $toolName = $_POST['toolName'] ?? '';
    $validation = Helper::validateToolName($toolName);

    if (!$validation['valid']) {
        http_response_code(400);
        return json_encode(['error' => $validation['message']]);
    }

    $toolSlug = $validation['slug'];
    $toolsPath = dirname(__DIR__) . '/tools/' . $toolSlug;

    // Create tool directory if it doesn't exist
    if (!is_dir($toolsPath)) {
        if (!mkdir($toolsPath, 0755, true)) {
            http_response_code(500);
            return json_encode(['error' => 'Failed to create tool directory']);
        }
    }

    $uploadedFiles = [];
    $mainPhpFile = null;

    // Handle multiple file uploads
    foreach ($_FILES as $fileInput) {
        if (is_array($fileInput['name'])) {
            // Handle multiple files
            for ($i = 0; $i < count($fileInput['name']); $i++) {
                $fileName = basename($fileInput['name'][$i]);
                $tmpName = $fileInput['tmp_name'][$i];
                $targetPath = $toolsPath . '/' . $fileName;

                // Store the first PHP file that's not index.php
                if (pathinfo($fileName, PATHINFO_EXTENSION) === 'php' && $fileName !== 'index.php' && !$mainPhpFile) {
                    $mainPhpFile = $fileName;
                }

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $uploadedFiles[] = $fileName;
                } else {
                    http_response_code(500);
                    return json_encode([
                        'error' => 'Failed to upload file: ' . $fileName
                    ]);
                }
            }
        } else {
            // Handle single file
            $fileName = basename($fileInput['name']);
            $tmpName = $fileInput['tmp_name'];
            $targetPath = $toolsPath . '/' . $fileName;

            // Store the first PHP file that's not index.php
            if (pathinfo($fileName, PATHINFO_EXTENSION) === 'php' && $fileName !== 'index.php' && !$mainPhpFile) {
                $mainPhpFile = $fileName;
            }

            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedFiles[] = $fileName;
            } else {
                http_response_code(500);
                return json_encode([
                    'error' => 'Failed to upload file: ' . $fileName
                ]);
            }
        }
    }

    // Create index.php only if we found a PHP file
    if ($mainPhpFile) {
        $indexContent = "<?php\ninclude __DIR__ . '/{$mainPhpFile}';\n?>";
        if (!file_put_contents($toolsPath . '/index.php', $indexContent)) {
            http_response_code(500);
            return json_encode(['error' => 'Failed to create index.php']);
        }
        $uploadedFiles[] = 'index.php';
    }

    // Return success response
    return json_encode([
        'success' => true,
        'message' => 'Tool uploaded successfully',
        'toolSlug' => $toolSlug,
        'files' => $uploadedFiles,
        'mainPhpFile' => $mainPhpFile
    ]);
});

$router->add('api/tool-delete', function () {
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return json_encode(['error' => 'Method not allowed']);
    }

    $toolName = $_POST['toolName'] ?? '';
    $validation = Helper::validateToolName($toolName);

    if (!$validation['valid']) {
        http_response_code(400);
        return json_encode(['error' => $validation['message']]);
    }

    $toolSlug = $validation['slug'];
    $toolPath = dirname(__DIR__) . '/tools/' . $toolSlug;

    if (!is_dir($toolPath)) {
        http_response_code(404);
        return json_encode(['error' => 'Tool not found']);
    }

    // Recursive directory deletion
    $deleteDir = function ($dir) use (&$deleteDir) {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $deleteDir($path) : unlink($path);
        }
        return rmdir($dir);
    };

    if ($deleteDir($toolPath)) {
        return json_encode([
            'success' => true,
            'message' => 'Tool deleted successfully',
            'toolSlug' => $toolSlug
        ]);
    }

    http_response_code(500);
    return json_encode(['error' => 'Failed to delete tool']);
});


// Dispatch the request
echo $router->dispatch($_SERVER['REQUEST_URI']);
