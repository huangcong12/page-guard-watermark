(function () {
    function drawImage(canvas, logo, logoWidth, logoHeight, alpha) {
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.src = logo;

        if (img.complete) {
            ctx.globalAlpha = alpha;  // 设置透明度
            ctx.drawImage(img, 0, 0, logoWidth, logoHeight);
        } else {
            img.onload = function () {
                // not working
                ctx.globalAlpha = alpha;  // 设置透明度
                ctx.drawImage(img, 0, 0, logoWidth, logoHeight);
            };
        }
    }

    function drawText(canvas, options) {
        const lines = options.content.split('\\n');
        const lineHeight = options.lineHeight;
        let maxWidth = 0;

        // 新建一个假的，用来计算长度
        const ctxFaker = canvas.getContext('2d');
        ctxFaker.textAlign = options.textAlign;
        ctxFaker.textBaseline = 'top'; // 使用 'top' 作为基准线
        ctxFaker.font = options.fontSize + "px " + options.font;
        ctxFaker.fillStyle = options.fillStyle;
        lines.forEach((line, index) => {
            const textWidth = ctxFaker.measureText(line).width;
            maxWidth = Math.max(maxWidth, textWidth);
        });

        // 设置 canvas 的宽度和高度
        canvas.width = maxWidth;
        canvas.height = lines.length * options.fontSize + (lines.length * lineHeight);
        // fillCanvasBackground(canvas, 'green');

        // 设置好 cavas 的长度以后再操作数据，因为 cavas 设置长度以后数据会被清空
        const ctx = canvas.getContext('2d');
        ctx.textAlign = options.textAlign;
        ctx.textBaseline = 'top'; // 使用 'top' 作为基准线
        ctx.font = options.fontSize + "px " + options.font;
        ctx.fillStyle = options.fillStyle;
        ctx.globalAlpha = options.alpha;

        lines.forEach((line, index) => {
            // lineHeight * index 是每一行的间距；options.fontSize * index 是每一行文字的高度；lineHeight / 2 是首行需要占用的高度，首行一半、尾行一半
            const textY = lineHeight * index + options.fontSize * index + lineHeight / 2;
            ctx.fillText(line, 0, textY);
        });

    }

    // 方向枚举
    const MergeDirection = {
        Left: 'left',
        Right: 'right',
        Top: 'top',
        Bottom: 'bottom',
        Center: 'center',
    };

    function mergeCanvases(canvas1, canvas2, direction = MergeDirection.Left) {
        const mergedCanvas = document.createElement('canvas');
        const ctx = mergedCanvas.getContext('2d');

        // 设置合并后的 canvas 大小
        mergedCanvas.width = Math.max(canvas1.width, canvas2.width);
        mergedCanvas.height = Math.max(canvas1.height, canvas2.height);

        switch (direction) {
            case MergeDirection.Left:
                mergedCanvas.width = canvas1.width + canvas2.width;
                mergedCanvas.height = Math.max(canvas1.height, canvas2.height);
                ctx.drawImage(canvas1, 0, mergedCanvas.height > canvas1.height ? (mergedCanvas.height - canvas1.height) / 2 : 0);
                ctx.drawImage(canvas2, canvas1.width, mergedCanvas.height > canvas2.height ? (mergedCanvas.height - canvas2.height) / 2 : 0);
                break;
            case MergeDirection.Right:
                mergedCanvas.width = canvas1.width + canvas2.width;
                mergedCanvas.height = Math.max(canvas1.height, canvas2.height);
                ctx.drawImage(canvas2, 0, mergedCanvas.height > canvas2.height ? (mergedCanvas.height - canvas2.height) / 2 : 0);
                ctx.drawImage(canvas1, canvas2.width, mergedCanvas.height > canvas1.height ? (mergedCanvas.height - canvas1.height) / 2 : 0);
                break;
            case MergeDirection.Top:
                mergedCanvas.width = Math.max(canvas1.width, canvas2.width);
                mergedCanvas.height = canvas1.height + canvas2.height;
                ctx.drawImage(canvas1, mergedCanvas.width > canvas1.width ? (mergedCanvas.width - canvas1.width) / 2 : 0, 0);
                ctx.drawImage(canvas2, mergedCanvas.width > canvas2.width ? (mergedCanvas.width - canvas2.width) / 2 : 0, canvas1.height);
                break;
            case MergeDirection.Bottom:
                mergedCanvas.width = Math.max(canvas1.width, canvas2.width);
                mergedCanvas.height = canvas1.height + canvas2.height;
                ctx.drawImage(canvas2, mergedCanvas.width > canvas2.width ? (mergedCanvas.width - canvas2.width) / 2 : 0, 0);
                ctx.drawImage(canvas1, mergedCanvas.width > canvas1.width ? (mergedCanvas.width - canvas1.width) / 2 : 0, canvas2.height);
                break;
            case MergeDirection.Center:
                mergedCanvas.width = Math.max(canvas1.width, canvas2.width);
                mergedCanvas.height = Math.max(canvas1.height, canvas2.height);
                ctx.drawImage(canvas1, mergedCanvas.width > canvas1.width ? (mergedCanvas.width - canvas1.width) / 2 : 0, mergedCanvas.height > canvas1.height ? (mergedCanvas.height - canvas1.height) / 2 : 0);
                ctx.drawImage(canvas2, mergedCanvas.width > canvas2.width ? (mergedCanvas.width - canvas2.width) / 2 : 0, mergedCanvas.height > canvas2.height ? (mergedCanvas.height - canvas2.height) / 2 : 0);

                break;
            default:
                break;
        }

        return mergedCanvas;
    }

    // 填充背景颜色
    function fillCanvasBackground(canvas, color) {
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }

    // 生成水印
    function createWaterMarkCanvas(alpha = 0.9, {
        logoUrl = '/test_logo_2.jpg',// 图片地址
        logoWidth = 100,  // 设置图片需要的宽度
        logoHeight = 100, // 设置图片需要的高度
    } = {}, {
                                       content = "abc",
                                       textAlign = 'left',
                                       textBaseline = 'top',
                                       fontSize = '20',
                                       font = 'Microsoft Yahei',
                                       lineHeight = "2",  // 行间距
                                       fillStyle = 'rgba(184, 184, 184, 0.5)',
                                   } = {}, direction = MergeDirection.Left) {
        const logoCanvas = document.createElement('canvas');

        logoCanvas.width = logoWidth;
        logoCanvas.height = logoHeight;
        // fillCanvasBackground(logoCanvas, 'red');

        // Draw image on the first canvas
        drawImage(logoCanvas, logoUrl, logoWidth, logoHeight, alpha);

        const textCanvas = document.createElement('canvas');
        // Draw text on the second canvas
        drawText(textCanvas, {
            textAlign,
            textBaseline,
            font,
            fillStyle,
            alpha,
            content,
            fontSize,
            lineHeight,
        });

        // Merge canvases into a single canvas
        return mergeCanvases(logoCanvas, textCanvas, direction);

        // Append the merged canvas to the document body

    };

    function __canvasWM() {
        // 获取总的cavans
        let canvas = createWaterMarkCanvas(0.4, {
            logoUrl: "/熊logo.png",
            logoWidth: 50,
            logoHeight: 50
        }, {content: "中华人民共和国\\n万岁\\n世界人民\\n大团结\\n万岁", fontSize: "20"}, MergeDirection.Left);
        const base64Url = canvas.toDataURL();
        // document.body.appendChild(canvas);

        // 检查和创建一个带有类名 __wm 的元素
        const __wm = document.querySelector('.__wm');
        const watermarkDiv = __wm || document.createElement("div");

        // 倾斜角度
        let rotate = -45

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
         */

        let width = window.screen.width +"px";
        let height = window.screen.height +"px";

        const styleStr = `
                  position:fixed;
                  top:-${height};
                  bottom:-${height};
                  left:-${width};
                  right:-${width};
                  z-index:999999;
                  pointer-events:none;
                  background-repeat:repeat;
                  background-image:url('${base64Url}');
                  transform-origin: center 0;
                  transform: rotate(${rotate}deg);`;

        watermarkDiv.setAttribute('style', styleStr);
        watermarkDiv.classList.add('__wm');

        let container = document.body
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
                    __canvasWM();
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

/**
 * 加载图片：先初始化，避免图片加载不出来，水印里的图片为空的问题
 *
 * @param logo
 */
function initLoadImg() {
    const img = new Image();
    img.src = "/熊logo.png";
}

initLoadImg()

window.onload = function () {
    __canvasWM();
}

// 调用
// document.addEventListener("DOMContentLoaded", function () {
//     __canvasWM()
// });