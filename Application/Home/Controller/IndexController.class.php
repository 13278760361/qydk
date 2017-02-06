<?php
namespace Home\Controller;
use Think\Controller; 
use Common\Controller\HBaseController;
use Common\Controller\BackController;
class IndexController extends HBaseController {

	 function _initialize(){
	 	parent::_initialize();
        $this->db=M('Users');
        $this->db_sign_history =M('Sign_history');


	 }


	public function index() {


		
		 $classR = I('get.classR'); //获取教室信息

         $ticket = I('get.ticket');//获取 ticket 信息



         $info =  $this->wechatObj->getShakeInfoShakeAroundUser($ticket); //获取摇一摇周边信息
         if ( empty($info) ) {
              $this->error('请重新摇一摇 -0-');
         }

         $openid = $info['data']['openid'];
         // dump($openid);exit();

         $now_time = time(); //当前时间

		 // $start = mktime(0,0,0,date("m",$now_time),date("d",$now_time),date("Y",$now_time));//获取今天开始时间
		 // $end = mktime(23,59,59,date("m",$now_time),date("d",$now_time),date("Y",$now_time));//获取进入结束时间



         if ( $this->db->where(array('openid'=>$openid))->find() ) { //找到该用户
	         	   $where['user_id'] = $this->user_id;
	         	   $where['sign_time'] = array('between',array(C('start_date'),C('end_date')));
	         	   $where['sign_type'] = 1; 
              if ( $sign_info =  $this->db_sign_history->where($where)->find()  ) {//当日第一次打卡有的话，进行第二次打卡
                    $whereE['user_id'] = $this->user_id;
                    $whereE['sign_time'] = array('between',array(C('start_date'),C('end_date')));
                    $whereE['sign_type'] = 2;

                     $time_diff = round(($now_time-$sign_info['sign_time'])%86400/3600,2);

                     if ($time_diff < 0.5) { //判断时间间隔 低于半小时的不予下班打卡
                        redirect( U('Public/signInfo') );
                     }

                    if ( $this->db_sign_history->where($whereE)->find() ) { //如果有第二次打卡，跳转详情页面
                        redirect( U('Public/signInfo') );
                     }
                        
                  // if (  $now_time < C('end_work') ) {//时间小于指定上班结束时间 请提交理由
                  //        redirect( U('Index/leave',array('classR'=>$classR) ) ); //跳转提交理由
                  // }else{
                    
		                   $data['user_id'] = $this->user_id;
		                   $data['sign_address'] = $classR;
		                   $data['sign_time'] =$now_time;
		                   $data['sign_type'] = 2;
                       $data['sign_date'] = date('Y-n-j',$now_time);
                         
                       $res = $this->db_sign_history->add($data);

		                    if ($res) {
		                    	 redirect( U('Public/signSuccess') );//成功打卡跳转成功页面
		                    }else{
		                    	$this->error('打卡失败');
		                    }

                   // }  
                 	
                 }else{ //没有的话 进行第一次打卡
                   $data['user_id'] = $this->user_id;
                   $data['sign_address'] = $classR;

                   // if ($now_time<mktime(9,0,0,date("m",$now_time),date("d",$now_time),date("Y",$now_time))){
                   //   $data['sign_time'] = mktime(9,0,0,date("m",$now_time),date("d",$now_time),date("Y",$now_time)); //如果上班打卡时间未到9点 算9点
                   // }else{
                   //   $data['sign_time'] =$now_time;
                   // }
                   $data['sign_time'] = $now_time;
                   $data['sign_type'] = 1;
                   $data['sign_date'] = date('Y-n-j',$now_time);

                   $res = $this->db_sign_history->add($data);

                    if ($res) {
                    	 redirect( U('Public/signSuccess') );//成功打卡跳转成功页面
                    }else{
                    	$this->error('打卡失败');
                    }
                 }   

         }else{
         	redirect( U('Public/bind') ); //用户不存在，跳转绑定页面
         }
	}


    public function leave(){
      if (IS_POST) {
          $leave_reason = I('post.leave_reason');
          $classR  =I('post.classR');
          // dump($_POST);exit();
          if ( empty($leave_reason) ) {
             $this->error('不填写理由可不能走！');
          }


       

        $whereE['user_id'] = $this->user_id;
        $whereE['sign_time'] = array('between',array( C('start_date') , C('end_date') ));
        $whereE['sign_type'] = 2;

        if ( $this->db_sign_history->where($whereE)->find() ) { //如果有第二次打卡，跳转详情页面
                        $this->error('不能重复提交理由');
        }

                       $data['user_id'] = $this->user_id;
                       $data['sign_address'] = $classR;
                       $data['sign_time'] =time();
                       $data['sign_type'] = 2;
                       $data['leave_reason'] = $leave_reason;
                       $data['sign_date'] = date('Y-n-j',time());  
                        $res = $this->db_sign_history->add($data);

                        if ($res) {
                          $this->success('成功',U('Public/signInfo'));
                            // redirect( U('Public/signSuccess') );//成功打卡跳转成功页面
                        }else{
                          $this->error('打卡失败');
                        }
       
      }else{
        $classR = I('get.classR');
        $this->assign('classR',$classR);
        $this->display();
      }
  }

  public function signPrompt(){

    $this->display();
  }

}
