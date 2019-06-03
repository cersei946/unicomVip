<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class About extends BaseController {
	
    public function index(){
		$id = $this->param("id");
		$parent_id = $this->param("parent_id");
		
		$data = Db::name('channelclass')->find($id);

		$where = array();
		$where['parent_id'] = $parent_id;
		$nav = Db::name('channelclass')->where($where)->select();
		
		$this->assign("data",$data);
		$this->assign("nav",$nav);
		$this->assign("id",$id);
		return $this->fetch();
	}
}
