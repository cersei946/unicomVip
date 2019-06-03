<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Index extends BaseController {
	//首页
    public function index(){
	    
		return $this->fetch();
	}
}