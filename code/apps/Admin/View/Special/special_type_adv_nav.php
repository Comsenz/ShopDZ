<if condition="!$edit">
<!--图文导航开始-->
    <div class="outside-link" style="margin-top: 55px;">
        <ul class="link-box" style="height:90px;">
        <foreach name="vo.data" item="vo1">
            <li class="link-li" style="height:100%">
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
<else />
<style type="text/css">
            .page-upload-list {
                width: 600px;
            }
            .page-upload-list>li {
                width: 100%;
                border: 1px dashed #e0e0e0;
                padding: 10px;
                overflow: hidden;
                zoom: 1;
            }
            .first-upload-left {
                max-width: 160px;
            }
            .first-upload-right {
                min-width: 400px;
                max-width: 420px;
                height: 100%;
                min-height: 165px;
                /*background: #f0f0f0;*/
            }
            .w120 {
                width: 120px;
            }
            .img-style {
            	width: 100px;
            	height: 100px;
            }
            .upload-p {
            	width: 100px;
            }
            .uploadbox-li {
            	width: 100px;
            	height: 130px;
            }
            .uploadbox {
            	height: 130px;
            	padding: 20px 0;
            }
            .asDefault-box-cover, .asDefault-box-cover2 {
            	width: 100px;
            	height: 100px;
            }
            .first-upload-right {
            	min-width: 440px;
            	max-width: 475px;
            }
            .uploadbox {
            	min-width: 130px;
            }
            select {
            	height: 28px;
            	margin-right: 10px;
            }
        </style>
<!--<div class="tip-remind">收起提示</div>-->
    <div class="tipsbox radius3">
        <div class="tips boxsizing radius3">
            <div class="tips-titbox">
                <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
                <span class="open-span span-icon"><i class="open-icon"></i></span>
            </div>
        </div>
        <ol class="tips-list" id="tips-list">
            <li>1.点击上传图片按钮可以为图文导航添加图片</li>
            <li>2.鼠标移动到上传的图片上，点击‘X’可以删除上传的图片</li>
            <li>3.填写图片导航下面的显示文字和图片导航的跳转链接或id</li>
            <li>4.操作完成后点击确认提交按钮进行保存</li>
        </ol>
    </div>
    <div class="iframeCon">
        <div class="iframeMain">
            <ul class="transverse-nav">
                <li class="activeFour"><a href="javascript:;"><span>图文导航</span></a></li>
            </ul>
            <div class="white-bg ">
                <div class="tab-conbox" style="margin-left: 100px;">
                <form method="post" class="form-horizontal" id="dataform" enctype="multipart/form-data" action="{:U('itemSave')}">
                <input type="hidden" name="item_id" id="item_id"  value="{$edit}">
                <input type="hidden" name="item_type" id="item_type"  value="{$item_type}">
                    <ul class="page-upload-list">
                        <li style="border-width: 1px;border-style:solid; border-color: transparent transparent #e0e0e0 transparent;">
                            <ul class="uploadbox boxsizing left first-upload-left">
                                <li class="left uploadbox-li boxsizing">
                                    <div class="img-style boxsizing">
                                        <img shopdz-action="upload_image"  src="<?php if($item_data[0]['img']){ ?>__ATTACH_HOST__{$item_data[0]['img']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" id="img_view_1" alt="" class="uploadimg boxsizing"/>
                                    </div>
                                    <div class="asDefault-box-cover boxsizing">
                                    </div>
                                    <i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
                                    <div class="operationbox boxsizing">
                                        <p class="upload-p">
                                            <input type="radio"  style="display:none;"   name="is_default" value="1" />
                                            <input type="file"   id="img_file_1"  class="upload-inp2" hidefocus="true"/>
                                             <input shopdz-action="upload_value"   type="hidden"  name="img_1"  value="<?php echo $item_data[0]['img']; ?>"  >  
                                            <span class="inp2-cover boxsizing "><i class="up-icon upload-icon"></i>上传</span>
                                        </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="first-upload-right right">
                                <div class="special-con addSpec-con">
                                    <span class="special-con-left left">文字：</span>
                                    <div class="input-group">
                                        <input type="text" name="title_1" id="title_1" class="com-inp1 radius3 boxsizing" localrequired="" value="<?php echo $item_data[0]['title']; ?>">
                                    </div>
                                    <div class="alerrt-sele-box">
                                        <span class="special-con-left left">操作：</span>
                                        <select name="type_1" id="type_1" class="left addFocus-sele w120" localrequired="">
                                            <option <?php if($item_data[0]['type'] == 'url'){ echo  'selected="selected"';}?> value="url">链接</option>
                                            <option <?php if($item_data[0]['type'] == 'goods'){ echo  'selected="selected"';}?> value="goods">SPU ID</option>
                                            <option <?php if($item_data[0]['type'] == 'category'){ echo  'selected="selected"';}?> value="category">商品分类</option>
                                            <!-- <option <?php if($item_data[0]['type'] == 'redpacket'){ echo  'selected="selected"';}?> value="redpacket">优惠券</option> -->
                                        </select>

                                        <?php if($item_data[0]['type'] == 'redpacket'){ ?>
                                            <select class="left addFocus-sele w120" name="data_1" id="data_1" localrequired="">
                                            <?php foreach ($rpacket_list as $v) {
                                             echo '<option '. ($item_data[0]['data']==$v['rpacket_t_id']  ? 'selected="selected"' : '' ).'  value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';   
                    
                                            }?>
                                            </select>
                                        <?php }else if($item_data[0]['type'] == 'category'){?>
                                            <select class="left addFocus-sele w120" name="data_1" id="data_1" localrequired="">
                                            <?php foreach($category_list as $one){  ?>
                                                <?php if(!empty($one['child'])){ ?>
                                                 <option <?php if($item_data[0]['data']==$one['gc_id']){echo 'selected="selected" ';} ?> value="<?php  echo $one['gc_id'];?>"><?php echo $one['gc_name']?></option>
                                                     <?php foreach($one['child'] as $two){ ?>
                                                         <option  <?php if($item_data[0]['data']==$two['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $two['gc_id'];?>" >--<?php echo $two['gc_name']?></option>
                                                     <?php } ?>
                                                <?php }else{ ?>
                                                <option   <?php if($item_data[0]['data']==$one['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                            </select>
                                        <?php } else{?>
                                        <input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_1" id="data_1" localrequired="" value="<?php echo $item_data[0]['data']; ?>"><?php }?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li style="border-width: 1px;border-style:solid; border-color: transparent transparent #e0e0e0 transparent;">
                            <ul class="uploadbox boxsizing left first-upload-left">
                                <li class="left uploadbox-li boxsizing">
                                    <div class="img-style boxsizing">
                                        <img shopdz-action="upload_image"  src="<?php if($item_data[1]['img']){ ?>__ATTACH_HOST__{$item_data[1]['img']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" id="img_view_2" alt="" class="uploadimg boxsizing"/>
                                    </div>
                                    <div class="asDefault-box-cover boxsizing">
                                    </div>
                                    <i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
                                    <div class="operationbox boxsizing">
                                        <p class="upload-p">
                                            <input type="radio"  style="display:none;"   name="is_default" value="2" />
                                            <input type="file"   id="img_file_2"  class="upload-inp2" hidefocus="true"/>
                                             <input shopdz-action="upload_value"   type="hidden"  name="img_2"  value="<?php echo $item_data[1]['img']; ?>"  >  
                                            <span class="inp2-cover boxsizing "><i class="up-icon upload-icon"></i>上传</span>
                                        </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="first-upload-right right">
                                <div class="special-con addSpec-con">
                                    <span class="special-con-left left">文字：</span>
                                    <div class="input-group">
                                        <input type="text" name="title_2" id="title_2" class="com-inp1 radius3 boxsizing" localrequired="" value="<?php echo $item_data[1]['title']; ?>">
                                    </div>
                                    <div class="alerrt-sele-box">
                                        <span class="special-con-left left">操作：</span>
                                        <select name="type_2" id="type_2" class="left addFocus-sele w120" localrequired="">
                                            <option <?php if($item_data[1]['type'] == 'url'){ echo  'selected="selected"';}?> value="url">链接</option>
                                            <option <?php if($item_data[1]['type'] == 'goods'){ echo  'selected="selected"';}?> value="goods">SPU ID</option>
                                            <option <?php if($item_data[1]['type'] == 'category'){ echo  'selected="selected"';}?> value="category">商品分类</option>
                                            <!-- <option <?php if($item_data[1]['type'] == 'redpacket'){ echo  'selected="selected"';}?> value="redpacket">优惠券</option> -->
                                        </select>

                                        <?php if($item_data[1]['type'] == 'redpacket'){ ?>
                                            <select class="left addFocus-sele w120" name="data_2" id="data_2" localrequired="">
                                            <?php foreach ($rpacket_list as $v) {
                                             echo '<option '. ($item_data[1]['data']==$v['rpacket_t_id']  ? 'selected="selected"' : '' ).'  value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';   
                    
                                            }?>
                                            </select>
                                        <?php }else if($item_data[1]['type'] == 'category'){?>
                                            <select class="left addFocus-sele w120" name="data_2" id="data_2" localrequired="">
                                            <?php foreach($category_list as $one){  ?>
                                                <?php if(!empty($one['child'])){ ?>
                                                 <option <?php if($item_data[1]['data']==$one['gc_id']){echo 'selected="selected" ';} ?> value="<?php  echo $one['gc_id'];?>"><?php echo $one['gc_name']?></option>
                                                     <?php foreach($one['child'] as $two){ ?>
                                                         <option  <?php if($item_data[1]['data']==$two['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $two['gc_id'];?>" >--<?php echo $two['gc_name']?></option>
                                                     <?php } ?>
                                                <?php }else{ ?>
                                                <option   <?php if($item_data[1]['data']==$one['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                            </select>
                                        <?php } else{?>
                                        <input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_2" id="data_2" localrequired="" value="<?php echo $item_data[1]['data']; ?>"><?php }?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li style="border-width: 1px;border-style:solid; border-color: transparent transparent #e0e0e0 transparent;">
                            <ul class="uploadbox boxsizing left first-upload-left">
                                <li class="left uploadbox-li boxsizing">
                                    <div class="img-style boxsizing">
                                        <img shopdz-action="upload_image"  src="<?php if($item_data[2]['img']){ ?>__ATTACH_HOST__{$item_data[2]['img']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" id="img_view_3" alt="" class="uploadimg boxsizing"/>
                                    </div>
                                    <div class="asDefault-box-cover boxsizing">
                                    </div>
                                    <i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
                                    <div class="operationbox boxsizing">
                                        <p class="upload-p">
                                            <input type="radio"  style="display:none;"   name="is_default" value="3" />
                                            <input type="file"   id="img_file_3"  class="upload-inp2" hidefocus="true"/>
                                             <input shopdz-action="upload_value"   type="hidden"  name="img_3"  value="<?php echo $item_data[2]['img']; ?>"  >  
                                            <span class="inp2-cover boxsizing "><i class="up-icon upload-icon"></i>上传</span>
                                        </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="first-upload-right right">
                                <div class="special-con addSpec-con">
                                    <span class="special-con-left left">文字：</span>
                                    <div class="input-group">
                                        <input type="text" name="title_3" id="title_3" class="com-inp1 radius3 boxsizing" localrequired="" value="<?php echo $item_data[2]['title']; ?>">
                                    </div>
                                    <div class="alerrt-sele-box">
                                        <span class="special-con-left left">操作：</span>
                                        <select name="type_3" id="type_3" class="left addFocus-sele w120" localrequired="">
                                            <option <?php if($item_data[2]['type'] == 'url'){ echo  'selected="selected"';}?> value="url">链接</option>
                                            <option <?php if($item_data[2]['type'] == 'goods'){ echo  'selected="selected"';}?> value="goods">SPU ID</option>
                                            <option <?php if($item_data[2]['type'] == 'category'){ echo  'selected="selected"';}?> value="category">商品分类</option>
                                            <!-- <option <?php if($item_data[2]['type'] == 'redpacket'){ echo  'selected="selected"';}?> value="redpacket">优惠券</option> -->
                                        </select>

                                        <?php if($item_data[2]['type'] == 'redpacket'){ ?>
                                            <select class="left addFocus-sele w120" name="data_3" id="data_3" localrequired="">
                                            <?php foreach ($rpacket_list as $v) {
                                             echo '<option '. ($item_data[2]['data']==$v['rpacket_t_id']  ? 'selected="selected"' : '' ).'  value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';   
                    
                                            }?>
                                            </select>
                                        <?php }else if($item_data[2]['type'] == 'category'){?>
                                            <select class="left addFocus-sele w120" name="data_3" id="data_3" localrequired="">
                                            <?php foreach($category_list as $one){  ?>
                                                <?php if(!empty($one['child'])){ ?>
                                                 <option <?php if($item_data[2]['data']==$one['gc_id']){echo 'selected="selected" ';} ?> value="<?php  echo $one['gc_id'];?>"><?php echo $one['gc_name']?></option>
                                                     <?php foreach($one['child'] as $two){ ?>
                                                         <option  <?php if($item_data[2]['data']==$two['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $two['gc_id'];?>" >--<?php echo $two['gc_name']?></option>
                                                     <?php } ?>
                                                <?php }else{ ?>
                                                <option   <?php if($item_data[2]['data']==$one['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                            </select>
                                        <?php } else{?>
                                        <input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_3" id="data_3" localrequired="" value="<?php echo $item_data[2]['data']; ?>"><?php }?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li style="border-width: 1px;border-style:solid; border-color: transparent transparent #e0e0e0 transparent;">
                            <ul class="uploadbox boxsizing left first-upload-left">
                                <li class="left uploadbox-li boxsizing">
                                    <div class="img-style boxsizing">
                                        <img shopdz-action="upload_image"  src="<?php if($item_data[3]['img']){ ?>__ATTACH_HOST__{$item_data[3]['img']}<?php }else{ ?>  __PUBLIC__/img/default.png<?php } ?>" id="img_view_4" alt="" class="uploadimg boxsizing"/>
                                    </div>
                                    <div class="asDefault-box-cover boxsizing">
                                    </div>
                                    <i   shopdz-action="upload_delete"  class="up-icon dele-icon"></i>
                                    <div class="operationbox boxsizing">
                                        <p class="upload-p">
                                            <input type="radio"  style="display:none;"   name="is_default" value="4" />
                                            <input type="file"   id="img_file_4"  class="upload-inp2" hidefocus="true"/>
                                             <input shopdz-action="upload_value"   type="hidden"  name="img_4"  value="<?php echo $item_data[3]['img']; ?>"  >  
                                            <span class="inp2-cover boxsizing "><i class="up-icon upload-icon"></i>上传</span>
                                        </p>
                                    </div>
                                </li>
                            </ul>
                            <div class="first-upload-right right">
                                <div class="special-con addSpec-con">
                                    <span class="special-con-left left">文字：</span>
                                    <div class="input-group">
                                        <input type="text" name="title_4" id="title_4" class="com-inp1 radius3 boxsizing" localrequired="" value="<?php echo $item_data[3]['title']; ?>">
                                    </div>
                                    <div class="alerrt-sele-box">
                                        <span class="special-con-left left">操作：</span>
                                        <select name="type_4" id="type_4" class="left addFocus-sele w120" localrequired="">
                                            <option <?php if($item_data[3]['type'] == 'url'){ echo  'selected="selected"';}?> value="url">链接</option>
                                            <option <?php if($item_data[3]['type'] == 'goods'){ echo  'selected="selected"';}?> value="goods">SPU ID</option>
                                            <option <?php if($item_data[3]['type'] == 'category'){ echo  'selected="selected"';}?> value="category">商品分类</option>
                                            <!-- <option <?php if($item_data[3]['type'] == 'redpacket'){ echo  'selected="selected"';}?> value="redpacket">优惠券</option> -->
                                        </select>

                                        <?php if($item_data[3]['type'] == 'redpacket'){ ?>
                                            <select class="left addFocus-sele w120" name="data_4" id="data_4" localrequired="">
                                            <?php foreach ($rpacket_list as $v) {
                                             echo '<option '. ($item_data[3]['data']==$v['rpacket_t_id']  ? 'selected="selected"' : '' ).'  value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';   
                    
                                            }?>
                                            </select>
                                        <?php }else if($item_data[3]['type'] == 'category'){?>
                                            <select class="left addFocus-sele w120" name="data_4" id="data_4" localrequired="">
                                            <?php foreach($category_list as $one){  ?>
                                                <?php if(!empty($one['child'])){ ?>
                                                 <option <?php if($item_data[3]['data']==$one['gc_id']){echo 'selected="selected" ';} ?> value="<?php  echo $one['gc_id'];?>"><?php echo $one['gc_name']?></option>
                                                     <?php foreach($one['child'] as $two){ ?>
                                                         <option  <?php if($item_data[3]['data']==$two['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $two['gc_id'];?>" >--<?php echo $two['gc_name']?></option>
                                                     <?php } ?>
                                                <?php }else{ ?>
                                                <option   <?php if($item_data[3]['data']==$one['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                                                <?php } ?>
                                            <?php } ?>
                                            </select>
                                        <?php } else{?>
                                        <input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_4" id="data_4" localrequired="" value="<?php echo $item_data[3]['data']; ?>"><?php }?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                     <div class="btnbox-c" style="padding: 40px 0;">
                        <a type="button" id="nav_submit" class="btn1 radius3 marginT10 btn3-btnmargin" href="javascript:;">{$Think.lang.submit_btn}</a>
                        <a type="button" class="btn1 radius3 marginT10" href="{:U('setting/personnel')}">返回上页</a>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>


<script type="text/javascript">
    $('#nav_submit').click(function(){
        flag=checkrequire('dataform');
        if(!flag){
            $('#dataform').submit();
        }
    });
    $(function(){
        //图片上传
        $('.uploadbox>li').mouseenter(function(){
            $(this).find('.asDefault-box-cover').addClass('block');
            $(this).find('.dele-icon').addClass('block');
            $(this).find('.inp2-cover').addClass('inp2-cover-hover');
            //$(this).find('.asDefault').addClass('asdefault-green');
        })
        $('.uploadbox>li').mouseleave(function(){
            $(this).find('.asDefault-box-cover ').removeClass('block');
            $(this).find('.dele-icon').removeClass('block');
            $(this).find('.inp2-cover').removeClass('inp2-cover-hover');
            //$(this).find('.asDefault').addClass('asdefault-green');
        })
        $('.uploadbox>li').bind('click',function(){
            //alert('666666666666');
            $(this).find('.asDefault-box-cover').addClass('block2').parents('.uploadbox-li').siblings().find('.asDefault-box-cover').removeClass('block2');
            $(this).find('.asDefault').addClass('asdefault-green').parents('.uploadbox-li').siblings().find('.asDefault').removeClass('asdefault-green');
            $(this).find('.inp2-cover').addClass('inp2-cover-hover2').parents('.uploadbox-li').siblings().find('.inp2-cover').removeClass('inp2-cover-hover2');
            
        })
        
        //商品发布步骤
        $(".release-tab li").click(function(){
            $(this).addClass("activeRelease").siblings().removeClass('activeRelease');
            
        })
    })
</script>
<script type="text/javascript">
    $(function(){
        //鼠标滑过时，添加样式
        // $('.asDefault-box').bind('click',function(){
        //     $(this).addClass('asDefault-box-cli').parents('li').siblings().find('.asDefault-box').removeClass('asDefault-box-cli')
        //     $(this).children().addClass('green-font').parents('li').siblings().children().children().removeClass('green-font');
        //     $(this).children('.img-dele').addClass('green-bor').parents('li').siblings().children().children().removeClass('green-bor');
        //     $(this).find('i').addClass('green-font').parents('li').siblings().children().find('i').removeClass('green-font');
            
        // })
        

        //删除图片按钮
        $('[shopdz-action="upload_delete"]').click(function(){
           $(this).parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/admin/images/uploadimg.png');
           $(this).parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val('');   
        });

        $('.upload-inp2').hover(function(){
            $(this).next().css('background','#f0f0f0')
        },function(){
            $(this).next().css('background','#f5f5f5')
        });


        $('#type_1').change(function(){

            var types = $(this).val();
            if(types == 'redpacket'){
                var datastr1 = '<select class="left addFocus-sele w120" localrequired="" name="data_1" id="data_1"><?php foreach ($rpacket_list as $v) {
                    echo '<option value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';
            }?></select>';
                $('#data_1').replaceWith(datastr1);
            }else if(types == 'category'){
                var datastr1 = '<select class="left addFocus-sele w120" localrequired="" name="data_1" id="data_1"><?php foreach ($category_list as $one) { if(!empty($one["child"])){echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';foreach ($one["child"] as $two) { echo '<option value="'.$two["gc_id"].'">--'.$two["gc_name"].'</option>'; } }else{echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';}}?></select>';

                $('#data_1').replaceWith(datastr1);
            }else{
                    var datastr1 = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_1" id="data_1" localrequired="">';
                    $('#data_1').replaceWith(datastr1);
                }
        });

        $('#type_2').change(function(){

            var types = $(this).val();
            if(types == 'redpacket'){
                var datastr2 = '<select class="left addFocus-sele w120" localrequired="" name="data_2" id="data_2"><?php foreach ($rpacket_list as $v) {
                    echo '<option value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';
            }?></select>';
                $('#data_2').replaceWith(datastr2);
            }else if(types == 'category'){
                var datastr2 = '<select class="left addFocus-sele w120" localrequired="" name="data_2" id="data_2"><?php foreach ($category_list as $one) { if(!empty($one["child"])){echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';foreach ($one["child"] as $two) { echo '<option value="'.$two["gc_id"].'">--'.$two["gc_name"].'</option>'; } }else{echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';}}?></select>';

                $('#data_2').replaceWith(datastr2);
            }else{
                var datastr2 = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_2" id="data_2" localrequired="">';
                $('#data_2').replaceWith(datastr2);
            }
        });

        $('#type_3').change(function(){

            var types = $(this).val();
            if(types == 'redpacket'){
                var datastr3 = '<select class="left addFocus-sele w120" localrequired="" name="data_3" id="data_3"><?php foreach ($rpacket_list as $v) {
                    echo '<option value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';
            }?></select>';
                $('#data_3').replaceWith(datastr3);
            }else if(types == 'category'){
                var datastr3 = '<select class="left addFocus-sele w120" localrequired="" name="data_3" id="data_3"><?php foreach ($category_list as $one) { if(!empty($one["child"])){echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';foreach ($one["child"] as $two) { echo '<option value="'.$two["gc_id"].'">--'.$two["gc_name"].'</option>'; } }else{echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';}}?></select>';

                $('#data_3').replaceWith(datastr3);
            }else{
                var datastr3 = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_3" id="data_3" localrequired="">';
                $('#data_3').replaceWith(datastr3);
            }
        });

        $('#type_4').change(function(){

            var types = $(this).val();
            if(types == 'redpacket'){
                var datastr4 = '<select class="left addFocus-sele w120" localrequired="" name="data_4" id="data_4"><?php foreach ($rpacket_list as $v) {
                    echo '<option value="'.$v['rpacket_t_id'].'">'.$v['rpacket_t_title'].'</option>';
            }?></select>';
                $('#data_4').replaceWith(datastr4);
            }else if(types == 'category'){
                var datastr4 = '<select class="left addFocus-sele w120" localrequired="" name="data_4" id="data_4"><?php foreach ($category_list as $one) { if(!empty($one["child"])){echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';foreach ($one["child"] as $two) { echo '<option value="'.$two["gc_id"].'">--'.$two["gc_name"].'</option>'; } }else{echo '<option value="'.$one["gc_id"].'">'.$one["gc_name"].'</option>';}}?></select>';

                $('#data_4').replaceWith(datastr4);
            }else{
                var datastr4 = '<input type="text" class="com-inp1 radius3 boxsizing left addFocus-inp" name="data_4" id="data_4" localrequired="">';
                $('#data_4').replaceWith(datastr4);
            }
        });

        var uploader1 = new plupload.Uploader({
            runtimes: 'html5,html4,flash,silverlight',
            browse_button: 'img_file_1',
            url: "{:U('Upload/common')}",
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
                    uploader1.start();
                },
                UploadProgress: function (up, file) {
                 $("#img_file_1").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
                },
                FileUploaded: function (up, file, res) {
                    var resobj = eval('(' + res.response + ')');
                    if(resobj.status){
                        $("#img_file_1").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
                        $("#img_file_1").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
                    }
                },
                Error: function (up, err) {
                    alert('err');
                }
            }
        });
        uploader1.init();

        var uploader2 = new plupload.Uploader({
            runtimes: 'html5,html4,flash,silverlight',
            browse_button: 'img_file_2',
            url: "{:U('Upload/common')}",
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
                    uploader2.start();
                },
                UploadProgress: function (up, file) {
                 $("#img_file_2").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
                },
                FileUploaded: function (up, file, res) {
                    var resobj = eval('(' + res.response + ')');
                    if(resobj.status){
                        $("#img_file_2").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
                        $("#img_file_2").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
                    }
                },
                Error: function (up, err) {
                    alert('err');
                }
            }
        });
        uploader2.init();

        var uploader3 = new plupload.Uploader({
            runtimes: 'html5,html4,flash,silverlight',
            browse_button: 'img_file_3',
            url: "{:U('Upload/common')}",
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
                    uploader3.start();
                },
                UploadProgress: function (up, file) {
                 $("#img_file_3").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
                },
                FileUploaded: function (up, file, res) {
                    var resobj = eval('(' + res.response + ')');
                    if(resobj.status){
                        $("#img_file_3").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
                        $("#img_file_3").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
                    }
                },
                Error: function (up, err) {
                    alert('err');
                }
            }
        });
        uploader3.init();

        var uploader4 = new plupload.Uploader({
            runtimes: 'html5,html4,flash,silverlight',
            browse_button: 'img_file_4',
            url: "{:U('Upload/common')}",
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
                    uploader4.start();
                },
                UploadProgress: function (up, file) {
                 $("#img_file_4").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__PUBLIC__/img/loading.gif');
                },
                FileUploaded: function (up, file, res) {
                    var resobj = eval('(' + res.response + ')');
                    if(resobj.status){
                        $("#img_file_4").parents('.uploadbox-li').find('[shopdz-action="upload_image"]').attr("src",'__ATTACH_HOST__/'+resobj.data);
                        $("#img_file_4").parents('.uploadbox-li').find('[shopdz-action="upload_value"]').val(resobj.data);
                    }
                },
                Error: function (up, err) {
                    alert('err');
                }
            }
        });
        uploader4.init();
    })
</script>
</if>