<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use think\Request;

class BaseController extends Controller
{
    protected $title = "艺麟盛世";
    protected $currUrl;
    protected $backUrl;
    protected $request;
    protected $userid = 0;

    /**
     * 检查管理员登录
     * @return [type] [description]
     */
    protected function checklogin(){
        $this->userid         = Session::get('manager_userid')?:0;
        // dump($this->userid);
        if($this->userid == 0){
            return $this->redirect(url('login/index'));
            exit;
        }
    }
	/**
     * 构造方法
     * @access public
     * @param Request $request Request 对象
     */
    public function __construct()
    {
    	parent::__construct();
    	
		//导航
    	$where = array();
    	$where['parent_id'] = 0;
    	$channelclass = Db::name('channelclass')->where($where)->select();
    	//友情链接
    	$link = Db::name('link')->select();
    	//系统参数
    	$config = Db::name('config')->find(1);
		
		$this->assign("config",$config);
    	$this->assign("link",$link);
    	$this->assign("channelclass",$channelclass);
	    $this->request =$this->app['request'];
    }
    /**
     * 获取参数
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function param($name){
        return $this->request->param($name);
    }
    /**
     * 获取完整的URL
     * @return [type] [description]
     */
    public function geturl($urlencode=true){
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);

        $returl = $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;

        return $urlencode ? urlencode($returl) : $returl;
    }
    /**
     * 成功直接返回URL
     * @param  [type] $res [description]
     * @return [type]      [description]
     */
    public function operRes($res){
        if($res){
            // dump($this->backUrl);exit;
            if($this->backUrl == "javascript:history.go(-1);" || empty($this->backUrl))
                $this->backUrl = $_SERVER['HTTP_REFERER'];

            $this->redirect($this->backUrl);
        }else{
            $this->error("操作失败");
        }
    }
    /**
     * 输出json格式数据
     * @param  [type] $res [description]
     * @return [type]      [description]
     */
    public function jsonRes($res){
        $result = array();
        if($res){
            $result['code']         = 0;
            $result['message']      = "操作成功";
        }else{
            $result['code']         = 1;
            $result['message']      = "操作失败";
        }
        return json_encode($result);
    }
    /**
     * 这里为通用的上传文件
     * @return [type] [description]
     */
    public function uploadfile(){
        $filename   = $this->request->param("upcontrol")?:"file";
        // 这里的userid只记录注册用的ID
        // 后台为0
        $userid     = 0;

        $result     = resm::upload($filename,$userid);
        return json_encode($result);
    }
 	/**
 	 * 获取limit参数
 	 * @param  [type] $page [description]
 	 * @param  [type] $size [description]
 	 * @return [type]       [description]
 	 */
 	protected static function getLimit($page,$size){
 		if($page > 0 && $size){
 			$start  = ($page-1)*$size;
 			return $start.",".$size;
 		}
 		return "";
 	}
}