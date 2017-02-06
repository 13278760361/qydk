<?php
namespace Home\Controller;
use Think\Controller; 
use Common\Controller\HBaseController;
use Common\Controller\BackController;
class DepartmentController extends HBaseController {

	 function _initialize(){
	 	parent::_initialize();
        $this->db=M('Users');
        $this->db_depart= M('Depart');

        //调试开启
        // $this->user_id = 2;

         $this->depart_rules = $this->db->where(array('id'=>$this->user_id))->getField('depart_rules');//用户权限
	 }

  public function index(){
      if ( empty($this->user_id) ) {
          redirect( U('Public/bind') );
      }
      
     if ( !empty($this->depart_rules) ) {
         
      $where['id'] = array('IN',$this->depart_rules); 
      $depart_lists = $this->db_depart->where($where)->field('id,depart_name')->select();
      

      foreach ($depart_lists as $key => $val) {
            $users_lists = $this->db->field('id,username,phone')-> where( array('depart_id'=> $val['id']) )->select();
            // $depart_lists[$key]['users_num'] = $this->db->where( array('depart_id'=> $val['id']) )->count();
          
            $depart_lists[$key]['users'] = $users_lists;

              unset($depart_lists[$key]['id']);

       } 
           // dump($depart_lists);
     
            $this->assign('depart_lists',$depart_lists);
            $this->display();
      }else{
        redirect(U('Department/noRules')); //跳转提示权限页面
      } 
          
  }

   public function userSearch(){
       $keyword = I('post.keyword');
       $field ="username,phone";
       $where = "depart_id IN({$this->depart_rules}) and  username like '%{$keyword}%'"; //关键词带权限查询
       $lists = $this->db->where($where)->select();
     
       $this->assign('lists',$lists);
       $this->assign('keyword',$keyword); 
       $this->display();
  }

  public function noRules(){
    $this->display();
  }

  

}
