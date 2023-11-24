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
        $sign = md5(serialize($data));
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

        // 字符串变量赋值
        $data['text_content'] = self::replaceVariables($data['text_content']);

        // 4、注册并添加JavaScript文件
        wp_register_script(self::JS_HANDLE_NAME, plugin_dir_url(__FILE__) . '../assets/js/page-guard-watermark.js', array('jquery'), $sign, true);
        wp_enqueue_script(self::JS_HANDLE_NAME);

        // 5、传递参数到 js
        wp_localize_script(self::JS_HANDLE_NAME, 'pgw_config', $data);
    }

    /**
     * 替换字符串的变量
     *
     * @param $text
     * @return string|string[]
     */
    public static function replaceVariables($text)
    {
        $currentUser = wp_get_current_user();
        // 获取变量值，这里假设你已经有了这些变量的值
        $adminId = get_current_user_id();
        $adminName = $currentUser->user_nicename;
        $nowTime = current_time('H:i:s');
        $nowDate = current_time('Y-m-d');

        // 定义变量替换数组
        $variables = array(
            '{admin_id}' => $adminId,
            '{admin_name}' => $adminName,
            '{ip}' => self::getIp(),
            '{now_time}' => $nowTime,
            '{now_date}' => $nowDate,
        );

        // 使用 str_replace 进行替换
        return str_replace(array_keys($variables), array_values($variables), $text);
    }

    /**
     * 获取 ip 地址
     */
    private static function getIp()
    {
        $userIp = $_SERVER['REMOTE_ADDR'];

        // 如果使用了代理服务器，需要检查是否有代理IP
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $userIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        // 使用过滤函数确保IP的合法性
        return filter_var($userIp, FILTER_VALIDATE_IP);
    }


    /**
     * 判断 url 是否匹配
     * @param $currentUrl
     * @param $effectivePaths
     * @return bool
     */
    private static function isUrlMatch($currentUrl, $effectivePaths)
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