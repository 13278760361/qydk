<?php
namespace Home\Controller;
use Think\Controller; 
use Common\Controller\HBaseController;
use Common\Controller\BackController;
class NoticeController extends HBaseController {

	 function _initialize(){
	 	parent::_initialize();
        $this->db=M('Notice');
      
        //调试开启
        // $this->user_id = 2;
	 }

  public function index(){

      $lists = $this->db->field('id,title,pic,v_title,time')->order('time desc')->select();
      foreach ($lists as $key => $val) {
         $lists[$key]['time'] = date('m月d日',$val['time']);
      }

       // dump($lists);
      $this->assign('lists',$lists);
      $this->display();
          
  }

  public function info(){
  	$id = I('get.id');
  	$info = $this->db->where( array('id'=>$id) )->find();
  
  	$info['time'] = date('Y-m-d',$info['time']);
    $info['content'] = htmlspecialchars_decode($info['content']);
  		
  	$this->assign('info',$info);
  	$this->display();
  }

  

}
