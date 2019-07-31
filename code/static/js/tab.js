$(document).ready(function() {  
	    $('#tab-title li').click(function(){ 
			$(this).addClass("selected").siblings().removeClass();//removeClass就是删除当前其他类；只有当前对象有addClass("selected")；siblings()意思就是当前对象的同级元素，removeClass就是删除； 
			$("#tab-content > div").hide().eq($('#tab-title li').index(this)).show(); 
		}); 
		
		 
	}); 