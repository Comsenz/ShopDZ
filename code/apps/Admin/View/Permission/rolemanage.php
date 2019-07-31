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
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>角色名</th>
                                            <th>登陆权限</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($data_list as $val):?>
                                        <tr class="gradeX">
                                            <td><?=$val['id']?></td>
                                            <td class="center"><?=$val['name']?>
                                            </td>
                                            <td class="center"><?=empty($val['status'])?'<span style="color:red">允许</span>':'停用'?></td>
                                            <td>
                                            <a href="javascript:void(0)" rel-href="<?=U('/permission/addrole',array('id'=>$val['id']))?>" class="btn-xs btn-info add-role">编辑</a>
                                            <?php if(empty($val['status'])):?>
                                            <a href="<?=U('/permission/changestatus',array('id'=>$val['id'],'type'=>1))?>" class="btn-xs btn-success ">停用</a>
                                            <?php else:?>
                                            <a href="<?=U('/permission/changestatus',array('id'=>$val['id'],'type'=>0))?>" class="btn-xs btn-primary">允许</a>
                                            <?php endif?>
                                            <a href="javascript:void(0)" rel-href="<?=U('/permission/casting',array('id'=>$val['id']))?>" class="btn-xs btn-danger">关联用户</a>
                                            <a href="javascript:void(0)" rel-href="<?=U('/permission/privileges',array('id'=>$val['id']))?>" class="btn-xs btn-warning">关联节点</a>
                                            <!-- <a href="javascript:void(0)" rel-href="<?=U('/permission/delete',array('id'=>$val['id'],'class'=>'role'))?>" class="btn-xs btn-danger del-button">删除</a> -->
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                                <?=$page?>
                            </div>
                        </div>
                    </div>
                </div>
<script type="text/javascript">
    $(function(){
        $('.del-button').click(function(){
            layer.confirm('确认删除?',del_handle);
            var href = $(this).attr('rel-href');
            function del_handle(){
                window.location.href=href;
            }
        })
 
        $('.add-role').click(function(){
            var href = $(this).attr('rel-href');
           parent.layer.open({
                type: 2,
                shadeClose: true,
                title: '添加角色',
                closeBtn: [0, false],
                shade: [0.2, '#000'],
                border: [0],
                offset: ['20px',''],
                
                content:   [href,'no']
            }); 
        })
        $('.btn-danger').click(function(){
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
		
        $('.btn-warning').click(function(){
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