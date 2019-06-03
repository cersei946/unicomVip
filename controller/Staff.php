<?php
namespace app\index\controller;
use think\Db;
use think\facade\Env;
use think\Request;

class Staff extends BaseController {
	//首页
    const QRCODE = QRcode;

    public function index(){
	    $post_data = $this->request->param();
	    $where = array();
	    $where['mobile'] = $post_data['phone'];
		//查询
	    $rs = Db::name('user_yingye')->where($where)->find();
		if($rs['qrcode'] == ''){
			require_once  Env::get('EXTEND_PATH'). 'phpqrcode/phpqrcode.php';
	        $qr = new \QRcode();
	        $filename = 'code/'.$rs['url_number'].'.png';
	        //生成二维码
	        $qr::png($rs['push_url'],$filename,'L',5,2);
			imagecreatefromstring(file_get_contents($filename));
			
	  		$xiugai = array();
	  		$xiugai['qrcode'] = $filename;
	  		//入库
	  		Db::name('user_yingye')->where("id",$rs['id'])->update($xiugai);
			
	  		$rs['qrcode'] = $filename;
		}
		
		$this->assign("rs",$rs);
		return $this->fetch();
	}
	//登录
	function login(){
		$post_data = $this->request->param();
		
		$where = array();
		$where['mobile'] = $post_data['phoneNumber'];
		//查询验证码
		$rs = Db::name('message')->where($where)->order("id desc")->find();
		
		if($post_data['verCode'] == $rs['code']){	//判断验证码是否一致
			$data = array();
			$data['mobile'] = $post_data['phoneNumber'];
			
			//查询手机号是否存在 存在就不需要填写邀请码了。
			if(Db::name('user_yingye')->where('mobile', '=', $post_data['phoneNumber'])->find()){
				$res = array();
				$res['code'] = 202;
				$res['message'] = '您的手机号已存在请直接登录';
				
				return json($res);
			}else{
				//不存在则添加
				//拿着邀请码去查询城市
				$city = Db::name('city_code')
					->where('code', '=', $post_data['inviteNumber'])
					->find();
				if(!$city){
					$res = array();
					$res['code'] = 204;
					$res['message'] = '邀请码输入错误';
					
					return json($res);
				}
				//得到城市名称
				$data['city'] = $city['city_name'];
				$data['code'] = $post_data['inviteNumber'];
				$code = substr($post_data['inviteNumber'],5);
				//随机数   去芬芳接口生成链接
				$code_suiji = rand(1000, 9998);
				$url_number = $code .''. time() .''. $code_suiji; //拼接17位随机数 00115590168069221 城市+当前时间戳+4位随机数
				$url = $this->fangxaing($url_number);
				$data['url_number'] = $url_number;
				$data['url'] = $url['data']['url'];	//链接生成 成功
				$data['push_url'] = 'http://v.ocoun.com/?number='.$url_number;
				$data['add_time'] = time();
				
				$res = Db::name('user_yingye')->insertGetId($data);
				if($res){
					$res = array();
					$res['code'] = 200;
					$res['message'] = '成功';
					
					return json($res);
				}
			}
		}else{
			$res = array();
			$res['code'] = 201;
			$res['message'] = '验证码错误';
			
			return json($res);
		}
	}
	//营业员登录
	function login_nocode(){
		$post_data = $this->request->param();
		
		$where = array();
		$where['mobile'] = $post_data['phoneNumber'];
		//查询验证码
		$rs = Db::name('message')->where($where)->order("id desc")->find();
		
		if($post_data['verCode'] == $rs['code']){	//判断验证码是否一致
			$data = array();
			$data['mobile'] = $post_data['phoneNumber'];
			
			//查询手机号是否存在 存在就不需要填写邀请码了。
			if(Db::name('user_yingye')->where('mobile', '=', $post_data['phoneNumber'])->find()){
				$res = array();
				$res['code'] = 200;
				$res['message'] = '成功';
				
				return json($res);
			}
		}else{
			$res = array();
			$res['code'] = 201;
			$res['message'] = '验证码错误';
			
			return json($res);
		}
	}
	//判断是否存在
	function phone(){
		$post_data = $this->request->param();
		$where = array();
		$where['mobile'] = $post_data['phoneNumber'];
		$res = Db::name('user_yingye')->where($where)->find();
		if($res){
			$res = array();
			$res['code'] = 200;
			$res['message'] = '存在';
			
			return json($res);
		}else{
			$res = array();
			$res['code'] = 201;
			$res['message'] = '不存在';
			
			return json($res);
		}
	}
	function user_phone(){
		$post_data = $this->request->param();
		$where = array();
		$where['number'] = $post_data['phoneNumber'];
		$res = Db::name('user_guanlian')->where($where)->find();
		if($res){
			$res = array();
			$res['code'] = 200;
			$res['message'] = '存在';
			
			return json($res);
		}else{
			$res = array();
			$res['code'] = 201;
			$res['message'] = '不存在';
			
			return json($res);
		}
	}
	//方向请求地址提交
	function fangxaing($userPositionId){
		$url = 'https://mall.ixiaocong.com/open/url/pullNew';
		
		$token = '6eb796cf4f7a45e3ad59a4ad57e5c07b';
		$inviteCode = 'ENBBAH';
		
		$data = array(
	    	'userPositionId' => $userPositionId,
	    	'inviteCode' => $inviteCode,
	    	'timestamp' => time(),
		);
		
		$data['sign'] = $this->getSign($token, $data);
		
		$res = $this->curlRequest($url, $data);
		return $res;
	}
	//加密
	function getSign($token, $data) {
		// 对数组的值按key排序
		ksort($data);
		
		$params = 'inviteCode=' . $data['inviteCode'] . '&timestamp=' . $data['timestamp'] . '&userPositionId=' . $data['userPositionId'] . '&' .$token;
		
		// 生成sign
		$sign = md5($params);
		return $sign;
	}
	
	//crul
	function curlRequest($url,$data = '',$method = 'POST')
	{
	    $ch = curl_init(); //初始化CURL句柄
	    curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而s不是直接输出
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method); //设置请求方式
		
	    curl_setopt($ch,CURLOPT_HTTPHEADER,array("X-HTTP-Method-Override: $method"));//设置HTTP头信息
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
	    $document = curl_exec($ch);//执行预定义的CURL
	    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE); //获取HTTP请求状态码～
	    curl_close($ch);
		
	    $document = json_decode($this->removeBOM($document),true);
	    $document['code'] = $code;
		
	    return $document;
	}
	function removeBOM($str = '')
	{
	    if (substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
	        $str = substr($str, 3);
	    }
	    return $str;
	}
}