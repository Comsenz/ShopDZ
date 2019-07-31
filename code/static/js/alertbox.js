$(function(){
	$('.alert-btn').click(function(){
		$('.cover').removeClass('alert-hide');
		$('.alertcon').removeClass('alert-hide');
	})
		
	$('.cover,.closebtn2').click(function(){
		$('.cover').addClass('alert-hide');
		$('.alertcon').addClass('alert-hide');
	})
	
	//弹框居中
	var alertW=$('.alertcon').width();
	var alertH=$('.alertcon').height();
	function Posi(){
		$('.alertcon').css({'margin-left':-alertW/2+'px','margin-top':-alertH/2+'px'});
//		$('.alertcon-box').css('height',alertH-52+'px')
	}
	Posi();
})
