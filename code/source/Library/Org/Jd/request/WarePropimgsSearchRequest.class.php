<?php
namespace Org\Jd\request;
/**
 * 根据商品ID获取商品图片
 */
class WarePropimgsSearchRequest{

	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "360buy.ware.propimgs.search";
	}
	
	public function getApiParas(){
		return json_encode($this->apiParas);
	}
	
	public function check(){
		
	}
	
	public function putOtherTextParam($key, $value){
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
      	                                                            
	public function setWareId($wareId){
		$this->wareId = $wareId;
		$this->apiParas["ware_id"] = $wareId;
	}

	public function getWareId(){
	  return $this->wareId;
	}

    private $page;
    	                        
	public function setPage($page){
		$this->page = $page;
		$this->apiParas["page"] = $page;
	}

	public function getPage(){
	  return $this->page;
	}

    private $pageSize;
    	                                                            
	public function setPageSize($pageSize){
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}

	public function getPageSize(){
	  return $this->pageSize;
	}

    private $fields;
    	                        
	public function setFields($fields){
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}

	public function getFields(){
	  return $this->fields;
	}

}