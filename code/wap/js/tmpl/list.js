function ImgLimit(){
    var ListDtW=$(".list-dl-dt").width();
    $(".list-dl-dt").height(ListDtW);
}

$(function() {

    var gc_id = get('gc_id');//商品分类id
    var p = get('p') ? get('p') : "1";//当前页码
    var q = get('q') ? get('q') : "";//搜索内容
    
    var isloading = false;//是否正在加载
    var shop_title = getwebConf('shop_name');

    $.get(ApiUrl+'/Goods/goods_list',{gc_id:gc_id,p:p,q:q},function(result){
        if(result.code==0){
            var datas = result.data;
            var shop_name = getwebConf('shop_name');
            if(typeof (datas.categorys) != 'undefined' && datas.categorys.desc!= '' ){
                $("[name='keywords']").attr('content',datas.categorys.desc+'-'+shop_name);
                $("[name='discription']").attr('content',datas.categorys.desc+'-'+shop_name);
            }else{
                $("[name='discription']").attr('content',datas.categorys.gc_name+'-'+shop_name);
            }
            if(datas.goods_list != ''){
                var htmls = template('categorys',datas);
                $('#con1').html(htmls);
                var  html = template('goods_list',datas);           
                $('#listbox').html(html);
                $('title').html(typeof(datas.categorys.gc_name) != "undefined" ? datas.categorys.gc_name+'-'+shop_title : shop_title);
                $("#search_con").val(""+q+"");
                p++;
                showscroll();
                //调用返回顶部的函数
                goTopEx();
                initPage();

             }else{
                var  html = '<div class="img-center"><img src="img/searchbtn.png" alt="" class="coupon-null-img"/><h4 class="coupon-tit">暂无搜索结果</h4></div>';
                $('#con1').html(html);
                $('title').html(shop_title);
                $("#search_con").val(""+q+"");
                //调用返回顶部的函数
                goTopEx();
            }

        }else{
            $('title').html('商品分类-'+shop_title);
            shopdz_alert(result.msg,1,function(){
                window.location.href=WapSiteUrl;
            });
        }       
    },'json');

    //绑定滚动事件
    function showscroll(){
        $(window).scroll(function(){
            if(isloading == true){//如果当前正在加载的话 
                return false;
            }
            var h = $(window).height();
            //整个文档的高度
            var th = $(document).height();
            //底部的高度
            var fth = $('#footer').height();
            //页面的滚动高度
            var st =$(window).scrollTop();
            if(th- h - fth - st < 300){
                //修改加载状态
                isloading = true;
                //开始请求

                $.get(ApiUrl+'/Goods/goods_list',{gc_id:gc_id,p:p,q:q},function(result){
                    if(result.code==0){
                        var datas = result.data;
                        var  html1 = template('goods_list',datas);
                        $('#listbox').append(html1);
                        ImgLimit();
                        p++;
                        
                        //修改加载状态
                        isloading = false;
                        initPage();

                    }else{
                        shopdz_alert(result.msg);
                    }       
                },'json');

            }
        });
    }


    //搜索框绑定单击事件
    // $(".searchbtn").click(function(){
    //     var search_con = $("#search_con").val();
    //     window.location.href = WapSiteUrl + "/list.html?q="+search_con;
    // })
    
});
