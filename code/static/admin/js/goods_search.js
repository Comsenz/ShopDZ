$(function(){
	function search(p) {
		p = p ? p : "1";
		gc_id_1 = $('#gc_id_1').val();
		gc_id_2 = $('#gc_id_2').val();
		search_text = $('#search_text').val();
		data = {
			gc_id_1:gc_id_1,
			gc_id_2:gc_id_2,
			content:search_text,
			p:p,
		};
		getdata(searchurl,data,function(data) {
			good = data.data.goods;
			page = data.data.page;
			li ='';
			$('.fight-icon-list').html(li);
			if(good.length > 0){
				for(i in good){
					var goods_images_first = good[i].goods_images[0];
					li +='<li  ><a href="#"><img src="'+good[i].goods_image+'" alt="" class="icon-img"/><p class="icon-name" goods_common_id="'+good[i].goods_common_id+'"  goods_id="'+good[i].goods_id+'" sign_price="'+good[i].goods_price+'" goods_storage="'+good[i].goods_storage+'" goods_images="'+goods_images_first+'" spec_name="'+good[i].spec_name+'">'+good[i].goods_name+'</p></a></li>';
				}
			}else{
				li +='<li class="no-data">暂无商品</li>';
			}
			$('.fight-icon-list').html(li);
			$('.pagination').html(page);
			var aobjs = $(".pagination").find(".page");
			$(aobjs).each(function(i){

				var currentpage = $(this).data('page');
				//绑定点击事件
				$(this).bind('click',function(){
					search(currentpage);//重新调用该函数查询
				});
			});
		});
	}
	function firstcategroy() {
		var option = '<option value="0">请选择商品分类</option>';
		for(i in category){
			gc_id = category[i].gc_id;
			gc_name = category[i].gc_name;
			option +='<option value="'+gc_id+'">'+gc_name+'</option>';
		}
		$('#gc_id_1').html(option);
	}
	function secondcategroy(gc_id_1) {
		child = category[gc_id_1].child;
		var option = '<option value="0">请选择商品分类</option>';
		for(i in child){
			gc_id = child[i].gc_id;
			gc_name = child[i].gc_name;
			option +='<option value="'+gc_id+'">'+gc_name+'</option>';
		}
		$('#gc_id_2').html(option);
	}
	$('#gc_id_1').bind('change',function() {
		gc_id_1 = $(this).val();
		secondcategroy(gc_id_1);
	});
	firstcategroy();
	$('.showsearchbtn').click(function() {
		$('#showsearch').show();
		$('.icon-alert').show();
		$('.icon-cover').show();
		search();
	});
	$('.group-search-btn').click(function() {
		search();
	});
	$('.icon-list ').on('click','li',function(){

		$(this).addClass('green-border');
		var	typeIcon = "<i class='type-icon'></i>";
		$(this).children('a').append(typeIcon);
		$(this).siblings().find('.type-icon').remove();
		$(this).siblings().removeClass('green-border');
	});
	$('.alert-close').bind('click',function(){
		$(this).parents('.icon-alert').hide();
		$('.icon-cover').hide();
	})
});
