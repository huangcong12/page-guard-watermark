<?php
/*
Plugin Name: PageGuard Watermark
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Protect and Label Your Webpage Content
Author: Hang Cong
Version: 1.0.0
Author URI: https://github.com/huangcong12
*/

if (!defined('ABSPATH')) {
    exit;
}


function pluginprefix_function_to_run()
{
    return true;
}

// 激活时候运行
register_activation_hook(__FILE__, 'pluginprefix_function_to_run');

function register_deactivation_hook2()
{
    return true;
}

// 停用的时候运行
register_deactivation_hook(__FILE__, 'register_deactivation_hook2');


function pageguard_watermark_scripts()
{
    // 获取动态生成的水印文本，可以是一个选项值或其他动态数据
    $watermark_text = get_option('pageguard_watermark_text', 'Default Watermark Text');

    // 注册并添加JavaScript文件
    wp_register_script('pageguard-watermark-js', false); // 注册时不设置文件路径
    wp_enqueue_script('pageguard-watermark-js');

    // 使用 wp_add_inline_script 动态生成 JavaScript 代码
    $custom_js = <<<EOF
        (function () {
            function __canvasWM({
                container = document.body,
                textAlign = 'center',
                textBaseline = 'middle',
                font = "20px Microsoft Yahei",
                fillStyle = 'rgba(184, 184, 184, 0.6)',
                content = '水印',
                rotate = '0',
                zIndex = 10000,
                marginTop = 0, // 新增的参数，用于控制水印之间的间距
                marginLeft = 0, // 新增的参数，用于控制左右间距
            } = {}) {
                const args = arguments[0];
        
                // 创建临时的 canvas 元素来测量文本的宽度和高度
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');
                tempCtx.font = font;
        
                // 处理带有换行符的内容
                const lines = content.split('\\n');
                const textWidth = Math.max(...lines.map(line => tempCtx.measureText(line).width));
        
                // 计算每行文字的高度
                const lineHeight = 20; // 每行文字的高度
                const totalHeight = lines.length * lineHeight  + marginTop;
        
                // 创建主要的 canvas 元素
                const canvas = document.createElement('canvas');
                canvas.setAttribute('width', (textWidth + marginLeft).toString() + 'px'); // 考虑左右间距
                canvas.setAttribute('height', totalHeight.toString() + 'px'); // 使用动态计算的高度
                const ctx = canvas.getContext('2d');
        
                ctx.textAlign = textAlign;
                ctx.textBaseline = textBaseline;
                ctx.font = font;
                ctx.fillStyle = fillStyle;
        
                lines.forEach((line, index) => {
                    const textX = parseFloat(canvas.width) / 2; // 居中
                    const textY = lineHeight * index + 10 + marginTop; // 动态计算每行文字的 Y 坐标
                    ctx.fillText(line, textX, textY);
                });
        
                const base64Url = canvas.toDataURL();
                const __wm = document.querySelector('.__wm');
        
                const watermarkDiv = __wm || document.createElement('div');
                const styleStr = `
                    position:fixed;
                    top:0;
                    left:0;
                    bottom:0;
                    right:0;
                    width:500%;
                    height:500%;
                    z-index:10000;
                    pointer-events:none;
                    background-repeat:repeat;
                    background-image:url('` + base64Url + `');
                    transform: rotate(0turn);
                    transform-origin: 50% 0%;`;
        
                watermarkDiv.setAttribute('style', styleStr);
                watermarkDiv.classList.add('__wm');
        
                if (!__wm) {
                    container.style.position = 'relative';
                    container.insertBefore(watermarkDiv, container.firstChild);
                }
        
                const MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
                if (MutationObserver) {
                    let mo = new MutationObserver(function () {
                        const __wm = document.querySelector('.__wm');
                        // 只在__wm元素变动才重新调用 __canvasWM
                        if ((__wm && __wm.getAttribute('style') !== styleStr) || !__wm) {
                            // 避免一直触发
                            mo.disconnect();
                            mo = null;
                            __canvasWM(JSON.parse(JSON.stringify(args)));
                        }
                    });
        
                    mo.observe(container, {
                        attributes: true,
                        subtree: true,
                        childList: true
                    });
                }
            }
        
            if (typeof module != 'undefined' && module.exports) { //CMD
                module.exports = __canvasWM;
            } else if (typeof define == 'function' && define.amd) { // AMD
                define(function () {
                    return __canvasWM;
                });
            } else {
                window.__canvasWM = __canvasWM;
            }
        })();
        
        // 调用
        jQuery(document).ready(function ($) {
            __canvasWM({
                //content: 'ip:127.0.0.1 admin 2023-11-11 22:1512345678901234567890\\nSecond line\\nThird line\\nFour \\nFive \\n2023-11-11 22:16\\n2023-11-11 22:17\\n2023-11-11 22:18\\n2023-11-11 22:19\\n2023-11-11 22:10',
                content: 'ip:127.0.0.1 admin 2023-11-11 22:1512345678901234567890\\nSecond line\\nThird line\\nFour \\nFive',
                fillStyle: 'rgba(0, 0, 255, 0.5)',
                textBaseline: 'middle',
            });
        });

EOF;

    wp_add_inline_script('pageguard-watermark-js', $custom_js, 'after'); // 将动态生成的 JS 代码注入到 'pageguard-watermark-js' 脚本之后
}

pageguard_watermark_scripts();


//function pageguard_watermark_scripts()
//{
//    // 注册并添加JavaScript文件
//    wp_register_script('pageguard-watermark-js', plugin_dir_url(__FILE__) . 'js/page-guard-watermark.js', array('jquery'), '1.0', true);
//    wp_enqueue_script('pageguard-watermark-js');

// 注册并添加样式表文件
//    wp_register_style('pageguard-watermark-css', plugin_dir_url(__FILE__) . 'css/page-guard-watermark.css', array(), '1.0', 'all');
//    wp_enqueue_style('pageguard-watermark-css');
//}

//add_action('wp_footer', 'pageguard_watermark_scripts');
