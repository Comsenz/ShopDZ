<?php
namespace Common\Service;
class FavoritesService {
	static function getFavorites($uid,$type=null,$page = 1,$prepage = 20) {
		$FavoritesModel  = D('Favorites');
		$data = $FavoritesModel->getFavorites($uid,$type,$page,$prepage);
		return $data;
	}
	
	static function getFavoritesCount($uid,$type=null) {
		$FavoritesModel  = D('Favorites');
		$data = $FavoritesModel->getFavoritesCount($uid,$type);
		return $data;
	}
	
	static function favorites($data) {
		$FavoritesModel  = D('Favorites');
		return $FavoritesModel->favorites($data);
	}
	
	static function delFavoritesById($uid,$id) {
		$FavoritesModel  = D('Favorites');
		return $FavoritesModel->delFavoritesById($uid,$id);
	}

	static function isFavoritesById_type($uid,$id,$type) {
		$FavoritesModel  = D('Favorites');
		return $FavoritesModel->isFavorites($uid,$id,$type);
	}
}