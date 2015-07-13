/**
 * 定义订单管理类
 */
var OrderApp = $.extend({
	init: function() {
		if (order_sn != '') {
			$.each(order_data, function(i, order) {
				if (order.order_sn == order_sn) {
					OrderApp.OrderInfo.render(order);
				}
			});
			return false;
		}
		this.Order.render();
	}
}, App || {});

/**
 * 定义订单列表页面
 */
OrderApp.Order = (function() {
	// 订单列表
	var addOrder = function(i, order) {
		var $el = $.tmpl($('#OrderItemTemplate').html(), order);
		// 查看订单详情
		$el.appendTo('.app-content .list-group').css('cursor', 'pointer').on('click', function() {
			OrderApp.OrderInfo.render(order);
			return false;
		});
	}

	return {
		init: function() {
			var el = $('#OrderTemplate').html();
			$('.body').html(el);
			$.each(order_data, addOrder);
		},
		render: function() {
			this.init();
		}
	}
})();

/**
 * 定义订单详情页面
 */
OrderApp.OrderInfo = (function() {
	return {
		init: function(order) {
			var $el = $.tmpl($('#OrderInfoTemplate').html(), order);
			$('.body').html($el);

			$.each(order.order_info, function(i, goods) {
				var $el = $.tmpl($('#OrderInfoGoodsTemplate').html(), goods);
				$el.appendTo('.orderinfo-goods');
				// 查看商品详情
				$('.goods-order-detail', $el).on('click', function() {
					OrderApp.GoodsDetail.render(order, goods);
				})
			});
		},
		render: function(order) {
			this.init(order);

			// 图片延迟加载
			OrderApp.ImageLazy.init();

			// 返回商品列表
			$('.btn-back,.app-topbar a').on('click', function() {
				OrderApp.Order.render();
				return false;
			});
		}
	}
})();

/**
 * 定义商品详情页
 */
OrderApp.GoodsDetail = (function() {
	return {
		init: function(goods) {
			var el = $.tmpl($('#GoodsDetailTemplate').html(), goods);
			$('img', $(el)).each(function() {
				$(this).attr('data-original', $(this).attr('src')).addClass('lazy').removeAttr('src');
			});
			$('.body').html(el);
		},
		render: function(order, goods) {
			this.init(goods);

			// 图片延迟加载
			OrderApp.ImageLazy.init();

			// 返回商品列表
			$('.btn-back,.app-topbar a').on('click', function() {
				OrderApp.OrderInfo.render(order);
				return false;
			})
		}
	}
})();

OrderApp.init();
