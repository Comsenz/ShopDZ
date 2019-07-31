 	$(function(){
		if(typeof $.datepicker  != 'undefined'){
			$.datepicker.regional["zh-CN"] = { closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "" }
			$.datepicker.setDefaults($.datepicker.regional["zh-CN"]);
		}
        /* 时间插件配置项结束 */
 		$('.form_data').click(function(){
		    flag=checkrequire($(this).parents('form').attr('id'));
		    if(!flag){
		       $(this).parents('form').submit();
		    }
		});
		//点击日历图标，触发时间选择
	    $('.timeinp-icon').click(function(){
	       // $(this).parent().find('input').trigger('focus');
	    });
		
		//鼠标悬停时给dl添加样式
		$('.jurisdiction dl').hover(function(){
//			$(this).addClass('juris-dl-hover');
			$(this).children('dt').addClass('black-font');
			$(this).find('.remind1').addClass('green-font');
			$(this).find('input[type=text]').addClass('green-bg');
			$(this).find('textarea').addClass('green-bg');

			
		},function(){
//			$(this).removeClass('juris-dl-hover');
			$(this).children('dt').removeClass('black-font');
			$(this).find('.remind1').removeClass('green-font');
			$(this).find('input[type=text]').removeClass('green-bg');
			$(this).find('textarea').removeClass('green-bg');

		});
	
		$("input[class='switch-radio']").each(function(i){
			$(this).click(function(){
				_this = this;
			if($(_this).is(":checked")){ 
				//alert('hhhhhhhhhhhh');
				$(_this).attr("checked","checked");
				$(_this).parent().find('.switch-open').addClass('open-bg');
				$(_this).parent().find('.switch-close').removeClass('close-bg');
			} else {
				//alert('eeeeeeeeeeee');
				$(_this).removeAttr("checked");
				$(_this).parent().find('.switch-close').addClass('close-bg');
				$(_this).parent().find('.switch-open').removeClass('open-bg'); 
			}
			})
		})	


		//关闭弹窗
		$('.close-icon').bind('click',function(){
			$('.alert').addClass('none');
			$('.cover').addClass('none');
			if(typeof(window.iframeMyScroll) !='undefined'){
				window.iframeMyScroll.enable();
				window.iframeMyScroll.refresh();
			}
		})

	    
	    //搜索框效果
	    $('.search-btn').mouseenter(function(){
	    	$(this).addClass('search-btn-hover');	    
	    });
	    $('.search-btn').mouseleave(function(){
	    	$(this).removeClass('search-btn-hover');	    
	    });
	    $(".search-btn").click(function(){ 
			$(this).addClass('search-btn-active');
  		});
	    
	    $('.search-boxcon').mouseenter(function(){
	    	$(this).addClass('search-boxcon-hover');  
	    });
	    $('.search-boxcon').mouseleave(function(){
	    	$(this).removeClass('search-boxcon-hover');  
	    });


	    
	    /* 表格设置展开 */
	    $('.setting-sele-par').mouseenter(function(){
	    	/* 获取表格高度 */
	    	var h = $(this).parents('table').height() + $(this).parents('table').offset().top;
	    	var but_h = $(this).offset().top + 26;
	    	if(but_h + 60 > h){
	    		var set_h = $(this).find('.setting-sele-con').height();
	    		$(this).find('.setting-sele-con').css({'top':-set_h-2+$(this).height()+'px'});
	    	}
	    	$(this).find('.setting-sele').addClass('setting-sele-hover');
	    	event=arguments.callee.caller.arguments[0] || window.event;
	    	$(this).find('.setting-sele-con').show();
	    });
	    /* 表格设置隐藏 */
	    $('.setting-sele-par').mouseleave(function(){
	    	$('.setting-sele-con').hide();
	    	$('.setting-sele').removeClass('setting-sele-hover');
	    })
	    
	  //   //表格查看按钮效果
	  //   $('.setting-sele').mouseenter(function(){
	  //   	$(this).addClass('setting-sele-hover');  
	  //   	event=arguments.callee.caller.arguments[0] || window.event;
	  //   	var thisparenttr = $(this).parents('tr'),
	  //   		conheight = $(this).next('.setting-sele-con').height() + 10,
	  //   		trheight = $(thisparenttr).height(),
	  //   		trnum = Math.ceil(conheight / trheight);
	  //   	thisparenttr.siblings().find('.setting-sele').removeClass('setting-btn-inp-bg');
	  //   	thisparenttr.siblings().find('.setting-sele').next('.setting-sele-con').hide();
			// var next2tr = $(this).parents('tbody').find("tr")[thisparenttr.index() + trnum];
			// if(next2tr === undefined || ($(next2tr).offset().top + trheight) > $("body").height()) {
		 //    	$(this).next('.setting-sele-con').toggle();
	  //   	} else {
		 //    	$(this).next('.setting-sele-con').toggle();
	  //   	}
	  //   	event.stopPropagation();
	  //   })
	  //   if($('.setting-sele-con').is(':hidden')){
	  //   	$(this).parent("tr").find('.setting-sele').removeClass('setting-sele-hover');
	  //   }
	  //   $('.setting-sele-con').mouseleave(function(){
	  //   	$(this).hide();
	  //   	$('.setting-sele').removeClass('setting-sele-hover');
	  //   })
	    
	    $('.most-box').click(function(event){
	    	$('.most-sele-con').toggle();
	    	event.stopPropagation();
	    })
	    $(document).click(function(){
	    	$('.most-sele-con').hide();
	    })
	    $('.most-sele-con>li').click(function(){
	    	$(this).addClass('most-active-sele').siblings().removeClass('most-active-sele');
	    })
 	})


	$("input[class='switch-radio']").each(function(i){
		_this = this;
		if($(_this).attr('checked')){ 
		     $(_this).parent().find('.switch-open').addClass('open-bg');
		     $(_this).parent().find('.switch-close').removeClass('close-bg');
		     
		} else {
			$(_this).find('.switch-open').addClass('close-bg');
			$(_this).find('.switch-close').removeClass('open-bg');
		}
	});
	
	
	
	
	
		/*表格滑过*/
		$('.com-table tbody tr td').mouseover(function(){
			var index=$(this).index();
			$(this).parents('table').find('th').eq(index).css('border-bottom','1px solid #37988b');
		})
		$('.com-table tbody tr td').mouseout(function(){
			var index=$(this).index();
			$(this).parents('table').find('th').eq(index).css('border-bottom','');
		})
	
	
	
	
	$("input[class='regular-radio']").each(function(i){
		$(this).click(function(){
			_this = this;
		if($(_this).is(":checked")){ 
			$(_this).attr("checked","checked");
			$(_this).parent('.radiobox').find('.radio-word').addClass('radio-word-black');
		} else {
			$(_this).removeAttr("checked");
			$(_this).parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
		}
		})
	})	
	
 	$("input[class='regular-radio']").each(function(i){
		_this = this;
		if($(_this).attr('checked')){ 
		     $(_this).parent('.radiobox').find('.radio-word').addClass('radio-word-black');
		} else {
			$(_this).parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
		}
	});
 	//全选
	var _radio = $(".regular-radio");
    $('#radio-1-1').click(function(){
		//	$(_this)[0].checked=ched;
      //  var isChecked = $('#radio-1-1:checked').val();
		var isChecked = $(this)[0].checked;
        if(isChecked == 'true'){
            _radio.each(function() {
				var _this = this;
				$(_this)[0].checked=isChecked;
				$(_this).parent('.radiobox').find('.radio-word').addClass('radio-word-black');
			 });
           // _radio.parent('.radiobox').find('.radio-word').addClass('radio-word-black');
        }else{
			  _radio.each(function() {
				var _this = this;
				$(_this)[0].checked=isChecked;
				$(_this).parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
			 });
          //  _radio.removeAttr('checked','');
           // _radio.parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
        }
    });
    //分组全选
	$('.this-parent').click(function(){
		var isChecked = $(this)[0].checked;
		_aradio = $('.'+$(this).attr('child'));
	    var isCheck= $(this).attr('checked');
		if(isChecked == 'true'){
	        _aradio.each(function() {
				var _this = this;
				$(_this)[0].checked=isChecked;
				$(_this).parent('.radiobox').find('.radio-word').addClass('radio-word-black');
			 });
	       // _aradio.parent('.radiobox').find('.radio-word').addClass('radio-word-black');
	    }else{
	        _aradio.each(function() {
				var _this = this;
				$(_this)[0].checked=isChecked;
				$(_this).parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
			 });
	       // _aradio.parent('.radiobox').find('.radio-word').removeClass('radio-word-black');
	    }
	})

    if($('#radio-1-1:checked').val() == 'on'){
        _radio.attr('checked','checked');
        _radio.parent('.radiobox').find('.radio-word').addClass('radio-word-black'); 
    }
 	
 	
 		function remindNeed(obj,e,remindText){
 			obj.addClass('need-remind');
			tipRemind(e,remindText);
 			
 		}
		function tipRemind(e,remindText){
			//alert('4444 ');
			var html = '';
			$('body').remove('.tip-remind');
			if($('.tip-remind').html()){
			} else {
				html +='<div class="tip-remind layer-shadow radius3">'+remindText+'</div>';
				$('body').append(html);
			}
			var top = e.pageY+18;
			var left = e.pageX-40;
			$('.tip-remind').css({
		     	'top' : top + 'px',
		     	'left': left+ 'px'
			})
		}
		//||"SELECT"==ee.tagName
		/**校验非空： empty
		 * 校验数值数：intval
		 * 校验限制长度：limit,默认为20，例：  limit: 50 限制50字以内  limit:10,50 限制在大于10小于50字之内   limit:coupon_t_price   校验大于id="coupon_t_price"的input框的值
		 * 校验大于：gt 例   gt:20    输入值要大于20
		 * 校验小于：lt 例   lt:30    输入值要小于30
		 * 校验时间：time 例    time  仅校验时间格式     time:start   校验大于 id="start" 的input框的日期
		 * 多个校验之间用‘;’分隔   例  localrequired="empty;intval;limit:50"
		 **/
    function checkrequire(formid) {
        var ff=$('#'+formid+' :input');
        var flag = false;
        for (var i=0;i<ff.length;i++){
            var ee=ff[i];
            if(typeof($(ee).attr("localrequired")) !="undefined"){
                if("INPUT"==ee.tagName || "TEXTAREA"==ee.tagName ){
                    var no = false;
                    if($(ee).attr("localrequired") == ""){
                        var local_arr = ['empty'];
                    }else{
                        var local_arr = $(ee).attr("localrequired").split(';');
                    }
                    $.each(local_arr, function(k, v) {
                        key = v;
                        if(key.indexOf("limit") >= 0){
                            param = key.split(':')[1] || '20';
                            param = param.split(',');
                            key = 'limit';
                        }
                        if(key.indexOf("lt") >= 0 || key.indexOf("gt") >= 0){
                            par_arr = key.split(':');
                            param =  par_arr[1] || '20';
                            key = par_arr[0];
                            if(isNaN(param) !== false){
                                param = $('#'+param).val();
                            }
                        }
                        if(key.indexOf("time") >= 0){
                            param = '';
                            par_arr = key.split(':');
                            if(par_arr.length > 1){
                                param = $('#'+par_arr[1]).val();
                            }
                            key = 'time';
                        }
                        switch(key){
                            case 'intval' :
                                if(isNaN($(ee).val()) !== false){
                                    no = true;
                                }
                                break;
                            case 'limit' :
                                if(param.length > 1){
                                    if($(ee).val().length > param[1] || $(ee).val().length < param[0]){
                                        no = true;
                                    }
                                }else if(param.length == 1){
                                    if($(ee).val().length > param[0]){
                                        no = true;
                                    }
                                }
                                break;
                            case 'empty' :
                                if($(ee).val() == '' || $(ee).val() == ' '){
                                    no = true;
                                }
                                break;
                            case 'lt' :
                                if(parseInt($(ee).val()) >= parseInt(param)){
                                    no = true;
                                }
                                break;
                            case 'gt' :
                                if(parseInt($(ee).val()) <= parseInt(param)){
                                    no = true;
                                }
                                break;
                            case 'time' :
                                var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}/;
                                if(!DATE_FORMAT.test($(ee).val())){
                                    no = true;
                                }
                                if(param != '' && $.myTime.DateToUnix($(ee).val()) <= $.myTime.DateToUnix(param) ){
                                    no = true;
                                }
                                break;
                        }
                    $(ee).bind('blur change',function() {
                        var _this = this;
                        var bno = false;
                        if($(_this).attr("localrequired") == ""){
                            var local_arr = ['empty'];
                        }else{
                            var local_arr = $(_this).attr("localrequired").split(';');
                        }
                        $(local_arr).each(function(k, v) {
                            console.log(v);
                            key = v;
                            if(key.indexOf("limit") >= 0){
                                param = key.split(':')[1] || '20';
                                param = param.split(',');
                                key = 'limit';
                            }
                            if(key.indexOf("lt") >= 0 || key.indexOf("gt") >= 0){
                                par_arr = key.split(':');
                                param =  par_arr[1] || '20';
                                key = par_arr[0];
                                if(isNaN(param) !== false){
                                    param = $('#'+param).val();
                                }
                            }
                            if(key.indexOf("time") >= 0){
                                par_arr = key.split(':');
                                if(par_arr.length > 1){
                                    param = $('#'+par_arr[1]).val();
                                }
                                key = 'time';
                            }
                            switch(key){
                                case 'intval' :
                                    if(isNaN($(_this).val()) !== false){
                                        bno = true;
                                    }
                                    break;
                                case 'limit' :
                                    if(param.length > 1){
                                        if($(_this).val().length > param[1] || $(_this).val().length < param[0]){
                                            bno = true;
                                        }
                                    }else if(param.length == 1){
                                        if($(_this).val().length > param[0]){
                                            bno = true;
                                        }
                                    }
                                    break;
                                case 'empty' :
                                    if( $(_this).val() == '' || $(_this).val() == ' '){
                                        bno = true;
                                    }
                                    break;
                                case 'lt' :
                                    if(parseInt($(_this).val()) >= parseInt(param)){
                                        bno = true;
                                    }
                                    break;
                                case 'gt' :
                                    if(parseInt($(_this).val()) <= parseInt(param)){
                                        bno = true;
                                    }
                                    break;
                                case 'time' :
                                    var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}/;
                                    if(!DATE_FORMAT.test($(_this).val())){
                                        no = true;
                                    }
                                    if(param && DATE_FORMAT.test($(_this).val()) <= DATE_FORMAT.test(param) ){
                                        no = true;
                                    }
                                    break;
                            }
                        });
                        if(bno === false){
                            if($(_this).parent().is('div') !== false){
                                $(_this).parent().removeClass('inp-waring');
                                $(_this).css('background','#fff');
                            }else{
                                $(_this).removeClass('inp-waring');
                            }
                        }else{
                            if($(_this).parent().is('div') !== false){
                                $(_this).parent().addClass('inp-waring');
                                $(_this).css('background','#fff5f3');
                            }else{
                                $(_this).addClass('inp-waring');
                            }
                        }
                    });
                    $(ee).bind('focus',function() {
                        if($(this).parent().is('div') !== false){
                            $(this).parent().removeClass('inp-waring');
                            $(this).css('background','#fff');
                        }else{
                            $(this).removeClass('inp-waring');
                        }
                    });
                    if(no === true){
                        if($(ee).parent().is('div') !== false){
                            $(ee).parent().addClass('inp-waring');
                            $(ee).css('background','#fff5f3');
                        }else{
                            $(ee).addClass('inp-waring');
                        }
                        flag = true;
                    }
                });

                }else if( "SELECT"==ee.tagName ){
                    var real_id = $(ee).attr('id');
                    var show_id = $('#'+real_id+'_ctrl');
                    if($(ee).val() =='' || parseInt($(ee).val()) ==0 ){
                        flag = true;
                        show_id.addClass('inp-waring');
                    }
                    show_id.bind('click',function() {
                        $(this).removeClass('inp-waring');
                    });
                }
            }
        }
        if($("#"+formid+" input:radio").length ){
            parent = $($("#"+formid+" input:radio").get(0)).parents('.button-holder');
            if(typeof(parent.attr("localrequired")) !='undefined' ){
                if(typeof($("#"+formid+" input:radio:checked").val()) == 'undefined'){
                    parent.addClass('inp-waring');
                    parent.bind('click',function() {
                        $(this).removeClass('inp-waring');
                    });
                    flag = true;
                }
            }
        }
        return flag;
    }

	function checkall(self,classname) {
		var ched = self.checked;
		 var k =$('.'+classname +" input[type='checkbox']").length;
		 $('.'+classname +" input[type='checkbox']").each(function() {
			var _this = this;
			$(_this)[0].checked=ched;
		 });
	}

		//图片预览
		function imgPreview(e){
			//alert('4444 ');
			var url = e.attr('url');
			var html = '';
			$('body').remove('.previewdiv');
			if($('.previewdiv').length > 0){
			} else {
				html +='<div class="previewdiv radius5 boxsizing"><div class="viewimg-div"><img src="'+url+'" class="view-img"/></div></div>'
				$('body').append(html);
			}
			$('.previewdiv').find('img').attr('src',url);
			var top = e.pageY+18;
			var left = e.pageX-40;
			$('.previewdiv').css({
		     	'top' : top + 'px',
		     	'left': left+ 'px'
			})
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
			settings.$area = $("<select id='area_"+settings.selected_deep+"' style='width:auto;'></select>");
			
			$inputArea.before(settings.$area);
			
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
			$area.change(function(){
				$(this).nextAll("select").each(function(){
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
				var area_id = settings.area_id = $(this).val();
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
						var $newArea = $("<select id='area_"+settings.selected_deep+"' style='width:auto;'></select>");
						$(this).after($newArea);
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
				$.getJSON(__BASE__+'district/json_area?src=' + settings.src + "&callback=?", function(data) {
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
})(jQuery);


(function($) {
    $.extend({
        myTime: {
            /**
             * 当前时间戳
             * @return <int>        unix时间戳(秒)  
             */
            CurTime: function(){
                return Date.parse(new Date())/1000;
            },
            /**              
             * 日期 转换为 Unix时间戳
             * @param <string> 2014-01-01 20:20:20  日期格式              
             * @return <int>        unix时间戳(秒)              
             */
            DateToUnix: function(string) {
                var f = string.split(' ', 2);
                var d = (f[0] ? f[0] : '').split('-', 3);
                var t = (f[1] ? f[1] : '').split(':', 3);
                return (new Date(
                        parseInt(d[0], 10) || null,
                        (parseInt(d[1], 10) || 1) - 1,
                        parseInt(d[2], 10) || null,
                        parseInt(t[0], 10) || null,
                        parseInt(t[1], 10) || null,
                        parseInt(t[2], 10) || null
                        )).getTime() / 1000;
            },
            /**              
             * 时间戳转换日期              
             * @param <int> unixTime    待时间戳(秒)              
             * @param <bool> isFull    返回完整时间(Y-m-d 或者 Y-m-d H:i:s)              
             * @param <int>  timeZone   时区              
             */
            UnixToDate: function(unixTime, isFull, timeZone) {
                if (typeof (timeZone) == 'number')
                {
                    unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
                }
                var time = new Date(unixTime * 1000);
                var ymdhis = "";
                ymdhis += time.getUTCFullYear() + "-";
                ymdhis += (time.getUTCMonth()+1) + "-";
                ymdhis += time.getUTCDate();
                if (isFull === true)
                {
                    ymdhis += " " + time.getUTCHours() + ":";
                    ymdhis += time.getUTCMinutes() + ":";
                    ymdhis += time.getUTCSeconds();
                }
                return ymdhis;
            }
        }
    });
})(jQuery);