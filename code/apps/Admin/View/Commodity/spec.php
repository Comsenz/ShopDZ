<div class="tipsbox">
	<div class="tips boxsizing radius3">
		<div class="tips-titbox">
			<h1 class="tip-tit"><i class="tips-icon-lamp"></i>操作提示</h1>
			<span class="open-span span-icon"><i class="open-icon"></i></span>
		</div>
	</div>
	<ol class="tips-list" id="tips-list">
		<li>1.所有商品规格管理。</li>
		<li>2.可以对规格进行添加、编辑、删除操作。</li>
		<li>3.有商品的规格不允许删除。</li>
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
            <h1 class="table-tit left boxsizing">规格列表</h1>
            <ul class="operation-list left">
                <li class="add-li"  title="添加规格" ><a  href="{:U('Commodity/spec_add')}"><span><i  class="operation-icon add-icon"></i></a></span></li>
                <li class="refresh-li"><a  href="{:U('Commodity/spec')}"><span><i  class="operation-icon refresh-icon"></i></span></a></li>
            </ul>
            <div class="search-box1 right">
            <form  method="get" class="form-horizontal"   action="<?php echo U('Commodity/spec');?>" name="Search_form"  >
                <div class="search-boxcon boxsizing radius3 left">
                    <select  id="field" name="field"  class="sele-com1 search-sele boxsizing">
                		<option  <?php if($_GET['field'] == 'spec_name'){ echo  'selected="selected"';}?> value="spec_name">规格名称</option>
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
                        <th  width="200">操作</th>
                        <th  width="220">规格名称</th>
                        <th></th>
                    </tr>
                    
                </thead>
                <tbody>
                    <empty name="lists">
                        <tr class="tr-minH">
                            <td colspan="2">暂无数据！</td>
                            <td></td>
                        </tr>
                    <else />
                    <?php foreach($lists as $v){ ?>
                    <tr>
                        <td>
                            <div class="table-iconbox">
                                <?php if(!$v['built_in']){ ?>
                                <div class="edit-iconbox left edit-sele boxsizing marginR10">
                                    <a class="edit-word spec_delete_button  table-icon-a" href='javascript:;'  data-spec_id="<?php echo $v['spec_id']?>"><i class="table-com-icon table-dele-icon"></i><span class="gap">删除</span></a>
                                </div>
                                <?php }else{ ?>
                                <!-- 
                                <div class="edit-iconbox left edit-sele radius3 boxsizing">
                                    <span class="edit-word-none">删除</span>                                
                                </div> -->
                                <?php }  ?>
                                <div class="table-icon left setting-sele-par">
                                    <div class="setting-sele left radius3 boxsizing">
                                        <span class="setting-word"><i class="table-com-icon table-setting-icon"></i><span class="gap">设置</span></span>
                                        <span class="jtb-span-box boxsizing"><i class="jtb-span setting-jtb-icon"></i></span>
                                    </div>
                                    <ul class="setting-sele-con remind-layer">
                                        <?php if(!$v['built_in']){ ?>
                                        <li><a href="<?php echo U('Commodity/spec_edit',array('spec_id'=>$v['spec_id']));?>">编辑规格</a></li>
                                        <?php } ?>
                                        <li><a href="<?php echo  U('Commodity/spec_value',array('spec_id'=>$v['spec_id']));?>">查看规格值</a></li>
                                    </ul>
                                </div>
                               
                            </div>
                        </td>
                        <td><?php echo $v['spec_name']; ?></td>
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
        $('.spec_delete_button').click(function(){
    	    var spec_id = parseInt($(this).attr('data-spec_id'));
    	    if(typeof spec_id  !='number'){
    		    return;
    		}else{
    			showConfirm('您确定要删除该规格吗？',function(){
    			    $.get('<?php echo U('Commodity/spec_delete')?>',{"spec_id":spec_id},function(data){
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