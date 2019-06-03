<?php
namespace app\index\controller;
use think\Db;
use think\Request;
use think\paginator\driver\Bootstrap;

class News extends BaseController {
	//列表
	public function index(){
		//获取ID
		$id = $this->param("id");
		$parent_id = $this->param("parent_id");
		//获取数组
		$where = array();
		$where['parent_id'] = $id;
		$data = Db::name('news')->where($where)->order('id desc')->limit($limit)->select();
		foreach( $data as $key => $val )
		{
			$data[$key]['add_time'] = date('Y-m-d', $val['add_time']);
		}
		//分类
		$where = array();
		$where['parent_id'] = $parent_id;
		$nav = Db::name('channelclass')->where($where)->select();
		
		$this->assign("data",$data);
		$this->assign("nav",$nav);
		$this->assign("class_id",$parent_id);
		$this->assign("parent_id",$id);
		return $this->fetch();
	}
	//详情
	function view(){
		//获取ID
		$id = $this->param("id");
		$parent_id = $this->param("parent_id");
		$class_id = $this->param("class_id");
		//获取详情
		$info = Db::name('news')->find($id);
		$info['add_time'] = date('Y-m-d', $info['add_time']);
		
		//分类
		$where = array();
		$where['parent_id'] = $class_id;
		$nav = Db::name('channelclass')->where($where)->select();

		//上一条
		$last = Db::name('news')->where(" id > " . $id . " and parent_id = " . $parent_id)->order("id", "asc")->find();
		
		//下一条
		$next = Db::name('news')->where(" id < " . $id . " and parent_id = " . $parent_id)->order("id", "desc")->find();

		$this->assign("last",$last);
		$this->assign("next",$next);
		$this->assign("nav",$nav);
		$this->assign("info",$info);
		$this->assign("parent_id",$parent_id);
		$this->assign("class_id",$class_id);
		return $this->fetch();
	}
}
