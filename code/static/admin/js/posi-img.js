(function($) {
	$.fn.posi = function(config){
		var setting = $.extend({}, {
			class: false, /* 传入的图片元素的类，可不填，不填就按该对象处理 */
			default_img: "http://"+window.location.host+"/static/admin/images/no_photo.png", /* 默认的图片，无图片时显示 */
			true_img: '', /* 显示的图片，特殊需求时可配置，一般为空就好 */
			new_img_id: 'view_big_img', /* 默认创建的图片元素的外层DIV的ID，一般不用配置 */
			width: 'auto', /* 默认的图片宽度，不用配置 */
			height: 'auto', /* 默认的图片高度，不用配置 */
			max_width: 300, /* 图片的最大高度，可配置 */
			max_height: 300, /* 图片的最大宽度，可配置 */
			z_index: 100,  /* 图片的层级，可配置 */
			position:'absolute'
		}, config), createDiv = function(){
			/* 创建图片元素函数 */
			if($('#'+setting.new_img_id).length < 1){
				var newdiv = $('<div></div>');
				newdiv.attr('id',setting.new_img_id);
				newdiv.css('display','none');
				newdiv.html('<img src="'+setting.default_img+'"/>');
				$('body').append(newdiv);
			}
		}, setStyleDiv = function(){
			/* 图片样式函数 */
			var newImg = new Image();
			newImg.src = setting.true_img;
			newImg.onerror= function(){
				this.onerror = null;
				this.src = setting.default_img;
			}
			
			newImg.onload = function(){
				setting.width = parseInt(newImg.width);
				setting.height = parseInt(newImg.height);
				/* 处理图片的宽高超过最大时的取值 */
				if(setting.width > setting.max_width && setting.height > setting.max_height){
					if(setting.width > setting.height){
						setting.width = setting.max_width;
						setting.height = '';
					}else{
						setting.width = '';
						setting.height = setting.max_height;
					}
				}else if(setting.width>setting.max_width){
					setting.width = setting.max_width;
					setting.height= '';
				}else if(setting.height>setting.max_height){
					setting.width = '';
					setting.height = setting.max_height;
				}
				/* 设置图片的样式 */
				$('#'+setting.new_img_id).find('img').css({
					'width': setting.width,
					'height': setting.height,
					'border-radius': '3px'
				});
				/* 设置图片外层DIV的样式 */
				$('#'+setting.new_img_id).css({
					'display':'none',
					'position': setting.position,
					'background-color': '#fff',
					'border-radius': '5px',
					'box-shadow': '0 0 10px #ccc',
					'padding': '10px 10px 5px 10px',
					'overflow': 'hidden',
					'z-index': setting.z_index
				});
				/* 显示DIV */
				$('#'+setting.new_img_id).find('img').attr('src',newImg.src);
				$('#'+setting.new_img_id).fadeIn();
			}
		}, setPositionDiv = function(ev){
			/* 图片定位函数 */
			var x = ev.clientX, y = ev.clientY, winWidth = window.innerWidth,winHeight = window.innerHeight, imgHeight = $('#'+setting.new_img_id).find('img').height(), imgWidth = $('#'+setting.new_img_id).find('img').width();
			if(x+imgWidth+15 > winWidth)
				x = x - imgWidth - 40;
			if(y+imgHeight+25 > winHeight)
				y = y - imgHeight - 60;
			$('#'+setting.new_img_id).css({
				top: y+25+'px',
				left: x+15+'px',
			});
		};
		/**
		 * 操作开始
		 */
		createDiv();
	    var $img = (setting.class && ('.'+setting.class)) || ('#'+$(this).attr('id'));
		$(document).on('mouseover',$img,function(ev){
			clearTimeout(setting.timeout);
			if($(this).attr('url').length > 1){
				setting.true_img = $(this).attr('url');
			}else{
				setting.true_img = setting.default_img;
			}
			setStyleDiv();	
		})
		$(document).on('mousemove',$img,function(ev){
			setPositionDiv(ev);	
		})
		$(document).on('mouseout',$img,function(ev){
			setting.timeout = setTimeout(function(){$('#'+setting.new_img_id).fadeOut()},200);
		})
	}
})(jQuery);