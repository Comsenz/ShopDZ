$(function() {

    $.get(ApiUrl+'/Cms/gethelpcms',function(result){
        if(result.code==0){
            var  html = template('help_list',result.data);
            $('#help-img').after(html);
            var shop_title = getwebConf('shop_name');
            $('title').html("帮助中心-"+shop_title);
            initPage();

        }      
    },'json');
    
});
