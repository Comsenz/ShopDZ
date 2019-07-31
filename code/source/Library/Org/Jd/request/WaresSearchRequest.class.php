<?php
namespace Org\Jd\request;
/**
 * 检索商品
 */
class WaresSearchRequest{

	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "360buy.wares.search";
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
    	                        
	public function setCid($cid){
		$this->cid = $cid;
		$this->apiParas["cid"] = $cid;
	}

	public function getCid(){
	  return $this->cid;
	}

    private $startPrice;
    	                                                            
	public function setStartPrice($startPrice){
		$this->startPrice = $startPrice;
		$this->apiParas["start_price"] = $startPrice;
	}

	public function getStartPrice(){
	  return $this->startPrice;
	}

    private $endPrice;
    	                                                            
	public function setEndPrice($endPrice){
		$this->endPrice = $endPrice;
		$this->apiParas["end_price"] = $endPrice;
	}

	public function getEndPrice(){
	  return $this->endPrice;
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

    private $title;
    	                        
	public function setTitle($title){
		$this->title = $title;
		$this->apiParas["title"] = $title;
	}

	public function getTitle(){
	  return $this->title;
	}

    private $orderBy;
    	                                                            
	public function setOrderBy($orderBy){
		$this->orderBy = $orderBy;
		$this->apiParas["order_by"] = $orderBy;
	}

	public function getOrderBy(){
	  return $this->orderBy;
	}

    private $startTime;
    	                                                            
	public function setStartTime($startTime){
		$this->startTime = $startTime;
		$this->apiParas["start_time"] = $startTime;
	}

	public function getStartTime(){
	  return $this->startTime;
	}

    private $endTime;
    	                                                            
	public function setEndTime($endTime){
		$this->endTime = $endTime;
		$this->apiParas["end_time"] = $endTime;
	}

	public function getEndTime(){
	  return $this->endTime;
	}

    private $startModified;
    	                                                            
	public function setStartModified($startModified){
		$this->startModified = $startModified;
		$this->apiParas["start_modified"] = $startModified;
	}

	public function getStartModified(){
	  return $this->startModified;
	}

    private $endModified;
    	                                                            
	public function setEndModified($endModified){
		$this->endModified = $endModified;
		$this->apiParas["end_modified"] = $endModified;
	}

	public function getEndModified(){
	  return $this->endModified;
	}

    private $wareStatus;
    	                                                            
	public function setWareStatus($wareStatus){
		$this->wareStatus = $wareStatus;
		$this->apiParas["ware_status"] = $wareStatus;
	}

	public function getWareStatus(){
	  return $this->wareStatus;
	}

    private $fields;
    	                        
	public function setFields($fields){
		$this->fields = $fields;
		$this->apiParas["fields"] = $fields;
	}

	public function getFields(){
	  return $this->fields;
	}

    private $parentShopCategoryId;
    	                        
	public function setParentShopCategoryId($parentShopCategoryId){
		$this->parentShopCategoryId = $parentShopCategoryId;
		$this->apiParas["parentShopCategoryId"] = $parentShopCategoryId;
	}

	public function getParentShopCategoryId(){
	  return $this->parentShopCategoryId;
	}

    private $shopCategoryId;
    	                        
	public function setShopCategoryId($shopCategoryId){
		$this->shopCategoryId = $shopCategoryId;
		$this->apiParas["shopCategoryId"] = $shopCategoryId;
	}

	public function getShopCategoryId(){
	  return $this->shopCategoryId;
	}

    private $itemNum;
    	                        
	public function setItemNum($itemNum){
		$this->itemNum = $itemNum;
		$this->apiParas["itemNum"] = $itemNum;
	}

	public function getItemNum(){
	  return $this->itemNum;
	}

}