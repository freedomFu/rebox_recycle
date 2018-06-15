<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/13 9:11
 * @Description: 管理员管理模块
 */
class Admin extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 9:14
     * @Description: 显示管理员列表
     */
    public function adminList(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))||session('adminAuthority')==2){
            $this->redirect("Login/index");
        }
        return $this->fetch('Admin/admin');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 9:28
     * @Description: 显示管理员的json数据
     */
    public function jsonAdmin(){
        $table=Db::name('admin');
        $commonWhere['admin_isdelete']=0;

        $page=input('page');
        $limit=input('limit');
        $count=$table
            ->where($commonWhere)
            ->count();
        $adminList=$table
            ->where($commonWhere)
            ->limit(($page-1)*$limit,$limit)
            ->select();

        $counts = count($adminList);
        for($i=1;$i<=$counts;$i++){
            $adminList[$i-1]['kid'] = $i;
            if($adminList[$i-1]['admin_authority']==1){
                $adminList[$i-1]['admin_authority']="超级管理员";
            }else{
                $adminList[$i-1]['admin_authority']="普通管理员";
            }
        }

        if($count){
            echo json(['code'=>0,'count'=>$count,'errmsg'=>'','data'=>$adminList])->getcontent();
        }else{
            echo json(['code'=>1,'count'=>$count,'errmsg'=>'','data'=>$adminList])->getcontent();
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 10:05
     * @Description: 添加管理员
     */
    public function add(){
        $table=Db::name('admin');
        $name=input('username');
        $password=input('password');
        $au_level=input('auth_level');
        $isOne=$table
            ->where('admin_name',$name)
            ->where('admin_isdelete',0)
            ->find();

        switch (true){
            case $isOne:
                falsePro(5,'该名称已存在！');exit;
            case !isUser($name):
                falsePro(4,'用户名可以由英文、数字以及下划线的3-16位组成！');exit;
            case !isPw($password):
                falsePro(3,'密码必须由6-16位的数字字母组合而成！');exit;
            default:
                break;
        }
        $data['admin_name']=$name;
        $data['admin_password']=enctypePw($password);
        $data['admin_authority']=$au_level+1;
        $res=$table
            ->insert($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'添加成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'添加失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 10:22
     * @Description: 删除管理员
     */
    public function delAdmin(){
        $table=Db::name('admin');
        $where['admin_isdelete']=0;
        $id=input('id');
        $where['admin_id']=$id;
        $data['admin_isdelete']=1;
        $res=$table
            ->where($where)
            ->update($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'删除成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'删除失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 11:00
     * @Description: 展示修改信息
     */
    public function showEdit(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))||session('adminAuthority')==2){
            $this->redirect("Login/index");
        }
        $id=input('id');
        $table=Db::name('admin');
        $field="admin_id,admin_name,admin_authority";
        $where['admin_isdelete']=0;
        $where['admin_id']=$id;
        $data=$table
            ->field($field)
            ->where($where)
            ->find();
        $this->assign('adminData',$data);
        return $this->fetch('Admin/edit');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 10:42
     * @Description: 修改管理员信息
     */
    public function editAdmin(){
        $id=input('admin_id');
        $name=input('username');
        $auth_level=input('auth_level');
/*        $id=10;
        $name="mwk";
        $auth_level=1;*/
        $table=Db::name('admin');
        $where['admin_name']=$name;
        $where['admin_isdelete']=0;
        $where1['admin_isdelete']=0;
        $where1['admin_id']=$id;
        $isName=$table
            ->where($where1)
            ->find();
        $isName0=$table
            ->where($where)
            ->find();
        $nameFlag=(($isName['admin_name']!=$name)&&($isName0['admin_name']==$name));
        switch (true){
            case ($nameFlag):
                falsePro(5,'此用户名已存在');exit;
            case !isUser($name):
                falsePro(4,'用户名可以由英文、数字以及下划线的3-16位组成！');exit;
            default:
                break;
        }
        $data['admin_name']=$name;
        $data['admin_authority']=$auth_level+1;
        $where['admin_id']=$id;
        $where['admin_isdelete']=0;
        $res=$table
            ->where($where)
            ->update($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'修改成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'修改失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 12:53
     * @Description: 管理员修改密码
     */
    public function exAdminPass(){
        $id=substr(session('adminId'),5);
        $where['admin_id']=$id;
        $where['admin_isdelete']=0;
        $table=Db::name('admin');
        $oldPass=input('old_pass');
        $newPass=input('new_pass');
        $confirmPass=input('confirm_pass');
        $isPass=$table
            ->where($where)
            ->find();

        switch (true){
            case empty($oldPass)||empty($newPass)||empty($confirmPass):
                falsePro(5,'密码不能为空');exit;
            case !(enctypePw($oldPass)==$isPass['admin_password']):
                falsePro(2,'原密码错误');exit;
            case $newPass!=$confirmPass:
                falsePro(3,'两次密码不相同');exit;
            case (!isPw($newPass) || !isPw($confirmPass)):
                falsePro(4,'密码必须由6-16位的数字字母组合而成！');exit;
            default:
                break;
        }
        $pass=enctypePw($newPass);
        $res=$table
            ->where($where)
            ->update(['admin_password'=>$pass]);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'修改成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'修改失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

}