/**
 * 定义商品管理类
 */
var GoodsApp = $.extend({
    init: function() {
        //页面显示判断
        this.GoodsDetail.init();
    }
}, App || {});
/**
 * 定义商品详情页
 */
GoodsApp.GoodsDetail = (function() {
    return {
        init: function() {
            //关注
            $('#focus_goods').on('click', function() {
                //检查是否注册
                return false;
            });
        }
    }
})();
GoodsApp.init();