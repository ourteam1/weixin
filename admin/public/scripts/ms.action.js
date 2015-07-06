var FileUpload = (function() {
	var boundx, boundy, eid, upload_url, $inp_image, $inp_thumb;

	var preview_w = 120; // 预览图宽
	var preview_h = 120; // 预览图高

	// 预览图
	var updatePreview = function(c) {
		if (parseInt(c.w) > 0) {
			var rx = preview_w / c.w;
			var ry = preview_h / c.h;

			$(".preview-image").css({
				width: Math.round(rx * boundx) + "px",
				height: Math.round(ry * boundy) + "px",
				marginLeft: "-" + Math.round(rx * c.x) + "px",
				marginTop: "-" + Math.round(ry * c.y) + "px"
			});

			$('#crop_offset').val(c.x + ',' + c.y + ',' + c.w + ',' + c.h + ',' + boundx + ',' + boundy);
		}
	}

	// 选择区域
	var scrop = function() {
		$(".original-image").Jcrop({
			onChange: updatePreview,
			onSelect: updatePreview,
			aspectRatio: 1
		}, function() {
			var bounds = this.getBounds();
			boundx = bounds[0];
			boundy = bounds[1];
		});
	}

	var upload_error = function(data, status, e) {

	}

	// 上传图片成功
	var upload_success = function(data, status) {
		if (data.error) {
			alert(data.error);
			return false;
		}
    
		$('.preview-image').attr('src', data.image_url);
		$inp_image.val(data.image);
		$inp_thumb.val(data.thumb);

		// 执行裁剪
//		scrop();
//		$('.btn-crop').on('click', function() {
//			var self = $(this);
//			var crop_offset = $('#crop_offset').val();
//			$.post($(this).attr('href'), {image: data.image, crop_offset: crop_offset}, function(res) {
//				if (res.error) {
//					alert(res.message);
//					return false;
//				}
//				$('#image').val(res.image);
//				$('#thumb').val(res.thumb);
//				alert('图片裁剪完成！');
//			}, 'json');
//			return false;
//		})
	}

	return {
		init: function(options) {
			$inp_image = $(options.inp_img) || null;
			$inp_thumb = $(options.inp_thumb) || null;
			eid = options.eid || '';
			upload_url = options.upload_url || '';

			$.ajaxFileUpload({
				url: upload_url,
				fileElementId: eid,
				secureuri: false,
				dataType: "json",
				success: upload_success,
				error: upload_error
			});
		}
	}
})();

$(function() {
	$('body').on('click', '.checkbox-inline', function(event) {
		var self = $(this);
		var checkbox = self.find('input:checkbox')[0];
		$(checkbox).click();
	});

	$('body').on('click', '.chkall', function(event) {
		event.stopPropagation();
		var self = $(this);
		$.each(self.closest('.panel-heading').next().find('input:checkbox'), function(i, ele) {
			$(ele).prop('checked', self.is(':checked'));
		})
	});

	$('body').on('change', '#iptGoodsImage', function() {
		var self = $(this);
		if (self.val() != '') {
			FileUpload.init({
				upload_url: self.attr('upload_url'),
				eid: 'iptGoodsImage',
				inp_img: '#image',
				inp_thumb: '#thumb',
			});
		}
		return false;
	});
        
        $('body').on('change', '#iptFocusImage', function() {
		var self = $(this);
		if (self.val() != '') {
			FileUpload.init({
				upload_url: self.attr('upload_url'),
				eid: 'iptFocusImage',
				inp_img: '#image',
			});
		}
		return false;
	});

	$("img.lazyload,.thumbnail>img").lazyload({
		placeholder: base_url("public/image/default.jpg"),
		effect: "fadeIn"
	});
});
