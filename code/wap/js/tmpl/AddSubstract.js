var ajaxsubmit = true;
//购物车数量加减
$(function(){

	$('.cart-proprice').html($('.cart-proprice').prev().val());
	$('#shopcart').on('blur','.text_box',function(){
		var val = parseInt($(this).val());
		var price = $(this).parents('.cart-list-rl').siblings().children('input').val();
		if(isNaN(val) || val == '' || val <= 0){
			$(this).val(1);
		}else{
			$(this).val(parseInt($(this).val()));
		}
		$(this).parents('.cart-list-rl').siblings().children('p').children('span').html((parseFloat(price)*100)*parseInt($(this).val())/100);
		var hid =  $(this).parents('.cart-list-r').find('input').val();
		setbasket($(this), hid, $(this).val());
	})
	//加的效果
	$('#shopcart').on('click','.jia',function(){
		if(ajaxsubmit){
			if($(this).prev().find('.text_box').val() >= 99){
				shopdz_alert('超过商品购买上限！');
				return false;
			}
			ajaxsubmit = false;
			var hid =  $(this).parent().parent().parent().parent().siblings("input['type'=hidden]").val();
			var label = $(this).prev().find('.text_box');
			label.val(parseInt(label.val())+1);
		  	setbasket(label, hid, label.val());
		}
	});
	//减的效果
	$('#shopcart').on('click','.jian',function(){
		if(ajaxsubmit){
			ajaxsubmit = false;
			var n = $(this).next().find('.text_box').val();
			var num = parseInt(n) - 1;
			if(num <= 0){
				ajaxsubmit = true;
				shopdz_alert('商品数量不能小于1');
				return false;
			}
			var hid =  $(this).parent().parent().parent().parent().siblings("input['type'=hidden]").val();
			var label = $(this).next().find('.text_box');
			label.val(parseInt(label.val())-1);
		  	setbasket(label, hid, label.val());
		}
	});
})
//求商品总价
function sumprice(){
	var span = $('#sumprice');
	var sum = 0;
	$('.sumber').each(function(){
		if($(this).parents('li').find('input[name="id"]').attr('checked')){
			var num = parseFloat($(this).html());
			if(isNaN(num)){
	  			num = 0;
	  		}
		 	sum = sum + num;
		}
		$(this).html(returnFloat(parseFloat($(this).html())));
	});
	span.html(returnFloat(sum));
	if(parseFloat(sum) == 0){
		ajaxsubmit = false;
		$('#submit').addClass('bg');
		return ;
	}
	ajaxsubmit = true;
	$('#submit').removeClass('bg');
}




 