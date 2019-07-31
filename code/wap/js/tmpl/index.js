$(function() {
	var url = ApiUrl+'/Index/index';
	var data = {};
	getdata(url,data,function(result) {
	if(result.code==0){
            var datas = result.data;
            datas.WapSiteUrl = WapSiteUrl;
            $.each(datas.special_item_list, function(k, v) {                 
                    switch (v.item_type) {
                        case 'adv_list':
                            $.each(v.item_data, function(kk, vv) {
                                v.item_data[kk].url = buildUrl(vv.type, vv.data);
                            });
                            break;
                        case 'adv_img':
                            $.each(v.item_data, function(kk, vv) {
                                v.item_data[kk].url = buildUrl(vv.type, vv.data);
                            });
                            break;
                        case 'adv_nav':
                            $.each(v.item_data, function(kk, vv) {
                                v.item_data[kk].url = buildUrl(vv.type, vv.data);
                            });
                            break; 
                        }
            });

            var  html = template('special_item_list',datas);
            $('#wrapper').prepend(html);
            var shop_title = getwebConf('shop_name');
            $('title').html(shop_title);
            initPage();
			SizeLimit($(".image-index"));
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
        }else{
            shopdz_alert(result.msg);
        }
	});
    //搜索框绑定单击事件
    // $(".searchbtn").click(function(){
    //     var search_con = $("#search_con").val();
    //     window.location.href = WapSiteUrl + "/list.html?q="+search_con;
    // })
});
