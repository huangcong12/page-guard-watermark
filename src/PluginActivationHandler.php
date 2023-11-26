<?php

namespace LitaCat\PageGuardWatermark;
defined('WPINC') || exit;

/**
 * 插件安装、停用、删除的助手类
 * Class PluginActivationHandler
 * @package LitaCat\PageGuardWatermark
 */
class PluginActivationHandler
{
    /**
     * 激活插件
     */
    public static function activation()
    {
        return true;
    }

    /**
     * 停用插件
     */
    public static function deactivation()
    {
        return true;
    }

    /**
     * 删除插件
     */
    public static function uninstall()
    {
        return true;
    }
}