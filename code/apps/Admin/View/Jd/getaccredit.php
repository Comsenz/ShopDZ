<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>jd授权</title>
</head>
<body>
	<a href="{:U('/Jd/getAccredit')}?shop_code=jd">授权</a>
	<p id="code">{$code}</p>
	<if condition="$code neq ''">
		<p id="token">已授权，正在获取token。。。</p>
	</if>
	<script>
		$(function(){
			if($('#code').html() != ''){
				$.ajax({
					url: "{:U('/Jd/getAccessToken')}",
					data:{shop_code:'jd',code:$('#code').html()},
					type:'get',
					dataType:'json',
					success:function(info){
						if(info['code'] == 0){
							alert(info['msg']);
							$('#token').html('token：'+info['data']['access_token']+'<br/>'+'有效期至：'+info['data']['validity_time']);
							$('#token').after('<p id="goods">开始查询商品信息。。。</p>');
							$.ajax({
								url: "{:U('/Jd/searchshop')}",
								data:{shop_code:'jd'},
								type:'get',
								dataType:'json',
								success:function(info){
									if(info['code'] == 0){
										alert(info['msg']);
										console.log(info);
										$('#goods').html('商品信息：'+info['data']);
									}else{
										alert(info['msg']);
									}
								}
							})
						}else{
							alert(info['msg']);
						}
					}
				})
			}
		});
	</script>
	
</body>
</html>

