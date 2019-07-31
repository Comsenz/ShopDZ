<if condition="!$edit">
    <div class="con-list" data-type="adv_goods">
        <div class="con1">
            <div class="listbox1">
                <foreach name="vo.goods_info" item="goods">
                    <a href="#">
                        <dl class="list-dl">
                            <dt><img src="__ATTACH_HOST__{$goods.goods_image}"/></dt>
                            <dd>
                                <p class="dl-tit">{$goods.goods_name}</p>
                                <p class="price1"><span>¥</span>{$goods.goods_price}</p>
                            </dd>
                        </dl>
                    </a>
                </foreach>
            </div>
        </div>
    </div>
<else />
    
        <!-- <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/extends.css?v=1" />
        <link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/css/shopcity.css" /> -->
        <div class="tipsbox">
            <div class="tips boxsizing radius3">
                <div class="tips-titbox">
                    <h1 class="tip-tit"><i class="tips-icon-lamp"></i>操作提示</h1>
                    <span class="open-span span-icon"><i class="open-icon"></i></span>
                </div>
            </div>
            <ol class="tips-list" id="tips-list">
                <li>1.从右侧筛选商品，点击添加按钮完成添加。</li>
                <li>2.鼠标移动到已有商品上，会出现删除按钮可以对商品进行删除。</li>
                <li>3.操作完成后点击确认提交按钮进行保存。</li>
            </ol>
        </div>
        <div class="iframeCon">
            <div class="white-shadow2">
            <form action="{:U('itemSave')}" method="post" id="goods_form">
                <input type="hidden" name="item_id" value="{$edit}"/>
                <input type="hidden" name="item_type" value="{$item_type}"/>
                <div class="zoom">
                	 <h1 class="details-tit">编辑商品</h1>
                    <div class="manage-left left">
                        <ul class="manage-left-con">
                            <foreach name="goods" item="vo">
                                <li id="{$vo.goods_common_id}">
                                    <div class="img-cover"></div>
                                    <div class="edit-btn1"><a href='javascript:delGoods({$vo.goods_common_id});'>删除</a></div>
                                    <div class="manage-picbox"><img src="__ATTACH_HOST__{$vo.goods_image}" alt="{$vo.goods_name}" class="manage-pic"/></div>
                                    <p class="manage-name ellipsis">{$vo.goods_name}</p>
                                    <p class="manage-price">￥{$vo.goods_price}</p>
                                    <input nctype="goods_common_id" name="item_data[item][]" type="hidden" value="{$vo.goods_common_id}">
                                </li>
                            </foreach>
                            
                        </ul>
                    </div>
                    <div class="manage-right left">
                        <p class="manange-right-tit">选择商品添加</p>
                        <p class="pro-keyword">商品关键字</p>
                        <div class="manage-search">
                            <input type="text" class="com-inp1 manage-inp left" placeholder="请输入商品关键字" id="goods_search_content" name="goods_search_content"/>                            
                            <!-- <button class="search-btn left radius3" id="btn_mb_special_goods_search" onclick="searchGoods();">搜索</button> -->
                            <input type="button" class="search-btn left radius3" id="btn_mb_special_goods_search" onclick="searchGoods();" value="搜索">
                        </div>
                        <ul class="manage-pro-list">
                            
                        </ul>
                        <div class="pagination boxsizing" id="goods_pagination">
                        </div>
                    </div>
                </div>
                 <div class="manege-btnbox">
                    <a type="button" class="btn1 radius3 marginT10 btn3-btnmargin normal" onclick="$('#goods_form').submit();">{$Think.lang.submit_btn}</a>
                    <a type="button" class="btn1 radius3 marginT10 normal" href="{:U('setting/personnel')}">返回上页</a>
                </div>
            </form>
            </div>
        </div>
<script type="text/javascript">

    //分页获取商品信息
    function searchGoods(p){
        p = p ? p : "1";
        var url = "{:U('Search/searchGoods')}";
        var content  = $("#goods_search_content").val();
        $.post(url,{content:content,p:p},function(data){
            if(data.status){
                //分页信息的输出
                $("#goods_pagination").empty();//清空当前div的元素
                $("#goods_pagination").html(data.data.page);//将分页html输出
                var aobjs = $("#goods_pagination").find(".page");
                $(aobjs).each(function(i){

                    var currentpage = $(this).data('page');
                    //绑定点击事件
                    $(this).click(function(){
                        searchGoods(currentpage);//重新调用该函数查询
                    });
                });
                //end分页信息
                //结果显示
                $(".manage-pro-list").empty();
                var html = buildGoodsHtml(data.data.goods);
                $(".manage-pro-list").append(html);
            }else{
                $(".manage-pro-list").empty();
                $(".manage-pro-list").append('&nbsp;&nbsp;<p>暂时没有此商品！</p>');
            }
        });
    }

    //生成商品选择html
    function buildGoodsHtml(goods){
        if(goods.length <= 0){
            return '';
        }
        var html = '';
        for(var i = 0;i<goods.length;i++){
            html += '<li>';
            html += '<img title="'+goods[i].goods_name+'" src="__ATTACH_HOST__'+ goods[i].goods_image+'"  class="manage-pro-img left"/>';
            html += '<div class="manage-pro-con right">';
            html += '<p class="manage-pro-name">'+goods[i].goods_name+'</p>';
            html += '<p class="zoom"><span class="manage-pro-price left">￥'+goods[i].goods_price+'</span><input type="button" class="search-btn radius3 manage-pro-btn right" value="添加" data-goods-id="'+goods[i].goods_common_id+'" data-goods-name="'+goods[i].goods_name+'" data-goods-price="'+goods[i].goods_price+'" data-goods-image="'+goods[i].goods_image+'"  onclick="addToList(this);"></p>';
            html += '</div>';
            html += '</li>';

        }
        return html;
    }
    

    //添加商品到
    function addToList(obj){
        if(checkExist($(obj).data("goods-id"))){
            showError("商品已添加，请添加其他商品！");
            return;
        }
        var goodshtml = '';
        goodshtml += '<li id="'+$(obj).data("goods-id")+'">';
        goodshtml += '<div class="img-cover"></div>';
        goodshtml += '<div class="edit-btn1"><a href="javascript:delGoods('+$(obj).data("goods-id")+');">删除</a></div>';
        goodshtml += '<div class="manage-picbox"><img src="__ATTACH_HOST__'+$(obj).data("goods-image")+'" alt="'+$(obj).data("goods-name")+'" class="manage-pic"/></div>';
        goodshtml += '<p class="manage-name ellipsis">'+$(obj).data("goods-name")+'</p>';
        goodshtml += '<p class="manage-price">￥'+$(obj).data("goods-price")+'</p>';
        goodshtml += '<input nctype="goods_common_id" name="item_data[item][]" type="hidden" value="'+$(obj).data("goods-id")+'">';
        goodshtml += '</li>';
        $(".manage-left-con").append(goodshtml);
    }

    
        
        
        
        
        
        
    

    //查询当前div是否已经存在当前商品
    function checkExist(goodsid){
        var goods_li = $(".manage-left-con").children('li');
        for(var i = 0;i<goods_li.length;i++){
            // console.log($(goods_li[i]));
            // console.log($(goods_li[i]).attr('id'));
            if(goodsid == $(goods_li[i]).attr('id')){
                return true;
            }
        }
        return false;
    }

    //删除
    function delGoods(goodsid){
        $("#"+goodsid).detach();
    }

</script>
</if>