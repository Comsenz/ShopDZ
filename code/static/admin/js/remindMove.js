	$(function(){
		/*提示框随鼠标位置移动*/
		$('.Telescopic').mousemove(function(e){
		   	$('.tip-remind').show();
		    var top = e.pageY+18;
		    var left = e.pageX-70;
		    $('.tip-remind').css({
		     	'top' : top + 'px',
		     '	left': left+ 'px'
		    });
		});
	    $('.Telescopic').mouseout(function(){
	     	$('.tip-remind').hide();
	    });
		$(".Telescopic").click(function(){
			$(this).hide();
			$(".tips-list").hide();
			$(".tips-tit").css("color","#fff");
			$(".fa-lightbulb-o").addClass('fa-whitefont');
		    $(".tips").animate({
		        height:'32px',
		        width:'98px'
		    });
		    $(".tips").addClass('tips-small tips-smallbtn');
		    return false;
		});
		$(document).on('click','.tips-smallbtn',function(){
			var conH1 = $(".tips-titbox").outerHeight(true);
			//alert(conH1);
			var conH2 = $('.tips-list').outerHeight(true);
			//alert(conH2);
			var conH=conH1+conH2;
			$(".tips").animate({
		      height:(conH+12)+'px',
		      width:'100%',
		      padding:'6px 9px'
		    });
			$(".Telescopic,.tips-list").show();
			$(".tips-tit").css("color","#1ab394");
			$(".fa-lightbulb-o").removeClass('fa-whitefont');
		    $(".tips").removeClass('tips-small tips-smallbtn');
		});
	})
