$(function() {

    $(document).ready(function(e) {
        SidebarTabHandler.Init();
    });
    var SidebarTabHandler={
        Init:function(){
            $(document).on('click','.tabItemContainer>li',function(){
                $(".tabItemContainer>li>a").removeClass("tabItemCurrent");
                $(".tabBodyItem").removeClass("tabBodyCurrent");
                $(this).find("a").addClass("tabItemCurrent");
                $($(".tabBodyItem")[$(this).index()]).addClass("tabBodyCurrent");
            });
        }
    }

    function tabImg(){
        var tabImgW=$(".tab-img").width();
        $(".tab-img").height(tabImgW);
    }
    tabImg();

    var myScroll;
    function loaded() {
        myScroll = new iScroll('tabItemCon,tabBodyCon');
    }
    //搜索框绑定单击事件
    $(document).on('click','.searchbtn',function(){
        var search_con = $("#search_con").val();
        window.location.href = WapSiteUrl + "/list.html?q="+search_con;
    });

    //侧边栏点击隐藏
    $(document).on('click','.cover',function(){
        $(".classify").animate({left:"-85%"});
        $(".cover,.img-none").hide();
        $('.wrapper').css('overflow-y','');
        if($(".shop-cart-have").html() > 0){
            $(".shop-cart-have").show();
        }
        $('body').css('overflow','');
    });


    $(document).on('click','icon-span',function(){
        $(".classify").animate({left:"-85%"});
        $(".cover,.img-none").hide();
        $('body').css('overflow','');
    });

    $.get(ApiUrl+'/Goods/all_category',function(result){
        if(result.code==0){
            var datas = result.data;
            datas.WapSiteUrl = WapSiteUrl;
            var  html = template('category',datas);
			if($('#header').length){
				$('#header').append(html);
			}
			if($('#member_header_has_nav').length){
				$('#member_header_has_nav').append(html);
			}
            initPage(); 
            //判断登录状态
            if(key){
                $("#islogin").attr('href',WapSiteUrl+'/member.html');
                $("#islogin").html('<img src="img/person.png" alt=""  class="icon-img right"/>');
            }else{
                $("#islogin").attr('href',WapSiteUrl+'/login.html');
                $("#islogin").html('<span class="icon-span right">登录</span>');
            }

            

        }else{
            shopdz_alert(result.msg);
        }       
    },'json');

    
});
