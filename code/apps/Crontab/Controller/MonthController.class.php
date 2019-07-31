<?php
namespace Crontab\Controller;
use Think\Controller;
class MonthController extends Controller {
    public function __construct(){
        parent::__construct();
        if(!IS_CLI){
            die('permisson dined');
        }
    }
    public function index(){
    }
}