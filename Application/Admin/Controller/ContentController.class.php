<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/22
 * Time: 10:05
 */
namespace Admin\Controller;

use Common\Controller\ABaseController;

class ContentController extends ABaseController{
    public function _initialize(){
        parent::_initialize();
        $this->db = M('Notice'); //当前模块数据库
    }
    public function index()
    {
        //查询条件匹配
        $keyword = I('get.keyword')?I('get.keyword'):'';
        $where =1;
        if ($keyword) {
            $where ="depart_name like '%{$keyword}%'";
        }

        $count = $this->db->where($where)->count();
        $page           = new \Think\Page($count,C('pageNum'));
        $show           = $page->show();
        $lists = $this->db->where($where)->order('time asc')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('keyword',$keyword);
        $this->assign('page',$show);
        $this->assign('lists',$lists);
        $this->display();
    }

    public function add()
    {
        if(IS_POST) {
            $datas = I('post.');
            $data['title'] = $datas['title'];
            $data['v_title'] = $datas['v_title'];
            $data['author'] = $datas['author'];
            $data['content'] = $datas['content'];
            $data['pic'] = $datas['pic'];
            $data['time'] = time();

            if ($data['pic'] == '') {
                $this->error('请上传封面图片');

            }
            if ($data['content'] == '') {
                $this->error('通知内容不能为空');
            }
            $re = $this->db->add($data);
            if($re){
                $this->success('发布成功!');
            } else {
                $this->error('发布失败!');
            }
        }
            $this->display();
    }

    public function edit()
    {
        $id = I('get.id');
        if(IS_POST){
            $datas = I('post.');
            $data['title'] = $datas['title'];
            $data['v_title'] = $datas['v_title'];
            $data['author'] = $datas['author'];
            $data['content'] = $datas['content'];
            $data['pic'] = $datas['pic'];
            $data['time'] = time();

            if ($data['pic'] == '') {
                $this->error('请上传封面图片');
            }
            if ($data['content'] == '') {
                $this->error('通知内容不能为空');
            }
            $where['id'] = $datas['id'];
            $re = $this->db->where($where)->save($data);
//            echo $this->db->getLastSql();exit();
            if($re){
                $this->success('修改成功!');
            } else {
                $this->error('修改失败!');
            }
        }else{
            $where['id'] = $id;
            $info = $this->db->where($where)->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
    public function del()
    {
        $ids = I('post.ids');
        $map['id']=['in',$ids];
        $this->db->startTrans(); //开启事物
        $re =  $this->db->where($map)->delete();
        if($re){
            $this->db->commit();
            $this->success('删除成功');
        } else {
            $this->db->rollback();
            $this->error('删除失败,请重试!');
        }
        echo $this->db->getLastSql();

    }
}