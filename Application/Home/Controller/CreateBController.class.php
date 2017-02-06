<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
// use OT\DataDictionary;
use Think\Controller;
use Wechat\TPWechat;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class CreateBController extends Controller {

	  public function _initialize(){
  
        $this->options = array('appid'=>C('appid'),'appsecret'=>C('appsecret'),'token'=>C('token'),'encodingaeskey'=>C('encodingaeskey'));
        $this->wechatObj = new TPWechat($this->options);
    
  
   
    }
	//微信菜单生成
    public function index(){



   $data = array (
            'button' => array (
              0 => array (
                'name' => '签到',
                'sub_button' => array (
                    0 => array (
                      'type' => 'view',
                      'name' => '部门管理',
                      'url' => 'http://ynrsks.520ssq.com/Home/Department/index',
                    ),
                    1 => array (
                      'type' => 'view',
                      'name' => '签到信息',
                      'url' => 'http://ynrsks.520ssq.com/Home/Sign/index',
                    ),
                     2 => array (
                      'type' => 'view',
                      'name' => '签到状态',
                      'url' => 'http://ynrsks.520ssq.com/Home/Public/signInfo',
                    ),
                ),
              ),
              1 => array (
                'type' => 'view',
                'name' => '通讯录',
                'url'  => 'http://ynrsks.520ssq.com/Home/Contact/index',             
              ),
              2 => array (
                'name' => '个人中心',
                'sub_button' => array (
                    0 => array (
                      'type' => 'view',
                      'name' => '通知',
                      'url' => 'http://ynrsks.520ssq.com/Home/Notice/index',
                    ),
                    1 => array (
                      'type' => 'view',
                      'name' => '个人信息',
                      'url' => 'http://ynrsks.520ssq.com/Home/Center/index',
                    ),
                ),
              ),
            ),
        );


  $this->wechatObj->createMenu($data);
  
      
    }
          
         
  

   




}