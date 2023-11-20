<?php

defined('WPINC') || exit;

if (!function_exists('lita_pig_pgw_wp_autoload')) {
    function lita_pig_pgw_wp_autoload($cls)
    {
        if (str_contains($cls, '.') || strpos($cls, 'LitaPig\PageGuardWatermark') !== 0) {
            return;
        }

        $file = explode('\\', $cls);
        $classFileName = end($file);
        $subPath = strtolower(prev($file));

        if (in_array($subPath, ['controller'])) {
            $file = LITA_PIG_PGW_WP_DIR . 'src/' . $subPath . '/' . $classFileName . '.php';
        } else {
            $file = LITA_PIG_PGW_WP_DIR . 'src/' . $classFileName . '.php';
        }

        if (file_exists($file)) {
            require_once $file;
        }
    }
}

spl_autoload_register('lita_pig_pgw_wp_autoload');

