//购物车数量加减
$(function(){
$('.cart-proprice').html($('.cart-proprice').prev().val());
$(".num-input1").live('keyup',function(){
	var cartprice=$(this).parents('.cart-list-rl').siblings().children('p');
	var price=$(this).parents('.cart-list-rl').siblings().children('input');
	var c=cartprice.html();
	var p=price.val();
	if(isNaN($(this).val()) || parseInt($(this).val())<1){
	   $(this).val("1");
	   c=p;
	   cartprice.html(p);
	   return;
	}else{				
	  	var total = parseFloat(p)*parseInt($(this).val());
	  	cartprice.html(total);
	    return;
	}

 })

//加的效果
$(".jia").click(function(){
	var n=$(this).prev().val();
	var num=parseInt(n)+1;
	if(num==0){ return;}
	$(this).prev().val(num);
	var cartprice=$(this).parents('.cart-list-rl').siblings().children('p');
	var price=$(this).parents('.cart-list-rl').siblings().children('input');
	var c=cartprice.html();
	var p=price.val();		
  	var total = parseFloat(p)*parseInt(num);
  	cartprice.html(total);
});
//减的效果
$(".jian").click(function(){
	var n=$(this).next().val();
	var num=parseInt(n)-1;
	if(num==0){ return}
	$(this).next().val(num);
	var cartprice=$(this).parents('.cart-list-rl').siblings().children('p');
	var price=$(this).parents('.cart-list-rl').siblings().children('input');
	var c=cartprice.html();
	var p=price.val();		
  	var total = parseFloat(p)*parseInt(num);
  	cartprice.html(total);
});
				
		
		
		
});
	

