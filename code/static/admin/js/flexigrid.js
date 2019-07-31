/*
 * Flexigrid for jQuery -  v1.1
 *
 * Copyright (c) 2008 Paulo P. Marinas (code.google.com/p/flexigrid/)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 */
(function ($) {
	/*
	 * jQuery 1.9 support. browser object has been removed in 1.9 
	 */
	var browser = $.browser
	
	if (!browser) {
		function uaMatch( ua ) {
			ua = ua.toLowerCase();

			var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
				/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
				/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
				/(msie) ([\w.]+)/.exec( ua ) ||
				ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
				[];

			return {
				browser: match[ 1 ] || "",
				version: match[ 2 ] || "0"
			};
		};

		var matched = uaMatch( navigator.userAgent );
		browser = {};

		if ( matched.browser ) {
			browser[ matched.browser ] = true;
			browser.version = matched.version;
		}

		// Chrome is Webkit, but Webkit is also Safari.
		if ( browser.chrome ) {
			browser.webkit = true;
		} else if ( browser.webkit ) {
			browser.safari = true;
		}
	}
	
    /*!
     * START code from jQuery UI
     *
     * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
     * Dual licensed under the MIT or GPL Version 2 licenses.
     * http://jquery.org/license
     *
     * http://docs.jquery.com/UI
     */
     
    if(typeof $.support.selectstart != 'function') {
        $.support.selectstart = "onselectstart" in document.createElement("div");
    }
    
    if(typeof $.fn.disableSelection != 'function') {
        $.fn.disableSelection = function() {
            return this.bind( ( $.support.selectstart ? "selectstart" : "mousedown" ) +
                ".ui-disableSelection", function( event ) {
                event.preventDefault();
            });
        };
    }
    
    /* END code from jQuery UI */
    
	$.addFlex = function (t, p) {
		if (t.grid) return false; /* return if already exist */
		p = $.extend({ /* 应用默认的属性 */
			height: 200, /* 默认高度 */
			width: 'auto', /* 自动宽度 */
			striped: true, /* apply odd even stripes */
			novstripe: false,
			minwidth: 30,/* 列的最小宽度 */
			minheight: 80, /* 列的最小高度 */
			resizable: true,/* 允许表格改变大小 */
			url: false, /* 如果使用AJAX数据的URL */
			method: 'POST', /* 数据发送方法 */
			dataType: 'xml', /* AJAX的类型的数据,xml或json */
			errormsg: 'Connection Error',
			usepager: false,
			nowrap: true,
			page: 1, /* 当前页 */
			total: 1, /* 总页数 */
			useRp: true, /* 使用每页结果选择框 */
			rp: 15, /* 每页的结果 */
			rpOptions: [10, 15, 20, 30, 50], /*  */
			title: false,
			idProperty: 'id',/* 主键（例：member_id） */
			pagestat: '第 {from} 条 ~ 第 {to} 条 | 共 {total} 条',
			pagetext: '跳至',
			outof: '当前',
			findtext: '',/* 搜索条件前面的提示字 */
			params: [], /* 允许可选参数传递 */
			procmsg: '数据加载中，请稍等 ...',
			query: '',/* 搜索文字 */
			qtype: '',/* 搜索字段 */
			nomsg: '没有数据！',
			minColToggle: 1, /* 最小允许多少列被隐藏 */
			showToggleBtn: true, /* 显示或隐藏列弹出切换 */
			hideOnSubmit: true,
			autoload: true,
			blockOpacity: 0.5,
			preProcess: false,
			addTitleToCell: false, /* 添加一个标题attr细胞与截断的内容 */
			dblClickResize: false, /* 通过双击自动调整列 */
			onDragCol: false,
			onToggleCol: false,
			onChangeSort: false,
			onDoubleClick: false,
			onSuccess: false,
			onError: false,
			onSubmit: false, /* 使用自定义填充函数 */
            __mw: { /* 可扩展的中间件功能对象 */
                datacol: function(p, col, val, id) { /* 中间件格式化数据列 */
                    
                    if(typeof p.datacol['*'] == 'function') { /* 如果通配符函数存在 */
                        return p.datacol['*'](val, id); /* 动行通配符函数 */
                    } else {
                    	return (typeof p.datacol[col] == 'function') ? p.datacol[col](val, id) : val; /* 返回没有通配符的纵列 */
                    }
                }
            },
            getGridClass: function(g) { /* 网格类,返回g */
                return g;
            },
            datacol: {}, /* datacol中间件对象“colkey”:函数(colval){ } */
            colResize: true, /* from: http://stackoverflow.com/a/10615589 */
            colMove: true
		}, p);
		$(t).show() /* 显示 */
			.attr({
				cellPadding: 0,
				cellSpacing: 0,
				border: 0
			}) /* 移除padding和spacing */
			.removeAttr('width'); /* 移除宽度属性 */
		/* 创建表格类 */
		var g = {
			hset: {},
			rePosDrag: function () {
				var cdleft = 0 - this.hDiv.scrollLeft;
				if (this.hDiv.scrollLeft > 0) cdleft -= Math.floor(p.cgwidth / 2);
				$(g.cDrag).css({
					top: g.hDiv.offsetTop + 1
				});
				var cdpad = this.cdpad;
				var cdcounter=0;
				$('div', g.cDrag).hide();
				$('thead tr:first th:visible', this.hDiv).each(function () {
					var n = $('thead tr:first th:visible', g.hDiv).index(this);
					var cdpos = parseInt($('div', this).width());
					if (cdleft == 0) cdleft -= Math.floor(p.cgwidth / 2);
					cdpos = cdpos + cdleft + cdpad;
					if (isNaN(cdpos)) {
						cdpos = 0;
					}
					$('div:eq(' + n + ')', g.cDrag).css({
						'left': (!(browser.mozilla) ? cdpos - cdcounter : cdpos) + 'px'
					}).show();
					cdleft = cdpos;
					cdcounter++;
				});
			},
			fixHeight: function (newH) {
				newH = false;
				if (!newH) newH = $(g.bDiv).height();
				var hdHeight = $(this.hDiv).height();
				$('div', this.cDrag).each(
					function () {
						$(this).height(newH + hdHeight);
					}
				);
				var nd = parseInt($(g.nDiv).height(), 10);
				if (nd > newH) $(g.nDiv).height(newH).width(200);
				else $(g.nDiv).height('auto').width('auto');
				$(g.block).css({
					height: newH,
					marginBottom: (newH * -1)
				});
				var hrH = g.bDiv.offsetTop + newH;
				if (p.height != 'auto' && p.resizable) hrH = g.vDiv.offsetTop;
				$(g.rDiv).css({
					height: hrH
				});
			},
			dragStart: function (dragtype, e, obj) { /* 拖拉开始事件 */
                if (dragtype == 'colresize' && p.colResize === true) {/* 列调整 */
					$(g.nDiv).hide();
					$(g.nBtn).hide();
					var n = $('div', this.cDrag).index(obj);
					var ow = $('th:visible div:eq(' + n + ')', this.hDiv).width();
					$(obj).addClass('dragging').siblings().hide();
					$(obj).prev().addClass('dragging').show();
					this.colresize = {
						startX: e.pageX,
						ol: parseInt(obj.style.left, 10),
						ow: ow,
						n: n
					};
					$('body').css('cursor', 'col-resize');
				} else if (dragtype == 'vresize') {/* 表调整 */
					var hgo = false;
					$('body').css('cursor', 'row-resize');
					if (obj) {
						hgo = true;
						$('body').css('cursor', 'col-resize');
					}
					this.vresize = {
						h: p.height,
						sy: e.pageY,
						w: p.width,
						sx: e.pageX,
						hgo: hgo
					};
				} else if (dragtype == 'colMove') {/* 列标题拖 */
                    $(e.target).disableSelection(); /* 禁用选择列标题 */
                    if((p.colMove === true)) {
                        $(g.nDiv).hide();
                        $(g.nBtn).hide();
                        this.hset = $(this.hDiv).offset();
                        this.hset.right = this.hset.left + $('table', this.hDiv).width();
                        this.hset.bottom = this.hset.top + $('table', this.hDiv).height();
                        this.dcol = obj;
                        this.dcoln = $('th', this.hDiv).index(obj);
                        this.colCopy = document.createElement("div");
                        this.colCopy.className = "colCopy";
                        this.colCopy.innerHTML = obj.innerHTML;
                        if (browser.msie) {
                            this.colCopy.className = "colCopy ie";
                        }
                        $(this.colCopy).css({
                            position: 'absolute',
                            'float': 'left',
                            display: 'none',
                            textAlign: obj.align
                        });
                        $('body').append(this.colCopy);
                        $(this.cDrag).hide();
                    }
				}
				$('body').noSelect();
			},
			dragMove: function (e) {
				if (this.colresize) {/* 列调整 */
					var n = this.colresize.n;
					var diff = e.pageX - this.colresize.startX;
					var nleft = this.colresize.ol + diff;
					var nw = this.colresize.ow + diff;
					if (nw > p.minwidth) {
						$('div:eq(' + n + ')', this.cDrag).css('left', nleft);
						this.colresize.nw = nw;
					}
				} else if (this.vresize) {/* 表调整 */
					var v = this.vresize;
					var y = e.pageY;
					var diff = y - v.sy;
					if (!p.defwidth) p.defwidth = p.width;
					if (p.width != 'auto' && !p.nohresize && v.hgo) {
						var x = e.pageX;
						var xdiff = x - v.sx;
						var newW = v.w + xdiff;
						if (newW > p.defwidth) {
							this.gDiv.style.width = newW + 'px';
							p.width = newW;
						}
					}
					var newH = v.h + diff;
					if ((newH > p.minheight || p.height < p.minheight) && !v.hgo) {
						this.bDiv.style.height = newH + 'px';
						p.height = newH;
						this.fixHeight(newH);
					}
					v = null;
				} else if (this.colCopy) {
					$(this.dcol).addClass('thMove').removeClass('thOver');
					if (e.pageX > this.hset.right || e.pageX < this.hset.left || e.pageY > this.hset.bottom || e.pageY < this.hset.top) {
						this.dragEnd();
						$('body').css('cursor', 'move');
					} else {
						$('body').css('cursor', 'pointer');
					}
					$(this.colCopy).css({
						top: e.pageY + 10,
						left: e.pageX + 20,
						display: 'block'
					});
				}
			},
			dragEnd: function () {
				if (this.colresize) {
					var n = this.colresize.n;
					var nw = this.colresize.nw;
					$('th:visible div:eq(' + n + ')', this.hDiv).css('width', nw);
					$('tr', this.bDiv).each(
						function () {
							var $tdDiv = $('td:visible div:eq(' + n + ')', this);
							$tdDiv.css('width', nw);
							g.addTitleToCell($tdDiv);
						}
					);
					this.hDiv.scrollLeft = this.bDiv.scrollLeft;
					$('div:eq(' + n + ')', this.cDrag).siblings().show();
					$('.dragging', this.cDrag).removeClass('dragging');
					this.rePosDrag();
					this.fixHeight();
					this.colresize = false;
					if ($.cookies) {
						var name = p.colModel[n].name;		/* 存储宽度的cookie */
						$.cookie('flexiwidths/'+name, nw);
					}
				} else if (this.vresize) {
					this.vresize = false;
				} else if (this.colCopy) {
					$(this.colCopy).remove();
					if (this.dcolt !== null) {
						if (this.dcoln > this.dcolt) $('th:eq(' + this.dcolt + ')', this.hDiv).before(this.dcol);
						else $('th:eq(' + this.dcolt + ')', this.hDiv).after(this.dcol);
						this.switchCol(this.dcoln, this.dcolt);
						$(this.cdropleft).remove();
						$(this.cdropright).remove();
						this.rePosDrag();
						if (p.onDragCol) {
							p.onDragCol(this.dcoln, this.dcolt);
						}
					}
					this.dcol = null;
					this.hset = null;
					this.dcoln = null;
					this.dcolt = null;
					this.colCopy = null;
					$('.thMove', this.hDiv).removeClass('thMove');
					$(this.cDrag).show();
				}
				$('body').css('cursor', 'default');
				$('body').noSelect(false);
			},
			toggleCol: function (cid, visible) {
				var ncol = $("th[axis='col" + cid + "']", this.hDiv)[0];
				var n = $('thead th', g.hDiv).index(ncol);
				var cb = $('input[value=' + cid + ']', g.nDiv)[0];
				if (visible == null) {
					visible = ncol.hidden;
				}
				if ($('input:checked', g.nDiv).length < p.minColToggle && !visible) {
					return false;
				}
				if (visible) {
					ncol.hidden = false;
					$(ncol).show();
					cb.checked = true;
				} else {
					ncol.hidden = true;
					$(ncol).hide();
					cb.checked = false;
				}
				$('tbody tr', t).each(
					function () {
						if (visible) {
							$('td:eq(' + n + ')', this).show();
						} else {
							$('td:eq(' + n + ')', this).hide();
						}
					}
				);
				this.rePosDrag();
				if (p.onToggleCol) {
					p.onToggleCol(cid, visible);
				}
				return visible;
			},
			switchCol: function (cdrag, cdrop) { /* 柱切换 */
				$('tbody tr', t).each(
					function () {
						if (cdrag > cdrop) $('td:eq(' + cdrop + ')', this).before($('td:eq(' + cdrag + ')', this));
						else $('td:eq(' + cdrop + ')', this).after($('td:eq(' + cdrag + ')', this));
					}
				);
				/* switch order in nDiv */
				if (cdrag > cdrop) {
					$('tr:eq(' + cdrop + ')', this.nDiv).before($('tr:eq(' + cdrag + ')', this.nDiv));
				} else {
					$('tr:eq(' + cdrop + ')', this.nDiv).after($('tr:eq(' + cdrag + ')', this.nDiv));
				}
				if (browser.msie && browser.version < 7.0) {
					$('tr:eq(' + cdrop + ') input', this.nDiv)[0].checked = true;
				}
				this.hDiv.scrollLeft = this.bDiv.scrollLeft;
			},
			scroll: function () {
				this.hDiv.scrollLeft = this.bDiv.scrollLeft;
				this.rePosDrag();
			},
			addData: function (data) { /* 解析数据 */
				if (p.dataType == 'json') {
					data = $.extend({rows: [], page: 0,params: [], total: 0}, data);
				}
				if (p.preProcess) {
					data = p.preProcess(data);
				}
				$('.pReload', this.pDiv).removeClass('loading');
				this.loading = false;
				if (!data) {
					$('.pPageStat', this.pDiv).html(p.errormsg);
                    if (p.onSuccess) p.onSuccess(this);
					return false;
				}
				if (p.dataType == 'xml') {
					p.total = +$('rows total', data).text();
				} else {
					p.total = data.total;
				}
				if (p.total === 0) {
					$('tr, a, td, div', t).unbind();
					$(t).empty();
					p.pages = 1;
					p.page = 1;
					this.buildpager();
					$('.pPageStat', this.pDiv).html(p.nomsg);
                    if (p.onSuccess) p.onSuccess(this);
					return false;
				}
				p.pages = Math.ceil(p.total / p.rp);
				if (p.dataType == 'xml') {
					p.page = +$('rows page', data).text();
				} else {
					p.page = data.page;
				}
				this.buildpager();
				/* 创建新的表格体 */
				var tbody = document.createElement('tbody');
				if (p.dataType == 'json') {
					$.each(data.rows, function (i, row) {
						var tr = document.createElement('tr');
						var jtr = $(tr);
						if (row.name) tr.name = row.name;
						if (row.color) {
							jtr.css('background',row.color);
						} else {
							if (i % 2 && p.striped) tr.className = 'erow';
						}
						if (row[p.idProperty]) {
							tr.id = 'row' + row[p.idProperty];
							jtr.attr('data-id', row[p.idProperty]);
						}
						$('thead tr:first th', g.hDiv).each(function () {
								/* 添加单元格监视 */
								var td = document.createElement('td');
								var idx = $(this).attr('axis').substr(3);
								td.align = this.align;
								
								/* 判断th是不是全选和操作 */
								if($(this).attr('checkall')){
									/* 多选框操作 */
									td.innerHTML = '<input type="checkbox" id="check-'+row[p.idProperty]+'" class="regular-radio" value="'+row[p.idProperty]+'"/><label for="check-'+row[p.idProperty]+'"></label>';
								}else{
									if (typeof row == 'undefined') {
										/* 如果这一行是未定义的 */
										td.innerHTML = row[p.colModel[idx].name];
										/* 也可以直接return出去，不显示这一行 */
									} else {
	                                    var iHTML = '';
	                                    if (typeof row[idx] != "undefined") {
	                                    	/* 如果json元素,使用数字顺序 */
	                                        iHTML = (row[idx] !== null)?row[idx]:''; 
	                                    } else {
	                                        iHTML = row[p.colModel[idx].name];
	                                    }
	                                    td.innerHTML = p.__mw.datacol(p, $(this).attr('abbr'), iHTML, row[p.idProperty]); /* 单元格的回调函数，参数1：配置项，参数2：字段名（需要加设置sortable : true才可使用），参数3：字段值，参数4：主键值 */
									}
									/* 如果内容有一个<背景色= nnnnnn >选项,解码。 */
									var offs = td.innerHTML.indexOf( '<BGCOLOR=' );
									if( offs >0 ) {
	                                    $(td).css('background', text.substr(offs+7,7) );
									}
								}
								$(td).attr('abbr', $(this).attr('abbr'));
								$(tr).append(td);
								td = null;
							}
						);
						if ($('thead', this.gDiv).length < 1) {/* 处理如果网格没有头 */
							for (idx = 0; idx < row.length; idx++) {
								var td = document.createElement('td');
								/*  如果json元素不叫(典型),使用数字顺序 */
								if (typeof row[idx] != "undefined") {
									td.innerHTML = (row[idx] != null) ? row[idx] : '';/* null-check for Opera-browser */
								} else {
									td.innerHTML = row[p.colModel[idx].name];
								}
								$(tr).append(td);
								td = null;
							}
						}
						$(tbody).append(tr);
						tr = null;
					});
				}
				$('tr', t).unbind();
				$(t).empty();
				$(t).append(tbody);
				this.addCellProp();
				this.addRowProp();
				this.rePosDrag();
				tbody = null;
				data = null;
				i = null;
				if (p.onSuccess) {
					p.onSuccess(this);
				}
				if (p.hideOnSubmit) {
					$(g.block).remove();
				}
				this.hDiv.scrollLeft = this.bDiv.scrollLeft;
				if (browser.opera) {
					$(t).css('visibility', 'visible');
				}
			},
			changeSort: function (th) { /* 更改排序顺序 */
				if (this.loading) {
					return true;
				}
				$(g.nDiv).hide();
				$(g.nBtn).hide();
				if (p.sortname == $(th).attr('abbr')) {
					if (p.sortorder == 'asc') {
						p.sortorder = 'desc';
					} else {
						p.sortorder = 'asc';
					}
				}
				$(th).addClass('sorted').siblings().removeClass('sorted');
				$('.sdesc', this.hDiv).removeClass('sdesc');
				$('.sasc', this.hDiv).removeClass('sasc');
				$('div', th).addClass('s' + p.sortorder);
				p.sortname = $(th).attr('abbr');
				if (p.onChangeSort) {
					p.onChangeSort(p.sortname, p.sortorder);
				} else {
					this.populate();
				}
			},
			buildpager: function () { /* 基于新的属性重建 */
				$('.pcontrol input', this.pDiv).val(p.page);
				$('.pcontrol span', this.pDiv).html(p.pages);
				var r1 = p.total == 0 ? 0 : (p.page - 1) * p.rp + 1;
				var r2 = r1 + p.rp - 1;
				if (p.total < r2) {
					r2 = p.total;
				}
				var stat = p.pagestat;
				stat = stat.replace(/{from}/, r1);
				stat = stat.replace(/{to}/, r2);
				stat = stat.replace(/{total}/, p.total);
				$('.pPageStat', this.pDiv).html(stat);
			},
			/* 重新发送请求，参数可加，要在配置里也设置才可以 */
			populate: function () { /* 获得最新的数据 */
				if (this.loading) {
					return true;
				}
				if (p.onSubmit) {
					var gh = p.onSubmit();
					if (!gh) {
						return false;
					}
				}
				this.loading = true;
				if (!p.url) {
					return false;
				}
				$('.pPageStat', this.pDiv).html(p.procmsg);
				$('.pReload', this.pDiv).addClass('loading');
				$(g.block).css({
					top: g.bDiv.offsetTop
				});
				if (p.hideOnSubmit) {
					$(this.gDiv).prepend(g.block);
				}
				if (browser.opera) {
					$(t).css('visibility', 'hidden');
				}
				if (!p.newp) {
					p.newp = 1;
				}
				if (p.page > p.pages) {
					p.page = p.pages;
				}
				var param = [{
					name: 'page',
					value: p.newp
				}, {
					name: 'rp',
					value: p.rp
				}, {
					name: 'sortname',
					value: p.sortname
				}, {
					name: 'sortorder',
					value: p.sortorder
				}, {
					name: p.qtype,
					value: p.query
				}];
				if (p.params.length) {
					for (var pi = 0; pi < p.params.length; pi++) {
						param[param.length] = p.params[pi];
					}
				}
				$.ajax({
					type: p.method,
					url: p.url,
					data: param,
					dataType: p.dataType,
					success: function (data) {
						g.addData(data);
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						try {
							if (p.onError) p.onError(XMLHttpRequest, textStatus, errorThrown);
						} catch (e) {}
					}
				});
			},
			/* 搜索请求 */
			doSearch: function () {
				p.query = $('input[name=q]', g.sDiv).val();
				p.qtype = $('select[name=qtype]', g.sDiv).val();
				p.newp = 1;
				this.populate();
			},
			/* 翻页请求 */
			changePage: function (ctype) { /* 换页 */
				if (this.loading) {
					return true;
				}
				switch (ctype) {
					case 'first':
						p.newp = 1;
						break;
					case 'prev':
						if (p.page > 1) {
							p.newp = parseInt(p.page, 10) - 1;
						}
						break;
					case 'next':
						if (p.page < p.pages) {
							p.newp = parseInt(p.page, 10) + 1;
						}
						break;
					case 'last':
						p.newp = p.pages;
						break;
					case 'input':
						var nv = parseInt($('.pcontrol input', this.pDiv).val(), 10);
						if (isNaN(nv)) {
							nv = 1;
						}
						if (nv < 1) {
							nv = 1;
						} else if (nv > p.pages) {
							nv = p.pages;
						}
						$('.pcontrol input', this.pDiv).val(nv);
						p.newp = nv;
						break;
				}
				if (p.newp == p.page) {
					return false;
				}
				if (p.onChangePage) {
					p.onChangePage(p.newp);
				} else {
					this.populate();
				}
			},
			addCellProp: function () {
				$('tbody tr td', g.bDiv).each(function () {
					var tdDiv = document.createElement('div');
					var n = $('td', $(this).parent()).index(this);
					var pth = $('th:eq(' + n + ')', g.hDiv).get(0);
					if (pth != null) {
						if (p.sortname == $(pth).attr('abbr') && p.sortname) {
							this.className = 'sorted';
						}
						$(tdDiv).css({
							textAlign: pth.align,
							width: $('div:first', pth)[0].style.width
						});
						if (pth.hidden) {
							$(this).css('display', 'none');
						}
					}
					if (p.nowrap == false) {
						$(tdDiv).css('white-space', 'normal');
					}
					if (this.innerHTML == '') {
						this.innerHTML = '&nbsp;';
					}
					tdDiv.innerHTML = this.innerHTML;
					var prnt = $(this).parent()[0];
					var pid = false;
					if (prnt.id) {
						pid = prnt.id.substr(3);
					}
					if (pth != null) {
						if (pth.process) pth.process(tdDiv, pid);
					}
					$(this).empty().append(tdDiv).removeAttr('width');
					g.addTitleToCell(tdDiv);
				});
			},
			getCellDim: function (obj) {/* 为可编辑事件得到属性 */
				var ht = parseInt($(obj).height(), 10);
				var pht = parseInt($(obj).parent().height(), 10);
				var wt = parseInt(obj.style.width, 10);
				var pwt = parseInt($(obj).parent().width(), 10);
				var top = obj.offsetParent.offsetTop;
				var left = obj.offsetParent.offsetLeft;
				var pdl = parseInt($(obj).css('paddingLeft'), 10);
				var pdt = parseInt($(obj).css('paddingTop'), 10);
				return {
					ht: ht,
					wt: wt,
					top: top,
					left: left,
					pdl: pdl,
					pdt: pdt,
					pht: pht,
					pwt: pwt
				};
			},
			/* 对每一行数据的点击，移入，移出，双击事件 */
			addRowProp: function () {
				$('tbody tr', g.bDiv).on('click', function (e) {
					var obj = (e.target || e.srcElement);
					if (obj.href || obj.type) return true;
					if (e.ctrlKey || e.metaKey) {
						/* mousedown already took care of this case */
						return;
					}
					$(this).toggleClass('trSelected');
					if (p.singleSelect && ! g.multisel) {
						$(this).siblings().removeClass('trSelected');
					}
				}).on('mousedown', function (e) {
					if (e.shiftKey) {
						$(this).toggleClass('trSelected');
						g.multisel = true;
						this.focus();
						$(g.gDiv).noSelect();
					}
					if (e.ctrlKey || e.metaKey) {
						$(this).toggleClass('trSelected');
						g.multisel = true;
						this.focus();
					}
				}).on('mouseup', function (e) {
					if (g.multisel && ! (e.ctrlKey || e.metaKey)) {
						g.multisel = false;
						$(g.gDiv).noSelect(false);
					}
				}).on('dblclick', function () {
					if (p.onDoubleClick) {
						p.onDoubleClick(this, g, p);
					}
				}).hover(function (e) {
					if (g.multisel && e.shiftKey) {
						$(this).toggleClass('trSelected');
					}
				}, function () {});
				if (browser.msie && browser.version < 7.0) {
					$(this).hover(function () {
						$(this).addClass('trOver');
					}, function () {
						$(this).removeClass('trOver');
					});
				}
			},

			combo_flag: true,
			combo_resetIndex: function(selObj)
			{
				if(this.combo_flag) {
					selObj.selectedIndex = 0;
				}
				this.combo_flag = true;
			},
			combo_doSelectAction: function(selObj)
			{
				eval( selObj.options[selObj.selectedIndex].value );
				selObj.selectedIndex = 0;
				this.combo_flag = false;
			},
			/* 添加标题属性div如果属性被截断 */
			addTitleToCell: function(tdDiv) {
				if(p.addTitleToCell) {
					var $span = $('<span />').css('display', 'none'),
						$div = (tdDiv instanceof jQuery) ? tdDiv : $(tdDiv),
						div_w = $div.outerWidth(),
						span_w = 0;

					$('body').children(':first').before($span);
					$span.html($div.html());
					$span.css('font-size', '' + $div.css('font-size'));
					$span.css('padding-left', '' + $div.css('padding-left'));
					span_w = $span.innerWidth();
					$span.remove();

					if(span_w > div_w) {
						$div.attr('title', $div.text());
					} else {
						$div.removeAttr('title');
					}
				}
			},
			autoResizeColumn: function (obj) {
				if(!p.dblClickResize) {
					return;
				}
				var n = $('div', this.cDrag).index(obj),
					$th = $('th:visible div:eq(' + n + ')', this.hDiv),
					ol = parseInt(obj.style.left, 10),
					ow = $th.width(),
					nw = 0,
					nl = 0,
					$span = $('<span />');
				$('body').children(':first').before($span);
				$span.html($th.html());
				$span.css('font-size', '' + $th.css('font-size'));
				$span.css('padding-left', '' + $th.css('padding-left'));
				$span.css('padding-right', '' + $th.css('padding-right'));
				nw = $span.width();
				$('tr', this.bDiv).each(function () {
					var $tdDiv = $('td:visible div:eq(' + n + ')', this),
						spanW = 0;
					$span.html($tdDiv.html());
					$span.css('font-size', '' + $tdDiv.css('font-size'));
					$span.css('padding-left', '' + $tdDiv.css('padding-left'));
					$span.css('padding-right', '' + $tdDiv.css('padding-right'));
					spanW = $span.width();
					nw = (spanW > nw) ? spanW : nw;
				});
				$span.remove();
				nw = (p.minWidth > nw) ? p.minWidth : nw;
				nl = ol + (nw - ow);
				$('div:eq(' + n + ')', this.cDrag).css('left', nl);
				this.colresize = {
					nw: nw,
					n: n
				};
				g.dragEnd();
			},
			pager: 0
		};
        
        g = p.getGridClass(g); /* 获取网格的类 */
        /* 如果真，则创建模型 */
		if (p.colModel) { 
			thead = document.createElement('thead');
			var tr = document.createElement('tr');
			for (var i = 0; i < p.colModel.length; i++) {
				var cm = p.colModel[i];
				var th = document.createElement('th');
				$(th).attr('axis', 'col' + i);
				/* 如果定义了cm */
				if( cm ) {
					if ($.cookies) {
						var cookie_width = 'flexiwidths/'+cm.name;		/* Re-Store the widths in the cookies */
						if( $.cookie(cookie_width) != undefined ) {
							cm.width = $.cookie(cookie_width);
						}
					}
					if( cm.display != undefined ) {
						th.innerHTML = cm.display;/* 设置表格头的名称 */
					}
					if (cm.name && cm.sortable) {
						$(th).attr('abbr', cm.name);/* 设置表格头的字段名 */
					}else if(cm.name == 'checkall') {
						$(th).attr('abbr', cm.name);
						$(th).attr('checkall','1');
						$(th).html('<input type="checkbox" id="check-all" class="regular-radio"/><label for="check-all"></label>' + cm.display);/* 设置表格头的名称 */
					}
					if (cm.align) {
						th.align = cm.align;/* 设置表格头的对齐方式 */
					}
					if (cm.width) {
						$(th).attr('width', cm.width);/* 设置表格头的宽度 */
					}
					if ($(cm).attr('hide')) {
						th.hidden = true;
					}
					if (cm.process) {
						th.process = cm.process;/* 设置表格头的回调函数 */
					}
				} else {
					th.innerHTML = "";
					$(th).attr('width',30);
				}
				$(tr).append(th);
			}
			$(thead).append(tr);
			$(t).prepend(thead);
		} /* end if p.colmodel */
		/* 初始化div容器 */
		g.gDiv = document.createElement('div'); /* 创建全局的容器 */
		g.mDiv = document.createElement('div'); /* 创建标题容器 */
		g.hDiv = document.createElement('div'); /* 创建表格头容器 */
		g.bDiv = document.createElement('div'); /* 创建表格体容器 */
		g.vDiv = document.createElement('div'); /* create grip */
		g.rDiv = document.createElement('div'); /* create horizontal resizer */
		g.cDrag = document.createElement('div'); /* 创建纵列拖拉容器 */
		g.block = document.createElement('div'); /* creat blocker */
		g.nDiv = document.createElement('div'); /* 创建显示列，隐含弹出式菜单 */
		g.nBtn = document.createElement('div'); /* 创建显示列，隐含弹出式按纽 */
		g.iDiv = document.createElement('div'); /* 创建可编辑表格的图层 */
		g.tDiv = document.createElement('div'); /* 创建工具栏（添加，修改，删除，刷新） */
		g.sDiv = document.createElement('div'); /* 创建搜索栏容器 */
		g.pDiv = document.createElement('div'); /* 创建分页栏容器 */
        /* 如果配置false,则不显示纵列的拖拉容器 */
        if(p.colResize === false) {
            $(g.cDrag).css('display', 'none');
        }
        /* 如果配置false,则不显示分页容器 */
		if (!p.usepager) {
			g.pDiv.style.display = 'none';
		}
		g.hTable = document.createElement('table');
		g.gDiv.className = 'flexigrid';
		if (p.width != 'auto') {
			g.gDiv.style.width = p.width + (isNaN(p.width) ? '' : 'px');
		} 
		/* 添加假定的类 */
		if (browser.msie) {
			$(g.gDiv).addClass('ie');
		}
		if (p.novstripe) {
			$(g.gDiv).addClass('novstripe');
		}
		$(t).before(g.gDiv);
		$(g.gDiv).append(t);
		/* 主操作按纽 */
		if (p.buttons) {
			g.tDiv.className = 'tDiv';
			var tDiv2 = document.createElement('div');
			tDiv2.className = 'tDiv2';
			for (var i = 0; i < p.buttons.length; i++) {
				var btn = p.buttons[i];
				if (!btn.separator) {
					var btnDiv = document.createElement('div');
					btnDiv.className = 'fbutton';
					btnDiv.innerHTML = ("<div><span>") + ("</span></div>");
					if (btn.bclass) $('span', btnDiv).addClass(btn.bclass);
					if (btn.bimage){ /* 设置按纽样式 */
						$('span',btnDiv).css( 'background', 'url('+btn.bimage+') no-repeat center left' );
						$('span',btnDiv).css( 'paddingLeft', 20 );
					}
					if (btn.tooltip) /* 添加标题如果存在(RS) */
						$('span',btnDiv)[0].title = btn.tooltip;
					if (btn.id) {
						btnDiv.id = btn.id;
					} else {
						btnDiv.id = btn.name;
					}
					btnDiv.onpress = btn.onpress;
					btnDiv.name = btn.name;
					if (btn.onpress) {
						btnDiv.onpress(btnDiv.id, btnDiv.id || btnDiv.name, g.gDiv);
					}
					$(tDiv2).append(btnDiv);
					if (browser.msie && browser.version < 7.0) {
						$(btnDiv).hover(function () {
							$(this).addClass('fbOver');
						}, function () {
							$(this).removeClass('fbOver');
						});
					}
				} else {
					/* $(tDiv2).append("<div class='btnseparator'></div>"); */
				}
			}
			/* 刷新按纽 */
			$(tDiv2).append('<div class="pReload fbutton"><div><span class="reload"></span></div></div>');
			/* 显示列按纽 */
			$(tDiv2).append('<div class="show-row fbutton" style="display:none;"><span></span></div>');
			$(g.tDiv).append(tDiv2);
			$(g.tDiv).append("<div style='clear:both'></div>");
			$(g.gDiv).prepend(g.tDiv);
			/* 刷新按纽事件 */
			$('.pReload', g.tDiv).click(function () {
				g.populate();
			});
			/* 显示列按纽事件 */
			$('.show-row', g.tDiv).mouseover(function(){
				var onl = parseInt($(this).offset().left);
				$(g.nDiv).hide();
				$(g.nBtn).hide();
				$(g.nBtn).css({
					'left': onl+$(this).width(),
					top: g.tDiv.offsetTop
				}).show();
				$(g.nDiv).css({
					top: parseInt(g.tDiv.offsetTop, $(this).height())
				});
				$(g.nDiv).css('left', onl+$(this).width());
				
				if ($(this).hasClass('sorted')) {
					$(g.nBtn).addClass('srtd');
				} else {
					$(g.nBtn).removeClass('srtd');
				}
			});
		}
		/* 搜索条件 */
		if (p.searchitems) {
			/* 创建搜索元素 */
			g.sDiv.className = 'sDiv';
			var sitems = p.searchitems;
			var sopt = '', sel = '';
			for (var s = 0; s < sitems.length; s++) {
				if (p.qtype === '' && sitems[s].isdefault === true) {
					p.qtype = sitems[s].name;
					sel = 'selected="selected"';
				} else {
					sel = '';
				}
				sopt += "<option value='" + sitems[s].name + "' " + sel + " >" + sitems[s].display + "&nbsp;&nbsp;</option>";
			}
			if (p.qtype === '') {
				p.qtype = sitems[0].name;
			}
			$(g.sDiv).append("<div class='sDiv2'>" + p.findtext +
					" <select name='qtype'>" + sopt + "</select> <input type='text' value='" + p.query +"' size='30' name='q' class='qsbox' /> "+
					"<input value='搜索' class='search-submit' type='button'/></div>");
			/* 提交搜索条件 */
			$('.search-submit', g.sDiv).click(function () {
					g.doSearch();
			});
			$(g.tDiv).after(g.sDiv);
		}
		g.hDiv.className = 'hDiv';
		/* 主操作按纽后面追加的下拉组合按纽 */
		if( p.combobuttons && $(g.tDiv2) ) {
			var btnDiv = document.createElement('div');
			btnDiv.className = 'fbutton';

			var tSelect = document.createElement('select');
			$(tSelect).change( function () { g.combo_doSelectAction( tSelect ) } );
			$(tSelect).click( function () { g.combo_resetIndex( tSelect) } );
			tSelect.className = 'cselect';
			$(btnDiv).append(tSelect);

			for (i=0;i<p.combobuttons.length;i++)
			{
				var btn = p.combobuttons[i];
				if (!btn.separator)
				{
					var btnOpt = document.createElement('option');
					btnOpt.innerHTML = btn.name;

					if (btn.bclass)
						$(btnOpt)
						.addClass(btn.bclass)
						.css({paddingLeft:20})
						;
					if (btn.bimage)  /* 如果bimage定义,使用它的字符串作为这种按钮风格的图像url(RS) */
						$(btnOpt).css( 'background', 'url('+btn.bimage+') no-repeat center left' );
					$(btnOpt).css( 'paddingLeft', 20 );

					if (btn.tooltip) /* 添加标题如果存在(RS) */
						$(btnOpt)[0].title = btn.tooltip;

					if (btn.onpress){
						btnOpt.value = btn.onpress;
					}
					$(tSelect).append(btnOpt);
				}
			}
			$('.tDiv2').append(btnDiv);
		}

		$(t).before(g.hDiv);
		g.hTable.cellPadding = 0;
		g.hTable.cellSpacing = 0;
		$(g.hDiv).append('<div class="hDivBox"></div>');
		$('div', g.hDiv).append(g.hTable);
		var thead = $("thead:first", t).get(0);
		if (thead) $(g.hTable).append(thead);
		thead = null;
		if (!p.colmodel) var ci = 0;
		$('thead tr:first th', g.hDiv).each(function () {
			var thdiv = document.createElement('div');
			if ($(this).attr('abbr')) {
				$(this).click(function (e) {
					if (!$(this).hasClass('thOver')) return false;
					var obj = (e.target || e.srcElement);
					if (obj.href || obj.type) return true;
					g.changeSort(this);
				});
				if ($(this).attr('abbr') == p.sortname) {
					this.className = 'sorted';
					thdiv.className = 's' + p.sortorder;
				}
			}
			if (this.hidden) {
				$(this).hide();
			}
			if (!p.colmodel) {
				$(this).attr('axis', 'col' + ci++);
			}
			
			/* 如果没有一个默认的宽度,然后列标题不匹配 */
			/* 我肯定有一个更好的办法,但这至少停止失败 */
			if (this.width == '') {
				this.width = 100;
			}
			$(thdiv).css({
				textAlign: this.align,
				width: this.width + 'px'
			});
			thdiv.innerHTML = this.innerHTML;
			$(this).empty().append(thdiv).removeAttr('width').mousedown(function (e) {
				g.dragStart('colMove', e, this);
			}).hover(function () {
				if (!g.colresize && !$(this).hasClass('thMove') && !g.colCopy) {
					$(this).addClass('thOver');
				}
				if ($(this).attr('abbr') != p.sortname && !g.colCopy && !g.colresize && $(this).attr('abbr')) {
					$('div', this).addClass('s' + p.sortorder);
				} else if ($(this).attr('abbr') == p.sortname && !g.colCopy && !g.colresize && $(this).attr('abbr')){
					var no = (p.sortorder == 'asc') ? 'desc' : 'asc';
					$('div', this).removeClass('s' + p.sortorder).addClass('s' + no);
				}
				if (g.colCopy) {
					var n = $('th', g.hDiv).index(this);
					if (n == g.dcoln) {
						return false;
					}
					if (n < g.dcoln) {
						$(this).append(g.cdropleft);
					} else {
						$(this).append(g.cdropright);
					}
					g.dcolt = n;
				}
			}, function () {
				$(this).removeClass('thOver');
				if ($(this).attr('abbr') != p.sortname) {
					$('div', this).removeClass('s' + p.sortorder);
				} else if ($(this).attr('abbr') == p.sortname) {
					var no = (p.sortorder == 'asc') ? 'desc' : 'asc';
					$('div', this).addClass('s' + p.sortorder).removeClass('s' + no);
				}
				if (g.colCopy) {
					$(g.cdropleft).remove();
					$(g.cdropright).remove();
					g.dcolt = null;
				}
			});
		});
		/* 设置表格体容器 */
		g.bDiv.className = 'bDiv';
		$(t).before(g.bDiv);
		$(g.bDiv).css({
			height: (p.height == 'auto') ? 'auto' : p.height + "px"
		}).scroll(function (e) {
			g.scroll()
		}).append(t);
		if (p.height == 'auto') {
			$('table', g.bDiv).addClass('autoht');
		}
		/* 添加td行内容 */
		g.addCellProp();
		g.addRowProp();
        
		/* 添加td样式 */
		if (p.striped) {
			$('tbody tr:odd', g.bDiv).addClass('erow');
		}
		if (p.resizable && p.height != 'auto') {
			g.vDiv.className = 'vGrip';
			$(g.vDiv).mousedown(function (e) {
				g.dragStart('vresize', e);
			}).html('<span></span>');
			$(g.bDiv).after(g.vDiv);
		}
		if (p.resizable && p.width != 'auto' && !p.nohresize) {
			g.rDiv.className = 'hGrip';
			$(g.rDiv).mousedown(function (e) {
				g.dragStart('vresize', e, true);
			}).html('<span></span>').css('height', $(g.gDiv).height());
			if (browser.msie && browser.version < 7.0) {
				$(g.rDiv).hover(function () {
					$(this).addClass('hgOver');
				}, function () {
					$(this).removeClass('hgOver');
				});
			}
			$(g.gDiv).append(g.rDiv);
		}
		/* 添加分页组件 */
		if (p.usepager) {
			g.pDiv.className = 'pDiv';
			g.pDiv.innerHTML = '<div class="pDiv2"></div>';
			$(g.bDiv).after(g.pDiv);
			var html = '';
			/* 分页组件开始 */
				html += '<div class="table-paging">';
				html += 	'<div class="pGroup">';
				html += 		'<div class="pFirst pButton">';
				html += 			'<span></span>';
				html += 		'</div>';
				html += 		'<div class="pPrev pButton">';
				html += 			'<span></span>';
				html += 		'</div>';
				html +=		'</div>';
				//html += '<div class="btnseparator"></div>';/* 每一块中间的间隔 */
				html += 	'<div class="pGroup">';
				html += 		'<span class="pcontrol">';
				html += 			p.pagetext;
				html += 			'<input type="text" size="4" value="1" />';
				html +=				p.outof;
				html += 			'<span> 1 </span>';
				html += 		'</span>';
				html += 	'</div>';
				//html += '<div class="btnseparator"></div>';/* 每一块中间的间隔 */
				html += 	'<div class="pGroup">';
				html += 		'<div class="pNext pButton">';
				html += 			'<span></span>';
				html +=			'</div>';
				html +=			'<div class="pLast pButton">';
				html += 			'<span></span>';
				html +=			'</div>';
				html += 	'</div>';
				/* 显示当前页数，数据总数 */
					//html += 	'<div class="pGroup">';
					//html += 		'<span class="pPageStat"></span>';
					//html += 	'</div>';
				html += '</div>';
			/* 分页组件结束 */
				//html += '<div class="btnseparator"></div>';/* 每一块中间的间隔 */
			$('div', g.pDiv).html(html);
			$('.pFirst', g.pDiv).click(function () {
				g.changePage('first');
			});
			$('.pPrev', g.pDiv).click(function () {
				g.changePage('prev');
			});
			$('.pNext', g.pDiv).click(function () {
				g.changePage('next');
			});
			$('.pLast', g.pDiv).click(function () {
				g.changePage('last');
			});
			$('.pcontrol input', g.pDiv).keydown(function (e) {
				if (e.keyCode == 13) { 
                    g.changePage('input');
				}
			});
			if (browser.msie && browser.version < 7) $('.pButton', g.pDiv).hover(function () {
				$(this).addClass('pBtnOver');
			}, function () {
				$(this).removeClass('pBtnOver');
			});
			if (p.useRp) {
				var opt = '',
					sel = '';
				for (var nx = 0; nx < p.rpOptions.length; nx++) {
					if (p.rp == p.rpOptions[nx]) sel = 'selected="selected"';
					else sel = '';
					opt += "<option value='" + p.rpOptions[nx] + "' " + sel + " >" + p.rpOptions[nx] + "&nbsp;&nbsp;</option>";
				}
				$('.pDiv2', g.pDiv).prepend("<div class='pGroup'><select name='rp'>" + opt + "</select></div> <div class='btnseparator'></div>");
				$('select', g.pDiv).change(function () {
					if (p.onRpChange) {
						p.onRpChange(+this.value);
					} else {
						p.newp = 1;
						p.rp = +this.value;
						g.populate();
					}
				});
			}
			
		}
		$(g.pDiv).append("<div style='clear:both'></div>");
		/* 添加标题 */
		if (p.title) {
			g.mDiv.className = 'mDiv';
			g.mDiv.innerHTML = '<div class="ftitle">' + p.title + '</div>';
			$(g.gDiv).prepend(g.mDiv);
			if (p.showTableToggleBtn) {
				$(g.mDiv).append('<div class="ptogtitle" title="Minimize/Maximize Table"><span></span></div>');
				$('div.ptogtitle', g.mDiv).click(function () {
					$(g.gDiv).toggleClass('hideBody');
					$(this).toggleClass('vsble');
				});
			}
		}
		/* setup cdrops */
		g.cdropleft = document.createElement('span');
		g.cdropleft.className = 'cdropleft';
		g.cdropright = document.createElement('span');
		g.cdropright.className = 'cdropright';
		/* 添加区块 */
		g.block.className = 'gBlock';
		var gh = $(g.bDiv).height();
		var gtop = g.bDiv.offsetTop;
		$(g.block).css({
			width: g.bDiv.style.width,
			height: gh,
			background: 'white',
			position: 'relative',
			marginBottom: (gh * -1),
			zIndex: 1,
			top: gtop,
			left: '0px'
		});
		$(g.block).fadeTo(0, p.blockOpacity);
		/* 添加列控制 */
		if ($('th', g.hDiv).length) {
			g.nDiv.className = 'nDiv';
			g.nDiv.innerHTML = "<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";
			$(g.nDiv).css({
				marginBottom: (gh * -1),
				display: 'none',
				top: gtop
			}).noSelect();
			$('th div', g.hDiv).each(function () {
				var cn = $(this).parent().attr('axis').substr(3);
				if($(this).parent().attr('checkall')){
					return ;
				}
				var kcol = $("th[axis='col" + cn + "']", g.hDiv)[0];
				var chk = 'checked="checked"';
				if (kcol.style.display == 'none') {
					chk = '';
				}
				$('tbody', g.nDiv).append('<tr><td class="ndcol1"><input type="checkbox" ' + chk + ' class="togCol" value="' + cn + '" /></td><td class="ndcol2">' + this.innerHTML + '</td></tr>');
				cn++;
			});
			if (browser.msie && browser.version < 7.0) $('tr', g.nDiv).hover(function () {
				$(this).addClass('ndcolover');
			}, function () {
				$(this).removeClass('ndcolover');
			});
			$('td.ndcol2', g.nDiv).click(function () {
				if ($('input:checked', g.nDiv).length <= p.minColToggle && $(this).prev().find('input')[0].checked) return false;
				return g.toggleCol($(this).prev().find('input').val());
			});
			$('input.togCol', g.nDiv).click(function () {
				if ($('input:checked', g.nDiv).length < p.minColToggle && this.checked === false) return false;
				$(this).parent().next().trigger('click');
			});
			$(g.gDiv).prepend(g.nDiv);
			$(g.nBtn).addClass('nBtn')
				.html('<div></div>')
				.attr('title', 'Hide/Show Columns')
				.click(function () {
					$(g.nDiv).toggle();
					return true;
				}
			);
			if (p.showToggleBtn) {
				$(g.gDiv).prepend(g.nBtn);
			}
		}
		/* 添加数据修改图层 */
		$(g.iDiv).addClass('iDiv').css({
			display: 'none'
		});
		$(g.bDiv).append(g.iDiv);
		/* 添加表格体事件 */
		$(g.bDiv).hover(function () {
			$(g.nDiv).hide();
			$(g.nBtn).hide();
		}, function () {
			if (g.multisel) {
				g.multisel = false;
			}
		});
		$(g.gDiv).hover(function () {}, function () {
			$(g.nDiv).hide();
			$(g.nBtn).hide();
		});
		/* 添加文档事件 */
		$(document).mousemove(function (e) {
			g.dragMove(e);
		}).mouseup(function (e) {
			g.dragEnd();
		}).hover(function () {}, function () {
			g.dragEnd();
		});
		/* 浏览器的调整 */
		if (browser.msie && browser.version < 7.0) {
			$('.hDiv,.bDiv,.mDiv,.pDiv,.vGrip,.tDiv, .sDiv', g.gDiv).css({
				width: '100%'
			});
			$(g.gDiv).addClass('ie6');
			if (p.width != 'auto') {
				$(g.gDiv).addClass('ie6fullwidthbug');
			}
		}
		g.rePosDrag();
		g.fixHeight();
		/* 制作可进入表格的方法 */
		t.p = p;
		t.grid = g;
		/* 加载数据 */
		if (p.url && p.autoload) {
			g.populate();
		}
		return t;
	};
	var docloaded = false;
	$(document).ready(function () {
		docloaded = true;
	});
	$.fn.flexigrid = function (p) {
		return this.each(function () {
			if (!docloaded) {
				$(this).hide();
				var t = this;
				$(document).ready(function () {
					$.addFlex(t, p);
				});
			} else {
				$.addFlex(this, p);
			}
		});
	};
	/* 刷新表格函数 */
	$.fn.flexReload = function (p) { 
		return this.each(function () {
			if (this.grid && this.p.url) this.grid.populate();
		});
	};
	/* 更新通用选项 */
	$.fn.flexOptions = function (p) {
		return this.each(function () {
			if (this.grid) $.extend(this.p, p);
		});
	};
	/* function to reload grid */
	$.fn.flexToggleCol = function (cid, visible) { 
		return this.each(function () {
			if (this.grid) this.grid.toggleCol(cid, visible);
		});
	};
	/* 添加数据网格 */
	$.fn.flexAddData = function (data) { 
		return this.each(function () {
			if (this.grid) this.grid.addData(data);
		});
	};
	/* 我没有选择插件:-) */
	$.fn.noSelect = function (p) {
		var prevent = (p === null) ? true : p;
		if (prevent) {
			return this.each(function () {
				if (browser.msie || browser.safari) $(this).bind('selectstart', function () {
					return false;
				});
				else if (browser.mozilla) {
					$(this).css('MozUserSelect', 'none');
					$('body').trigger('focus');
				} else if (browser.opera) $(this).bind('mousedown', function () {
					return false;
				});
				else $(this).attr('unselectable', 'on');
			});
		} else {
			return this.each(function () {
				if (browser.msie || browser.safari) $(this).unbind('selectstart');
				else if (browser.mozilla) $(this).css('MozUserSelect', 'inherit');
				else if (browser.opera) $(this).unbind('mousedown');
				else $(this).removeAttr('unselectable', 'on');
			});
		}
	};
	/* 搜索表格数据 */
	$.fn.flexSearch = function(p) { 
		return this.each( function() { if (this.grid&&this.p.searchitems) this.grid.doSearch(); });
	};
	/* 返回选中的行作为一个数组 */
	$.fn.selectedRows = function (p) { 
		/* Returns the selected rows as an array, taken and adapted from http://stackoverflow.com/questions/11868404/flexigrid-get-selected-row-columns-values */
		var arReturn = [];
		var arRow = [];
		var selector = $(this.selector + ' .trSelected');
		$(selector).each(function (i, row) {
			arRow = [];
			var idr = $(row).data('id');
			$.each(row.cells, function (c, cell) {
				var col = cell.abbr;
				var val = cell.firstChild.innerHTML;
				if (val == '&nbsp;') val = '';      /* 调整的内容 */
        		var idx = cell.cellIndex;                
				arRow.push({
					Column: col,        /* 纵列字段名 */
					Value: val,         /* 纵列字段值 */
					CellIndex: idx,     /* 纵列行号 */
					RowIdentifier: idr  /* 这一行元素的标识符 */
				});
			});
			arReturn.push(arRow);
		});
		return arReturn;
	};
})(jQuery);
