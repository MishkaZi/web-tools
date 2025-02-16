<?php

if (!function_exists('debug')) {
    function debug($var, $die = false) {
        echo '<div style="position: fixed; bottom: 10px; right: 10px; max-width: 800px; max-height: 90vh; overflow: auto; 
                      background: rgba(0,0,0,0.9); color: #fff; padding: 15px; border-radius: 8px; 
                      font-family: monospace; font-size: 14px; z-index: 9999; white-space: pre;">';
        print_r($var);
        echo '</div>';
        if ($die) die;
    }
}