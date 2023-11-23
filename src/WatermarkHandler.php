<?php

namespace LitaPig\PageGuardWatermark;

use LitaPig\PageGuardWatermark\Controller\PageGuardWatermark;

/**
 * 控制水印加载逻辑
 * Class WatermarkHandler
 * @package LitaPig\PageGuardWatermark
 */
class WatermarkHandler
{
    const JS_HANDLE_NAME = "page-guard-watermark-js";

    /**
     * 控制水印加载的方法
     */
    public static function show()
    {
        $data = PageGuardWatermark::getData();
        // 1、还未配置
        if (empty($data)) {
            return;
        }

        // 2、隐藏了
        $status = ArrayHelper::safeGet($data, 'status');
        if ($status != 1) {
            return;
        }

        // 3、判断路径是否合适
        $currentPath = $_SERVER['REQUEST_URI'];
        $effectivePaths = ArrayHelper::safeGet($data, 'effective_paths');
        if (empty($effectivePaths)) {
            return;
        }
        // 3.1、当前路径不匹配
        if (!self::isUrlMatch($currentPath, explode("\t", $effectivePaths))) {
            return;
        }

        // 4、注册并添加JavaScript文件
        wp_register_script(self::JS_HANDLE_NAME, plugin_dir_url(__FILE__) . '../assets/js/page-guard-watermark.js', array('jquery'), '1.0', true);
        wp_enqueue_script(self::JS_HANDLE_NAME);

        // 5、传递参数到 js
        wp_localize_script(self::JS_HANDLE_NAME, 'pgw_config', $data);
    }

    /**
     * 判断 url 是否匹配
     * @param $currentUrl
     * @param $effectivePaths
     * @return bool
     */
    public static function isUrlMatch($currentUrl, $effectivePaths)
    {
        foreach ($effectivePaths as $pathPattern) {
            $pattern = '/^' . str_replace(['*', '/'], ['.*', '\/'], $pathPattern) . '$/';
            if (preg_match($pattern, $currentUrl)) {
                return true;
            }
        }
        return false;
    }
}