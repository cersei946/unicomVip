<?php
namespace app\index\controller;
use think\Db;
use think\Request;

class Join extends BaseController {
	
    public function index(){
	    //查询城市
		$where = array();
		$where['region_type'] = 1;
		$provinceList = Db::name('region')->where($where)->select();
		
		$this->assign("provinceList",$provinceList);
		return $this->fetch();
	}
	//城市
	function city(){
		$province_id 	= $this->request->param("id");
		
		$where 			= array();
		$where['parent_id']	= $province_id;
		
		return Db::name('region')->where($where)->order("id ASC")->select();
	}
	/**
	 * 保存
	 */
	function saves(){
		$data = array();
		$data['join_name'] = $this->request->param("join_name");
		$data['phone'] = $this->request->param("phone");
		$data['money'] = $this->request->param("money");
		$data['province_id'] = $this->request->param("province_id");
		$data['city_id'] = $this->request->param("city_id");
		$data['content'] = $this->request->param("content");
		$data['add_time'] = time();
		
		$res = Db::name('join')->insertGetId($data);
		$this->operRes($res);
	}
}