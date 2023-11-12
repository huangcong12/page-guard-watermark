(function () {
    function __canvasWM({
                            container = document.body,
                            width = '300px',
                            height = '200px',
                            textAlign = 'center',
                            textBaseline = 'middle',
                            font = "20px Microsoft Yahei",
                            fillStyle = 'rgba(184, 184, 184, 0.6)',
                            content = '水印',
                            rotate = '45',
                            zIndex = 10000
                        } = {}) {
        const args = arguments[0];
        const canvas = document.createElement('canvas');

        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        const ctx = canvas.getContext("2d");


        ctx.textAlign = textAlign;
        /* 设置 Canvas 2D 上下文中文本基线的属性
            top: 顶部对齐
            hanging: 挂起基线对齐
            middle: 居中对齐
            alphabetic: 默认，基线是标准的字母基线
            ideographic: 基线是标准的表意字符基线
            bottom: 底部对齐
        */
        ctx.textBaseline = textBaseline;
        ctx.font = font;
        ctx.fillStyle = fillStyle;
        // 旋转 Canvas 2D 上下文中的绘图 Math.PI:3.1415
        ctx.rotate(Math.PI / 180 * rotate);
        // Canvas 2D 上下文的 fillText 方法在指定的坐标位置绘制文本
        ctx.fillText(content, parseFloat(width) / 2, parseFloat(height) / 2);

        // Canvas 元素的 toDataURL 方法将 Canvas 上的内容转换为 base64 编码的图像数据
        const base64Url = canvas.toDataURL();
        // 检查和创建一个带有类名 __wm 的元素
        const __wm = document.querySelector('.__wm');
        const watermarkDiv = __wm || document.createElement("div");

        /**
         * 每个属性：
         1. `position: fixed;`： - 指定元素相对于浏览器窗口进行定位。
         2. `top: 0;`： - 设置元素相对于其内容区域顶部边缘的顶部距离。在这里，设置为 `0`，使元素与窗口顶部对齐。
         3. `left: 0;`： - 设置元素相对于其内容区域左侧边缘的左侧距离。在这里，设置为 `0`，使元素与窗口左侧对齐。
         4. `bottom: 0;`： - 设置元素相对于其内容区域底部边缘的底部距离。在这里，设置为 `0`，使元素与窗口底部对齐。
         5. `right: 0;`： - 设置元素相对于其内容区域右侧边缘的右侧距离。在这里，设置为 `0`，使元素与窗口右侧对齐。
         6. `width: 100%;`： - 将元素的宽度设置为其包含元素的 `100%`，在这里是整个窗口。
         7. `height: 100%;`： - 将元素的高度设置为其包含元素的 `100%`，在这里是整个窗口。
         8. `z-index: ${zIndex};`： - 设置元素的层叠顺序。`zIndex` 的值是动态插入的，使用模板字面量，使开发人员能够以编程方式控制层叠顺序。
         9. `pointer-events: none;`： - 指定元素不响应鼠标事件。这确保水印不会干扰页面上的用户交互。
         10. `background-repeat: repeat;`： - 设置背景图像的重复行为。在这里，背景图像（稍后指定）将在垂直和水平方向上重复。
         11. `background-image: url('${base64Url}')`： - 设置元素的背景图像。使用 `url()` 函数并动态插入 `base64Url`。这是水印图像作为背景应用的位置。
         总而言之，`styleStr` 定义了水印的视觉属性，使其成为一个固定位置、全屏的元素，具有指定的层叠顺序，对鼠标事件透明，使用base64编码的图像作为其重复的背景。
         * @type {string}
         */
        const styleStr = `
                  position:fixed;
                  top:0;
                  left:0;
                  bottom:0;
                  right:0;
                  width:100%;
                  height:100%;
                  z-index:${zIndex};
                  pointer-events:none;
                  background-repeat:repeat;
                  background-image:url('${base64Url}')`;

        watermarkDiv.setAttribute('style', styleStr);
        watermarkDiv.classList.add('__wm');

        if (!__wm) {
            container.style.position = 'relative';
            container.insertBefore(watermarkDiv, container.firstChild);
        }

        // 防止水印代码被手动删除
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

            // 观察元素变化
            mo.observe(container, {
                attributes: true,   // 观察属性变化
                subtree: true,      // 观察子树变化
                childList: true     // 观察子节点的增减
            })
        }

    }

    if (typeof module != 'undefined' && module.exports) {  //CMD
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
__canvasWM({
    content: '水印123'
});