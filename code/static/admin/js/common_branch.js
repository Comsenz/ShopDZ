var shopdz_dialog_tag = 1;
var   counter =  {};//所有页面弹出的计时器
function showSuccess(title,functionOrUrl){
	if(typeof functionOrUrl  =='string' && functionOrUrl!=''){
		var  html = '';
		var  selfId =  'shopdz_dialog_'+shopdz_dialog_tag;
		html+=''
		+'<div class="cover '+selfId+' "></div>'
		+'<div class=" '+selfId+' alert showAlert radius3 saveSuccess" id="'+selfId+'" >'
			+'<i class="close-icon" onclick="$(\'.'+selfId+'\').remove();window.location.href=\''+functionOrUrl+'\';"></i>'
			+'<i class="alert-icon"></i>'
			+'<h3 class="alert-tit">'+title+'</h3>'
			+'<p class="save-remind"><a href="'+functionOrUrl+'">页面如不能自动跳转，请选择手动操作</a></p>'
			+'<div class="saveSuccess-btnbox">'
				+''
			+'</div>'
		+'</div>';
		shopdz_dialog_tag++;
		$('body').append(html);
		setTimeout(function(){
			$('.'+selfId).remove();
			window.location.href=functionOrUrl;
		},2000);
	}else{
		var  html = '';
		var  selfId =  'shopdz_dialog_'+shopdz_dialog_tag;
		html+=''
	    +'<div class="cover '+selfId+'"></div>'
		+'<div  id="'+selfId+'" class="alert showAlert radius3 showSuccess '+selfId+'">'
			+'<i id="close_'+selfId+'"  class="close-icon"></i>'
			+'<i class="alert-icon"></i>'
			+'<h3 class="alert-tit">'+title+'</h3>'
			+'<p class="timeNum"><i class="time-icon"></i><span id="CountDown"></span><span id="time_'+selfId+'">3</span>秒后窗口关闭</p>'
		+'</div>';
		shopdz_dialog_tag++;
		$('body').append(html);
		var  t =3;
		var interval = setInterval(function(){
			$('#time_'+selfId).html(--t);	
		}, 1000);
		var  mytimeout = setTimeout(function(){
			clearInterval(interval);
			$('.'+selfId).remove();
			if(typeof functionOrUrl=='function'){
				functionOrUrl();
			}
		},3000);
		$('#close_'+selfId).click(function(){
			clearInterval(interval);
			clearTimeout(mytimeout);
			clearInterval(interval);
			$('.'+selfId).remove();
			if(typeof functionOrUrl=='function'){
				functionOrUrl();
			}
		});
	}
}

function  showError(title,callback){
	var  html = '';
	var  selfId =  'shopdz_dialog_'+shopdz_dialog_tag;
	html+=''
	+'<div class="cover '+selfId+'"></div>'
	+'<div id="'+selfId+'"  class="alert showAlert radius3 showFailed '+selfId+'">'
		+'<i id="close_'+selfId+'"  class="close-icon"></i>'
		+'<i class="alert-icon"></i>'
		+'<h3 class="alert-tit">错误提示</h3>'
		+'<p class="Failed-remind">'+title+'</p>'
		+'<p class="timeNum"><i class="time-icon"></i><span id="CountDown"></span><span id="time_'+selfId+'">3</span>秒后窗口关闭</p>'
	+'</div>';
	shopdz_dialog_tag++;
	$('body').append(html);
	var  t =3;
	var interval = setInterval(function(){
		$('#time_'+selfId).html(--t);	
	}, 1000);
	var  mytimeout = setTimeout(function(){
		clearInterval(interval);
		$('.'+selfId).remove();
		if(typeof callback=='function'){
			callback();
		}
	},3000);
	$('#close_'+selfId).click(function(){
		clearInterval(interval);
		clearTimeout(mytimeout);
		clearInterval(interval);
		$('.'+selfId).remove();
		if(typeof callback=='function'){
			callback();
		}
	});
}


function  showConfirm(title,callback,target){
	// target = iframeMyScroll || {};
	// target.disable();
	var  showConfirmHtml  = '';
	var  showConfirmId =  'shopdz_confirm_'+shopdz_dialog_tag;
	showConfirmHtml+='<div class="cover '+showConfirmId+' "   ></div><div   id="'+showConfirmId+'"   class="'+showConfirmId+'  alert showAlert radius3 confirm">'
		+'<i class="close-icon"  onclick="$(\'.'+showConfirmId+'\').remove();"></i>'
		+'<i class="alert-icon"></i>'
		+'<h3 class="alert-tit">'+title+'</h3>'
		+'<div class="confirm-btnbox">'
			+'<input type="button" class="btn1 confirmbtn confirmbtnSure radius3"  value="确定">'
			+'<input type="button"  class="confirmbtn confirmbtnCancel radius3 confirmbtn-margin"  value="取消">'
		+'</div>'
	+'</div>';
	shopdz_dialog_tag++;
	$('body').append(showConfirmHtml);
	$('#'+showConfirmId+' '+'.confirmbtnSure').click(function(){
		$('.'+showConfirmId).remove();
		if(typeof   callback=='function'){
			callback();
		}
		// target.enable();
	});
	$('#'+showConfirmId+' '+'.confirmbtnCancel').click(function(){
		$('.'+showConfirmId).remove();
		// target.enable();
	});
}

function closeW(id) {
	//console.log(arguments);
	var calssname = $(id).attr("id");
	$('.'+calssname).remove();
	window.iframeMyScroll.enable();
}

function  showConfirmWithScroll(title, callback, target) {
	target.disable();
	var  showConfirmHtml  = '';
	var  showConfirmId =  'shopdz_confirm_'+shopdz_dialog_tag;

	showConfirmHtml+='<div class="cover '+showConfirmId+' "   ></div><div   id="'+showConfirmId+'"   class="'+showConfirmId+'  alert showAlert radius3 confirm">'
		+'<i class="close-icon"  onclick="closeW('+showConfirmId+')"></i>'
		+'<i class="alert-icon"></i>'
		+'<h3 class="alert-tit">'+title+'</h3>'
		+'<div class="confirm-btnbox">'
			+'<input type="button" class="btn1 confirmbtn confirmbtnSure radius3"  value="确定">'
			+'<input type="button"  class="confirmbtn confirmbtnCancel radius3 confirmbtn-margin"  value="取消">'
		+'</div>'
	+'</div>';
	shopdz_dialog_tag++;
	$('body').append(showConfirmHtml);
	$('#'+showConfirmId+' '+'.confirmbtnSure').click(function() {
		$('.'+showConfirmId).remove();
		if(typeof  callback=='function'){
			callback();
		}
		target.enable();
	});
	$('#'+showConfirmId+' '+'.confirmbtnCancel').click(function(){
		$('.'+showConfirmId).remove();
		target.enable();
	});
}

/*
* 成功错误提示
* */
function getResultDialog(response,show){
	show  = show ? show : true;
	if(response.status==1){
		if(show){
			showSuccess("操作成功",function(){
				window.location.reload();});
		}else{
			window.location.reload();
		}
	}else{
		showError("提示",response.info);
	}
}