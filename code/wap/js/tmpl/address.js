var getaddressurl = "/Member/addressList";
var addaddressurl = "/Member/addressAdd";
var deladdressurl = "/Member/addressDel";
var setdefaulturl = "/Member/addressSetDefault";
var getareaurl    = "/Member/getChildAreaList"

var isdefault = false;
$(function(){
	var address = '';
	var province = '';
	var city = '';
	var area = '';
	/* 限制收货人名字长度 */
	$('#address-name').blur(function(){
		judgeuser($(this), '名字不能超过七位！');
	})
	/* 获取市 */
	$('.outer').on('change','#province',function(){
		var province_id = $(this).val();
		$('#city').html('<option value="0" selected>请选择市</option>');
		$('#area').html('<option value="0" selected>请选择区</option>');
		getarea(0,province_id);
		
	})
	/* 获取区 */
	$('.outer').on('change','#city',function(){
		var city_id = $(this).val();
		$('#area').html('<option value="0" selected>请选择区</option>');
		getarea(0,city_id);
	})
	
	/* 收货地址事件开始 */
		/* 删除收货地址 */
	    $('.content').on('click','.address-dele',function(){
	    	$('.cover').css('display','block');
	    	$('.surebtn1').attr('pid',$(this).attr('addressid'));
	    	$('.dele-sure').css('display','block');
		});
		/* 确定删除收货地址 */
		$('.content').on('click','.surebtn1',function(){
			$('.cover').css('display','none');
			$('.dele-sure').css('display','none');
			var par = $(this).parent().parent().parent();
			address_id = $('.surebtn1').attr('pid');
			data = {
				key: key,
				address_id: address_id
			};
			deladdress(data, $('.liclass_' + address_id));
		});
	    /* 取消删除收货地址 */
	    $('.content').on('click','.cancelbtn1',function(){
	    	$('.cover').css('display','none');
	    	$('.dele-sure').css('display','none');
	    });
	   	
		/* 设置默认收货地址 */
		$('.content').on('click','.setdefault',function(){
			var data = {
				key:key,
				member_id:$(this).attr('memberid'),
				address_id:$(this).attr('addressid')
			}
			setdefault(data);
		})
		/* 取消操作 */
		$('.cancelbtn2').bind('click',function(){
			$('.address-choice').css('display','block');
			$('.address-edit-page').css('display','none');
			$('#head_tit').html('地址管理');
		})
		
		/* 新建收货地址 */
		$('.content').on('click','.new-build',function(){
			$('#editaddress_id').val('');
			$('#address-name').val('');
			$('#address-phone').val('');
			$('#address-word').val('');
			$('.address-choice').hide();
			$('.address-edit-page').show();
			$('#head_tit').html('新建地址');
			$('#province').html('<option value="0" selected>请选择省</option>');
			$('#city').html('<option value="0" selected>请选择市</option>');
			$('#area').html('<option value="0" selected>请选择区</option>');
			/* 获取省级列表 */
			getarea(0,0);
			

		})
		/*点击修改选中发货地址*/
		$('.content').on('click','.address-edit',function(){
			var par = $(this).parent().parent();
			
			$('#editaddress_id').val($(this).attr('addressid'));
			$('#address-name').val(par.find('.name2').text());
			$('#address-phone').val(par.find('.phone1').text());
			$('#address-word').val(par.find('.address-word_hide').text());
			
			$('.address-choice').hide();
			$('.address-edit-page').show();
			$('#head_tit').html('修改地址');
			/* 获取省级列表 */
			getarea(par.find('input[name="province"]').val(), 0);
			/* 获取市级列表 */
			getarea(par.find('input[name="city"]').val(), par.find('input[name="province"]').val());
			/* 获取区域级列表 */
			getarea(par.find('input[name="area"]').val(), par.find('input[name="city"]').val());
		})
		/* 判断收货信息编辑页面信息输入是否正确 提交操作 */
		$('.surebtn2').click(function(){
			var result = judgeaddress($("#address-name"),$("#address-phone"),$("#address-word"),$('#province'),$('#city'),$('#area'));
			var nameres = judgeuser($('#address-name'), '名字不能超过七位！');
			if(result && nameres){
				isdefault = false;
				var data = {
					key:key,
					true_name:$('#address-name').val(),
					province_id:$('#province').find('option:selected').val(),
					city_id:$('#city').find('option:selected').val(),
					area_id:$('#area').find('option:selected').val(),
					area_info:$('#province').find('option:selected').html() + ' '
							+ $('#city').find('option:selected').html() + ' '
							+ $('#area').find('option:selected').html(),
					address:$('#address-word').val(),
					tel_phone:$('#address-phone').val(),
					is_default:$('#default').attr('checked')?1:0
				};
				if(data['is_default'] == 1){
					isdefault = true;
					$('.default').remove();
				}
				if($('#editaddress_id').val() == ''){
					addaddress(data, $('#address_list'));
				}else{
					data['address_id'] = $('#editaddress_id').val();
					editaddress(data,$('#editaddress_id'));
				}
			}
		})
	/* 收货地址事件结束 */
});

function getaddress(callback){
	getaddresscallback = callback || getaddresscallback;
	data = {
		key:key
	}
	url = ApiUrl + getaddressurl;
	addressdata(url, data, getaddresscallback);
}

function getarea(label, area_parent_id, callback){
	getareacallback = callback || getareacallback;
	data = {
		key:key,
		area_parent_id:area_parent_id
	}
	url = ApiUrl + getareaurl;
	addressdata(url, data, getareacallback, label);
}

function addaddress(data, label, callback){
	addaddresscallback = callback || addaddresscallback;
	url = ApiUrl + addaddressurl;
	addressdata(url, data, addaddresscallback, label);
}

function editaddress(data, label, callback){
	editaddresscallback = callback || editaddresscallback;
	url = ApiUrl + addaddressurl;
	addressdata(url, data, editaddresscallback, label);
}

function setdefault(data, callback){
	setdefaultcallback = callback || setdefaultcallback;
	url = ApiUrl + setdefaulturl;
	addressdata(url, data, setdefaultcallback);
}

function deladdress(data, label, callback){
	deladdresscallback = callback || deladdresscallback;
	url = ApiUrl + deladdressurl;
	addressdata(url, data, deladdresscallback, label);
}
function addressdata (ApiUrl, data, callback, label) {
	label = label || '';
	$.ajax({
		type: 'POST',
		url: ApiUrl,
		data: data,
		success: function(info){
			callback(info,label);
		},
		dataType: 'json'
	});
}

function getaddresscallback(info){
	if(info.code == 0){
		var html = template('address_list_template',info.data);
		$('#address_list').html(html);
		initPage();
	}else{
		shopdz_alert(info.msg);
	}
}

function getareacallback(info, label){
	if(info['code']){
		shopdz_alert(info.msg);
		return ;
	}
	
	var html = template('areacontent',info);
	if(info['data'][0]['area_deep'] == 1){
		//省
		$('#province').html(html);
		$('#province').prepend('<option value="0" selected>请选择省</option>');
		$('#province option[value="' + label + '"]').attr('selected','selected');
	} else if (info['data'][0]['area_deep'] == 2){
		//市
		$('#city').html(html);
		$('#city').prepend('<option value="0" selected>请选择市</option>');
		$('#city option[value="' + label + '"]').attr('selected','selected');
	} else if (info['data'][0]['area_deep'] == 3){
		//区
		$('#area').html(html);
		$('#area').prepend('<option value="0" selected>请选择区</option>');
		$('#area option[value="' + label + '"]').attr('selected','selected');
	}
	initPage();
}

function addaddresscallback(info,label){
	if(info['code']){
		shopdz_alert(info.msg);
		return ;
	}
	shopdz_alert('添加成功！',0,function(){
		var html = '';
		html += '<li class="liclass_' + info['data'] + '" addressid="' + info['data'] + '">';
		html += '<div class="address-box border-bot marginB-no">';
		html += '	<div class="address-name" style="text-align:center">';
		html += '		<p class="name2">' + $('#address-name').val() + '</p>';
		if(isdefault){
			html += '<span class="default" addressid="' + info['data'] + '">默认</span>'
		}
		html += '		<input type="hidden" name="address_id" value="' + info['data'] + '"/>';
		html += '		<input type="hidden" name="province" value="' + $('#province option:selected').val() + '"/>';
		html += '		<input type="hidden" name="city" value="' + $('#city option:selected').val() + '"/>';
		html += '		<input type="hidden" name="area" value="' + $('#area option:selected').val() + '"/>';
		html += '	</div>';
		html += '	<div class="address-describe address-describe-list" style="width: 63%;">';
		html += '		<p class="phone1">' + $('#address-phone').val() + '</p>';
		html += '		<p class="address-word_hide" style="display:none">' + $('#address-word').val() + '</p>';
		html += '		<p class="address-word">' +$('#province option:selected').text()+$('#city option:selected').text()+ $('#area option:selected').text()+$('#address-word').val() + '</p>';
		html += '	</div>';
		html += '	<div class="jt-r2" style="right: ';
		if(del){
			html += '5rem;">';
		}else{
			html += '2rem;">';
		}
		html += '		<img src="img/address-edit.png" class="address-edit" addressid="' + info['data'] + '"/>';
		html += '	</div>';
		if(del){
			html += '	<div class="jt-r2" style="right: 2.5rem;">';
			html += '		<img src="img/dele2.png" class="address-dele" addressid="' + info['data'] + '"/>';
			html += '	</div>';
		}
		html += '</div>';
		if(del){
			html += '<div class="alertbox dele-sure none">';
			html += '	<p class="alert-p">要删除此地址？</p>';
			html += '	<div class="btnbox1">';
			html += '		<span class="cancelbtn1">取消</span>';
			html += '		<span class="surebtn1">确定</span>';
			html += '	</div>';
			html += '</div>';
		}
		html += '</li>';
		if(isdefault){
			label.prepend(html);
		}else{
			label.append(html);
		}
		if($('.img-center')){
			$('.img-center').remove();
			$('#address').find('input[name="address_id"]').val(info['data']);
			addressoff = true;
			$('#payment').removeClass('bg');
		}
		if(!del){
			$('#address_list li').removeClass('border-redleft');
			$('#address_list .liclass_' + info['data']).addClass('border-redleft');
			html += ' border-redleft';
		}
		
		$('.address-choice').css('display','block');
		$('.address-edit-page').css('display','none');
	})
}

function editaddresscallback(info,label){
	if(info['code']){
		shopdz_alert(info.msg);
		return ;
	}
	shopdz_alert('修改成功！',0,function(){
		var editaddressid = label.val();
		var lab = '';
		$('.address-edit').each(function(){
			if($(this).attr('addressid') == editaddressid){
				lab = $(this).parents('li');
				return ;
			}
		})
		if(isdefault){
			lab.find('.address-name').append('<span class="default" addressid="' + info['data'] + '">默认</span>');
		} else {
			lab.find('.default').remove();
		}
		lab.find('input[name="province"]').val($('#province option:selected').val());
		lab.find('input[name="city"]').val($('#city option:selected').val());
		lab.find('input[name="area"]').val($('#area option:selected').val());
		lab.find('.name2').text($('#address-name').val());
		lab.find('.phone1').text($('#address-phone').val());
		lab.find('.address-word_hide').text($('#address-word').val());
		lab.find('.address-word').text($('#province option:selected').text()+$('#city option:selected').text()+$('#area option:selected').text()+$('#address-word').val());
		lab.parent().prepend(lab);
		label.val('');
		$('.address-choice').css('display','block');
		$('.address-edit-page').css('display','none');
	})
}

function setdefaultcallback(info){
	if(info['code'] == 0){
		//window.location.href = WapSiteUrl + '/address.html';
	} else {
		shopdz_alert(info.msg);
	}
}

function deladdresscallback(info, label){
	if(info['code']){
		shopdz_alert(info.msg);
		return ;
	}
	shopdz_alert('删除成功！',0,function(){
		if(label.siblings().length <1){
			var html = '';
			html += '<div class="img-center">';
			html += '<img src="img/addressnull.png" alt="" class="coupon-null-img"/>';
			html += '<h4 class="coupon-tit">没有收货地址，赶快添加一个吧~</h4>';
			html += '</div>';
			label.parent().append(html);
		}
		label.remove();

	})
}


//判断手机号和验证码信息是否有误
function judgeaddress(name,phone,code,province,city,area){
	var name1 = name.val();
	var phone1 = phone.val();
	var pword1 = code.val();
	var regexName=/^(13[0-9]|14[5|7]|15[0-9]|17[0-9]|18[0-9])\d{8}$/,
		regexPas=/^\d{6}$/;
	if(name1==""){
		shopdz_alert("请输入收件人姓名！");
		return false;
	} else if(phone1==""){
		shopdz_alert("请输入正确的手机号！");
		return false;
	} else if(!regexName.test(phone1)){
		 shopdz_alert("请输入正确的手机号！");
		 return false;
	} else if(province.find('option:selected').val() == 0 ){
		shopdz_alert("请选择省！");
		return false;
	} else if(city.find('option:selected').val() == 0 ){
		shopdz_alert("请选择市！");
		return false;
	} else if(area.find('option:selected').val() == 0 ){
		shopdz_alert("请选择区！");
		return false;
	} else if(pword1==''){
		shopdz_alert("请输入收货地址！");
		return false;
	}
	return true;
}