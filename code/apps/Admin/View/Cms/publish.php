<title>基本设置</title>
<link rel="stylesheet" href="__PUBLIC__/css/bootstrap.min.css" />
<link rel="stylesheet" href="__PUBLIC__/css/style.min862f.css" />
<link href="__PUBLIC__/css/plugins/switchery/switchery.css" rel="stylesheet">
<link href="__PUBLIC__/css/reset.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/common.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/style.css" rel="stylesheet"/>
<link href="__PUBLIC__/css/plugins/steps/jquery.steps.css" rel="stylesheet" />

    <!--content开始-->
	<div class="content"> 

	<form class="form-horizontal" id="add_goods_step1" autocomplete="off"  method="post">
	<input name="cms_id"   type="hidden"  value="<?=$data['article_id']; ?>"   > 
	<input name="form_submit"   type="hidden"  value="submit"   > 
	<input name="goods_common_id"   type="hidden"  value="<?php  echo intval($goods_common['goods_common_id']);?>"   > 
		<div class="jurisdiction">
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>文章名称：</dt>
				<dd class="left text-l">
					<input name="cms_name" class="com-inp1 radius3 boxsizing" value="<?= $data['article_title'];?>"  placeholder="请输入商品名称" type="text"> 
                    <p class="remind1">请输入文章名称</p>
				</dd>
			</dl>


			<dl class="juris-dl boxsizing" style='display:'>
				<dt class="left text-r boxsizing"><span class="redstar">*</span>商品分类：</dt>
				<dd class="left text-l">
					<select class="com-sele radius3 juris-dl-sele" name="article_class_id">
                        <option value="0" >请选择文章分类</option>
                        <?php if(!empty($category_list)){ ?>
                            <?php foreach($category_list as $one){  ?>
                                <?php if(!empty($one['child'])){ ?>
                                 <optgroup label="<?php  echo $one['gc_name'];?>">
                                     <?php foreach($one['child'] as $two){ ?>
                                         <option  <?php if($goods_common['gc_id']==$two['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $two['gc_id'];?>" >&nbsp;<?php echo $two['gc_name']?></option>
                                     <?php } ?>
                                  </optgroup>
                                <?php }else{ ?>
                                <option   <?php if($goods_common['gc_id']==$one['gc_id']){echo 'selected="selected" ';} ?>   value="<?php echo $one['gc_id'];?>" ><?php echo $one['gc_name']?></option>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </select>
					<p class="remind1">请选择文章分类</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>文章排序：</dt>
				<dd class="left text-l">
					<input name="article_sort" class="com-inp1 radius3 boxsizing" value="<?= $data['article_sort'];?>"  placeholder="请输入商品名称" type="text"> 
                    <p class="remind1">请输入文章排序：</p>
				</dd>
			</dl>
			<dl class="juris-dl boxsizing">
				<dt class="left text-r boxsizing"><span class="redstar">*</span>文章内容：</dt>
				<dd class="left text-l">
					<script id="desc" name="cms_detail" type="text/plain"><?php echo htmlspecialchars_decode(stripslashes($data['article_content']));?></script>
					<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
                    <script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
                    <script type="text/javascript">
                        var ue = UE.getEditor('desc',{
                            'initialFrameWidth':"100%",
                            'initialFrameHeight':500,
							'toolbars':[[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'link', 'unlink', '|', 'simpleupload'
        ]],
                        	});
                    </script>
					<p class="remind1">请输入文章内容</p>
				</dd>
			</dl>
		</div>
		<div class="btnbox3">
		    <button class="btn1 radius3 btn-margin" name="next" value="1"  type="submit">保存</button>
		</div>
		</form>
	</div>
<!--content结束-->
<script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
<script type="text/javascript">
	
</script>