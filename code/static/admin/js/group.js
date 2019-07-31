$(function() {
//确认
$('#searchok').click(function() {
	var info = $('.green-border').find('p');
	if(info.length){
		var goods_name = info.html();
		var goods_storage = info.attr('goods_storage');
		var goods_images = info.attr('goods_images');
		var goods_id = info.attr('goods_id');
		var spec_name = info.attr('spec_name');
		var sign_price = info.attr('sign_price');
		var img_src = info.find('img').attr('src');
		var goods_common_id = info.attr('goods_common_id');
		//getGoodsDetail(goods_common_id);
		$('#goods_name').val(goods_name);
		$('.goods_name_text').html(goods_name);
		$('.spec_name_text').html(spec_name);
		$('.fight-pro-name').html(goods_name);
		$('.sign_price').html(sign_price);
		$('.group-pro-num').find('span').html(goods_storage);
		$('#group_img_list').attr('src',img_src);
		$('.group-detail-img').attr('src',goods_images);
		$('.alert-close').click();
		$('#goods_id').val(goods_id);
		$('.firstshow').hide();
		$('.secondshow').show();
	}
});

var list = ['price','group_person_num','group_hour','group-content','goods_name','group_name','start_time','end_time'];
 for (var j = 0; j < list.length; j++) {
	(function(j){
	var vl = $('.'+list[j]).val();
	if(vl)
		$('.'+list[j]+'_text').html(vl+'');
	if(list[j] !='start_time' || list[j] !='end_time') {
		$('.'+list[j]).bind('focus keyup keydown change blur',function() {
			var thisval = $('.'+list[j]).val();
			hs = $('.'+list[j]).hasClass('must_num');
			if(hs) {
				thisval = thisval.replace(/[^\d]/g,'');
				$('.'+list[j]).val(thisval);
			}
			$('.'+list[j]+'_text').html(thisval);
		});
	}
	})(j)
}
$('.must_num').bind('focus keyup keydown change blur',function() {
	var thisval = $(this).val();
	thisval = thisval.replace(/[^\d]/g,'');
	$(this).val(thisval);
})
$( "#starttime" ).datepicker({
changeMonth: true,
changeYear: true,
showButtonPanel:true,
dateFormat: 'yy-mm-dd',
showAnim:"fadeIn",//淡入效果
});
$( "#endtime" ).datepicker({
changeMonth: true,
changeYear: true,
showButtonPanel:true,
dateFormat: 'yy-mm-dd',
showAnim:"fadeIn",//淡入效果
});
$("#starttime").bind('focus keyup keydown change',function() {
	var starttime = $(this).val();
	if(starttime){
		var d2 = Date.parse(starttime);
		var d1 = new Date(parseInt(d2)).toLocaleString().replace(/:\d{1,2}$/,' ').split(' ')[0];
		if(d1)
		$('.start_time_text').html(d1);
	}
});
$("#endtime").bind('focus keyup keydown change',function() {
	var endtime = $(this).val();
	if(endtime){
		var d2 = Date.parse(endtime);
		var d1 = new Date(parseInt(d2)).toLocaleString().replace(/:\d{1,2}$/,' ').split(' ')[0];
		if(d1)
		$('.end_time_text').html(d1);
	}
});
  //图片上传;
    var uploader = new plupload.Uploader({
        runtimes: 'html5,html4,flash,silverlight',
        browse_button: 'upload_img',
        url: uploadurl,
        filters: {
            
            mime_types: [{
                title: "Image files",
                extensions: "jpg,gif,png,jpeg",
                prevent_duplicates: true
            }]
        },
        init: {
            PostInit: function () {
            },
            FilesAdded: function (up, files) {
                uploader.start();
            },
            UploadProgress: function (up, file) {
                //alert('这里可以做进度条');
            },
            FileUploaded: function (up, file, res) {
                var resobj = eval('(' + res.response + ')');
                if(resobj.status){
                    $(".group-list-img").attr('src',attach_dir+resobj.data);
                    $('.group-img-route').val(resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
    });
    uploader.init();
});
function getGoodsDetail(goods_common_id){
	var data = {
		goods_common_id : goods_common_id
	};
	getdata(getgoodsurl,data,function(data) {
		good = data.data;
		$("#goods_detail").html(good.goods_detail);
	});
}
