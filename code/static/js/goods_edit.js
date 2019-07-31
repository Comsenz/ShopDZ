

//in_array
in_array = function(e,a) {
	for (ik = 0; ik < a.length; ik++) {
		if (a[ik] == e)
			return true;
	}
	return false;
}


//生成笛卡尔积
function descartes(list) {
	//parent上一级索引;count指针计数
	var point = {};
	var result = [];
	var pIndex = null;
	var tempCount = 0;
	var temp = [];
	//根据参数列生成指针对象
	for (var index in list) {
		if (typeof list[index] == 'object') {
			point[index] = {
				'parent': pIndex,
				'count': 0
			}
			pIndex = index;
		}
	}
	//单维度数据结构直接返回
	if (pIndex == null) {
		return list;
	}
	//动态生成笛卡尔积
	while (true) {
		for (var index in list) {
			tempCount = point[index]['count'];
			temp.push(list[index][tempCount]);
		}
		//压入结果数组
		result.push(temp);
		temp = [];
		//检查指针最大值问题
		while (true) {
			if (point[index]['count'] + 1 >= list[index].length) {
				point[index]['count'] = 0;
				pIndex = point[index]['parent'];
				if (pIndex == null) {
					return result;
				}
				//赋值parent进行再次检查
				index = pIndex;
			} else {
				point[index]['count']++;
				break;
			}
		}
	}
}

function  countObj(obj){
	var _n=0;
	for(_i in obj){
		_n++;
	}
	return _n;
}



var   spec_value_list = {};
//添加商品 规格点击之后处理
function  re_compute_spec(){
	if($(this).prop('checked')){
		if(spec_num>0){
			$('.spec_lists').each(function(){
				if(!$(this).prop('checked')){
					$(this).attr('disabled','disabled');
				}
			});
		}
		spec_num++;
	}else{
		if(spec_num<=2){
			$('.spec_lists').removeAttr('disabled'); 
	    }else{
	    	$('.spec_lists').each(function(){
				if(!$(this).prop('checked')){
					$(this).attr('disabled','disabled');
				}
			});
	    }
		spec_num--;
	}
	var new_spec =  [];
	$('.spec_lists:checked').each(function(k,v){
		new_spec.push(parseInt($(v).val()));
	});
	for(i in new_spec){ //不在的重新添加
		if(!in_array(new_spec[i],spec_id_list)){
			var spec_id  = parseInt(new_spec[i]);
			$.get(__BASE__+'Commodity/get_spec',{"spec_id":spec_id},function(data){
				if(data.status==1){
					var  spec_info  = data.info;
					spec_id_name[spec_info.spec_id]   = spec_info.spec_name;
					var html = '';
					var  spec_value_html = '';
					var spec_values =  spec_info.spec_values; 
					$(spec_values).each(function(k,spec_value){
						spec_value_id_v[spec_value.spec_value_id]  = spec_value.spec_value;
						spec_value_id_spec_id[spec_value.spec_value_id]  =  spec_id;
						spec_value_html+='<p class="radiobox release-check"><input  data-spec_id="'+spec_id+'" id="spec_value_lists_'+spec_value.spec_value_id+'" type="checkbox"  value="'+spec_value.spec_value_id+'" name="spec_value['+spec_id+'][]"  class="regular-radio spec_value_lists" /><label for="spec_value_lists_'+spec_value.spec_value_id+'"  class="spec_lists_label"></label><span  title="'+spec_value.spec_value+'"  class="radio-word">'+spec_value.spec_value+'</span></p>';	
					});
					var html = ''
					+'<dl  id="spec_value_div_'+spec_info.spec_id+'"  class="juris-dl boxsizing release-dl">'
						+'<dt class="left text-r boxsizing"><span class="redstar">*</span>'+spec_info.spec_name+'：</dt>'
						+'<dd class="left text-l">'
							+'<div class="checkbox-holder">'
							  + spec_value_html
							+'</div>'
							+'<div class="add-release release-icon radius3">'
								+'<span class="re-add-span left"><i class="release-addicon"></i></span>'
								+'<span  data-spec_id="'+spec_info.spec_id+'"   class=" spec_value_add_btn re-add-word left">添加</span>'
							+'</div>'
							+'<p  id="spec_value_tail_'+spec_info.spec_id+'"  class="remind1">请选择'+spec_info.spec_name+'规格值 至少要选一个</p>'
						+'</dd>'
					+'</dl>';
                        $('#spec_value_after').before(html);
				    	// $(document).on('ifChanged',"#spec_value_div_"+spec_id+" .spec_value_lists",re_compute_spec_value); 
				}else{
					showError(data.info);	
				}
			},'json');
		}
	}
	//没有的删除掉
	for(i in spec_id_list){
		if(!in_array(spec_id_list[i],new_spec)){
			var spec_id  = parseInt(spec_id_list[i]);
			$('#spec_value_div_'+spec_id).remove();
		}
		// spec_id_list.splice(i,1);
	}
	spec_id_list =  new_spec;
	//删除 已有的商品
	$('#goods_list_div table').remove();
}

function  re_compute_spec_value(){  //规格值被选中之后
	spec_value_list = {};
	$('.spec_value_lists:checked').each(function(k,v){
		var  spec_id =  parseInt($(v).attr('data-spec_id'));
		var spec_value_id = parseInt($(v).val());
		if(typeof spec_value_list[spec_id] == 'undefined'){
			spec_value_list[spec_id] = [];	
		}
		spec_value_list[spec_id].push(spec_value_id);
	});
	if(countObj(spec_value_list) < 1){
		layer.alert('请至少选择一种规格');		
	}
	
	var  descartes_list = descartes(spec_value_list);
	//根据不同的规格生成 规格表格
	if(countObj(spec_value_list) == spec_id_list.length){
		create_spec_form(descartes_list);
	}else{
		$('#goods_list_div table').remove();
	}
}

$(function(){
	/***选择规格的点击事件**/
	$(document).on('click','.spec_lists',re_compute_spec);
	//批量设置商品价格和库存
	$(document).on('click','#price_button',function(){
		var price =   parseFloat($('#price_text').val());
		if(isNaN(price)){
			$(this).parents('.edit-spec').hide();
			return ;
		}
		if(price>0){
			$('.sku_price').val(price);
		}
		$(this).parents('.edit-spec').hide();
	});
	$(document).on('click','#storage_button',function(){
		var storage =  parseInt($('#storage_text').val());
		if(isNaN(storage)){
			storage =  0;
		}
		$('.sku_storage').val(storage);
		$(this).parents('.edit-spec').hide();
	});
	$(document).on('click','#image_button',function(){
		var image =  $('#image_text').val();
		$('.sku_image').val(image);
		$(this).parents('.edit-spec').hide();
	});	
		
});


function    create_spec_form(descartes_list){
	var   html =  '';
	var  tr_datas = '';
	var id_key_list =   [];
	$(descartes_list).each(function(k,v){
		var  text_array = v.slice().sort(function(m,n){
			return  (parseInt(spec_value_id_spec_id[m]) - parseInt(spec_value_id_spec_id[n]));
		});
		var  key_array  = v.sort(function(m,n){
			return parseInt(m)-parseInt(n);
		});
		var idkey =    key_array.join('_');
		descartes_list[k]  = text_array;
		
		if(typeof goods_list[idkey] == 'undefined'){  
			tr_datas+='<tr>';
			$(text_array).each(function(k2,v2){
				tr_datas+='<td ><div class="td-word"  title="'+spec_value_id_v[v2]+'" >'+spec_value_id_v[v2]+'</div></td>';	
			});
			tr_datas+='<td><div class=" release-price release-inp release-table-price boxsizing radius3"><input type="text" class="price-inp left release-inp sku_price"  name="goods_list['+idkey+'][goods_price]"  value="'+goods_common_price+'" /><span class="price-unit left release-inp">元</span></div></td>';
			tr_datas+='<td><input type="text"  name="goods_list['+idkey+'][goods_storage]"  class=" sku_storage com-inp1 radius3 boxsizing release-inp table-inp-relative spec-inp1" /></td>';
			tr_datas+='<td><input type="text" name="goods_list['+idkey+'][goods_barcode]"   class="com-inp1 radius3 boxsizing release-inp table-inp-relative spec-inp2"/></td>';
			tr_datas+='<td><div  shopdz-action="upload_group"   class="spec-uploadbox release-price release-table-price boxsizing radius3"><input type="text"  name="goods_list['+idkey+'][goods_image]"  shopdz-action="upload_value"   class=" sku_image price-inp left spec-inp3"/><input type="file"   id="upload_file_'+idkey+'"  shopdz-action="upload_file"  class="spec-file release-inp" hidefocus="true"/><input type="button"   onclick="$(this).parents(\'[shopdz-action=upload_group]\').find(\'[shopdz-action=upload_file]\').click();"  class="price-unit left spec-uploadbtn"  value="选择图片"/></div></td>';
			tr_datas+='</tr>';
			id_key_list.push(idkey);
		}else{
			tr_datas+= goods_list[idkey];
		}

	});
	var  html = '';
		html+= '<table class="com-table spec-table"><thead>'
		html+='<tr>';
						var  temp_spec_id = [];
						$(descartes_list[0]).each(function(_k1,_v1){
							temp_spec_id.push(spec_value_id_spec_id[_v1]);
						});
						temp_spec_id.sort();
						$(temp_spec_id).each(function(_k1,_v1){
							html+='<th width="100" >';
    							html+=spec_id_name[_v1];
							html+='</th>';	
						});
						html+='<th width="103" class="posiR">';
							html+='<div class="release-icon spec-icon">';
								html+='价格';
								html+='<i class="edit-icon price-icon"></i>';
							html+='</div>';
							html+='<div class="edit-spec radius5">';
					             html+='<div class="edit-con">';
					             	html+='<p class="edit-tit">批量设置价格</p>';
					             	html+='<div class="edit-price release-inp boxsizing radius3">';
										html+='<input id="price_text" type="text" class="price-inp left release-inp"/>';
										html+='<span id="price_button"  class="price-unit left release-inp">设置</span>';
									html+='</div>';
					             html+='</div>';
					             html+='<s class="jt-top">';
					                html+='<i class="jt-top-icon"></i>';
					            html+='</s>';
					         html+='</div>';
						html+='</th>';
						html+='<th width="103" class="posiR">';
							html+='<div class="release-icon spec-icon">';
								html+='库存';
								html+='<i class="edit-icon stock-icon"></i>';
							html+='</div>';
							html+='<div class="edit-spec radius5">';
					             html+='<div class="edit-con">';
					             	html+='<p class="edit-tit">批量设置库存</p>';
					             	html+='<div class="edit-price release-inp boxsizing radius3">';
										html+='<input  id="storage_text"  type="text" class="price-inp left release-inp"/>';
										html+='<span id="storage_button" class="price-unit left release-inp">设置</span>';
									html+='</div>';
					             html+='</div>';
					             html+='<s class="jt-top">';
					                html+='<i class="jt-top-icon"></i>';
					            html+='</s>';
					         html+='</div>';
						html+='</th>';
						html+='<th width="130">商家货号</th>';
						html+='<th width="239" class="posiR">';
							html+='<div class="release-icon spec-icon">';
								html+='图片';
								html+='<i class="edit-icon uploadimg-icon"></i>';
							html+='</div>';
							html+='<div class="edit-spec radius5 edit-uploadImg">';
					             html+='<div class="edit-con">';
					             	html+='<p class="edit-tit">批量上传图片</p>';
					             	html+='<div shopdz-action="upload_group"   class="edit-uploadbox release-price release-table-price boxsizing radius3 left">';
										html+='<input type="text"  id="image_text" shopdz-action="upload_value"  class="price-inp left edit-inp1"/>';
										html+='<input type="file"   id="all_file"  shopdz-action="upload_file"  class="spec-file release-inp"/>';
										html+='<input type="button"  onclick="$(this).parents(\'[shopdz-action=upload_group]\').find(\'[shopdz-action=upload_file]\').click();"   class="price-unit left spec-uploadbtn"  value="批量上传"/>';
									html+='</div>	';
									html+='<button type="button"  id="image_button" class="edit-alertBtn left radius3">保存</button>';
					            html+='</div>';
					            html+='<s class="jt-top">';
					                html+='<i class="jt-top-icon"></i>';
					            html+='</s>';
					         html+='</div>';
					    html+='</th>';
					html+='</tr>';
				html+='</thead><tbody>';
					html+= tr_datas;
			html+='</tbody></table>';
        	
        $('#goods_list_div').html(html);
        //给没个上传加上上传事件
        uploader_list  = {};
        $(id_key_list).each(function(k3,v3){
        	uploader_list[v3] = new plupload.Uploader({
                runtimes: 'html5,html4,flash,silverlight',
                browse_button: 'upload_file_'+v3,
                url: __BASE__+'Upload/common',
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
                        uploader_list[v3].start();
                    },
                    UploadProgress: function (up, file) {
                    },
                    FileUploaded: function (up, file, res) {
                        var resobj = eval('(' + res.response + ')');
                        if(resobj.status){
                        	$('#upload_file_'+v3).parents('[shopdz-action="upload_group"]').find('[shopdz-action="upload_value"]').val(resobj.data);
                        }
                    },
                    Error: function (up, err) {
                        alert('err');
                    }
                }
            });
            uploader_list[v3].init();
        });


		// for(d)
		//批量设置 图片的上传事件
        uploader_all = new plupload.Uploader({
            runtimes: 'html5,html4,flash,silverlight',
            browse_button: 'all_file',
            url: __BASE__+'Upload/common',
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
                    uploader_all.start();
                },
                UploadProgress: function (up, file) {
                },
                FileUploaded: function (up, file, res) {
                    var resobj = eval('(' + res.response + ')');
                    if(resobj.status){
                    	 $("#all_file").parents('[shopdz-action="upload_group"]').find('[shopdz-action="upload_value"]').val(resobj.data);
                    }
                },
                Error: function (up, err) {
                    alert('err');
                }
            }
        });
        uploader_all.init();
}

