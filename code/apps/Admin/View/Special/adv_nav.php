<!--图文导航开始-->
    <div class="outside-link" style="margin-top: 5px;">
        <ul class="link-box">
        <foreach name="vo.item_data" item="vo1">
            <li class="link-li">
                <a>
                <?php if ($vo1['img'] != '') { ?>
                <img src="__ATTACH_HOST__{$vo1.img}" class="link-img"/>
                <?php }else{?>
                <img src="__PUBLIC__/img/default_goods_image.gif" class="link-img"/>
                <?php } ?>
                <p class="link-tit">{$vo1.title}</p>
                </a>
            </li>
        </foreach>    
        </ul>
    </div>
<!--图文导航结束-->