/* 商城全局变量配置开始 */
var key = getcookie('key') || '';/* 用户的KEY */
var fromid = get('fromid') || 0;/* 用户的上级推广人ID */
var tag = 0; /* 全局递增变量 所有人只增加他不要减少 */
var goodsnum = 0;/* 右上角购物车数量 */
var wait = 60;/* 手机验证码发送间隔时间 */
var regexPas=/^(?=.*\d)(?=.*[a-zA-Z]).{6,18}$/;/* 注册密码规则 */
var screenH=document.documentElement.clientHeight;/* 页面的高度 */
/* 商城全局变量配置结束 */

(function() {
	/* 设置商城网址的全局变量 */
    var m = /^(https?:\/\/.+)\/wap/i.exec(location.href);
    if (m && m.length > 1) {
        SiteUrl = m[1] + '/index.php';
        ApiUrl = m[1] + '/api.php';
        WapSiteUrl = m[1] + '/wap';
        LogoUrl = m[1] + '/data/Attach/';
    }
})();

$(function(){
	$('[name="keywords"]').after('<meta name="discription" content="SHOPDZ" />');
	if(fromid){
		setCookie('fromid',fromid,'','/');
	}
	//被选中的收货地址都有红色边框
	$('.address-describe').bind('click',function(){
		$(this).parents('li').addClass('border-redleft').siblings().removeClass('border-redleft');
		$('.address-choice').css('display','none');	
	});
	
	//公共的头部不带搜索框
	if($('#header').length){
		header = '';
		header +='<div class="header-box">';
		header +='<div class="index-header">';
		header +='<a href="javascript:;" class="toggle-icon"><img src="img/toggle.png" class="toggle-img"/></a>';
		header +='<a href="/wap"><img src="img/logo.png" class="logo"/></a>';
		header +='<a href="basket.html" class="head-cartimg"><img src="img/cart.png" class="shop-cart-img"/><span class="shop-cart-have" style="display: block;">' + goodsnum + '</span></a>';
		header+='<img src="img/toggle.png" class="img-none"/>'
		header +='</div>';
		header +='</div>';
		$('#header').html(header);
	}

	if($('#header_has_back').length){
			var header_has_back = '';
			header_has_back +='<div class="header-box">';
			header_has_back +='<div class="index-header goback-header">';
			header_has_back +='<img src="img/back.png" class="back-icon"/>';
			header_has_back +='<h1 class="head-tit" id="head_tit" >页面标题</h1>';
			header_has_back +='<a href="basket.html" class="head-cartimg"><img src="img/cart.png" class="shop-cart-img"/><span class="shop-cart-have" style="display: block;">' + goodsnum + '</span></a>';
			header_has_back +='</div>';
			header_has_back +='</div>';
			
		$('#header_has_back').html(header_has_back);
	}
	if($('#header_has_nav').length){
			var header_has_nav = '';
			header_has_nav +='<div class="header-box">';
			header_has_nav +='<div class="index-header goback-header">';
			header_has_nav +='<img src="img/back.png" class="back-icon"/>';
			header_has_nav +='<h1 class="head-tit" id="head_tit" >页面标题</h1>';
			header_has_nav +='<a href="javascript:;" class="head-cartimg"><span class="head-more head-moreimg head-moreimgred"></span></a>';
			header_has_nav +='</div>';
			header_has_nav +='</div>';
			header_has_nav += '<ul class="nav-list radius3" style="display:none;">';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/index.html" class="navf-page">首页</a></li>';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/basket.html" class="navbasket">购物车<span class="basket-have"></span></a></li>';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/member.html" class="navmember">个人中心</a></li>';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/news.html" class="navnews">消息<span class="basket-have" >' + goodsnum + '</span></a></li>';
			header_has_nav += '</ul>';
			$('#header_has_nav').html(header_has_nav);
	}
	if($('#header_has_nav_card').length){
		header = '';
		header +='<div class="header-box">';
		header +='<div class="index-header">';
		header +='<a href="/wap"><img src="img/logo.png" class="logo"/></a>';
		header +='</div>';
		header +='</div>';
		$('#header_has_nav_card').html(header);
	}
/*	if($('#header_has_nav_card').length){
		var header_has_nav_card = '';
		header_has_nav_card +='<div class="header-box">';
		header_has_nav_card +='<div class="index-header goback-header">';
		header_has_nav_card +='<img src="img/back.png" class="back-icon"/>';
		header_has_nav_card +='<h1 class="head-tit" id="head_tit" >页面标题</h1>';
		header_has_nav_card +='<a href="javascript:;" class="head-cartimg"><span class="head-more head-moreimg head-moreimgred"></span></a>';
		header_has_nav_card +='</div>';
		header_has_nav_card +='</div>';
		header_has_nav_card += '<ul class="nav-list radius3" style="display:none;">';
		header_has_nav_card += '<li><a href="'+WapSiteUrl+'/index.html" class="navf-page">首页</a></li>';
		header_has_nav_card += '</ul>';
		$('#header_has_nav_card').html(header_has_nav_card);
	}*/

	if($('#member_header_has_nav').length){
			var header_has_nav = '';
			header_has_nav +='<div class="header-box">';
			header_has_nav +='<div class="index-header goback-header">';
			header_has_nav +='<a href="javascript:;" class="toggle-icon"><img src="img/toggle.png" class="toggle-img"/></a>';
			header_has_nav +='<a href="/wap"><img src="img/logo.png" class="logo"/></a>';
			header_has_nav +='<a href="javascript:;" class="head-cartimg"><span class="head-more head-moreimg head-moreimgred"></span></a>';
			header_has_nav +='</div>';
			header_has_nav +='</div>';
			header_has_nav += '<ul class="nav-list radius3" style="display:none;">';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/index.html" class="navf-page">首页</a></li>';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/basket.html" class="navbasket">购物车<span class="basket-have"></span></a></li>';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/member.html" class="navmember">个人中心</a></li>';
			header_has_nav += '<li><a href="'+WapSiteUrl+'/news.html" class="navnews">消息<span class="basket-have">' + goodsnum + '</span></a></li>';
			header_has_nav += '</ul>';
			$('#member_header_has_nav').html(header_has_nav);
	}
		$(document).on('click','.head-more',function(){
			if($(".nav-list").css("display")=="none"){
				$(".nav-list").fadeIn();
			} else {
				$(".nav-list").fadeOut();
			}
		});
		$(document).on('click',function(event){
			if($('.nav-list').length > 0 && $(".nav-list").css("display")!="none"){
				var x = event.clientX;
				var y = event.clientY;
				var left = parseInt($('.nav-list').offset().left);
				var top = parseInt($('.head-more').offset().top);
				var right = parseInt($('.nav-list').width()+left);
				var bottom = parseInt($('.nav-list').height()+top);
				if(x<left || x>right || y<top || y>bottom)
					$('.nav-list').fadeOut();
			}
		});
		$(document).on('mouseout','.nav-list',function(event){
			var x = event.clientX;
			var y = event.clientY;
			var left = parseInt($(this).offset().left);
			var top = parseInt($(this).offset().top);
			var right = parseInt($(this).width()+left);
			var bottom = parseInt($(this).height()+top);
			if(x<left || x>right || y<top || y>bottom)
				$(this).fadeOut();
		});

	if($('#head_tit').length>0){
		$('#head_tit').html($('title').text());
	}
	//公共的尾部
	if($('#footer').length){
		var  footer=  '<div class="foot1">'
						+'<p class="footbtn-box"><a href="index.html">商城首页</a><a href="member.html">个人中心</a></p>'
	//+'<p class="icp"><span>网站版权所有</span><a href="http://www.miibeian.gov.cn" target="_blank">豫ICP备000001号</a></p>'
						+'<p class="icp"><span>ICP证</span><a href="http://www.miibeian.gov.cn" target="_blank"></a>&nbsp;<a href="javascript:;" id="footernumber"></a></p>'
    					+'<p class="icp"><span id="footer_info">ShopDZ版权所有&nbsp;©&nbsp;</span><a href="#" target="_blank"></a></p>'
    					+'</div>'
					   +'<div style="display:none;" class="gotobtn" id="goTopBtn"><img border=0 src="img/goToTop.png"></div>'
		$('#footer').append(footer);
	}
	//PC和移动端侧边栏
	$(".toggle-icon").click(function(){
		if(document.body.clientWidth<540){ 
			$(".cover").css("width","100%");
			$(".classify").css({"width":"85%","left":"-85%"});
			$(".classify").animate({left:"0"});
			$('.wrapper').css('overflow-y','hidden');
		} else {
			$(".classify").css("left",(document.body.clientWidth/2-729)+'px');
			$(".cover").css("width","540px");
			$(".classify").animate({left:(document.body.clientWidth/2-270)+'px'});
			$(".cover").css("left",(document.body.clientWidth/2-270)+'px')
			$(".classify").css("width","459px");
			
		}
			$(".cover").show();
			$(".img-none").show();
			$(".shop-cart-have").hide();
			//$('body').css('overflow','hidden');
	})

	if($('.shop-cart-img').length >0 || $('.navbasket').length > 0) {
		basketgoodsnum();
	}
	if($('.navnews').length >0) {
		getnoticenum();
	}
	bindcart();
	getsettings();
	var web_introduce = getwebConf('web_introduce');
	var shop_name = getwebConf('shop_name');
	if(web_introduce){
		$("[name='keywords']").attr('content',web_introduce+'-'+shop_name);
		$("[name='discription']").attr('content',web_introduce+'-'+shop_name);
	}
	if($('#statistics_code').length <= 0){
		var statistics = getwebConf('statistics_code');
		$('body').append('<div id="statistics_code" style="display: none;">'+statistics+'</div>');
	}
});

function getsettings() {
	var webconf = getwebConf();
	//if(webconf == null) {
  	    $.ajax({
	        url:ApiUrl + '/index/getSettings',
	        type:'post',
	        data:{},
	        async: false,
	        success: function(result) {
	        	result = eval("("+result+")");
                if (result.code == 0) {
                    addcookie('webconfig',JSON.stringify(result.data));
                }
	        }
	    })
	//}
}

function bindcart() {
	$('.goback-header').find('.back-icon').bind('click',function() {
		goBack();
	});
}

function goBack(){
        document.referrer === '' ?
          window.location.href = WapSiteUrl +'?t='+Math.random():
          window.history.go(-1);
      }

/* 限制图片宽高一致 */
function SizeLimit(obj){
	initPage();
	var objW=obj.width();
		obj.height(objW);
}

function redirectindex() {
	if( !key) {
		window.location.href = WapSiteUrl + '/login.html?t='+Math.random();
	}
}

function category(){
	var html = '';
	var current = get('cateid') || 0;
	html = '<script type="text/html" id="category">';
	html += '<div class="cover">';
	html += '<!-- <p class="tab-coverp">点击此处返回</p>-->';
	html += '</div>';
	html += '<div class="classify">';
	html += '<div class="title2">';
	html += '<p class="search-btn1"><input id="search_con" type="text" placeholder="搜索"/><img src="img/searchbtn.png" class="searchbtn"/></p>';
	// html += '<a href="<%=WapSiteUrl %>/index.html"><img src="img/f-page.png" alt="" class="icon-img right"/></a>';
	html += '<a id="islogin" href=""></a></div>';
	// html += '<a href="<%=WapSiteUrl %>/member.html"><img src="img/person.png" alt=""  class="icon-img right"/></a></div>';
	html += '<div class="TabMain">';
	html += '<div id="tabItemCon">';
	html += '<ul class="tabItemContainer scroller">';
	html += '<% for(i in category){ %>';
	html += '<% if (i=='+current+') { %>';
	html += '<li cateid="<%=i%>"><a class="tabItemCurrent"><%=category[i].gc_name %></a></li>';
	html += '<% }else{ %>';
	html += '<li cateid="<%=i%>"><a><%=category[i].gc_name %></a></li>';
	html += '<% } %>';
	html += '<% } %>';
	html += '</ul></div>';
	html += '<div class="tabBodyContainer" id="tabBodyCon">';
	html += '<% for(i in category){ %>';
	html += '<% if (i=='+current+') { %>';
	html += '<div class="tabBodyItem tabBodyCurrent">';
	html += '<ul class="listul1">';
	html += '<% var v1=category[i].child %>';
	html += '<% for(s in v1){ %>';
	html += '<li>';
	html += '<a href="list.html?gc_id=<%=v1[s].gc_id %>&cateid=<%=i%>">';
	html += '<div class="tab-img">';
	html += '<img src="<%=v1[s].gc_image %>">';
	html += '</div>';
	html += '<p class="tab-desc"><%=v1[s].gc_name %></p>';
	html += '</a>';
	html += ' </li>';
	html += '<% } %>';
	html += '</ul>';
	html += '</div>';
	html += '<% }else{ %>';
	html += '<div class="tabBodyItem">';
	html += '<ul class="listul1">';
	html += '<% var v2=category[i].child %>';
	html += '<% for(z in v2){ %>';
	html += '<li>';
	html += '<a href="list.html?gc_id=<%=v2[z].gc_id %>&cateid=<%=i%>">';
	html += '<div class="tab-img">';
	html += '<img src="<%=v2[z].gc_image %>">';
	html += '</div>';
	html += '<p class="tab-desc"><%=v2[z].gc_name %></p>';
	html += ' </a></li><% } %></ul></div><% } %><% } %></div></div></div>';
	html += '</script>';
	$('body').append(html);
}

function navShow(){
	var html = '';
	html += '<ul class="nav-list radius3">';
	html += '<li><a href="#" class="navf-page">首页</a></li>';
	html += '<li><a href="#" class="navbasket">购物车</a></li>';
	html += '<li><a href="#" class="navmember">个人中心</a></li>';
	html += '</ul>';
	$('body').append(html);
}


function getnoticenum() {
	var noticeurl = ApiUrl+'/notice/only_notice_count';
	getdata(noticeurl,{key:key},function(result) {
		noticenum = parseInt(result.data['count']) || 0;
		if(noticenum > 0) {
			$('.head-more').addClass('head-moreimgred');
			$('.navnews').find('.basket-have').html(noticenum);
		}else{
			$('.navnews').find('.basket-have').hide();
		}
	});
}

/* 获取购物车商品数量 */
function basketgoodsnum(callback){
	$.ajax({
		url:ApiUrl + "/Basket/goodssum",
		data:{key:key},
		type:'POST',
		dataTpye:'json',
		success:function(info){
			info = eval('('+info+')');
			if(info.code > '0'){//location
				if(info.code == '20002'){
					close = get('close');
					if(close != '1'){
						window.location.href = WapSiteUrl + '/close.html?close=1';
						return;
					}
				}else if(info.code == '20003'){
					window.location.href = WapSiteUrl + '/license.html';
				}
				if($('.ellipsis').length)
					$('.ellipsis').html(info.msg);
			}
			goodsnum = parseInt(info['data']['num']) || 0;
			var basket_label = new Object;
			if($('.navbasket').length > 0){
				basket_label = $('.navbasket').find('.basket-have');
			}else if($('.head-cartimg').length > 0){
				basket_label = $('.head-cartimg').find('span');
			}else{
				console.log('head not exists');
			}
			if(basket_label.length > 0){
				if(goodsnum > 0 ){
					$('.head-more').addClass('head-moreimgred');
					basket_label.css('display','inline-block').html(goodsnum);
				}else{
					$('.head-more').removeClass('head-moreimgred');
					basket_label.css('display','none');
				}
				if(typeof(callback) == 'function'){
					callback();
				}
			}
		},
		async:false
	})
}

/* 补全小数点 */
function returnFloat(value){
	var value=Math.round(parseFloat(value)*100)/100;
	var xsd=value.toString().split(".");
	if(xsd.length==1){
		value=value.toString()+".00";
		return value;
	}
	if(xsd.length>1){
		if(xsd[1].length<2){
			value=value.toString()+"0";
		}
		return value;
	}
}

/* 判断手机号和验证码信息是否有误 */
function judge(name,code){
	var phone1 = name.val();
	var pword1 = code.val();
	var regexName=/^(13[0-9]|14[5|7]|15[0-9]|17[0-9]|18[0-9])\d{8}$/,
		regexPas=/^\d{6}$/;
	if(phone1==""){
		shopdz_alert("请输入手机号！");
		return false;
	}else if(!regexName.test(phone1)){
		 shopdz_alert("输入的账号不正确！");
		 return false;
	} 
	if(pword1==''){
		shopdz_alert("请输入验证码！");
		return false;
	}else if(!regexPas.test(pword1)){
		shopdz_alert("输入的验证码不正确！");
		return false;
	} 
	return true;
}

function get(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return unescape(r[2]); return '';
}

function addcookie(name,value,expireHours){
	var cookieString=name+"="+escape(value)+"; path=/";
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
	document.cookie=cookieString;
}

function getcookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
	var arr=arrcookie[i].split("=");
	if(arr[0]==name)return arr[1];
	}
	return "";
}

function getCookies(name){
	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arr != null) return unescape(arr[2]); return '';
}

function setCookie(name, value, expires, path, domain, secure){
	var liveDate = new Date();
	expires = liveDate.setTime(liveDate.getTime() + expires*60*1000);//毫秒
	//expires = new Date((new Date()).getTime() + expires * 60000);//按分钟
	document.cookie = name + "=" + escape (value) +
			((expires) ? "; expires=" + expires : "") +
			((path) ? "; path=" + path : "") +
			((domain) ? "; domain=" + domain : "") +
			((secure) ? "; secure" : "");
}

/* 获取phpcookie */
function getphpCookie(c_name){
	if (document.cookie.length > 0){
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1){
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";",c_start);
			if (c_end == -1) c_end = document.cookie.length;
			return unescape(document.cookie.substring(c_start,c_end));
		}
	}
	return "";
}

/* 删除cookie */
function delCookie(name){
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getcookie(name);
	if(cval!=null) document.cookie = name + "="+cval+"; path=/;expires="+exp.toGMTString();
}

function checklogin(state){
	if(state == 0){
		location.href = WapSiteUrl+'/signIn.html';
		return false;
	}else {
		return true;
	}
}

function contains(arr, str) {
    var i = arr.length;
    while (i--)  if (arr[i] === str) return true;
    return false;
}

function buildUrl(type, data) {
    switch (type) {
        case 'keyword':
            return WapSiteUrl + '/tmpl/product_list.html?keyword=' + encodeURIComponent(data);
        case 'special':
            return WapSiteUrl + '/special.html?special_id=' + data;
        //case 'goods':
            //return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
        case 'url':
            return data;
        case 'goods':
            return WapSiteUrl + '/goods_detail.html?id=' + data;
        case 'category':
            return WapSiteUrl + '/list.html?gc_id=' + data;
        case 'redpacket':
            return WapSiteUrl + '/coupon-receive.html?id=' + data;
    }
    return WapSiteUrl;
}

function get_area(key, area_id) {
	var area_list;
	$.ajax({
		type : 'post',
		url : ApiUrl + '/index.php?act=member_address&op=area_list',
		data : {key:key, area_id:area_id},
		dataType : 'json',
		async : false,
		success : function(result){
			checklogin(result.login);
			area_list = result.datas.area_list;
		}
	});
	return area_list;
}

/* 获取get参数 */
function get(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return decodeURI(r[2]); return '';
}

function getFirstValue(a){
    for(i in a) return a[i];
}

/* 返回 不小于0的整数 */
function intval(num){
	num = parseInt(num);
	if(isNaN(num)) return  0;
	if(num<0) return 0;
	return num;
}

/* 返回对象的长度 */
function countObj(a){
    var num = 0;
    for(i in a) num++;
    return num;
}

/* 公共confirm函数 */
function shopdz_confirm(title,sure_btn_text,cancel_btn_text,sure_callback){
	var cover = $('<div></div>'); //创建一个父DIV
        //main.attr('id', 'parent'); //给父DIV设置ID
        cover.addClass('cover'); //添加CSS样式
		cover.appendTo('body');
		cover.show();
	var tk=$('<div></div>');
		tk.addClass('alertbox order-remind order-dele');
	var page_p=$('<p></p>');
		page_p.addClass('alert-p');
		page_p.html(title);
		page_p.appendTo(tk);
	var btnbox1=$('<p></p>');
		btnbox1.addClass('btnbox1');
		btnbox1.appendTo(tk);
	var cancelbtn1=$('<span></span>');
		cancelbtn1.addClass('cancelbtn1 order-cancel1');
		cancelbtn1.html(cancel_btn_text);
		cancelbtn1.appendTo(btnbox1);
	var surebtn=$('<span></span>');
		surebtn.addClass('surebtn1 order-sure1');
		surebtn.html(sure_btn_text);
		surebtn.appendTo(btnbox1);
		tk.appendTo('body');
		initPage();
	close_ff(cancelbtn1);
	close_ff(surebtn,sure_callback);
	function close_ff(even,sure_callback){
		even.click(function(){
			cover.hide();
			tk.hide();
			if(typeof  sure_callback == 'function'){
				sure_callback();	
			}	
		});
	}
}

/* 公共alert 函数 */
function shopdz_alert(title,second,callback){
	$('.shopdz_alert').remove();
	$('#alertbox').remove();
	var _tag = tag++;
	var  html = '<div id="alertbox"><div id="address-remind-'+_tag+'" class="address-remind shopdz_alert">'+title+'</div></div>';
	if(typeof second == 'number') {
		var timeout = second*1000;
	} else {
		var timeout = 1000;	
	}
	$('body').append(html);
	initPage();
	$('#address-remind-'+_tag).fadeIn().delay(timeout).fadeOut(1000,'swing',function(){
		if(typeof  callback == 'function') callback();	
		$('#address-remind-'+_tag).remove();
		$('#alertbox').remove();
	});
}

/* 验证上传文件是否是图片 */
function regImage(dirname){
	var arr = dirname.split('.');
	var reg = /(BMP|JPG|JPEG|PNG|GIF)/i;
	return reg.test(arr[arr.length-1]);
}

/* 上传图片函数 label(上传图片的input标签) */
//var img = true;/* 没看到哪里有用到，暂时先注释，不报错后删除掉 */
function uploadimages(label, url, type, callback, filesize){
	filesize = filesize || 5242880;
	label.change(function(){
		var file_Size = this.files[0].size;
		if(regImage(label.val()) && file_Size < filesize){
			$(".cover,.inner").show();
		} else {
			label.parent('li').css('display','block');
			label.parent('li').prev('li').remove();
			if(!regImage(label.val())){
				shopdz_alert('请上传JPEG,PNG,BMP,JPG格式！');
			}else if(file_Size >= filesize){
				shopdz_alert('上传图片不能大于5M！');
			}
		}
	});
	imgcallback = callback || imgcallback;
	var bodyheight = document.documentElement.clientHeight || document.body.clientHeight;
	var bodywidth = document.documentElement.clientWidth || document.body.clientWidth;
	if(bodywidth > 540){
		bodywidth = 540;
	}
	label.localResizeIMG({
		//width: 1,//宽度
		filesize: filesize,//设置上传图片的大小
		quality: 0.2,//压缩比例0-1之间
		success: function (result) { 
			var submitData={
				key:key,
				type:type,
				base64_string:result.clearBase64,
				bodyheight:bodyheight,
				bodywidth:bodywidth,
			};
			$.ajax({
				type: "POST",
				url: url,
				data: submitData,
				dataType:"json",
				success: function(info){
					return imgcallback(info, label);
				},
				complete :function(XMLHttpRequest, textStatus){
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){ //上传失败 
					if($('.cover').length){
						$(".cover,.inner").hide();
					}
					shopdz_alert("上传失败，请重试！");
				}
			});
		},
	});
}

/* 默认的上传图片回调方法 info(返回的数据) label(上传图片的input标签) */
function imgcallback(info, label){
	if($('.cover').length) $(".cover,.inner").hide();
	var li = label.parent('li').prev('li');
	if(!info.code){
		/* 上传成功 */
		li.html("<span class='evaluate-have'>X</span><img src='" + info['data']['url'] + "' class='upload-cover'>");
		li.find('.evaluate-have').click(function(){
			if(label.parent().siblings('li').length>=imglen){
				label.parent().css('display','block');
			}
			li.remove();
		});
		shopdz_alert('上传成功！');
	} else {
		/* 上传失败 */
		li.remove();
	}
	if(label.parent('li').siblings('li').length<imglen) label.parent('li').css('display','block');
	return false;
}

/* 图形验证码刷新 */
function ref_verifi() {
	$('#register-verification').val('');
	var src = $('.verification').attr('rel-src');
	$('.verification').attr('src',src+'?rand='+Math.random());
}

function times(return_function, secound) {
	secound = secound || wait;
	var o = $("#send_seccode_btn");
    if (secound == 1) { 
        o.html("获取验证码");  
        o.addClass('send-out-bg');
        o.bind('click',function(){
        	eval(return_function + "()");
        });
    } else {
        o.unbind("click");
        o.html("重新发送(<span style='color:#ab2b2b;font-weight:700'>" + (secound-1) + "</span>)");
        setTimeout(function(){
            times(return_function, --secound);
        }, 1000);
    }
}

/* 判断商品数量是否为1 */
function numAddClass(inp,maxnum){
	var val = inp.val();
	maxnum = maxnum || 99;
	inp.parents('.jia-jian').children('span').children('i').removeClass("hxwx-gray");
	inp.parents('.jia-jian').children('span').removeClass("num-small");
	inp.parents('.jia-jian').children('span').removeClass("num-big");
	if (val <= 1){
		inp.parent('div').prev("span").addClass("num-small");
		inp.parent('div').prev("span").children("i").addClass("hxwx-gray");
	} else if (val >= maxnum){
		inp.parent('div').next("span").addClass("num-big");
		inp.parent('div').next("span").children("i").addClass("hxwx-gray");
	}
}

/* 公共获取数据函数 */
function getdata (ApiUrl,data,callback) {
	$.ajax({
		type: 'POST',
		url: ApiUrl,
		data: data,
		success: callback,
		dataType: 'json'
	});
}

/* 公共的滚动下拉展示数据 */
function scrollgetdata(url, data, callback){
	page_on_off = page_on_off || false;
    $(window).scroll(function(){
    	if(page_on_off){
		    var h = $(window).height();
		    /* 整个文档的高度 */
		    var th = $(document).height();
		    /* 底部的高度 */
		    var fth = $('#footer').height();
		    /* 页面的滚动高度 */
		    var st =$(window).scrollTop();
		    if(th- h - fth - st < 300){
		    	/* 开关(在回调函数中开启) */
		    	page_on_off = false;
		    	data['page'] += 1;
		        /* 开始请求 */
		        getdata(url, data, callback);
		    }
		}
    });
}

function initPage(){
	var remingW=$(document).find('#alertbox').children('.address-remind').width();
	var  bodywidth =  0;
	if(intval(document.body.clientWidth)>0){
		bodywidth	 =  intval(document.body.clientWidth);
	}
	if(intval(document.documentElement.clientWidth<540)>0){
		bodywidth	 =  intval(document.documentElement.clientWidth<540);
	}
	if(bodywidth<540){ 
		$(".header-box,.index-header,.foot-btn").css("width","100%");
		$(".address-remind").css({"width":"auto","left":((bodywidth-remingW)/2)+'px'});
	} else { 
		$("body").find(".header-box").css("width","100%");
		$("body").find(".index-header").css("width","540px");
		$(".wrapper").css("width","540px");
		$(".content").css({"width":"540px","padding":"4rem auto 4.5rem auto"});
		$(".swiper-container,.swiper-wrapper,.swiper-slide").css("width","540px");
		$(".wrapper,.index-header,.foot-btn").css("margin","auto");
		$("body").find(".foot-btn,.spec-none,.details-share-box").css({"width":"540px","left":(bodywidth/2-270)+'px'});
		$("body").find(".savecode-box,.httpcode-box").css({"width":"378px","left":(bodywidth/2-270+81)+'px','margin-left':'0'});
		$("body").find("#mdatetimer").css({"width":"540px","left":(bodywidth/2-270)+'px'});
		$("body").find(".wx-alert").css({"width":"540px","left":(bodywidth/2-270)+'px'});
		$("body").find(".alertbox").css({"width":"432px","left":(bodywidth/2-270+54)+'px'});
		$("body").find(".sign-alert").css({"width":"432px","left":(bodywidth/2-270+54)+'px'});
		$("body").find('#alertbox').children('.address-remind').css({"width":"auto","left":(bodywidth/2-270+(540-remingW)/2)+'px'});
		$("body").find('.nav-list').css({'width':'10rem','right':(bodywidth/2-270+10)+'px'});
		//$("body").find(".address-remind").css({"width":"216px","left":(bodywidth/2-270+162)+'px'});
	} 
 	return true;
}

function trim(str) {
  return str.replace(/(^\s+)|(\s+$)/g, "");
}

function isWeiXin(){
	var ua = navigator.userAgent.toLowerCase();//获取判断用的对象
	if (ua.match(/MicroMessenger/i) == "micromessenger") {
		return true;
	}else{
		return false;
	}
}

//var isLoad = false;/* 加到函数内部了，出现问题再调回来，没问题之后删除这一行 */
function isImgLoad(classname,callback,isLoad){
    $('.'+classname).each(function(){
        if(this.height === 0){
            isLoad = false;
            return false;
        }
    });
    if(isLoad){
        clearTimeout(t_img); // 清除定时器
        // 回调函数
        callback();
    }else{
        isLoad = true;
        t_img = setTimeout(function(){
            isImgLoad(classname,callback,isLoad); 
        },500); 
    }
}

/* 创建图片轮播图 */
function viewimg() {
	$('.comment-img-list').find('img').bind('click',function() {
		if(!$('.swiperbox').length){
			$('body').append('<div class="swiperbox"></div>');
		}
		$('.swiperbox').empty();
		$('.swiperbox').append('<div class="swiper-container swiper-container-box"><div class="swiper-wrapper"></div><div class="swiper-pagination"></div></div>');
		$('.swiperbox').show();
		$('.swiper-wrapper').html('');
		var img_id = $(this).attr('img-id');
		allimg = $(this).parents('.comment-img-list').find('img');
		allimg.each(function(i) {
			var newdiv = $('<div class="swiper-slide swiper-slide-bg" data-hash="slide'+i+'" ></div>');
			var newimg = $('<img src="'+$(this).attr('src')+'" class="swiper-img"/>');
			var newsrc = $(this).attr('src').replace('.thumb.jpeg','');
			var img = new Image();
			img.src = newsrc;
			img.onload = function(){
				newimg.attr('src',img.src);
			}
			newdiv.append(newimg);
			$('.swiper-wrapper').append(newdiv);
		});
		commentLimit();
		var start_len;
		var move_len;
		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			paginationClickable: '.swiper-pagination',
			nextButton: '.swiper-button-next',
			prevButton: '.swiper-button-prev',
			spaceBetween: 0,
			lazyLoading : true,//延时加载
			//hashnav: true
			initialSlide:img_id
		});
		/* 点击隐藏图片轮播图 */
		$('.swiperbox').unbind('click').bind('click',function(){
			$(this).hide();
		});
	});
}

function getwebConf(code){
	var code = code;
	var webconf = getCookies('webconfig');
	if(webconf){
		var  res = eval('('+webconf+')');
		return code ? res[code] : res;
	}
	return null;
}

function isPhone(){
	var ua = navigator.userAgent;
	var ipad = ua.match(/(iPad).*OS\s([\d_]+)/);
	var isIphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/);
	var isAndroid = ua.match(/(Android)\s+([\d.]+)/);
    //或者单独判断iphone或android 
    if(isIphone){
        return 1;
    }else if(isAndroid){
        return 2;
    }else{
        return 0;
    }
}

function judgeuser(name, msg, maxlen){
	msg = msg || "昵称不能超过七位！";//提示信息
	maxlen = maxlen || 14;//最大长度
	var len = 0;//计算长度的变量
	var ret = true;//返回值
	var val = name.val();//需要检测的元素的值
	var newval = '';//初始化超长的字符串
	for (var i=0; i<val.length; i++) { 
		if (val.charCodeAt(i)>127 || val.charCodeAt(i)==94) { 
			len += 2;
		} else { 
			len ++;
		}
		if(len > maxlen){
			shopdz_alert(msg);
			name.val(newval);
			ret = false;
			break;
		}
		newval += val[i];
	} 
	return ret;
}

function in_array(_search,_array){
	for( _i in  _array){
		if(_array[_i] == _search){
			return true;
		}
	}
	return  false;
}

function str_repeat(string, multiplier) {
	var ret = '';
	for(i=0;i<multiplier;i++) ret+=string;
	return ret;
}

/* 处理隐藏中间字符 */
function hide_middle_str(label){
	for (var i = 0; i < label.length; i++) {
		var str = '', newstr = '';
		str = $(label[i]).html();
		if(str == '') continue;		
		newstr = str.substr(0,3);
		newstr += str_repeat('*',4);
		newstr += str.slice(str.length - 3);
		label[i].innerText = newstr;
	};
}

function img_center(){
	/* 图片外层的li */
	var li = $('.imgW');
	/* 遍历li */
	li.each(function(){
		var this_ = $(this);
		var img = $(this).find('img');
		img.hide();
		/* 处理等比例缩放 */
		var newimg = new Image();
		newimg.src = img.attr('src');
		newimg.onload = function(){
			var w = img.width() - this_.width();
			var h = img.height() - this_.height();
			if(w > h){
				img.height(this_.height());
				img.width('auto');
				/* 处理居中 */
				img.css({'position':'relative','left':-Math.abs(img.width()-this_.width())/2});
			}else if(w < h){
				img.width(this_.width());
				img.height('auto');
				/* 处理居中 */
				img.css({'position':'relative','top':-Math.abs(img.height()-this_.height())/2});
			}
			img.show();
		}
	})
}

/* 创建form对象提交数据（效果跟form表单相同） */
function sub_form(data, url, type){
	type = type || 'get';
	var form = new FormData();
	$.each(data,function(k,v){
		form.append(k,v);
	});
	var http = new XMLHttpRequest();
	http.open(type,url);
	http.send(form);
}

/* 返回顶部 */
function goTopEx() { 
	var obj = document.getElementById("goTopBtn"); 
	function getScrollTop() { 
		return document.documentElement.scrollTop + document.body.scrollTop; 
	} 
	function setScrollTop(value) { 
		if (document.documentElement.scrollTop) { 
			document.documentElement.scrollTop = value; 
		} else { 
			document.body.scrollTop = value; 
		} 
	} 
	window.onscroll = function() { 
		getScrollTop() > screenH-100 ? obj.style.display = "": obj.style.display = "none"; 
	} 
	obj.onclick = function() { 
		var goTop = setInterval(scrollMove, 10); 
		function scrollMove() { 
			setScrollTop(getScrollTop() / 1.1); 
			if (getScrollTop() < 1) clearInterval(goTop); 
		} 
	}
}