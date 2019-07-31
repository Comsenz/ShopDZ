<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/extends.css?v=1" />

<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/font-awesome.min93e3.css?sv=1" />
<style type="text/css">
	.btnbox-c {
		padding: 10px 0;
		text-align: center;
	}
</style>
        <div class="tipsbox radius3">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.点击右侧组件的“添加”按钮，增加对应类型版块到页面</li>
                <li>2.鼠标触及左侧页面对应版块，出现操作类链接，可以对该区域块进行“移动”、“启用/禁用”、“编辑”、“删除”操作。</li>
                <li>3.新增加的版块内容默认为“禁用”状态，编辑内容并“启用”该块后将在手机端即时显示。</li>
            </ol>
        </div>
        <div class="iframeCon">
            <div class="iframeMain">
                <div class="white-bg ">
                    <div class="content-box2" style="margin-top: 0;">
                        <div class="mb-special-layout firstP-box" style="background: none;border: none;padding: 50px 0;">
                            <div class="mb-item-box">
                                <div id="item_list" class="item-list">
                                    <foreach name="item_list" item="vo">
                                        <div nctype="special_item" <if condition="$vo.item_usable eq 1 "> class="special-item goods usable" <else /> class="special-item goods unusable"  </if> data-item-id="{$vo.item_id}">
                                            <div class="item_type">{$vo.item_name}</div>
                                            <div id="item_edit_content" style="margin-top: -25px;margin-bottom: -30px;">
                                                <div class="index_block goods-list">
                                                    <div class="title">
                                                        <span></span>
                                                    </div>
                                                    <div nctype="item_content" class="content">
                                                        <!--这里需要引入首页中的样式文件-->
                                                        <link rel="stylesheet" href="__PUBLIC__/web/css/reset.css" />
                                                        <link rel="stylesheet" href="__PUBLIC__/web/css/swiper.min.css">
                                                        <link rel="stylesheet" href="__PUBLIC__/web/css/style.css" />
                                                        <!--end!!!-->
                                                        <switch name="vo.item_type">
                                                            <case value="adv_list">
                                                                <include file="Special/special_type_adv_list" item_id= "{$vo.item_id}"/>
                                                            </case>
                                                            <case value="adv_img">
                                                                <include file="Special/special_type_adv_img" item_id= "{$vo.item_id}"/>
                                                            </case>
                                                            <case value="adv_nav">
                                                                <include file="Special/special_type_adv_nav" item_id= "{$vo.item_id}"/>
                                                            </case>
                                                            <case value="adv_html">
                                                                <include file="Special/special_type_adv_html" item_id= "{$vo.item_id}"/>
                                                            </case>
                                                            <case value="goods">
                                                                <include file="Special/special_type_goods" item_id= "{$vo.item_id}"/>
                                                            </case>
                                                            <default />
                                                        </switch>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="handle">
                                                <a nctype="btn_move_up" href="javascript:;"><i class="fa fa-arrow-up
                                            "></i>上移</a> <a nctype="btn_move_down" href="javascript:;"><i class="fa fa-arrow-down
                                            "></i>下移</a><if condition="$vo.item_usable eq 1 ">  <a nctype="btn_usable" data-item-id="{$vo.item_id}" href="javascript:;"><i class="fa fa-toggle-on
                                            "></i>禁用</a> <else />  <a nctype="btn_usable" data-item-id="{$vo.item_id}" href="javascript:;"><i class="fa fa-toggle-on
                                            "></i>启用</a>  </if> <a nctype="btn_edit_item" data-item-id="{$vo.item_id}" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a> <a nctype="btn_del_item" data-item-id="{$vo.item_id}" href="javascript:;"><i class="fa fa-trash-o
                                            "></i>删除</a>
                                            </div>

                                        </div>
                                    </foreach>
                                </div>
                            </div>
                            <div class="module-list">
                                <div class="module_adv_list"> <span>幻灯版块</span> <a nctype="btn_add_item" class="add" href="javascript:;" data-specialid = 1 data-module-type="adv_list">添<br>加</a> </div>
                                <div class="module_adv_list"> <span>通栏图片广告</span> <a nctype="btn_add_item" class="add" href="javascript:;" data-specialid = 1 data-module-type="adv_img">添<br>加</a> </div>
                                <div class="module_adv_list"> <span>预置通栏模板</span> <a nctype="btn_add_item" class="add" href="javascript:;" data-specialid = 1 data-module-type="adv_html">添<br>加</a> </div>
                                <div class="module_adv_list" style="background-position: -440px -880px;height: 70px;"> <span>图文导航</span> <a nctype="btn_add_item" class="add" href="javascript:;" data-specialid = 1 data-module-type="adv_nav">添<br>加</a> </div>
                                <div class="module_goods"> <span>商品展示版块</span> <a nctype="btn_add_item" class="add" href="javascript:;" data-specialid = 2 data-module-type="goods">添<br>加</a> </div>
                            </div>
                        </div>
						<!--<div class="btnbox-c">
                       	 	<a type="button" class="btn1 radius3 marginT10" href="{:U('Admin/Special/showIndex')}" target="_blank">首页预览</a>
                   		</div>-->
                    </div>

                </div>
            </div>
        </div>

<script type="text/javascript" src="__PUBLIC__/admin/js/common2.js"></script>
<script>
    var html = '';//全局的当前首页内容
    var special_id = "{$special_id}";
    var url_item_add = "{:U('Special/itemAdd')}";
    var url_item_del = "{:U('Special/itemDel')}";
    var url_item_edit = "{:U('Special/itemEdit')}";
    $(function(){
        //添加模块
        $('[nctype="btn_add_item"]').on('click', function() {
            var data = {
                special_id: special_id,
                item_type: $(this).attr('data-module-type')
            };
            $.post(url_item_add, data, function(data) {
                getResultDialog(data,false);
            }, "json");
        });
        //对div进行排序
        //上移
        $('#item_list').on('click', '[nctype="btn_move_up"]', function() {
            var $current = $(this).parents('[nctype="special_item"]');
            $prev = $current.prev('[nctype="special_item"]');
            if($prev.length > 0) {
                $prev.before($current);
                update_item_sort();
            } else {
                showError('已经是第一个了');
            }
        });

        //下移
        $('#item_list').on('click', '[nctype="btn_move_down"]', function() {
            var $current = $(this).parents('[nctype="special_item"]');
            $next = $current.next('[nctype="special_item"]');
            if($next.length > 0) {
                $next.after($current);
                update_item_sort();
            } else {
                showError('已经是最后一个了');
            }
        });

        //删除模块
        $('#item_list').on('click', '[nctype="btn_del_item"]', function() {
        	var $this = $(this);
            var item_id = $this.attr('data-item-id');
 			showConfirm("确认要删除么？",function(){	    		
	            $.post(url_item_del, {item_id: item_id, special_id: special_id} , function(data) {
	                if(data.status == 1) {
	                	showSuccess("操作成功");
	                	var obj = $('[data-item-id="'+item_id+'"]');
	                	obj.remove();
	                } else {
	                	showError(data.info);
	                }
	            }, "json");
	    	});
	    	
        });


        //编辑模块
        $('#item_list').on('click', '[nctype="btn_edit_item"]', function() {
            var item_id = $(this).attr('data-item-id');
            window.location.href = url_item_edit + '?item_id=' + item_id;
        });

        //启用/禁用控制
        $('#item_list').on('click', '[nctype="btn_usable"]', function() {
            var $current = $(this).parents('[nctype="special_item"]');
            var item_id = $current.attr('data-item-id');
            var usable = '';
            if($current.hasClass('usable')) {
                $current.removeClass('usable');
                $current.addClass('unusable');
                usable = 'unusable';
                $(this).html('<i class="fa fa-toggle-off"></i>启用');
            } else {
                $current.removeClass('unusable');
                $current.addClass('usable');
                usable = 'usable';
                $(this).html('<i class="fa fa-toggle-on"></i>禁用');
            }

            $.post("{:U('Special/itemStatus')}", {item_id: item_id, usable: usable, special_id: special_id}, function(data) {
                if(!data.status){
                    showError("操作失败");
                }
            }, 'json');
        });

        var update_item_sort = function() {
            var item_id_string = '';
            $item_list = $('#item_list').find('[nctype="special_item"]');
            $item_list.each(function(index, item) {
                item_id_string += $(item).attr('data-item-id') + ',';
            });
            $.post("{:U('Special/itemSort')}",{special_id: special_id, item_id_string: item_id_string},function(data){
                if(!data.status){
                    showError("操作失败");
                }
            });
        };
    });

</script>