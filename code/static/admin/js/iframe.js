$(window).load(function() {
	var windowH = $(window.parent).height(),
		parentDOM = $(window.parent.document),
		parentDOMHeaderH = parentDOM.find(".head-box").height(),
		iframeH = parentDOM.find("#myIframeId").height(),
		iframeBoxPaddingBottom = parseInt(parentDOM.find(".content-iframe").css("paddingBottom").replace("px", ""));
		var minH = parentDOMHeaderH + iframeH + iframeBoxPaddingBottom + 28,
		bodyH = iframeH;
		
	if(windowH > minH) {
		bodyH = iframeH + (windowH - minH) - 3;
		parentDOM.find("#myIframeId").height(bodyH);
	}

	if(window.parent.iframeW === undefined) {
		window.parent.iframeW = parentDOM.find("#myIframeId").width();
		window.parent.iframeW = window.parent.iframeW <= 1093 ? 1093 : window.parent.iframeW;
	}

	parentDOM.find("#myIframeId").width(window.parent.iframeW);
	$(".scrollbox").height(bodyH);
	reloadTableScroll();

	if($(".comtable-box").length && $(".com-table").length) {
			var tableOffsetTop = $(".com-table").offset().top + 10,
				tableHeight = $(".com-table").height() + 10,
				scrollEndF = function() {
					var bartop = $("body").height() - tableOffsetTop + Math.abs($(".scrollbox").scrollTop());

					bartop = bartop > tableHeight ? tableHeight : bartop;
//					$(".comtable-box .iScrollHorizontalScrollbar").css("top", bartop);
					$(".comtable-box .iScrollHorizontalScrollbar").css("bottom", "-8px");
					$(".comtable-box .iScrollHorizontalScrollbar").show();
				},
				initScroll = setInterval(function() {
					if($(".comtable-box .iScrollHorizontalScrollbar").length) {
						scrollEndF();
						clearInterval(initScroll);
					}
				}, 100);

			$(".scrollbox").scroll(function() {
				scrollEndF();
			});
	}
});

/**
 * 添加表格滚动条
 * @return {[type]} [description]
 */
function reloadTableScroll() {
	if($(".comtable-box").length) {
        window.iframeMyScroll1 = new IScroll('.comtable-box', {
            scrollX: true,
            scrollbars: true,
            bounce: false,
            disableMouse: true,
            disablePointer: true,
            interactiveScrollbars: true,
        });
    }
}

//三级导航
$(".transverse-nav>li").bind('click',function(){
	$(this).addClass("activeFour").siblings().removeClass();
	$($(".tab-conbox")[$(this).index()]).show().siblings().hide();
})

$('.open-span').bind('mousemove',function(){
    if($('.tips-titbox').children('span').hasClass('open-span')){
	    $(this).bind('mousemove',function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($(this),e,'点击折叠');
		})
	} else {
	   	
	}
})

$('.open-span').mouseout(function(){
	$('.tip-remind').remove();
});

//点击展开 
$('.tipsbox').find(".open-span").on("click", function() {
	var flag = !$(this).hasClass("Telescopic"), tipH = 0;
	if(flag){
		//alert('无');
		var conH1 = $(this).parents('.tips').find(".tips-titbox").height();
		var ol = $(this).parents('.tipsbox').find('ol');
		var conH2 = ol.innerHeight();
		var conW2 = ol.width();
//		ol.width(178);
//		ol.height(10);

		//$('body').find('.tips-list').show();
		$(this).parents(".tips").animate({
			height:'28px',
			width:'178px',
		});
		$(this).parents(".tipsbox").find('.tips-list').hide();
		$(this).parents(".tipsbox").find('.tips-list').animate({
			//height:conH2+'px',
			//width:'100%'
			//height:'28px',
			//width:'178px',
		});

		$(this).removeClass('open-span');
		$(this).unbind('mousemove');
		$(this).addClass('Telescopic');
		$('.Telescopic').bind('mousemove',function(){
			e=arguments.callee.caller.arguments[0] || window.event; 
			remindNeed($('.Telescopic'),e,'点击展开');
		})
		
		$('.Telescopic').mouseleave(function(){
			$('.tip-remind').remove();
		});
		$('.tipsbox').css('background','transparent');
		//$('.tipsbox').css('background','#d8edea');
		tipH = $(".tipsbox").height();
	} else {
		//alert('有');
		tipH = $(".tipsbox").height();
		//$(this).parents(".tipsbox").find('.tips-list').hide();
		var conH1 = $(this).parents('.tips').find(".tips-titbox").height();
		var ol = $(this).parents('.tipsbox').find('ol');
		var conH2 = ol.innerHeight();
		var conW2 = ol.width();
		ol.width(178);
		ol.height(10);
		$(this).parents(".tips").animate({
			//height:'28px',
			width:'100%',
		});
		$(this).removeClass('Telescopic');
		$(this).unbind('mousemove');
		$(this).addClass('open-span').addClass('rdreload');
		$('.open-span').bind('mousemove',function(){
		    if($('.tips-titbox').children('span').hasClass('open-span')){
			    $(this).bind('mousemove',function(){
					e=arguments.callee.caller.arguments[0] || window.event; 
					remindNeed($(this),e,'点击折叠');
				})
			} else {
			   	
			}
		})
		
		//$(this).unbind('mousemove');
		
		$(this).parents(".tipsbox").find('.tips-list').animate({
			height:conH2+'px',
			width:'100%'
		});
		$(this).parents(".tipsbox").find('.tips-list').show();
//		$('.Telescopic').bind('mousemove',function(){
//			e=arguments.callee.caller.arguments[0] || window.event; 
//			remindNeed($('.Telescopic'),e,'点击展开');
//		})
			
		$('.Telescopic').mouseleave(function(){
			$('.tip-remind').remove();
		});

		$('.tipsbox').css('background','#d8edea');
	}
	
	return false;
})


