$(function(){
	setimgedit();
	function updateCoords(c){
		$('#x1').val(c.x);
		$('#y1').val(c.y);
		$('#w1').val(c.w);
		$('#h1').val(c.h);
	};

	function setimgedit(info, label){
		var imgurl = get('imgurl');
		var smallurl = get('smallurl');
		// var width = get('width');
		// var height = get('height');
		$('#abssrc').val(smallurl);
		$('.returnimg').attr('src', imgurl);

		// var new_width =  bodyW*0.8;
		// var new_height = bodyH*0.8;
		// var h_bili = height/ new_height;
		// var w_bili = width/ new_width;
		// if(height > bodyH*0.8 || width > bodyW*0.8 ){
		// 	if(w_bili > h_bili){

		// 		$('.returnimg').css("height",height/w_bili);
		// 		$('.returnimg').css("width",new_width);
		// 	}else{
		// 		$('.returnimg').css("height",new_height);
		// 		$('.returnimg').css("width",width/h_bili);
		// 	}
		// } else {
		// 	$('.returnimg').css("height","auto");
		// }
	  	initJcrop();
		$('.spec-none').show();
	}

	var jcrop_api;
	function initJcrop()
	{
		// var bodyW=$('.wrapper').width();
		// var bodyH=$('.wrapper').height();
		var bodyW = get('width');
		var bodyH = get('height');
		$('#cropbox').Jcrop({
			boxWidth:bodyW,
			boxHeight:bodyH,
		    aspectRatio: 1,
		 	onSelect: updateCoords,
			addClass:"img",
			setSelect:[0,0,100,100]
			},function(){
		    	jcrop_api = this;
		    	$('.jc-demo-box').width(bodyW);
		    	$('.jc-demo-box').height(bodyH);
		});

	};
})