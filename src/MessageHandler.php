<?php

namespace LitaPig\PageGuardWatermark;

defined('WPINC') || exit;

/**
 * 消息提醒类
 * Class MessageHandler
 * @package LitaPig\PageGuardWatermark
 */
class MessageHandler
{
    public static function addError($message)
    {
        echo '<div class="notice notice-error"><p>' . $message . '</p></div>';
    }

    public static function addWarning($message)
    {
        echo '<div class="notice notice-warning"><p>' . $message . '</p></div>';
    }

    public static function addInfo($message)
    {
        echo '<div class="notice notice-info"><p>' . $message . '</p></div>';
    }

    public static function addSuccess($message)
    {
        echo '<div class="notice notice-success"><p>' . $message . '</p></div>';
    }
}