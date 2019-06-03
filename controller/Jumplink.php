<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Jumplink extends BaseController {
	//首页
    public function index(){
	    $post_data = $this->request->param();
	    $where = array();
	    $where['id'] = $post_data['id'];
	    //$number = Db::name('user_guanlian')->where($where)->value('number');
	    
	    //$where = array();
	    //$where['number'] = $number;

	    $mobile = Db::name('user_guanlian')->where($where)->value('mobile');

	    $where = array();
	    $where['mobile'] = $mobile;

	    $rs = Db::name('user_yingye')->where($where)->find();
	    
	    $this->assign("rs",$rs);
		return $this->fetch();
	}
}