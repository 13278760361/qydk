<?php
namespace Admin\Controller;

use Common\Controller\ABaseController;

class StaffController extends ABaseController{
    public function _initialize(){
        parent::_initialize();
        $this->db = M('Users'); //当前模块数据库
        $this->assign('departs', M('Depart')->where(array('status'=>1))->getField('id,depart_name'));
    }
    public function index()
    {
        $users = M('Users');
        //查询条件匹配
        $keyword = I('get.keyword')?I('get.keyword'):'';
        $departs   = I('get.departs')?I('get.departs'):'';
        $sex   = I('get.sex')?I('get.sex'):'';
        if (empty($keyword) && empty($departs) &&empty($sex)) {
            $where = "u.depart_id = d.id and d.status=1";
        }elseif($keyword && $departs){
            $where ="u.depart_id = d.id and u.depart_id ={$departs} and (u.job_number like '%{$keyword}%' OR u.username like '%{$keyword}%') and d.status=1";
        }elseif($keyword && $sex){
            if($sex==3){
                $where ="u.depart_id = d.id and u.sex =0 and (u.job_number like '%{$keyword}%' OR u.username like '%{$keyword}%') and d.status=1";
            }else{
                $where ="u.depart_id = d.id and u.sex ={$sex} and (u.job_number like '%{$keyword}%' OR u.username like '%{$keyword}%') and d.status=1";
            }
        }elseif ($keyword) {
            $where = "u.depart_id = d.id and (u.job_number like '%{$keyword}%' OR u.username like '%{$keyword}%') and d.status=1";
        }elseif ($departs) {
            $where ="u.depart_id = d.id and u.depart_id ={$departs} and d.status=1";
        }elseif($sex){
            if($sex ==1){
                $where ="u.depart_id = d.id and u.sex ={$sex} and d.status=1";
            }elseif($sex ==2){
                $where ="u.depart_id = d.id and u.sex ={$sex} and d.status=1";

            }elseif($sex ==3){
                $where ="u.depart_id = d.id and u.sex =0 and d.status=1";
            }
        }
        $count = $users->table(array('qcdk_users'=>'u','qcdk_depart'=>'d'))->field('u.*,d.depart_name')->where($where)->count();

        $page           = new \Think\Page($count,C('pageNum'));
        $show           = $page->show();
        $lists=$users->table(array('qcdk_users'=>'u','qcdk_depart'=>'d'))->field('u.*,d.depart_name')->where($where)->order('job_number asc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('lists',$lists);
        $this->display();
    }
    //添加员工
    public function add()
    {
        $users = M('Users');
        $depart = M('Depart');
        if(IS_POST){
            $data['job_number'] = I('post.job_number');
            $data['username'] = I('post.username');
            $data['depart_id'] = I('post.parent_id');
            $data['sex'] = I('post.sex');
            $data['phone'] = I('post.phone');
            $data['time'] = time();
            $job_num['job_number'] = I('post.job_number');
            $phone['phone'] = I('post.phone');
            if($users->where($job_num)->find()){
                $this->error('工号已存在,请重新操作!');
            }
            if($users->where($phone)->find()){
                $this->error('手机号码已存在,请重新操作!');
            }
            $re = $users->add($data);
            if($re){
                $this->success('添加成功!');
            } else {
                $this->error('添加失败!');
            }
        } else {
            $where['status'] = 1;
            $this->assign('departs',$depart->field('id,depart_name')->where($where)->select());
            $this->display();
        }

    }
    //员工编辑
    public function edit()
    {
        $users = M('Users');
        $depart = M('Depart');
        if(IS_POST){
            $data['job_number'] = I('post.job_number');
            $data['username'] = I('post.username');
            $data['depart_id'] = I('post.parent_id');
            $data['sex'] = I('post.sex');
            $data['phone'] = I('post.phone');
            $job_num['job_number'] = I('post.job_number');
            $job_num['id'] = array('not in',I('post.id'));
            $phone['phone'] = I('post.phone');
            $phone['id'] = array('not in',I('post.id'));
            if($users->where($job_num)->find()){
                $this->error('工号已存在,请重新操作!');
            }
            if($users->where($phone)->find()){
                $this->error('手机号码已存在,请重新操作!');
            }
            $where['id'] = I('post.id');
            $re =$users->where($where)->save($data);
            if($re){
                $this->success('修改成功!');
            } else {
                $this->error('修改失败,请重试');
            }
        }else{
            $where['u.id'] = I('get.id');
            $info = $users->table(array('qcdk_users'=>'u','qcdk_depart'=>'d'))->field('u.*,d.depart_name')->where($where)->find();
            $wheres['status'] = 1;
            $this->assign('departs',$depart->field('id,depart_name')->where($wheres)->select());
            $this->assign('info',$info);
            $this->display();
        }
    }
    //删除员工
    public function del()
    {
        $ids = I('post.ids');
        $map['id']=['in',$ids];
        $map1['user_id']=['in',$ids];

        M('Users')->startTrans(); //开启事物
        $re = M('Users')->where($map)->delete();
        $r1 = M('Sign_history')->where($map1)->count();
        if($r1){
            $re_1 = M('Sign_history')->where($map1)->delete();
        }else{
            $re_1=1;
        }

        if($re && $re_1){
            M('Users')->commit();
            $this->success('删除成功');
        } else {
            M('Users')->rollback();
            $this->error('删除失败,请重试!');
        }
    }

    public function rules(){
        if (IS_POST) {
            $rules = I("post.rules");
            // if ( empty($rules) ) {
            //     $this->error('请分配权限,否则关闭');
            // }
            $data['depart_rules'] = $rules;
            $data['id'] = I('post.id');
            $res = $this->db->save($data);
            if ($res !== false) {
                  $this->success('权限分配成功',U('Staff/index'));
              }else{
                  $this->error('权限分配失败');  
              }  
        }else{
            $id = I('get.id');
            $this->assign('id',$id);
           $this->display();
        }
        
    }

    public function getDeparts(){
         if(IS_POST){
           $id = I('post.id');
           $rules = $this->db->where( array('id'=>$id) )->getField('depart_rules');
           $e= explode(',', $rules);
        
           
           $add = M('Depart')->field('id ,depart_name as name')->select();
            //组装
    
                foreach ($add as $key=>$val) {
               
                   $add[$key]['pId'] = 0;
                   if (in_array($add[$key]['id'], $e)) {
                       $add[$key]['checked'] = 'true';
                   }
        
                }

       
           
           echo (json_encode($add));
         }else{
            $this->error('非法请求');
         }  
    }
}