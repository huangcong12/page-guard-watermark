<?php

namespace LitaPig\PageGuardWatermark;

defined('WPINC') || exit;

/**
 * 数组助手类
 * Class ArrayHelper
 * @package LitaPig\PageGuardWatermark
 */
class ArrayHelper
{
    /**
     * 安全获取数组里的值
     * @param array $data
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public static function safeGet(array $data, string $key, $default = "")
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * 是否等于预期的值
     *
     * @param array $data
     * @param string $key
     * @param $wantValue
     * @return bool
     */
    public static function safeEqual(array $data, string $key, $wantValue)
    {
        return self::safeGet($data, $key) == $wantValue;
    }
}