<?php
namespace Common\Helper;

class PageHelper extends \Think\Page{
    private $config  = array(
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev'   => '<<',
        'next'   => '>>',
        'first'  => '1...',
        'last'   => '...%TOTAL_PAGE%',
        'theme'  => '<div class="pagination boxsizing">
					%PERPAGE%
					<ul class="page-list">
						 %FIRST_PAGE%
						 %PRE_PAGE% 
						 %LINK_PAGE% 
						%ALL_PAGE% 
						 %NEXT_PAGE% 
						 %LAST_PAGE% 
					</ul>
				</div>',
    );
    
	public $perpage = 20;
	private $p       = 'p';
	private $url     = ''; //当前链接URL
    private $nowPage = 1;
	public function __construct($totalRows, $listRows=20, $parameter = array()) {
		parent::__construct($totalRows, $listRows, $parameter );
		$this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
		$this->perpage  =  $this->listRows = (!empty($_GET['perpage']) ? $_GET['perpage'] : $listRows);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);
		$this->rollPage = 0;
	}
	private function url($page,$ext = array()){
		if(!empty($ext) )  {
		
			$this->parameter = array_merge($this->parameter,$ext);
		
			$this->url = U(ACTION_NAME, $this->parameter);
		}
		
		return str_replace(urlencode('[PAGE]'), $page, $this->url);
	}
	public function show() {
        if(intval($this->totalRows)<1) return '';
        /* 生成URL */
// 		if(!floor($this->totalRows/$this->listRows)) return;
        $this->parameter[$this->p] = '[PAGE]';
		$this->parameter['perpage'] = $this->perpage;
        $this->url = U(ACTION_NAME, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页临时变量 */
        $now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
		
        $up_page = 1/*$up_row > 0*/ ? '	<li class="prev-pagenation"><a href="'.$this->url($up_row).'" ><i class="prev-icon"></i></a></li>': '';
		$current = '<li>|</li><li class="page-jump radius3"><input type="text" class="page-num" id="page-num" value="'.$this->nowPage.'" /></li><li class="fontface2 page-common"><span>共</span>&nbsp;<span>'.$this->totalPages.'</span>&nbsp;<span>页</span></li>
							<li>|</li>';
        //下一页
        $down_row  = $this->nowPage + 1;
        if($down_row > $this->totalPages){
            $down_row  =  $this->totalPages;
        }
            
        
        $down_page = (1/*$down_row <= $this->totalPages*/) ? '<li class="next-pagenation"><a href="'.$this->url($down_row).'" ><i class="next-icon"></i></a></li>' : '';

        //第一页
        $the_first = '';
        $the_first = '<li class="first-pagenation"><a href="'.$this->url(1).'" ><i class="first-icon"></i></a></li>';

        //最后一页
        $the_end = '';
        $the_end = '<li class="last-pagenation"><a href="' . $this->url($this->totalPages) . '"><i class="last-icon"></i></a></li>';
        
        $all_page =    '<li class="all-pagenation">'.$this->totalPages.'页</li>';
		$show_start = $this->nowPage * $this->perpage - $this->perpage;
		$show_end = $this->nowPage * $this->perpage;
		$perpage = '<div class="eachPage-most left">
						<label for="" class="left">每页最多显示</label>
						<div class="most-box radius3 boxsizing left">
							<input type="text" name="perpage"  value="'.intval($this->perpage).'" readonly="readonly" class="most-inp left"/>
							<span class="most-span left"><i class="most-icon"></i></span>
						</div>
						<ul class="most-sele-con layer-shadow radius3">
							<li  '.($this->perpage==10 ? 'class="most-active-sele"': '').' ><a  href="'.$this->url(1,array('perpage'=>10)).'">10</a></li>
							<li  '.($this->perpage==20 ? 'class="most-active-sele"': '').' ><a  href="'.$this->url(1,array('perpage'=>20)).'">20</a></li>
							<li  '.($this->perpage==30 ? 'class="most-active-sele"': '').' ><a  href="'.$this->url(1,array('perpage'=>30)).'">30</a></li>
							<li  '.($this->perpage==40 ? 'class="most-active-sele"': '').' ><a  href="'.$this->url(1,array('perpage'=>40)).'">40</a></li>
						</ul>
					</div>';
		              
		$link_page  =  '<li class="now-pagenation"><input type="text"  id="page_num" value="'.intval($this->nowPage).'" class="now-inp radius3"/></li><script type="text/javascript">
								document.onkeydown=function(event){ 
									e = event ? event :(window.event ? window.event : null); 
									if(e.keyCode==13){
										aa ="'.$this->url('###',array('perpage'=>$this->perpage)).'";
										page = $("#page_num").val();
										if(isNaN(page)){ 
											alert("只能输入数字");
											$("#page-num").focus();
											return;
										}
										href = aa.replace("###",page);
										
										window.location.href = href;
									}
								}
								</script>';
        //替换分页内容
        $page_str = str_replace(
            array('%PERPAGE%', '%FIRST_PAGE%', '%PRE_PAGE%',' %LINK_PAGE%', '%ALL_PAGE%', ' %NEXT_PAGE%', '%LAST_PAGE%'),
            array($perpage, $the_first, $up_page,$link_page,$all_page, $down_page, $the_end),
            $this->config['theme']);
//            p(array('',$the_first, $up_page,'','', $down_page, $the_end),1);
//         p(array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%','%CURRENT%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%','%BOTTOM%'));
//         p( array($this->config['header'], $this->nowPage, $up_page,$current, $down_page, $the_first, $link_page, $the_end, $this->totalRows,$bottom));
//         p($this->config['theme'],1);
        return  $page_str;
        
        
        
    }
	
	function isSelectEd($perpage) {

		return $this->perpage == $perpage ? "selected" : '';
	}
}