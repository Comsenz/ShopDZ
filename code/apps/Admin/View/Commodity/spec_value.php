<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>操作提示</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.所有商品规格值管理。</li>
		<li>2.可以对商品规格值添加、编辑、删除。</li>
		<li>3.有商品的规格值不允许删除。</li>
	</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
    <?php if(!empty($small_nav)){ ?>
    <ul class="transverse-nav">
        <?php foreach($small_nav  as $nav_key=>$nav_url){ ?>
         <li    <?php if($nav_key==$small_nav_key){ ?>class="activeFour" <?php } ?>  >
             <a href="<?php echo  $nav_url?>"><span><?php echo $nav_key;?></span></a>
         </li>
        <?php } ?>
    </ul>
    <?php } ?>
    <div class="white-bg">
        <div class="table-titbox">
            <div class="option">
                <h1 class="table-tit left boxsizing">规格值列表</h1>
                <ul class="operation-list left">
                    <li class="add-li"  title="添加规格值" ><a  href="<?php  echo U('Commodity/spec_value_add_form',array('spec_id'=>$_GET['spec_id'])); ?>"><span><i  class="operation-icon add-icon"></i></span></a></li>
                    <li class="refresh-li"><a  href="javascript:void(0)" onclick="window.location.reload()"><span><i  class="operation-icon refresh-icon"></i></span></a></li>
                </ul>
            </div>
            <div class="search-box1 right">
            <form  method="get" class="form-horizontal" action="<?php echo U('Commodity/spec_value');?>" name="Search_form"  >
                <div class="search-boxcon boxsizing radius3 left">
                    <select  id="field"  name="field"  class="sele-com1 search-sele boxsizing">
                    <option      <?php if($_GET['field'] == 'spec_value'){ echo  'selected="selected"';}?> value="spec_value">规格值</option>
                		<option  <?php if($_GET['field'] == 'spec_name'){ echo  'selected="selected"';}?> value="spec_name">所属规格</option>
                	</select>
                    <input type="text" name="q" value="<?php echo $_GET['q']; ?>" class="search-inp-con boxsizing"/>
                </div>
                <input type="submit"  value="搜索" class="search-btn right radius3"/>
            </form>
            </div>
        </div>
        
        <div class="comtable-box boxsizing">
            <table class="com-table">
                <thead>
                    <tr>
                        <th  width="180">操作</th>
                        <th  width="220">规格值名称</th>
                        <th  width="220">所属规格</th>
                        <th></th>
                    </tr>
                    
                </thead>
                <tbody>
                    <empty name="lists">
                        <tr class="tr-minH">
                            <td colspan="3">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
                    <?php foreach($lists as $v){ ?>
                    <tr>
                        <td>
                            <div class="table-iconbox">
                            <?php if(!$v['built_in']){ ?>
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a" href="<?php echo U('Commodity/spec_value_edit',array('spec_value_id'=>$v['spec_value_id'],'spec_id'=>$v['spec_id']));?>"><i class="table-com-icon table-edit-icon"></i><span class="gap">编辑</span></a>
                                </div>
                                <div class="edit-iconbox left edit-sele marginR10">
                                    <a class="edit-word table-icon-a spec_value_delete_button" href='javascript:;' data-spec_value_id="<?php echo $v['spec_value_id']?>" ><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                                <?php }else{ ?>
                                --
                                <?php } ?>
                            </div>
                        </td>
                        <td><?php echo $v['spec_value'];?></td>
						<td><?php echo $v['spec_name'];?></td>
                        <td></td>
                    </tr>
                    <?php } ?>
                    </empty>
                </tbody>
            </table>
        </div>
        {$page}
    </div>
</div>
</div>
<script type="text/javascript">
    $(function(){
        //添加会员提示
        $('.add-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,$(this).attr('title'));
        })
        $('.add-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.refresh-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'刷新');
        })
        $('.refresh-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.export-li').mousemove(function(){
            e=arguments.callee.caller.arguments[0] || window.event; 
            remindNeed($('.add-li'),e,'导出');
        })
        $('.export-li').mouseout(function(){
            $('.tip-remind').remove();
        });
        $('.spec_value_delete_button').click(function(){
    	    var spec_value_id = parseInt($(this).attr('data-spec_value_id'));
    	    if(typeof spec_value_id  !='number'){
    		    return;
    		}else{
    			showConfirm('您确定要删除该规格值吗？',function(){
    			    $.get('<?php echo U('Commodity/spec_value_delete')?>',{"spec_value_id":spec_value_id},function(data){
    			        if(data.status==1){
        			        showSuccess(data.info,function(){
        			            window.location.reload();
            			    });
        			    }else{
            			    showError(data.info);
            			}    
        			},'json')
    			});    
    		}
        });
    })
    
</script>