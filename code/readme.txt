1、必须安装在域名根目录下, 不能圈套圈套在某个目录下 否则会路径出错
安装需要host一个域名根目录下安装. apache配置一个虚拟主机 不能圈套在某个目录下
示例:
 http://www.xxx.com/index.php   正确
 http://www.xxx.com/shopdz/index.php   错误 (很多路径出错)

2、建议安装环境：php版本5.5 + mysql版本5.6+（nginx|apache）
3、URL Rewrite需要支持pathinfo模式