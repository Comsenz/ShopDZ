function mousePosition(){
	
}

function doconfirm(msg,callback){
	if(confirm(msg)){
		callback();
	};
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

function isNumeric(val){
	return true;
}

function in_array(needle, haystack) {
	if(typeof needle == 'string' || typeof needle == 'number') {
		for(var i in haystack) {
			if(haystack[i] == needle) {
				return true;
			}
		}
	}
	return false;
}


var showDialogST = null;//唯一句柄




function showSuccess(title,message,callback){
	var  extend ={"title": title,"text":message,"type":"success"};
	swal(extend,callback);
}

function  showError(title,message,callback){
	var  extend ={"title": title,"text":message,"type":"error"};
	swal(extend,callback);
}


function  showConfirm(title,message,callback){
	swal({
	  title: title,
	  text: message,
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: "确定",
	  cancelButtonText: "取消",
	  closeOnConfirm: false
	},callback);
}


/*
* 显示日期框
* elemeid 当前日期要显示的input框
* start 起始日期天数
* end   截至日期天数
* */
function showlaydate(elemid,start,end){
	start = start ? start : '';
	end = end ? start : 10000;
	laydate({elem: '#'+elemid,istime: true,format: 'YYYY-MM-DD hh:mm',min:laydate.now(-start),max:laydate.now(+end)});
}

/*
* 成功错误提示
* */
function getResultDialog(response,show){
	show  = show ? show : true;
	if(response.status==1){
		if(show){
			showSuccess("提示","操作成功",function(){window.location.reload();});
		}else{
			window.location.reload();
		}
	}else{
		showError("提示",response.info);
	}
}
/*
 * 地址联动选择
 * input不为空时出现编辑按钮，点击按钮进行联动选择
 *
 * 使用范例：
 * [html]
 * <input id="region" name="region" type="hidden" value="" >
 * [javascrpt]
 * $("#region").nc_region();
 * 
 * 默认从cache读取地区数据，如果需从数据库读取（如后台地区管理），则需设置定src参数
 * $("#region").nc_region({{src:'db'}});
 * 
 * 如需指定地区下拉显示层级，需指定show_deep参数，默认未限制
 * $("#region").nc_region({{show_deep:2}}); 这样最多只会显示2级联动
 * 
 * 如需指定地区下拉显示宽度，需指定width参数，默认为select的宽度
 * $("#region").nc_region({{width:90}}); 这样宽度为90px
 * 
 * 系统分自动将已经选择的地区ID存放到ID依次为_area_1、_area_2、_area_3、_area_4、_area的input框中
 * _area存放选中的最后一级ID
 * 这些input框需要手动在模板中创建
 * 
 * 取得已选值
 * $('#').val() ==> 河北 石家庄市 井陉矿区
 * $('#region').fetch('islast')  ==> true; 如果非最后一级，返回false
 * $('#region').fetch('area_id') ==> 1127
 * $('#region').fetch('area_ids') ==> 3 73 1127
 * $('#region').fetch('selected_deep') ==> 3 已选择分类的深度
 * $("#region").fetch('area_id_1') ==> 3
 * $("#region").fetch('area_id_2') ==> 73
 * $("#region").fetch('area_id_3') ==> 1127
 * $("#region").fetch('area_id_4') ==> ''
 */

(function($) {
	$.fn.nc_region = function(options) {
		var $region = $(this);
		var settings = $.extend({}, {
			area_id: 0,
			region_span_class: "_region_value",
			src: "cache",
			show_deep: 0,
			width: '',
			btn_style_html: "",
			tip_type: ""
		}, options);
		settings.islast = false;
		settings.selected_deep = 0;
		settings.last_text = "";
		this.each(function() {
			var $inputArea = $(this);
			if ($inputArea.val() === "") {
				initArea($inputArea);
			} else {
				var $region_span = $('<span id="_area_span" class="' + settings.region_span_class + '">' + $inputArea.val() + "</span>");
				var $region_btn = $('<input type="button" class="btn2 edit-btn3 radius3 boxsizing" ' + settings.btn_style_html + ' value="编辑" />');
				$inputArea.after($region_span);
				$region_span.after($region_btn);
				$region_btn.on("click", function() {
					$region_span.remove();
					$region_btn.remove();
					initArea($inputArea)
				});
				settings.islast = true
			}
			this.settings = settings;
			if ($inputArea.val() && /^\d+$/.test($inputArea.val())) {
				$.getJSON('json_area_show?area_id=' + $inputArea.val() + "&callback=?", function(data) {
					nc_a = data;
					$("#_area_span").html(data.text == null ? "无" : data.text)
				})
			}
		});

		function initArea($inputArea) {
			settings.$area = $("<select id='area_"+settings.selected_deep+"' class='com-sele radius3 juris-dl-sele sele-m1' style='width:auto;'></select>");
			var newdiv = $('<div class="search-boxcon boxsizing radius3 left"></div>');
			$inputArea.before(newdiv);
			newdiv.append(settings.$area);
			loadAreaArray(function() {
				loadArea(settings.$area, settings.area_id)
			})
		}
		function loadArea($area, area_id) {
			
			if ($area && nc_a[area_id].length > 0) {
				var areas = [];
				areas = nc_a[area_id];
				if (settings.tip_type && settings.last_text != "") {
					$area.append("<option value=''>" + settings.last_text + "(*)</option>")
				} else {
					$area.append("<option value=''>-请选择-</option>")
				}
				for (i = 0; i < areas.length; i++) {
					$area.append("<option value='" + areas[i][0] + "'>" + areas[i][1] + "</option>")
				}
				settings.islast = false
			}
			
			simulateSelect($area.attr('id'),settings.width,function(th,sel) {
				$(sel).parent().nextAll(".search-boxcon").each(function(){
					var id = $(this).find('select').attr('id') + '_ctrl_menu';
					$("#"+id).remove();
					//$(this).next().remove();
					$(this).remove();

				});
				var region_value = "",area_ids = [],selected_deep = 1;
				
				$region.parent().find("select").each(function() {
					if ($(this).val() != "") {
						region_value += $(this).find('option:selected').text() + " ";
						area_ids.push($(this).val());
					}
					settings.last_text = $(this).find('option:selected').text();
				});

				settings.selected_deep = area_ids.length;
				settings.area_ids = area_ids.join(" ");
				$region.val(region_value);
				settings.area_id_1 = area_ids[0] ? area_ids[0] : "";
				settings.area_id_2 = area_ids[1] ? area_ids[1] : "";
				settings.area_id_3 = area_ids[2] ? area_ids[2] : "";
				settings.area_id_4 = area_ids[3] ? area_ids[3] : "";
				//settings.last_text = $region.prevAll("select").find("option:selected").last().text();
				var area_id = settings.area_id = $(sel).val();
				if ($('#_area_1').length > 0) $("#_area_1").val(settings.area_id_1);
				if ($('#_area_2').length > 0) $("#_area_2").val(settings.area_id_2);
				if ($('#_area_3').length > 0) $("#_area_3").val(settings.area_id_3);
				if ($('#_area_4').length > 0) $("#_area_4").val(settings.area_id_4);
				if ($('#_area').length > 0) $("#_area").val(settings.area_id);
				if ($('#_areas').length > 0) $("#_areas").val(settings.area_ids);
				if (settings.show_deep > 0 && $('select').length == settings.show_deep) {
					settings.islast = true;
					if (typeof settings.last_click == 'function') {
						settings.last_click(area_id);
					}
					return ;
				}
				if (area_id > 0) {
					if (nc_a[area_id] && nc_a[area_id].length > 0) {
						var $newArea = $("<select id='area_"+settings.selected_deep+"' class='com-sele radius3 juris-dl-sele sele-m1' style='width:auto;'></select>");
						var newdiv = $('<div class="search-boxcon boxsizing radius3 left"></div>');
						$(sel).parent().after(newdiv);
						newdiv.append($newArea);
						//$(sel).parent().append($newArea);
						loadArea($newArea, area_id);
						settings.islast = false
					} else {
						settings.islast = true;
						if (typeof settings.last_click == 'function') {
							settings.last_click(area_id);
						}
					}
				} else {
					settings.islast = false
				}
				if ($('#islast').length > 0) $("#islast").val("");
			})
		}
		function loadAreaArray(callback) {
			if (typeof nc_a === "undefined") {
				$.getJSON('/index.php/district/json_area?src=' + settings.src + "&callback=?", function(data) {
					nc_a = data;
					callback()
				})
			} else {
				callback()
			}
		}
		if (typeof jQuery.validator != 'undefined') {
			jQuery.validator.addMethod("checklast", function(value, element) {
				return $(element).fetch('islast');
			}, "请将地区选择完整");
		}
	};
	$.fn.fetch = function(k) {
		var p;
		this.each(function() {
			if (this.settings) {
				p = eval("this.settings." + k);
				return false
			}
		});
		return p
	}
})(jQuery);
