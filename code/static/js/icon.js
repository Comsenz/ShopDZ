$(function(){
	$('.icon-img').hover(function(){
			//alert('0000');
			$(this).next('p').css('display','block');
			
	},function(){
		//alert('1111');
		$(this).next('p').css('display','none');
	});
})
