<?php

namespace LitaPig\PageGuardWatermark\Controller;

defined('WPINC') || exit;

class PageGuardWatermark
{
    /**
     * 列表页面
     */
    public static function list()
    {
        // 查询列表数据
        
        return include(LITA_PIG_PGW_WP_DIR . 'tpl/pageGuardWatermarkList.php');
    }
}