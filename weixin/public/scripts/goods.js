/**
 * 定义商品管理类
 */
var GoodsApp = $.extend({
	init: function() {
		this.Goods.render();
	}
}, App || {});

/**
 * 定义购物车中的商品总数
 */
GoodsApp.total_cart_goods_num = function() {
	var total_num = 0;
	$.each(goods_data, function(i, goods) {
		total_num += goods.cart_num || 0;
	});
	return total_num;
}

/**
 * 定义购物车中的商品总价格
 */
GoodsApp.total_cart_goods_price = function() {
	var total_price = 0;
	$.each(goods_data, function(i, goods) {
		total_price += goods.discount > 0 ? goods.discount * goods.cart_num : goods.price * goods.cart_num;
	});
	return total_price;
}

/**
 * 定义商品列表页
 */
GoodsApp.Goods = (function() {
	// 初始化购物车商品总数
	var set_total_num_price = function() {
		var total_num = GoodsApp.total_cart_goods_num();
		if (total_num > 0) {
			$('.btn-cart').html('<span class="glyphicon glyphicon-shopping-cart"></span> 我的购物车 <span class="badge">' + total_num + '</span>');
		}
		else {
			$('.btn-cart').html('<span class="glyphicon glyphicon-shopping-cart"></span> 我的购物车');
		}
	}

	return {
		init: function() {
			// 初始化商品页面
			$('.body').html($('#GoodsTemplate').html());

			// 定义商品分类
			var el = '<li class=""><a href="#tab${category_id}" role="tab" data-toggle="tab">${category_name}</a></li>';
			$.tmpl(el, category_data).appendTo('.app-topbar .nav');

			// 定义商品列表
			var el = '<div class="tab-pane fade" id="tab${category_id}"><div class="row"></div></div>';
			$.tmpl(el, category_data).appendTo('.app-content .tab-content');
			$.each(goods_data, function(i, goods) {
				goods.cart_num = goods.cart_num || 0;
				var $el = $.tmpl($('#GoodsItemTemplate').html(), goods);
				$el.appendTo('.tab-content #tab' + goods.category_id + ' .row');
				// 加入购物车事件
				$('.btn-add-shopping-cart', $el).on('click', function() {
					goods.cart_num = goods.cart_num * 1 + 1;
					$(this).html('<span class="glyphicon glyphicon-shopping-cart"></span> 购买 <span class="badge">' + goods.cart_num + '</span>');
					set_total_num_price();
					return false;
				});
				// 商品详情
				$('.thumbnail > a', $el).on('click', function() {
					GoodsApp.GoodsDetail.render(goods);
					return false;
				});
			});
		},
		render: function(tabid) {
			this.init();

			// 显示第一个分类
			tabid = tabid || (window.location.hash != '' ? window.location.hash : '#tab1');
			$('.app-topbar .nav a[href="' + tabid + '"]').tab('show');

			// 显示购物车总数
			set_total_num_price();

			// 图片延迟加载
			GoodsApp.ImageLazy.init();

			// 选中商品分类
			$('.app-topbar .nav a').on('shown.bs.tab', function() {
				GoodsApp.ImageLazy.loading();
			});

			// 进入购物车页面
			$('.btn-cart').on('click', function() {
				GoodsApp.Cart.render();
				return false;
			});
		}
	}
})();

/**
 * 定义商品详情页
 */
GoodsApp.GoodsDetail = (function() {
	return {
		init: function(goods) {
			var el = $.tmpl($('#GoodsDetailTemplate').html(), goods);
			$('img', $(el)).each(function() {
				$(this).attr('data-original', $(this).attr('src')).addClass('lazy').removeAttr('src');
			})
			$('.body').html(el);
		},
		render: function(goods) {
			// 获取商品分类当前激活的分类
			var tabid = $('.app-topbar .nav li.active a').attr('href');

			// 初始化商品详情页面
			this.init(goods);

			// 图片延迟加载
			GoodsApp.ImageLazy.init();

			// 返回商品列表
			$('.btn-back,.app-topbar a').on('click', function() {
				GoodsApp.Goods.render(tabid);
				return false;
			});
		}
	}
})();

/**
 * 定义购物车页面
 */
GoodsApp.Cart = (function() {
	// 初始化总数、总价格
	var init_total_num_price = function() {
		$('.app-content .total-num').text(GoodsApp.total_cart_goods_num());
		$('.app-content .total-price').text(GoodsApp.total_cart_goods_price());
	}

	// 购物车商品列表
	var init_cart_goods = function(i, goods) {
		goods.cart_num = goods.cart_num || 0;
		if (!goods.cart_num) {
			return;
		}

		var $el = $.tmpl($('#CartGoodsTemplate').html(), goods);
		$el.appendTo('.app-content .cart-list');

		// 减
		$('.btn-minus', $el).on('click', function() {
			if (goods.cart_num * 1 == 0) {
				return false;
			}
			goods.cart_num = goods.cart_num * 1 - 1;
			$('.cart-num', $el).text(goods.cart_num);
			init_total_num_price();
			return false;
		});

		// 加
		$('.btn-plus', $el).on('click', function() {
			goods.cart_num = goods.cart_num * 1 + 1;
			$('.cart-num', $el).text(goods.cart_num);
			init_total_num_price();
			return false;
		});
	}

	return {
		init: function() {
			// 初始化购物车页面
			$('.body').html($('#CartTemplate').html());

			// 初始化购物车商品列表
			$.each(goods_data, init_cart_goods);

			// 初始化购物车商品总数、总价格
			init_total_num_price();
		},
		render: function() {
			// 获取商品分类当前激活的分类
			var tabid = $('.app-topbar .nav li.active a').attr('href');

			// 初始购物车页面
			this.init();

			// 返回商品列表
			$('.btn-back,.app-topbar a').on('click', function() {
				GoodsApp.Goods.render(tabid);
				return false;
			});

			// 去结算，然后填写收货人信息
			$('.btn-submit-cart').on('click', function() {
				var btn = $(this);
				btn.button('loading');
				var cart_data = [];
				$.each(goods_data, function(i, goods) {
					if (!goods.cart_num) {
						return;
					}
					cart_data.push({
						goods_id: goods.goods_id,
						goods_price: goods.discount > 0 ? goods.discount : goods.price,
						cart_num: goods.cart_num
					});
				});
				$.post(site_url('goods/cart'), {cart: cart_data}, function(res) {
					btn.button('reset')
					if (res.error) {
						GoodsApp.Dialog.show('提示', res.error);
						return false;
					}
					GoodsApp.Consignee.render();
				}, 'json');
				return false;
			});
		}
	}
})();

/**
 * 定义收货人页面
 */
GoodsApp.Consignee = (function() {
	return {
		init: function() {
			consignee_data.amount = user_data.amount;
			$el = $.tmpl($('#ConsigneeTemplate').html(), consignee_data);
			$('.body').html($el);
		},
		render: function() {
			this.init();

			// 返回商品列表
			$('.btn-back,.app-topbar a').on('click', function() {
				GoodsApp.Cart.render();
				return false;
			});

			// 选中支付方式
			$('.payment-model').on('click', function() {
				var payment_model = $(this).val();
				if (payment_model == '微信支付') {
					GoodsApp.Dialog.show('提示', '微信支付尚未开通，敬请期待！');
					return false;
				}
				if (payment_model == '余额支付') {
					var total_price = 0;
					$.each(goods_data, function(i, goods) {
						total_price += goods.discount > 0 ? goods.discount * goods.cart_num : goods.price * goods.cart_num;
					});
					if (!user_data.amount || user_data.amount * 1 < total_price * 1) {
						GoodsApp.Dialog.show('提示', '账户余额不足！');
						return false;
					}
					$('.user-amount strong', $(this).parent()).html(user_data.amount - total_price * 1);
				}
			});

			// 完善收货人信息，并下订单
			$('.btn-submit-consignee').on('click', function() {
				var self = $(this);
				if ($.trim($("#Consignee_username").val()) == "") {
					GoodsApp.Dialog.show('提示', '请填写收货人的姓名！');
					return false;
				}
				if ($.trim($("#Consignee_mobile").val()) == "") {
					GoodsApp.Dialog.show('提示', '请填写收货人的手机号！');
					return false;
				}
				if ($.trim($("#Consignee_address").val()) == "") {
					GoodsApp.Dialog.show('提示', '请填写收货人的详细地址！');
					return false;
				}
				self.button('loading');
				var data = $('.consignee-form').serialize();
				$.post(site_url('goods/order'), data, function(res) {
					self.button("reset");
					if (res.error) {
						GoodsApp.Dialog.show('提示', res.error);
						return false;
					}
					GoodsApp.Order.render(res);
				}, 'json');
				return false;
			});
		}
	}
})();

/**
 * 定义订单页面
 */
GoodsApp.Order = (function() {
	return {
		init: function(order) {
			var el = $('#OrderTemplate').html();
			$('.body').html($.tmpl(el, {order_sn: order.order_sn || '-'}));
		},
		render: function(order) {
			this.init(order);

			// 返回商品列表
			$('.app-topbar a').on('click', function() {
				window.location.reload();
				return false;
			});
		}
	}
})();

GoodsApp.init();
