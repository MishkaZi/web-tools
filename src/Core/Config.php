<?php

namespace Core;

class Config {
    private static ?string $baseUrl = null;

    public static function getBaseUrl(): string {
        if (self::$baseUrl === null) {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            
            // On Windows, dirname() returns backslash - let's handle this
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
            
            $baseDir = rtrim(str_replace('/public', '', $scriptDir), '/');
            
            self::$baseUrl = $protocol . '://' . $host . $baseDir;
        }
        
        return self::$baseUrl;
    }

    public static function getToolUrl(string $toolName): string {
        $url = self::getBaseUrl() . '/tool/' . $toolName;
        return $url;
    }

    public static function getPublicPath(string $path = ''): string {
        return self::getBaseUrl() . '/' . ltrim($path, '/');
    }

    public static function getAssetsPath(string $path = ''): string {
        return self::getPublicPath('assets/' . ltrim($path, '/'));
    }
}