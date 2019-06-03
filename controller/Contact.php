<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Contact extends BaseController {
	
    public function index(){
	    
		return $this->fetch();
	}
}