<?php

namespace app\index\controller;

use think\Db;

use think\Request;



class Coupons extends BaseController {

	

    public function index(){


		return $this->fetch();

	}




}