<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Recruit extends BaseController {
	
    public function index(){
	    $data = Db::name('recruit')->limit(5)->order('id desc')->select();
	    
	    $this->assign("data",$data);
		return $this->fetch();
	}
}