<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/8 22:04
 * @Description: 管理用户类
 */
class User extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 21:45
     * @Description: 展示用户列表
     */
    public function showUser(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        return $this->fetch('User/user');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 21:46
     * @Description: 获取数据json格式
     */
    public function searchUser(){
        $table=Db::name('user');
        $commonWhere['user_isdelete']=0;

        $page=input('page');
        $limit=input('limit');
        $count=$table
            ->where($commonWhere)
            ->count();
        $userList=$table
            ->where($commonWhere)
            ->limit(($page-1)*$limit,$limit)
            ->select();

        $counts = count($userList);
        for($i=1;$i<=$counts;$i++){
            $userList[$i-1]['kid'] = $i;
        }

        if($count){
            echo json(['code'=>0,'count'=>$count,'msg'=>'','data'=>$userList])->getcontent();
        }else{
            echo json(['code'=>1,'count'=>$count,'msg'=>'','data'=>$userList])->getcontent();
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 8:47
     * @Description: 逻辑删除用户
     */
    public function delUser(){
        $id=input('id');
        $table=Db::name('user');
        $where['user_id']=$id;
        $update['user_isdelete']=1;
        $res=$table
            ->where($where)
            ->update($update);
        if($res){
            echo json(['code'=>0,'errmsg'=>"删除成功"])->getcontent();
        }else{
            echo json(['code'=>1,'errmsg'=>"删除失败"])->getcontent();
        }
    }
}