$(function(){
    var footernumber = getwebConf('record_number');
    if(typeof(footernumber) != 'undefined'){
        $('#footernumber').html(footernumber);
    }else{
        $('#footernumber').html('暂无备案信息');
    }
    var footer_info = getwebConf('footer_info');
    if(typeof(footer_info) != 'undefined'){
        $('#footer_info').html(footer_info);
    }
    // $('#footer').append(html);  
	  goTopEx();
      initPage();  
})