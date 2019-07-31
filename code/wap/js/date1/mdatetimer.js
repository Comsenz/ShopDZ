function mdatetimer(opts, label){
		var defaults = {
			mode : 1, //时间选择器模式：1：年月日，2：年月日时分（24小时），3：年月日时分（12小时），4：年月日时分秒
			format : 2, //时间格式化方式：1：2015年06月10日 17时30分46秒，2：2015-05-10  17:30:46
			startyear: 1950,//开始年份
			endyear: 2016,//结束年份
			years : [], //年份，不用设置，会自动从startyear-endyear中计算得出
			nowbtn : true,
			onOk : null,
			onCancel : null,
			exceedcurdate: false,//true：选中日期可以超过当前日期，false：不可以超过
		};
		for (var i = defaults.startyear; i <= defaults.endyear; i++) {
			defaults.years[defaults.years.length] = i;
		};
		var option = $.extend(defaults, opts);

		//通用函数
		var F = {
			//计算某年某月有多少天
			getDaysInMonth : function(year, month){
			    return new Date(year, month+1, 0).getDate();
			},
			getMonth : function(m){
				return ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'][m];
			},
			//计算年某月的最后一天日期
			getLastDayInMonth : function(year, month){
				return new Date(year, month, this.getDaysInMonth(year, month));
			},
			//小于10的数字前加0
			preZero : function(num){
				num = parseInt(num);
				if(num<10){
					return '0'+num;
				}
				else{
					return num;
				}
			},
			formatDate : function(year, month, day, hour, minute, second){
				month = F.preZero(month+1);
				day = F.preZero(day);
				hour = F.preZero(hour);
				minute = F.preZero(minute);
				if(option.mode!=3){
					second = F.preZero(second);
				}
				else{
					//可能点“现在”取时间，需根据时间判断上下午
					if(second == 'am'){
						second = '上午';
					}
					else if(second == 'pm'){
						second = '下午';
					}
					else{
						//传入的是秒数
						if(hour<12){
							second = '上午';
						}
						else{
							second = '下午';
						}
					}
					
				}
				
				var result = '';
				if(option.format==1){
					result += year+'年'+month+'月'+day+'日';
					if(option.mode!=1 && hour){
						result += ' '+hour+'时'+minute+'分';
						if(option.mode!=2){
							if(!isNaN(parseInt(second))){
								result += second+'秒';
							}
							else{
								result += ' '+second;
							}
						}
					}
				}
				else{
					result += year+'-'+month+'-'+day;
					if(option.mode!=1 && hour){
						result += ' '+hour+':'+minute+'';
						if(option.mode!=2){
							if(!isNaN(parseInt(second))){
								result += ':'+second+'';
							}
							else{
								result += ' '+second;
							}
						}
					}
				}
				return result;
			},
			getDateValue : function(value){
				if(option.format==2){
					return new Date(value);
				}
				else{
					var array = value.replace(/\D ?/g,',').slice(0,-1).split(',');
					if(array.length==7){
						//带有“上午、下午”
						if(value.indexOf('上午')>=0){
							return new Date(array[0], array[1]-1, array[2], array[3], array[4]);
						}
						else{
							return new Date(array[0], array[1]-1, array[2], parseInt(array[3])+12, array[4]);
						}
						
					}
					else{
						return new Date(array[0], array[1]-1, array[2], array[3], array[4], array[5]);
					}
				}
			}
		}
		/* 当前日期之后不可选择 */
		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth();
		var day = date.getDate();
		//滑动配置项
		var scrollConf = {
			snap : 'li',
			snapSpeed: 600,
			probeType : 1,
			tap : false
		};

		var input = label,
			itemHeight = 40;
		var picker = {
			renderHTML : function(){
				var stime = option.timeStart + ':00';
				var etime = option.timeStart + option.timeNum + ':00';
				var html = '<div class="mt_mask"></div><div id="mdatetimer" class="mt_poppanel"><div class="mt_panel"><h3 class="mt_title">请选择时间</h3><div class="mt_body"><div class="mt_year"><ul><li class="mt_note">选择年份</li><li></li></ul></div><div class="mt_month"><ul><li class="mt_note">选择月份</li><li></li></ul></div><div class="mt_day"><ul><li class="mt_note">选择日期</li><li></li></ul></div><div class="mt_sepline"></div><div class="mt_hour"><ul><li class="mt_note">选择时</li><li></li></ul></div><div class="mt_minute"><ul><li class="mt_note">选择分</li><li></li></ul></div><div class="mt_second"><ul><li class="mt_note">选择秒</li><li></li></ul></div><div class="mt_indicate"></div><div class="mt_indicate cate2"></div></div><div class="mt_confirm"><a href="javascript:void(0);" class="mt_cancel">取消</a><a href="javascript:void(0);" class="mt_ok">确定</a><a href="javascript:void(0);" class="mt_setnow">现在</a></div></div></div>';
				$(document.body).append(html);
				initPage();
			},
			updateSelected : function(container, iscroll){
				var index = (-iscroll.y) / itemHeight + 2;
				var current = container.find('li').eq(index);
				current.addClass('selected').siblings().removeClass('selected');
			},
			showPanel : function(container){
				$('.mt_poppanel, .mt_mask').addClass('show');
			},
			hidePanel : function(){
				$('.mt_poppanel, .mt_mask').removeClass('show');
			},
			setValue : function(y, m, d, h, min, s){
				var yearItem = $('.mt_year li[data-year="'+y+'"]'),
					monthItem = $('.mt_month li[data-month="'+m+'"]'),
					dayItem = $('.mt_day li[data-day="'+d+'"]'),
					hourItem = $('.mt_hour li[data-hour="'+h+'"]'),
					minuteItem = $('.mt_minute li[data-minute="'+min+'"]'),
					secondItem = $('.mt_second li[data-second="'+s+'"]');
				if(option.mode==3){
					if(h-12>0){
						s = 'pm';
						hourItem = $('.mt_hour li[data-hour="'+(h-12)+'"]');
					}
					else{
						s = 'am';
					}
					secondItem = $('.mt_second li[data-second="'+s+'"]');
				}
				this.checkYear(yearItem);
				this.checkMonth(monthItem);
				this.checkDay(dayItem);
				if(option.mode!=1){
					this.checkHour(hourItem);
					this.checkMinute(minuteItem);
					this.checkSecond(secondItem);	
				}
				
			},
			//滚动的时候动态调节日期，用于计算闰年的日期数
			updateDay : function(mpYear, mpMonth, mpDay){
				var _this = this;
				/* 获取选中的元素 */
				var checkedYear = mpYear.find('li.selected').data('year');
				var checkedMonth = mpMonth.find('li.selected').data('month');
				var checkedDay = mpDay.find('li.selected').data('day');
				var days = F.getDaysInMonth(checkedYear, checkedMonth);
				var dayStr = '<li class="mt_note">选择日期</li><li></li>';
				for(var k=1; k<=days; k++){
					var sel = k==checkedDay ? 'selected' : '';
					dayStr += '<li class="'+sel+'" data-day="'+k+'">'+F.preZero(k)+'日</li>';
				}
				dayStr += '<li></li><li></li>';
				mpDay.find('ul').html(dayStr);

				//内容改变后，iscroll的滚动会发生错误，所以在此将日期scorll重新初始化一遍
				this.dayScroll.destroy();
				this.dayScroll = new IScroll('.mt_day', scrollConf);
				this.dayScroll.on('scroll', function(){
					_this.updateSelected(mpDay, this);
				});
				this.dayScroll.on('scrollEnd', function(){
					_this.updateSelected(mpDay, this);
					/* 选中年和月超过时日期的置灰 */
					if(!defaults.exceedcurdate && checkedYear >= year && checkedMonth >= month){
						if(mpDay.find('li.selected').data('day') > day){
							_this.checkDay($('.mt_day li[data-day="'+day+'"]'));
						}
					}
				});

				//然后给day重新选择
				setTimeout(function(){
					for (var i = checkedDay; i > 0; i--) {
						checkedDay = i;
						if(mpDay.find('li[data-day="' + i + '"]').html())break;
					};
					var dayEl = mpDay.find('li[data-day="' + checkedDay + '"]');
					if(dayEl.length>0){
						_this.checkDay(dayEl);
					}
				},10);
				
				/* 选中年超过时月份的置灰 */
				if(!defaults.exceedcurdate && checkedYear >= year){
					$('.mt_month li').each(function(){
						if($(this).attr('data-month')>month){
							$(this).css('color','#ccc');
						}
					});
					if(checkedMonth > month){
						_this.checkMonth(mpMonth.find('li[data-month="'+month+'"]'));
					}
				}
				/* 选中年和月超过时日期的置灰 */
				if(!defaults.exceedcurdate && checkedYear >= year && checkedMonth >= month){
					$('.mt_day li').each(function(){
						if($(this).attr('data-day')>day){
							$(this).css('color','#ccc');
						}
					});
				}
			},
			checkYear : function(el){
				if(el.text()=='')return;
				if(!defaults.exceedcurdate && el.data('year') > year){
					//el = $('.mt_year li[data-year="'+year+'"]');
					return false;
				}
				var target = el.prev('li').prev('li');
				var container = $('.mt_poppanel');
				el.addClass('selected').siblings().removeClass('selected');
				this.updateDay($('.mt_year', container),$('.mt_month', container),$('.mt_day', container));
				this.yearScroll.scrollToElement(target[0]);
			},
			checkMonth : function(el){
				if(el.text()=='')return;
				if(!defaults.exceedcurdate && $('.mt_year').find('li.selected').data('year') >= year && el.data('month') > month){
					//el = $('.mt_month li[data-month="'+month+'"]');
					return false;
				}
				var target = el.prev('li').prev('li');
				el.addClass('selected').siblings().removeClass('selected');
				var container = $('.mt_poppanel');
				this.updateDay($('.mt_year', container),$('.mt_month', container),$('.mt_day', container));
				this.monthScroll.scrollToElement(target[0]);
			},
			checkDay : function(el){
				if(el.text()=='')return;
				if(!defaults.exceedcurdate && $('.mt_year').find('li.selected').data('year') >= year && $('.mt_month').find('li.selected').data('month') >= month && el.data('day') > day){
					//el = $('.mt_day li[data-day="'+day+'"]');
					return false;
				}
				var target = el.prev('li').prev('li');
				el.addClass('selected').siblings().removeClass('selected');
				this.dayScroll.scrollToElement(target[0]);
			},
			checkHour : function(el){
				if(el.text()=='')return;
				var target = el.prev('li').prev('li');
				this.hourScroll.scrollToElement(target[0]);
			},
			checkMinute : function(el){
				if(el.text()=='')return;
				var target = el.prev('li').prev('li');
				this.minuteScroll.scrollToElement(target[0]);
			},
			checkSecond : function(el){
				if(el.text()=='')return;
				if(option.mode<3)return;
				var target = el.prev('li').prev('li');
				this.secondScroll.scrollToElement(target[0]);
			},
			init : function(){
				var _this = this;

				_this.renderHTML();
				var container = $('.mt_poppanel'),
					mpYear = $('.mt_year', container),
					mpMonth = $('.mt_month', container),
					mpDay = $('.mt_day', container),
					mpHour = $('.mt_hour', container),
					mpMinute = $('.mt_minute', container),
					mpSecond = $('.mt_second', container);
				//初始化year
				
				var val = '';
				if(input.is('input')){
					val = input.val();
				} else {
					val = input.html();
				}
				var defaultDate = val!='' ? F.getDateValue(val) : new Date(),
					dYear = defaultDate.getFullYear(),
					dMonth = defaultDate.getMonth(),
					dDate = defaultDate.getDate(),
					dHour = defaultDate.getHours(),
					dMinute = defaultDate.getMinutes(),
					dSecond = defaultDate.getSeconds();

				var yearStr = '';
				for(var i=0; i<option.years.length; i++){
					var y = option.years[i];
					var sel = y==dYear ? 'selected' : '';
					yearStr += '<li class="'+sel+'" data-year="'+y+'">'+y+'年</li>';
				}
				yearStr += '<li></li><li></li>';
				mpYear.find('ul').append(yearStr);

				//初始化month
				var monthStr = '';
				for(var j=1; j<=12; j++){
					var sel = j==dMonth ? 'selected' : '';
					monthStr += '<li class="'+sel+'" data-month="'+(j-1)+'">'+F.preZero(j)+'月</li>';
				}
				monthStr += '<li></li><li></li>';
				mpMonth.find('ul').append(monthStr);

				//初始化day
				var dayStr = '';
				var defaultDays = F.getDaysInMonth(dYear, dMonth);
				for(var k=1; k<=defaultDays; k++){
					var sel = k==dDate ? 'selected' : '';
					dayStr += '<li class="'+sel+'" data-day="'+k+'">'+F.preZero(k)+'日</li>';
				}
				dayStr += '<li></li><li></li>';
				mpDay.find('ul').append(dayStr);

				if(option.mode==1){
					//把不该显示的删除掉
					$('.mt_body', container).css('height', 200);
					$('.mt_year, .mt_month, .mt_day', container).css('height', '100%');
					$('.mt_sepline, .mt_hour, .mt_minute, .mt_second, .mt_indicate.cate2', container).remove();
				}
				if(!option.nowbtn){
					$('.mt_setnow', container).remove();
					$('.mt_cancel, .mt_ok', container).css('float', 'none');
					$('.mt_cancel', container).css('border-right', '1px solid #4eccc4');
				}

				document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
				
				
				
				//初始化scroll
				var elHeight = itemHeight;
				var yearScroll = new IScroll('.mt_year', scrollConf);
				this.yearScroll = yearScroll;
				yearScroll.on('scroll', function(){
					_this.updateSelected(mpYear, this);
				});
				yearScroll.on('scrollEnd', function(){
					_this.updateSelected(mpYear, this);
					_this.updateDay(mpYear, mpMonth, mpDay);
					_this.yearScroll = yearScroll;
				});

				var monthScroll = new IScroll('.mt_month', scrollConf);
				this.monthScroll = monthScroll;
				monthScroll.on('scroll', function(){
					
					_this.updateSelected(mpMonth, this);
				});
				monthScroll.on('scrollEnd', function(){
					_this.updateSelected(mpMonth, this);
					_this.updateDay(mpYear, mpMonth, mpDay);
					_this.monthScroll = monthScroll;
				});

				var dayScroll = new IScroll('.mt_day', scrollConf);
				this.dayScroll = dayScroll;
				dayScroll.on('scroll', function(){
					_this.updateSelected(mpDay, this);
				});
				dayScroll.on('scrollEnd', function(){
					_this.updateSelected(mpDay, this);
					if(!defaults.exceedcurdate){
						var checkedYear = mpYear.find('li.selected').data('year');
						var checkedMonth = mpMonth.find('li.selected').data('month');
						var checkedDay = mpDay.find('li.selected').data('day');
						/* 选中年和月超过时日期的置灰 */
						if(checkedYear >= year && checkedMonth >= month && checkedDay > day){
							_this.checkDay($('.mt_day li[data-day="'+day+'"]'));
						}
					}
					_this.dayScroll = dayScroll;
				});

				//初始化时分秒

				//初始化hour
				if(option.mode != 1){
					var hourStr = '';
					var hourcount = option.mode == 3 ? 12 : 24;
					for(var l=1; l<=hourcount; l++){
						var sel = l==dHour ? 'selected' : '';
						hourStr += '<li class="'+sel+'" data-hour="'+l+'">'+F.preZero(l)+'时</li>';
					}
					hourStr += '<li></li><li></li>';
					mpHour.find('ul').append(hourStr);

					var hourScroll = new IScroll('.mt_hour', scrollConf);
					hourScroll.on('scroll', function(){
						_this.updateSelected(mpHour, this);
					});
					hourScroll.on('scrollEnd', function(){
						_this.updateSelected(mpHour, this);
					});
					this.hourScroll = hourScroll;

					//初始化minute
					var minuteStr = '';
					for(var m=0; m<=60; m++){
						var sel = m==dMinute ? 'selected' : '';
						minuteStr += '<li class="'+sel+'" data-minute="'+m+'">'+F.preZero(m)+'分</li>';
					}
					minuteStr += '<li></li><li></li>';
					mpMinute.find('ul').append(minuteStr);

					var minuteScroll = new IScroll('.mt_minute', scrollConf);
					minuteScroll.on('scroll', function(){
						_this.updateSelected(mpMinute, this);
					});
					minuteScroll.on('scrollEnd', function(){
						_this.updateSelected(mpMinute, this);
					});
					this.minuteScroll = minuteScroll;

					//初始化second
					var secondStr = '';
					if(option.mode==4){
						for(var n=0; n<=60; n++){
							var sel = n==dSecond ? 'selected' : '';
							secondStr += '<li class="'+sel+'" data-second="'+n+'">'+F.preZero(n)+'秒</li>';
						}
					}
					else if(option.mode==3){
						var sel1 = dHour<=12 ? 'selected' : '';
						var sel2 = dHour>=12 ? 'selected' : '';
						secondStr += '<li class="'+sel1+'" data-second="am">上午</li><li class="'+sel2+'" data-second="pm">下午</li>';
					}
					
					secondStr += '<li></li><li></li>';
					mpSecond.find('ul').append(secondStr);

					var secondScroll = new IScroll('.mt_second', scrollConf);
					secondScroll.on('scroll', function(){
						_this.updateSelected(mpSecond, this);
					});
					secondScroll.on('scrollEnd', function(){
						_this.updateSelected(mpSecond, this);
					});
					this.secondScroll = secondScroll;

					if(option.mode==2 || option.mode==3){
						$('.mt_second .mt_note').html('&nbsp;');
					}
				}

				//初始化点击input事件
				input.on('click', function(){
					if(container.hasClass('show')){
						_this.hidePanel();
					}
					else{
						_this.showPanel();
						var newyear = mpYear.find('.selected').data('year');
						var newmonth = mpMonth.find('.selected').data('month');
						var newday = mpDay.find('.selected').data('day');
						var newhour = mpHour.find('.selected').data('hour');
						var newminute = mpMinute.find('.selected').data('minute');
						var newsecond = mpSecond.find('.selected').data('second');
						_this.setValue(newyear, newmonth, newday, newhour, newminute, newsecond);
					}
				});

				//初始化点击li
				mpYear.on('tap', 'li', function(){
					if($(this).text() == '选择年份'){
						return false;
					}
					_this.checkYear($(this));
				});
				mpMonth.on('tap', 'li', function(){
					if($(this).text() == '选择月份'){
						return false;
					}
					_this.checkMonth($(this));
				});
				mpDay.on('tap', 'li', function(){
					if($(this).text() == '选择日期'){
						return false;
					}
					_this.checkDay($(this));
				});
				if(option.mode != 1){
					mpHour.on('tap', 'li', function(){
						_this.checkHour($(this));
					});
					mpMinute.on('tap', 'li', function(){
						_this.checkMinute($(this));
					});
					mpSecond.on('tap', 'li', function(){
						_this.checkSecond($(this));
					});
				}
				

				//初始化点击事件
				$('.mt_ok', container).on('tap', function(){
					var newyear = mpYear.find('.selected').data('year') || 0000;
					var newmonth = mpMonth.find('.selected').data('month') || 01;
					var newday = mpDay.find('.selected').data('day') || 01;
					var newhour = mpHour.find('.selected').data('hour') || 01;
					var newminute = mpMinute.find('.selected').data('minute') || 01;
					var newsecond = mpSecond.find('.selected').data('second') || 01;
					input.val(F.formatDate(newyear, newmonth, newday, newhour, newminute, newsecond));
					_this.hidePanel();
					option.onOk && typeof option.onOk=='function' && option.onOk(container);
				});
				$('.mt_cancel', container).on('tap', function(){
					_this.hidePanel();
					option.onCancel && typeof option.onCancel=='function' && option.onCancel(container);
				});
				$('.mt_setnow', container).on('tap', function(){
					var n = new Date();
					input.val(F.formatDate(n.getFullYear(), n.getMonth(), n.getDate(), n.getHours(), n.getMinutes(), n.getSeconds()));
					_this.hidePanel();
				});
				
				$('.mt_mask').on('tap', function(){
					_this.hidePanel();
				});


				//初始化原有的数据
				this.setValue(dYear, dMonth, dDate, dHour, dMinute, dSecond);
			}
			
		}
		picker.init();
		return picker;
}
