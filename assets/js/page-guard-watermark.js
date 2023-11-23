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

        // 图片颜色转成和文字颜色一样
        let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let data = imageData.data;
        for (let i = 0; i < data.length; i += 4) {
            // 如果是白色，改为透明
            if (data[i] === 255 && data[i + 1] === 255 && data[i + 2] === 255) {
                data[i + 3] = 0;
            } else {
                // 否则改变每个像素的颜色
                data[i] = 184;     // red
                data[i + 1] = 184;   // green
                data[i + 2] = 184;   // blue
            }
        }
        ctx.putImageData(imageData, 0, 0);
    }

    function drawText(canvas, options) {
        const lines = options.content.split(/[\r\n]+/);
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
        canvas.width = maxWidth + 2 * options.marginVertical;
        canvas.height = lines.length * options.fontSize + (lines.length * lineHeight) + 2 * options.marginHorizontal;
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

    function __canvasWM() {
        const canvas = document.createElement('canvas');
        drawText(canvas, {
            textAlign: pgw_config.text_align,
            textBaseline: "top",
            font: 'Microsoft Yahei',
            fillStyle: pgw_config.text_color,
            alpha: pgw_config.text_alpha,
            content: pgw_config.text_content,
            fontSize: pgw_config.text_size,
            lineHeight: pgw_config.text_line_spacing,
            marginVertical: pgw_config.vertical,
            marginHorizontal: pgw_config.horizontal,
        });
        const base64Url = canvas.toDataURL();

        // 检查和创建一个带有类名 __wm 的元素
        const __wm = document.querySelector('.__wm');
        const watermarkDiv = __wm || document.createElement("div");

        // 倾斜角度
        let rotate = pgw_config.rotate_angle

        let width = window.screen.width + "px";
        let height = window.screen.height + "px";

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

window.onload = function () {
    __canvasWM();
}