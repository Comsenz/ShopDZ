var key = getcookie("key");
var page_on_off = false;
var page = 1;

$(document).ready(function(){
    page_on_off = true;
    newslist_data = {
            key:key,
            page:1,
            type:3,
    }
    getdata(ApiUrl+'/notice/lists',{key:key},function(result){
        if(result.code == 0){
            if(result.data.list == ''){
                page_on_off = false;
                $('.wrapper').html('<div class="spread-null nullbox"><img src="img/newsNull.png" alt="" class="spreadnull-img"/><p class="spreadnull-remind">暂无消息</p></div>');

            }else{
                var html = template('data_list', result);
                $('#news_list').append(html);
                page_on_off = true;
            }
        }else{
            shopdz_alert('数据请求失败，请重试！');
        }


    });
    scrollgetdatacoupon(ApiUrl+'/notice/lists',newslist_data,newslistcallback);
})

function newslistcallback(result){
    if(result.code == 0){
        if(result.data.list == ''){
            page_on_off = false;
            shopdz_alert('已经到底了哦~~');

        }else{
            var html = template('data_list', result);
            $('#news_list').append(html);
            page_on_off = true;
        }
    }else{
        shopdz_alert('数据请求失败，请重试！');
    }
}

function scrollgetdatacoupon(url, data, callback){
    page_on_off = page_on_off || false;
    $(window).scroll(function(){
        data['status'] = $('#statusinput').val();
        if(page_on_off){
             /* 底部的高度 */
            var fth = $('#footer').offsetheight || 0;
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(window).height();
            if(parseInt(scrollTop) + parseInt(windowHeight) + parseInt(fth) + 100 >= parseInt(scrollHeight)){
                /* 开关(在回调函数中开启) */
                page_on_off = false;
                page += 1;
                data['page'] = page;
                /* 开始请求 */
                getdata(url, data, callback);
            }
        }
    });
}


function del_notice(id){
    if(!id){
        shopdz_alert('参数错误！');
    }
    del_url = ApiUrl+'/notice/del_notice';
    shopdz_confirm('确定要删除吗？','确定','取消',function(){
        $.post(del_url,{key:key,id:id},function(result) {
            result = eval("("+result+")");
            if(result.code == 0){
                shopdz_alert(result.msg,1,function(){
                    if(id == 'all'){
                        $('.wrapper').html('<div class="spread-null nullbox"><img src="img/newsNull.png" alt="" class="spreadnull-img"/><p class="spreadnull-remind">暂无消息</p></div>');
                        $('#clean_notice').hide();
                        page_on_off = false;
                    }else{
                        if($('#notice_li_'+id).parent().children().length - 1 <= 0){
                            $('.wrapper').html('<div class="spread-null nullbox"><img src="img/newsNull.png" alt="" class="spreadnull-img"/><p class="spreadnull-remind">暂无消息</p></div>');
                        }else{
                            $('#notice_li_'+id).remove();
                        }
                    }
                });
            }else{
                shopdz_alert(result.msg);
            }
        });
    });
}