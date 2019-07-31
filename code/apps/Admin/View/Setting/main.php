 <ul class="survey-tit">
      <li class="gross-box gross-income">
        <h6 class="gross-tit">总收入</h6>
        <p class="gross-num"><?php echo $count['amount']['all']; ?></p>
        <div class="gross-con">近30天下单金额&nbsp;<?php echo $count['amount']['30']; ?></div>
        <i class="gross-icon income-icon"></i>
      </li>
      <li class="gross-box gross-order">
        <h6 class="gross-tit">总订单数</h6>
        <p class="gross-num"><?php echo $count['num']['all']; ?></p>
        <div class="gross-con">近30天下单量&nbsp;<?php echo $count['num']['30']; ?></div>
        <i class="gross-icon order-icon"></i>
      </li>
      <li class="gross-box gross-member">
        <h6 class="gross-tit">总下单会员数</h6>
        <p class="gross-num"><?php echo $count['omember']['all']; ?></p>
        <div class="gross-con">近30天下单会员数&nbsp;<?php echo $count['omember']['30']; ?></div>
        <i class="gross-icon member-icon"></i>
      </li>
      <li class="gross-box  gross-member2">
        <h6 class="gross-tit">总会员数</h6>
        <p class="gross-num"><?php echo $count['amember']['all']; ?></p>
        <div class="gross-con">近30天新增会员数&nbsp;<?php echo $count['amember']['30']; ?></div>
        <i class="gross-icon member2-icon"></i>
      </li>
    </ul>
    <div class="plugBox">
    	<ul class="transverse-nav">
		    <li class="activeFour"><a href="javascript:;"><span>营销应用</span></a></li>
		</ul>
	    <!--插件-->
	    <ul class="plug-box">
			<?php foreach($plugin_list as $pk=>$pv){ ?>
	    	<li>
	    		<div class="plug-module">
				<a href=" {:U('Cms/plugins',array('module'=>$pv['code'],'controller'=>'admin','method'=>$pv['code']))}">
				<img src="<?php echo trim(SITE_URL,'/');?>/plugins/<?php echo $pv['code'];?>/<?php echo $pv['icon'];?>" />
				</a>
				</div>
	    		<p class="plug-word"><a href=" {:U('Cms/plugins',array('module'=>$pv['code'],'controller'=>'admin','method'=>$pv['code']))}">管理</a></p>
	    	</li>
			<?php }?>
	    </ul>
    	
    </div>
    
    
    <div class="iframeCon surveyIframe shadow-none">
	<div class="iframeMain shadow-none">
    	<div class="survey-tabcon">
    		<ul class="transverse-nav">
		        <li <?php if($search_arr['search_key'] == 'order_amount'){ echo 'class="activeFour"'; } ?> data-param="order_amount"><a href="javascript:;"><span>下单金额</span></a></li>
		        <li <?php if($search_arr['search_key'] == 'order_num'){ echo 'class="activeFour"'; } ?> data-param="order_num"><a href="javascript:;"><span>下单数量</span></a></li>
		        <li <?php if($search_arr['search_key'] == 'order_member'){ echo 'class="activeFour"'; } ?> data-param="order_member"><a href="javascript:;"><span>下单会员</span></a></li>
		        <li <?php if($search_arr['search_key'] == 'new_member'){ echo 'class="activeFour"'; } ?> data-param="new_member"><a href="javascript:;"><span>新增会员</span></a></li>
		      </ul>
		      <div class="survey-topcon">
		        <div class="tab-conbox <?php if($search_arr['search_key'] != 'order_amount'){ echo 'none'; } ?>">
		            <div id="order_amount" style=""></div>
		        </div>
		        <div class="tab-conbox <?php if($search_arr['search_key'] != 'order_num'){ echo 'none'; } ?>">
		            <div id="order_num" style=""></div>      
		        </div>
		        <div class="tab-conbox <?php if($search_arr['search_key'] != 'order_member'){ echo 'none'; } ?>">
		          <div id="order_member" style=""></div> 
		        </div>
		        <div class="tab-conbox <?php if($search_arr['search_key'] != 'new_member'){ echo 'none'; } ?>">
		            <div id="new_member" style=""></div>
		        </div>
		      </div>
    	</div>
      
        <div class="hot_goods_list">
          <div class="top-con radius5 white-shadow2 left margin-L1">
            <h2 class="top-tit boxsizing left-toptit">
              <p class="main-tit">单品销售排名</p>
              <p class="sub-tit">掌握30日内最热销的商品及时补货</p>
              <span class="top-span left-topspan"></span>
            </h2>
            <div class="table-div">
              <table class="survey-table">
                <tr>
                  <th width="65">排名</th>
                  <th colspan="2">
                    商品信息
                  </th>
                  <th width="90">销量</th>
                </tr>
                <?php foreach($hotgoods as $k => $v){ ?>
                  <tr>
                    <td><?php echo $k+1; ?></td>
                    <td width="60">
                      <a href="<?php echo $v['url']; ?>" target="_blank">
                        <img src="<?php echo $v['goods_image']; ?> " alt="" class="goodsImg"/>
                      </a>
                    </td>
                    <td class="text-l">
                      <a href="<?php echo $v['url']; ?>" target="_blank" class="goods-det">
                        <?php echo $v['goods_name']; ?>
                      </a>
                    </td>
                    <td><?php echo $v['goods_num']; ?></td>
                  </tr>
                <?php } ?>
              </table>
            </div>
          </div>
          <div class="top-con radius5 white-shadow2 left">
            <h2 class="top-tit boxsizing right-toptit">
              <p class="main-tit">单品浏览量排名</p>
              <p class="sub-tit">掌握30日内浏览量最高的商品及时补货</p>
              <span class="top-span right-topspan"></span>
            </h2>
            <div class="table-div">
              <table class="survey-table">
                <tr>
                  <th width="65">排名</th>
                  <th colspan="2">
                    商品信息
                  </th>
                  <th width="90">浏览量</th>
                </tr>
                <?php foreach($viewgoods as $k => $v){ ?>
                  <tr>
                    <td><?php echo $k+1; ?></td>
                    <td width="60">
                      <a href="<?php echo $v['url']; ?>" target="__blank">
                        <img src="<?php echo $v['goods_image']; ?> " alt="" class="goodsImg"/>
                      </a>
                    </td>
                    <td class="text-l">
                      <a href="<?php echo $v['url']; ?>" target="__blank" class="goods-det">
                        <?php echo $v['goods_name']; ?>
                      </a>
                    </td>
                    <td><?php echo $v['view_number']; ?></td>
                  </tr>
                <?php } ?>
              </table>
            </div>
          </div>
        </div>
    </div>
    </div>
    </div>
    <script type="text/javascript" src="__PUBLIC__/js/common2.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/bootstrap.min.js"></script>
    <script src="__PUBLIC__/js/moment.js" charset="UTF-8"></script>
    <script src="__PUBLIC__/js/moment-with-locales.js" charset="UTF-8"></script>
    <script src="__PUBLIC__/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script src="__PUBLIC__/admin/js/highcharts.js"></script>
    <script type="text/javascript">
      var data_arr = new Array();
      data_arr['order_num'] = <?php echo $stat_json['order_num'];?>;
      data_arr['order_amount'] = <?php echo $stat_json['order_amount'];?>;
      data_arr['order_member'] = <?php echo $stat_json['order_member'];?>;
      data_arr['new_member'] = <?php echo $stat_json['new_member'];?>;
      $(function(){
           var iframeConH = Math.max(document.body.scrollHeight,document.body.clientHeight);
           $('#myIframeId', window.parent.document).attr('height',iframeConH+10-1200);
           var search_key = '<?php echo $search_arr['search_key']; ?>';
           gethigtcharts(search_key);
          $(".transverse-nav>li").unbind("click").bind('click',function(){
              $(this).addClass("activeFour").siblings().removeClass();
              $($(".tab-conbox")[$(this).index()]).show().siblings().hide();
              var par = $(this).attr('data-param');
              gethigtcharts(par);
              reloadScroll();
          });

      });
      function gethigtcharts(keys){
          if($('#'+keys).html().length <= 0){
              $('#'+keys).highcharts(data_arr[keys]);
          }
      }
            
    </script>