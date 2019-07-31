$(function(){
	
	//按钮一鼠标悬停状态（btnhover1）
//	$('.btn1').mouseover(function(){
//		$(this).addClass('btnhover1');
//	});
//	$('.btn1').mouseout(function(){
//		$(this).removeClass('btnhover1');
//	});
	//按钮一鼠标悬停状态（btnhover1）
//	$('.btn2').mouseover(function(){
//		$(this).addClass('btnhover2');
//	});
//	$('.btn2').mouseout(function(){
//		$(this).removeClass('btnhover2');
//	});
	
	//表格滑过隔行变色
	 //var obj=document.getElementById("tb");
	 var rows=$('.tablelist1 tr');
	 for(var i=0;i<rows.length;i++){  //循环表格行设置鼠标事件
	   rows[i].onmouseover=function(){this.style.background="#f5f5f5";}
	   rows[i].onmouseout=function(){this.style.background="";}
	 }
	
	
	//鼠标悬停输入框添加样式
	$(document).on('mouseover','.com-inp1,.com-textarea1,.juris-dl-sele',function(){
		$(this).addClass('inp1-hover');
	})

	$(document).on('mouseout','.com-inp1,.com-textarea1,.juris-dl-sele',function(){
		$(this).removeClass('inp1-hover');
	})

	$(document).on('focus','.com-inp1',function(){
		$(this).addClass('inp1-hover');	
	})
	$(document).on('blur','.com-inp1',function(){
		$(this).removeClass('inp1-hover');
	})
	



//鼠标悬停时给dl添加样式
	$('.jurisdiction dl').hover(function(){
		$(this).addClass('juris-dl-hover');
		$(this).children('dt').addClass('black-font');
		$(this).find('.remind1').addClass('green-font');
		var i=$(this).index();
		var s=(i+1)%2;
		if(s==0){
			$(this).css('background','#fff');
		}
		
	},function(){
		$(this).removeClass('juris-dl-hover');
		$(this).children('dt').removeClass('black-font');
		$(this).find('.remind1').removeClass('green-font');
		var i=$(this).index();
		var s=(i+1)%2;
		if(s==0){
			$(this).css('background','#fafafa');
		}
	});
	
	$('.jurisdiction2 dl').hover(function(){
		$(this).children('dt').addClass('black-font');
		$(this).find('.remind1').addClass('green-font');
	},function(){
		$(this).children('dt').removeClass('black-font');
		$(this).find('.remind1').removeClass('green-font');
	})
	
	
	
	
	//搜索框鼠标悬浮样式
	$('.search-sele').mouseover(function(){
		$('.search-sele,.search-inp').addClass('inp1-hover');
		$('.search-inp').css('border-left','none');
	});
	$('.search-sele').mouseout(function(){
		$('.search-sele,.search-inp').removeClass('inp1-hover');
	});	
	
	$('.search-inp').mouseover(function(){
		$('.search-sele,.search-inp').addClass('inp1-hover');
		$('.search-inp').css('border-left','none');
	});
	$('.search-inp').mouseout(function(){
		$('.search-sele,.search-inp').removeClass('inp1-hover');
	});		
	
	
	
	//时间选择鼠标悬浮样式
	$('.time2').mouseover(function(){
		$(this).find('input').addClass('inp1-hover');
		$('.time-label,.timeto').css('color','#23C6C8');
	});
	$('.time2').mouseout(function(){
		$(this).find('input').removeClass('inp1-hover');
		$('.time-label,.timeto').css('color','');
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
})
