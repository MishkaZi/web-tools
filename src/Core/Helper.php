<?php

namespace Core;

class Helper {
    /**
     * Convert a string to a URL-friendly slug
     */
    public static function slugify($text) {
        // Transliterate non-ASCII characters
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
        
        // Replace anything that's not alphanumeric or hyphen with a hyphen
        $text = preg_replace('/[^a-z0-9-]/', '-', strtolower($text));
        
        // Replace multiple consecutive hyphens with a single hyphen
        $text = preg_replace('/-+/', '-', $text);
        
        // Remove leading and trailing hyphens
        return trim($text, '-');
    }

    /**
     * Validate and sanitize a tool name
     */
    public static function validateToolName($name) {
        if (empty($name)) {
            return [
                'valid' => false,
                'message' => 'Tool name cannot be empty',
                'slug' => ''
            ];
        }

        $slug = self::slugify($name);
        
        if (strlen($slug) < 2) {
            return [
                'valid' => false,
                'message' => 'Tool name is too short after slugification',
                'slug' => ''
            ];
        }

        return [
            'valid' => true,
            'message' => 'Valid tool name',
            'slug' => $slug
        ];
    }
}