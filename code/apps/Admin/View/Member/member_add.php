<!--内容开始-->
<div class="tipsbox radius3">
    <div class="tips boxsizing radius3">
        <div class="tips-titbox">
            <h1 class="tip-tit"><i class="tips-icon-lamp"></i>{$Think.lang.operation_tips}</h1>
            <span class="open-span span-icon"><i class="open-icon"></i></span>
        </div>
    </div>
   	<ol class="tips-list">{$Think.lang.memberadd_tips_content}</ol>
</div>
<div class="iframeCon">
<div class="iframeMain">
	<ul class="transverse-nav">
		<li class="activeFour"><a  href="#"><span><if condition="$info.member_id eq ''">{$Think.lang.member_add}<else/>{$Think.lang.member_edit}&nbsp;&nbsp;{$info.member_mobile}</if></span></a></li>
	</ul>
	<div class="white-bg">
		<div class="tab-conbox">
		<form method="post" class="form-horizontal" name="memberForm" id="memberForm" action="{:U('Member/add',array('area_id',$info.area_id))}" enctype="multipart/form-data">
<input type="hidden" name="member_id" value="{$info.member_id}"/>
<input type="hidden" value="submit" name="form_submit">
			<div class="jurisdiction boxsizing">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.member_name}：</dt>
					<dd class="left text-l">
					<if condition="$info.member_id eq ''">
						<input type="text" name="member_username" class="com-inp1 radius3 boxsizing" localrequired="" value="{$info.member_username}" readonly />
						<p class="remind1">{$Think.lang.member_name_tips}</p>
					<else/>
			            <input name="member_username" class="form-control" type="hidden" value="{$info.member_username}">
			            <p class="form-control-static">{$info.member_username}</p>
					
			        </if>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.member_mobile}：</dt>
					<dd class="left text-l">
						<input type="text" name="member_mobile" class="com-inp1 radius3 boxsizing"   value="{$info.member_mobile}" />
						<p class="remind1">{$Think.lang.member_mobile_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.member_psw}：</dt>
					<dd class="left text-l">
						<input type="password" name="member_passwd" class="com-inp1 radius3 boxsizing" <?php if (!$info['member_id']){ ?>localrequired=""<?php } ?>/>
						<p class="remind1">{$Think.lang.member_psw_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.member_psw_again}：</dt>
					<dd class="left text-l">
						<input type="password" name="confirm_password" class="com-inp1 radius3 boxsizing" <?php if (!$info['member_id']){ ?>localrequired=""<?php } ?>/>
						<p class="remind1">{$Think.lang.member_psw_again_tips}</p>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.allow_login}：</dt>
					<dd class="left text-l">

                        <div class="switch-box">
                            <input type="checkbox" name="member_state" id="switch-radio" class="switch-radio" <if condition="$info['member_state']">checked="true" </if>/>
                            <span class="switch-half switch-open">{$Think.lang.yes}</span>
                            <span class="switch-half switch-close close-bg">{$Think.lang.no}</span>
                        </div>
                    </dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.nick_name}：</dt>
					<dd class="left text-l">
						<input type="text" name="member_truename" value="{$info.member_truename}" class="com-inp1 radius3 boxsizing"/>
					</dd>
				</dl>
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.member_default_avatar}：</dt>
					<dd class="left text-l">
						<div class="input-group">
                            <span class="previewbtn"><img src="__PUBLIC__/admin/images/imgGray.png" class=" viewimg view_img" url="<?php echo $info['member_avatar']?'__ATTACH_HOST__'.$info['member_avatar']:'';?>"/></span>
                            <input type="text" name="member_avatar" id="member_avatar" value="{$info.member_avatar}" value="" class="form-control upload-inp com-inp1 radius3 boxsizing">
                            <input type="file"   id="avatar_file" class="form-control" style="display: none;">
                            <span class="input-group-btn left">
                                <input type="button" onclick="$('input[id=avatar_file]').click();"  value="选择文件" class="upload-btn search-btn"/>
                            </span>
                        </div>
                      
						<p class="remind1">{$Think.lang.default_avatar_tips}</p>
					</dd>
				</dl>
				<if condition="$info.member_id neq ''">
				<dl class="juris-dl boxsizing">
					<dt class="left text-r boxsizing">{$Think.lang.member_points}：</dt>
					<dd class="left text-l">
						<p>{$info.member_points|default="0"} {$Think.lang.points}</p>
					</dd>
				</dl>
				</if>
			</div>
			<div class="btnbox3 boxsizing">
				<a type="button" id="member_subbtn" class="btn1 radius3 marginT10  btn3-btnmargin">{$Think.lang.submit_btn}</a>
				<a class="btn1 radius3 marginT10" href="{:U('member/lists')}">{$Think.lang.return_list}</a>
			</div>
		</form>
		</div>
	</div>
</div>	
</div>	
<script type="text/javascript">
$(function(){
    var uploader = new plupload.Uploader({
        runtimes: 'html5,html4,flash,silverlight',
        browse_button: 'avatar_file',
        url: "{:U('Member/upload_avatar')}",
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
                uploader.start();
            },
            UploadProgress: function (up, file) {
           	 $("#avatar_view").attr("src",'__PUBLIC__/img/loading.gif');
            },
            FileUploaded: function (up, file, res) {
                var resobj = eval('(' + res.response + ')');
                if(resobj.status){
                    $("#avatar_view").attr("src",'__ATTACH_HOST__/'+resobj.data);
                    $("#member_avatar").val(resobj.data);
                }
            },
            Error: function (up, err) {
                alert('err');
            }
        }
    });
    uploader.init();
});

	var settting = <?= $setting?>;
	$(function(){
		$(document).posi({
			class:"view_img",
			default_img:settting.shop_member
		});
    
     //点击关闭图片预览 
    $('.view-close').bind('click',function(){
		$(this).parents('.viewdiv').css('display','none');
	})
	function Size(wid,heig){
		$('.viewdiv').find('.view-img').css({'width':wid,'height':heig});
		$('.viewdiv').css({'width':wid+24,'height':heig+24});
	}
	function Posi1(event){
		var x =++event.pageX+'px';
        var y =++event.pageY+'px';
        $('.viewimgbtn1').parents('.input-group').next().toggle();
        $('.viewimgbtn1').parents('.input-group').next().css("left",x);
        $('.viewimgbtn1').parents('.input-group').next().css("top",(y-70)+'px');
	}

	$('#member_subbtn').click(function(){
        flag=checkrequire('memberForm');
        if(!flag){
           $('#memberForm').submit();
        }
    });
	
	$('.input-group').mouseenter(function(){
		$(this).find('.viewimg').attr('src','__PUBLIC__/admin/images/imgGreen.png');
	})
	$('.input-group').mouseleave(function(){
		$(this).find('.viewimg').attr('src','__PUBLIC__/admin/images/imgGray.png');
	})
	})
</script>
