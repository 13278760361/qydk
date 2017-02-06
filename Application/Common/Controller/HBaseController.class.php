<?php
namespace Common\Controller;
use Wechat\TPWechat;
/**
* 前台底层控制器
*/
class HBaseController extends BackController
{
	public $wx_info=array();
	
	function _initialize(){
	   parent::_initialize();

	   if (C('site_status') == 0) { //判断是否站点维护
	   		    $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>(>﹏<) </h1><p>'.C('site_maintain'),'utf-8');
	   		        exit();
	   	}else{

	   		$this->options = array('appid'=>C('appid'),'appsecret'=>C('appsecret'),'token'=>C('token'),'encodingaeskey'=>C('encodingaeskey'));
	        $this->wechatObj = new TPWechat($this->options);
	        $this->wx_info = $this->get_info();

	        $this->user_id = M('Users')->where(array('openid'=>cookie('myopenid')))->getField('id');
	   	}
	  

	}


	 //获取用户信息
    protected function get_info()
    {
    	$access=cookie('access_token');
    	$refresh=cookie('refresh_token');
    	$openid=cookie('myopenid');
         
    	if($access)
    	{  
           
    		return $this->wechatObj->getOauthUserinfo($access,$openid);

    	}else if($refresh)
    	{
           
    		$token=$this->wechatObj->getOauthRefreshToken($refresh);
    		if($token)
    		{
    			cookie('access_token',$token['access_token'],C('ACCESS_TOKEN_TIME'));
        		cookie('refresh_token',$token['access_token'],C('REFRESH_TOKEN_TIME'));
        		cookie('myopenid',$token['openid'],C('REFRESH_TOKEN_TIME'));
        		return $this->wechatObj->getOauthUserinfo($token['access_token'],$token['openid']);
    		}else
    		{
    			cookie('access_token',null);
        		cookie('refresh_token',null);
            	$this->get_oauth(1);
    		}
    	}else
    	{
          
    		$code=isset($_GET['code'])?$_GET['code']:'';
    		if($code)
    		{
    			$token=$this->wechatObj->getOauthAccessToken();
                $info=$this->wechatObj->getOauthUserinfo($token['access_token'],$token['openid']);
                if(!$info){$this->error('授权失败！！请重新授权');}
                cookie('access_token',$token['access_token'],C('ACCESS_TOKEN_TIME'));
                cookie('refresh_token',$token['access_token'],C('REFRESH_TOKEN_TIME'));
                cookie('myopenid',$token['openid'],C('REFRESH_TOKEN_TIME'));
                return $info;
    		}else
    		{
    			$this->get_oauth(1);
    		}
    	}
    }

    //获取授权--0:基本信息授权,1:所有信息授权
    protected function get_oauth($all=0)
    {
    	$url=$this->get_url();
    	$type=$all==1?'snsapi_userinfo':'snsapi_base';
    	$wurl=$this->wechatObj->getOauthRedirect($url,$type,$type);
        redirect($wurl);
    }

      //获取当前地址
    public function get_url()
    {
    	//协议
        $protocol=isset($_SERVER['SERVER_PORT'])&&$_SERVER['SERVER_PORT']=='443'?'https://':'http://';
        //入口
        $main=$_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
        //参数
        $parm=isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'';
        //地址
        $url=isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:$main.(isset($_SERVER['QUERY_STRING'])?'?'.$_SERVER['QUERY_STRING']:$parm);
        //返回地址
        return $protocol.(isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'').$url;
    }

	


}

?>