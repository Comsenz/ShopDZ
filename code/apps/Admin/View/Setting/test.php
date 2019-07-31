<div class="hr-line-dashed"></div>
弹出提示框：
<button onclick="showDialog('提示','消息体')">只弹出框体</button>
<button onclick="showDialog('提示','消息体','',function(){
alert('fadfadfafda');
})">弹框且回调</button>

<button onclick="showDialog('提示','消息体','','',{type:'error'})">弹错误类型框体</button>
<button onclick="showDialog('提示','消息体','','',{type:'warning'})">弹警告类型框体</button>
<button onclick="showDialog('提示','消息体','','',{type:'info'})">弹提示类型框体</button>
<button onclick="showDialog('提示','消息体','','',{type:'info',timer:2000,jumpurl:'http://www.baidu.com'})">弹框并定时跳转</button>

<div class="hr-line-dashed"></div>
弹出确认框：
<button onclick="showDialog('提示','消息体','confirm')">弹删除确认框体</button>
<button onclick="showDialog('提示','消息体','confirm',function(isConfirm){
        if(isConfirm){
            alert('ok');
        }else{
            alert('no');
        }
})">弹删除确认框体并回调</button>

<div class="hr-line-dashed"></div>
弹出提示框：
<button onclick="showDialog('提示','消息体','tip','',{type:'success'})">弹提示类型框体</button>
<div class="hr-line-dashed"></div>
显示时间引入文件：__PUBLIC__/js/plugins/layer/laydate/laydate.js
<script src="__PUBLIC__/js/plugins/layer/laydate/laydate.js"></script>
<div class="hr-line-dashed"></div>
显示时间：
<input readonly class="form-control layer-date" id="hello1" name="starttime">
<label class="laydate-icon inline demoicon" onclick="showlaydate('hello1',0);"></label>
<div class="hr-line-dashed"></div>
显示可选范围时间：
<input readonly class="form-control layer-date" id="hello2" name="starttime">
<label class="laydate-icon inline demoicon" onclick="showlaydate('hello2',2,4);"></label>

