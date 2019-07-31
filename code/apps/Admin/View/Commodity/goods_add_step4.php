<ul class="release-tab">
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step1',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >1.&nbsp;基本信息</a></li>
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step2',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >2.&nbsp;规格设置</a></li>
	<li class="radius5"><a href="<?php echo  U('Commodity/goods_add_step3',array('goods_common_id'=>$goods_common['goods_common_id']));?>" >3.&nbsp;运费设置</a></li>
	<li class="activeRelease radius5">4.&nbsp;参数设置</li>
	<li class="radius5">5.&nbsp;发布成功</li>
</ul>
<!--内容开始-->
<div class="iframeCon">
	<ul class="transverse-nav">
	    <li class="activeFour"><a href="javascript:;"><span>参数设置</span></a></li>
	</ul>
	<div class="white-bg">
		<div class="comtable-box boxsizing">
			<div class="releaseThree-tabletit">
				<input type="button" id="param_add_btn" class="release-btn left search-btn radius3" value="新增参数"/>
			</div>
			<form class="form-horizontal" id="main_form" autocomplete="off"  action="<?php echo  U('Commodity/goods_add_step4');?>"  method="post">
             <input name="goods_common_id"   type="hidden"  value="<?php  echo intval($goods_common['goods_common_id']);?>"   > 
			<table class="com-table releaseThree-table" id="param_list_div">
					<tr>
						<th width="150">参数名称</th>
						<th width="732">参数值</th>
						<th width="120">排序</th>
						<th width="70">操作</th>
					</tr>
					<tr>
                       <td colspan="4" ><p class="remind1" style="">点击左上角“新增参数”按钮添加商品参数，如“材质，纯棉”等等，也可以不添加任何参数直接发布商品。</p></td>
                   </tr>
				<?php $param_num = 1;?>
                <?php if(!empty($param_list)){ ?>
                <?php foreach($param_list as $v){ ?>
            	<tr>
            	
            	        <td>
							<input name="param_list[{$param_num}][param_name]"  value="{$v['param_name']}"  type="text" class="com-inp1 radius3 boxsizing release-inp table-inp-relative para-inp1" placeholder="请输入参数名称"/>
						</td>
						<td>
							<input name="param_list[{$param_num}][param_value]"  value="{$v['param_value']}" type="text" class="com-inp1 radius3 boxsizing release-inp table-inp-relative para-inp2" placeholder="请输入参数值"/>
						</td>
						<td>
							<input name="param_list[{$param_num}][listorder]"   value="{$v['listorder']}"  type="text" class="com-inp1 radius3 boxsizing release-inp table-inp-relative para-inp3" placeholder="99"/>
						</td>
						<td class="release-icon">
							<i class="operation-icon op-dele-icon remove_btn"></i>
						</td>
                </tr>
               <?php $param_num ++;?>
               <?php } ?>
               <?php } ?>
			</table>
		</div>
		<div class="release-btnbox boxsizing">
		    <?php if($_GET['save']==1){ ?>
		    <a type="button" id="submit_button" class="btn1 submit_button radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
		    <a class="btn1 radius3 marginT10 " href="{:U('Commodity/goods_list')}">返回列表</a>
			<?php }else{  ?>
            <input type="button" shopdz-submittype="sell" id="base_setting" class="submit_button btn1 radius3 btn3-btnmargin" value="立即发布">
            <input type="button" shopdz-submittype="unsell" id="base_setting" class="submit_button btn1 radius3 marginT10" value="放入仓库">
            <?php } ?>
        </div>
        </form>
	</div>
</div>
<!--内容结束-->
<?php if($param_num==1){ ?>
<?php } ?>
<script>
$(function(){
    var   param_num =   <?php echo $param_num; ?>;
	 //添加按钮点击之后添加一行
    $('#param_add_btn').click(function(){
       var  html ='';
       html+=''
        +'<tr>'
       		+'<td>'
       			+'<input type="text" name="param_list['+param_num+'][param_name]"   class="com-inp1 radius3 boxsizing release-inp table-inp-relative para-inp1" placeholder="请输入参数名称"/>'
       		+'</td>'
       		+'<td>'
       			+'<input type="text"  name="param_list['+param_num+'][param_value]"    class="com-inp1 radius3 boxsizing release-inp table-inp-relative para-inp2" placeholder="请输入参数值"/>'
       		+'</td>'
       		+'<td>'
       			+'<input type="text" name="param_list['+param_num+'][listorder]"    value="'+(100-param_num)+'"  class="com-inp1 radius3 boxsizing release-inp table-inp-relative para-inp3" placeholder="99"/>'
       		+'</td>'
       		+'<td class="release-icon">'
       			+'<i class="operation-icon op-dele-icon remove_btn"></i>'
       		+'</td>'
       	+'</tr>';
       $('#param_list_div').append(html);
       param_num++;
    });
    $(document).on('click','.remove_btn',function(){
        $(this).parents('tr').remove();
    });

    $('.submit_button').click(function(){
//        flag=checkrequire('main_form');
    $.post($('#main_form').attr('action'),$('#main_form').serialize()+'&'+$(this).attr('shopdz-submittype')+'=1',function(data){
    	if(data.status==1){
        	showSuccess(data.info,function(){
        		<?php if($_GET['save']==1){ ?>
            	window.location.href = '<?php echo U('Commodity/goods_list');?>' ;    
            	<?php }else{ ?>
                window.location.href = '<?php echo U('Commodity/goods_add_step5',array('goods_common_id'=>$goods_common['goods_common_id']));?>' ;
        	    <?php } ?>
        	   
            });
        }else{
             showError(data.info);
        }
    },'json');
});
});
</script>

