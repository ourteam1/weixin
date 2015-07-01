var App = App || {};

// 图片延迟加载
App.ImageLazy = (function() {
	var _class_name = "img.lazy";
	var _options = {
		effect: "fadeIn",
		failurelimit: 10
	};
	return {
		init: function(class_name) {
			_class_name = class_name || _class_name;
			$(_class_name).lazyload(_options);
			return this;
		},
		loading: function() {
			$(_class_name).trigger('scroll');
		}
	}
})();

// 弹框
App.Dialog = (function() {
	return {
		init: function(title, body) {
			var el = '<div class="modal fade" id="ModalDialog" tabindex="-1" role="dialog" aria-labelledby="ModalDialogLabel" aria-hidden="true">' +
					'	<div class="modal-dialog">' +
					'		<div class="modal-content">' +
					'			<div class="modal-header">' +
					'				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
					'				<h4 class="modal-title" id="myModalLabel">${title}</h4>' +
					'			</div>' +
					'			<div class="modal-body"><p>{{html body}}</p></div>' +
					'			 <div class="modal-footer">' +
					'		        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>' +
					'		      </div>' +
					'		</div>' +
					'	</div>' +
					'</div>';
			$.tmpl(el, {title: title, body: body}).appendTo('.body');
		},
		show: function(title, body) {
			if ($('#ModalDialog').html() == null) {
				this.init(title, body);
			}
			else {
				$('#ModalDialog .modal-title').html(title);
				$('#ModalDialog .modal-body').html("<p>" + body + "</p>");
			}
			$('#ModalDialog').modal();
		}
	}
})();

function onBridgeReady() {
	WeixinJSBridge.call('hideToolbar');
	WeixinJSBridge.call('hideOptionMenu');
}
if (typeof WeixinJSBridge == "undefined") {
	if (document.addEventListener) {
		document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	} else if (document.attachEvent) {
		document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
		document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	}
} else {
	onBridgeReady();
}
