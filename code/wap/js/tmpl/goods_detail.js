var  goods_storage =  0;  //当前商品库存
var  goods_list = {};	//商品列表
var now_goods =  {};    //当前选中的商品
var now_goods_spec = {};
var sell_goods_list =  [];
var  all_no_goods = false;
var footPrintUrl= ApiUrl+"/FootPrint/footprint";
function goods_detail_size(){
	var specH=$(".spec-dl1").height();
	$(".spec-dl1-dt").height(specH);
	$(".spec-dl1-dt").width(specH);
	$(".spec-dl1-dd").css("padding-left",(specH+5)+'px');
}

$(function(){
	
	var id = intval(get('id'));
	var fromid = intval(get('fromid'));

	if(fromid != ''){

		addcookie('fromid',fromid);
	}
	if(id<1){
		shopdz_alert('商品id错误',1,function(){
			window.location.href = WapSiteUrl;
		});
		return;
	}
	$.get(ApiUrl+'/Goods/goods_detail',{"id":id},function(data){
		if(data.code==0){
			var shop_name = getwebConf('shop_name');
            if(data.data.goods_jingle != ''){
                $("[name='keywords']").attr('content',data.data.goods_jingle+'-'+shop_name);
                $("[name='discription']").attr('content',data.data.goods_jingle+'-'+shop_name);
            }else{
                $("[name='discription']").attr('content',data.data.goods_jingle+'-'+shop_name);
            }
			goods_list =  data.data.goods_list;
			sell_goods_list  =   data.data.sell_goods_list;
			if(countObj(goods_list)==1){
				now_goods  =  getFirstValue(goods_list);
				now_goods_spec = now_goods.goods_spec;
			}
			var  html = template('goods_detail_template',data.data);
			$('.wrapper').append(html);
			$('#qrcode').attr('src',data['data']['qr_code']);//分享二维码
			$('.http').html(WapSiteUrl+'/goods_detail.html?id='+id);//分享链接
			/* 查询是否收藏过 */
			getdata(ApiUrl+'/Favorites/hasfavorites',{key:key,fav_id:data.data.goods_common_id},function(info){
				if(!info['code'] && info['data']){
					$('.collect-icon').addClass('collect-icon-cover');
				}
			});
			if($('.choice-ul').length < 2) {
				for(j in goods_list) {
					if(!in_array(j,sell_goods_list)){
						$('.spec_'+j).addClass('gray-f0');
					}
				}
			}
			if(data.data.goods_storage == 0){
				all_no_goods = true;
				$('.do_buy').addClass('disable_buy');
				$('.spec_value_li').addClass('gray-f0');
			}
			initPage();
			numAddClass($("#goods_num"),goods_storage);
			settitle();
						//焦点图
		    var swiper = new Swiper('.swiper-container', {
		        pagination: '.swiper-pagination',
		        nextButton: '.swiper-button-next',
		        prevButton: '.swiper-button-prev',
		        paginationClickable: true,
		        spaceBetween: 30,
		        centeredSlides: true,
		        autoplay: 2500,
		        autoplayDisableOnInteraction: false
		    });
		    
		    //调用返回顶部的函数
		    goTopEx();
		    $(function(){
		    	$('.spec-con').bind('click',function(){
					has = $('.add-shopcart').hasClass('disable_buy');
					classname = has ?  ' disable_buy ' :'';
		    		var  _html = '<span class="choice-back"><img src="img/detail-back.png" class="choice-back-img"/></span><input type="button"   value="立即购买" data-old_value="立即购买"  class="buy_now choice-com-btn now-buy do_buy'+classname+'" /><input type="button"  value="加入购物车"  data-old_value="加入购物车" class="'+classname+'add_basket choice-com-btn add-shopcart do_buy"/>';
					$('#button_div').html(_html);
					if(all_no_goods){
						$('.do_buy').addClass('disable_buy');	
					}
					$(".cover").css("display","block")
					$('.spec-none').slideDown('fast');
					
				});
				$(document).on('click','.choice-back,.cover',function(){
					$('.spec-none').slideUp('fast');
					$(".cover").css("display","none");
				});
		    })
			goods_detail_size();
			if(key){
				getdata(footPrintUrl,{key:key,common_id:data.data.goods_common_id},function(result){})
			}
		}else{
			shopdz_alert(data.msg,1,function(){
				window.location.href = WapSiteUrl+'?t='+Math.random();
			});
		}		
	},'json');






    function jumpPage1(){
    	self.location='settlement.html';
    }
	//商品数量加号减号点击事件
	$(document).on('click','#increase_goods_num',function(){
		if(typeof now_goods.goods_storage == 'undefined'){
			shopdz_alert('请先选择规格');
			return  true;
		}
		var goods_num =  intval($('#goods_num').val());
		var goods_storage  =  intval(now_goods.goods_storage);
		if(goods_num < goods_storage){
			goods_num++;
		}else{
			if(goods_storage>0){
				//shopdz_alert('商品库存为:'+intval(goods_storage));
				shopdz_alert('超出库存数量！');
		    }
			goods_num  = goods_storage;
		}
		if(goods_num<1){
			goods_num = 1;
		}
		
		$("#goods_num").val(goods_num);
		$(".goods_num").text(goods_num);
		numAddClass($("#goods_num"),goods_storage);
	});
	$(document).on('click','#decrease_goods_num',function(){
		if(typeof now_goods.goods_storage == 'undefined'){
			shopdz_alert('请先选择规格');
			return  true;
		}
		var goods_num =  intval($('#goods_num').val());
		var goods_storage  =  intval(now_goods.goods_storage);
		if(goods_num < goods_storage){
			goods_num--;
		}else{
			goods_num  = goods_storage;
			goods_num--;
			shopdz_alert('超出库存数量！');
		}
		if(goods_num<1){
			shopdz_alert('商品数量不能小于1');
			goods_num = 1;
		}
		$("#goods_num").val(goods_num);
		$(".goods_num").text(goods_num);
		numAddClass($("#goods_num"),goods_storage);
	});
	//商品数量直接输入
	$(document).on('blur','#goods_num',function(){
		if(typeof now_goods.goods_storage == 'undefined'){
			$("#goods_num").val(1);
			shopdz_alert('请先选择规格');
			return  true;
		}
		var goods_num =  intval($('#goods_num').val());
		var goods_storage  =  intval(now_goods.goods_storage);
		if(goods_num > 99) {
			goods_num  = 99;
			shopdz_alert('超出最大购买量！');
		}
		if(goods_num  > goods_storage){
			goods_num  = goods_storage;
			shopdz_alert('超出库存数量！');
		}
		if(goods_num<1){
			goods_num = 1;
		}
		
		$("#goods_num").val(goods_num);
		$(".goods_num").text(goods_num);
		numAddClass($("#goods_num"),goods_storage);
	});
	//购买和加入购物车按钮点击之后改成确定
	$(document).on('click','.change_two',function(){
		var  _html = '<span class="choice-back"><img src="img/detail-back.png" class="choice-back-img"/></span><input type="button"  value="确定"  class="do_buy choice-com-btn add-shopcart " id="submit" style="width:80%"  />';
		$('#button_div').html(_html);
	});
	function settitle() {
		title = $('.details1 p:first').html();
		$(document).attr("title",title+'-'+getwebConf('shop_name'));

	}
	//立即购买和加入购物车按钮
	$(document).on('click','.buy_now',function(){
		if($(this).hasClass('disable_buy')){
			shopdz_alert('商品库存不足');
				return true;	
		}
		$('#op').val('buy');
		var goods_id =   intval(now_goods.goods_id);
		var  goods_num  =  intval($("#goods_num").val());
		if(!goods_id){
			$(".cover").css("display","block")
			$('.spec-none').slideDown();
			return true;
		}
		var goods_storage  =  intval(now_goods.goods_storage);
		if(goods_storage==0){
			shopdz_alert('商品库存不足');
			return true;
		}
		if(goods_num>goods_storage){
			shopdz_alert('商品库存不足');
		}else{
			window.location.href=   WapSiteUrl+'/settlement.html?goods_id='+goods_id+'&goods_num='+goods_num;
		}

	});
	$(document).on('click','.add_basket',function(){
		if($(this).hasClass('disable_buy')){
			shopdz_alert('商品库存不足');
				return true;	
		}
		$('#op').val('basket');
		var goods_id =   intval(now_goods.goods_id);
		var  goods_num  =  intval($("#goods_num").val());
		if(!goods_id){
			if($(".cover").css("display") =='block'){
				shopdz_alert('请先选择规格和数量');
			}else{
				$(".cover").css("display","block")
				$('.spec-none').slideDown();
				//$('.wrapper').css('overflow-y','hidden');
			}
			return true;
		}
		var goods_storage  =  intval(now_goods.goods_storage);
		if(goods_storage==0){
			shopdz_alert('该商品暂时无货');	
			return false;
		}
		if(goods_num>goods_storage){
				shopdz_alert('商品库存不足');
				return false;
		}
		$(".choice-back").click();
		setbasket($('#goods_num'),goods_id,goods_num,'detail',function(data){
			if(data.code==0){
				add_basket();
				$('.head-cartimg').find('span').css('display','block');
				$('.head-cartimg').find('span').html(data.data.num)
			}else{
				shopdz_alert(data.msg);
			}
		});	
	});


	$(document).on('click','#submit',function(){
		var  op =  $('#op').val();
		var goods_id =   intval(now_goods.goods_id);
		var  goods_num  =  intval($("#goods_num").val());
		if(!goods_id){
			shopdz_alert('请先选择规格和数量');	
			return false;	
		}
		if(op == 'buy'){
			if(!key){
				window.location.href=   WapSiteUrl+'/ordernow.html?goods_id='+goods_id+'&goods_num='+goods_num;
			}else{
				var goods_storage  =  intval(now_goods.goods_storage);
				if(goods_num>goods_storage){
					shopdz_alert('商品库存不足');
					return;
				}else{
					window.location.href=   WapSiteUrl+'/settlement.html?goods_id='+goods_id+'&goods_num='+goods_num;	
				}
			}
		}
		if(op == 'basket'){   //加入购物车按钮
			//调用  basket.js里面的通用函数
			var goods_storage  =  intval(now_goods.goods_storage);
			if(goods_num>goods_storage){
				shopdz_alert('商品库存不足');
				return;
			}
			$(".choice-back").click();
			setbasket($('#goods_num'),goods_id,goods_num,'detail',function(data){
				if(data.code==0){
					add_basket();
					$('.head-cartimg').find('span').css('display','block');
					$('.head-cartimg').find('span').html(data.data.num);
				}else{
					shopdz_alert(data.msg);
				}
			});	
		}
	});

	//点击规格之后选择商品
	$(document).on('click','.spec_value_li',function(){
		var _this = this;
		var  spec_id =  intval($(this).attr('data-spec_id'));
		var  spec_value_id =  intval($(this).attr('data-spec_value_id'));
		now_goods_spec[spec_id]   = spec_value_id;
		var  now_spec_ids  = [];
		var  spec_value_ids =   [];
		if($(_this).hasClass('gray-f0')){
			return;
		}
			if($(_this).hasClass('active-bor')){
				$(_this).removeClass('active-bor').find('span').removeClass('active-bg');
				$(_this).parents('.choice-box1').siblings('.choice-box1').find('.spec_value_li').removeClass('gray-f0');
			}else{
					$(_this).siblings('.spec_value_li').removeClass('active-bor').find('span').removeClass('active-bg');
					$(_this).addClass('active-bor').find('span').addClass('active-bg');
				spec_id_ = [];
				spec_gray_ = [];
				for(i in  sell_goods_list){
					var sell_goods  =  sell_goods_list[i];
					if(in_array(spec_value_id,sell_goods)){
						for(j in sell_goods) {
							 if(spec_value_id !=sell_goods[j]){
								spec_id_.push(sell_goods[j]);
								//spec_id_ = sell_goods[j];
							 }
						}
					}else{
						$(_this).parents('.choice-box1').siblings('.choice-box1').find('.spec_value_li').addClass('gray-f0');
					}
				}
				for(k in spec_id_) {
					if(k==0){//第一次执行
						$('.spec_'+spec_id_[k]).parent().find('.spec_value_li').addClass('gray-f0');
					}
					$('.spec_'+spec_id_[k]).removeClass('gray-f0');
				}
		}
		
		$('.active-bor').each(function() {
			spec_value_ids.push($(this).attr('data-spec_value_id'));
		});
		spec_value_ids.sort(function(m,n){
			return m-n;
		});
		var  id_key =   spec_value_ids.join('_');
		if($('.choice-ul').length > spec_value_ids.length) {
				now_goods = {};
				$('.spec_goods_name').html('请选择规格和数量');
				$(".goods_num").text('');
				$('.x').text('');
				
		}else{
			if(typeof goods_list[id_key]  =="undefined"){
				return true;
			};
			now_goods  =  goods_list[id_key];
			var goods_num =  intval($('#goods_num').val());
			var goods_storage  =  intval(now_goods.goods_storage);
			if(goods_num  > goods_storage){
				goods_num  = goods_storage;
			}
			if(goods_storage==0){
				goods_num=1;
				$('.do_buy').addClass('disable_buy');
			}else{
				$('.do_buy').removeClass('disable_buy');
			}
			if(goods_num<0){
				goods_num = 1;
			}
			$("#goods_num").val(goods_num);
			$(".goods_num").text(goods_num);
			$('.x').text(' x');
			$('#goods_id').text(now_goods.goods_id);
			$('.goods_image').attr('src',now_goods.goods_image);
			$('.goods_price').text(now_goods.goods_price);
			$('.spec_goods_name').text(now_goods.spec_goods_name);
		}
	});

	/* 添加收藏商品 */
	$(document).on('click','.add_favorites',function(){
		var fav_id = $(this).attr('goods_common_id');
		var favorites_status = 0;
		if($('.collect-icon').is('.collect-icon-cover')){
			//$('.collect-icon').removeClass('collect-icon-cover');
			favorites_status = 'is_favorites';
		}
		getdata(ApiUrl + '/favorites/favorites',{key:key,fav_id:fav_id,favorites_status:favorites_status},function(info){
			if(info['code']){
				shopdz_alert(info['msg']);
			}else{
				$('.collect-icon').toggleClass('collect-icon-cover');
				shopdz_alert(info['msg']);
			}
		})
	});

});


function add_basket(startlabel, endlabel, time, angle){
	startlabel = startlabel || $('<img class="add_basket_img" src="'+$('.goods_image').attr('src')+'"/>');
	startlabel.css({
		'position':'fixed',
		'top':$(document).scrollTop()+$(window).height()-$(window).height()/3,
		'left':$(window).width()/3,
		'width':'8rem',
		'height':'8rem',
		'border-radius':'8rem',
		'z-index':100000
	});
	$('body').append(startlabel);
	endlabel = endlabel || $('.shop-cart-img');
	time = time || 1200;
	angle = angle || 0.004;
	var bool = new Parabola({
		delay:20,
        el: startlabel,
        offset: [111, 222],
        curvature: angle,
        duration: time,
        targetEl: endlabel,
        callback:function(){
            //alert("完成后回调")
            shopdz_alert('加入成功',1);
            $('.add_basket_img').remove();
        },
        stepCallback:function(x,y){
            //运行中的线路
            var wid = this.wid;
            var hei = this.hei;
            if(wid > 5)startlabel.css({'width':wid});
        	if(hei > 5)startlabel.css({'height':hei});

        }
    });
    bool.start();
}
			