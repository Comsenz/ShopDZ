<style>
.tr_hide{display:none}
</style>           
		   <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>角色详细<small></small></h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="col-lg-8">
                                    <a href="javascript:void(0)" class="btn btn-success btn-rounded add-role" rel-href="<?=U('/permission/addrole')?>">添加角色</a>
                                </div>
                                <div class="col-lg-8">　</div>
                                <table class="table table-striped table-bordered table-hover dataTables-example">
								<form action="<?=U('/permission/addnode')?>" method="post">
                               
                                    <tbody>
                                    <?php foreach($data_list as $val=>$v):?> 
									<tr class="gradeX tr_toggle" data-col="<?=$val?>"><td colspan='7'> 
										<?=$val?>  <input class="addcontroller" name="<?=$val?>" value="<?=$rbacnode[$val][$val]['name_cn']?>" index="<?=$rbacnode[$val][$val]['id']?>" rel-href="<?=U('/permission/addcontroller')?>"   />
										排序<input   name="sort[<?=$rbacnode[$val][$val]['id']?>]" value="<?=$rbacnode[$val][$val]['sort']?>" index="<?=$rbacnode[$val][$val]['id']?>" rel-href="<?=U('/permission/addcontroller')?>"   />
										classname<input  name="rbac[classname][<?=$val?>]" value="<?=$rbacnode[$val][$val]['classname']?>" index="<?=$rbacnode[$val][$val]['id']?>" rel-href="<?=U('/permission/addcontroller')?>"   />
									</td></tr>
									<?php foreach($v as $method):?>
                                       <tr  class="gradeX stats_<?=$val?> tr_hide">
											<td><?=$rbacnode[$val][$method]['id']?></td>
										   <td><?=$method?></td>
											<td>
											<?php if($rbacnode[$val][$method]['id']) { ?>
											<input name="rbac[name][<?=$val?>][<?=$rbacnode[$val][$method]['id']?>]" value="<?=$rbacnode[$val][$method]['name_cn']?>"  />
											<?php }else {?>
												<input name="rbac[name][<?=$val?>][<?=$method?>]"  value="<?=$method?>" />
											<?php }?>
											</td>
											
											<td>
											排序<input   name="sort[<?=$rbacnode[$val][$method]['id']?>]" value="<?=$rbacnode[$val][$method]['sort']?>"  />
											</td>
                                            <td>
											
										<?php if($rbacnode[$val][$method]['id']) { ?>
												<input 	name="rbac[edit][<?=$val?>][]"  type="checkbox" value="<?=$rbacnode[$val][$method]['id']?>" checked="checked" />
											<?php }else {?>
												<input name="rbac[news][<?=$val?>][]"  type="checkbox" value="<?=$method?>" />
											<?php }?>
											</td>
											<td>设为导航
											<?php  if(!empty($rbacnode[$val][$method]['asmenu'])) {?> 	<input rel-href="<?=U('/permission/addmenu')?>" class="menu"     type="checkbox"  value="<?=$rbacnode[$val][$method]['id']?>" checked="checked" />  <?php }else {?>
											<input rel-href="<?=U('/permission/addmenu')?>" class="menu"     type="checkbox"  value="<?=$rbacnode[$val][$method]['id']?>" />
											<?php }?>
											</td>
											<td>关联<input type="text" name="siblings[<?=$rbacnode[$val][$method]['id']?>]"  value="<?=$rbacnode[$val][$method]['siblings']?>" /></td>
                                        </tr>
                                    <?php endforeach;?>
                                    <?php endforeach;?>
									<tr>
									<td></td>
									<td colspan=3>
									<input type="submit" name="保存" value="保存" />
								 
									</td></tr>
                                    </tbody>
									</form>
                                </table>
                                <?=$page?>
                            </div>
                        </div>
                    </div>
                </div>
<script type="text/javascript">

    $(function(){
        $('.menu').click(function(){
			var _val = $(this).val();
            var href = $(this).attr('rel-href');
			$.post(href,{id:_val},function (data) {
				var obj = eval('('+data+')');
				if(obj.code <0){
					alert(obj.msg);
				}
			});
        });
		$('.addcontroller').blur(function() {
			var _val = $(this).val();
			var _name = $(this).attr('name');
			var _this =this;
			var _id = $(this).attr('index');
		 
            var href = $(this).attr('rel-href');
			$.post(href,{_name:_val,name:_name,id:_id},function (data ) {
					var obj = eval('('+data+')');
				
					if(obj.id >='0')
						$(_this).attr('index',obj.id);
			});
		});
		$('.tr_toggle').click(function() {
			var con = $(this).attr('data-col');
			 $(".stats_"+con).toggleClass("tr_hide");
		});
		

        $('.add-role').click(function(){
            var href = $(this).attr('rel-href');
            $.layer({
                type: 2,
                shadeClose: true,
                title: false,
                closeBtn: [0, false],
                shade: [0.8, '#000'],
                border: [0],
                offset: ['20px',''],
                area: ['500px', ($(window).height() - 100) +'px'],
                iframe: {src: href}
            }); 
        })

    });
</script>