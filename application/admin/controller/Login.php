<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/12 16:47
 * @Description: 登录模块
 */
class Login extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 20:38
     * @Description: 显示登录界面
     */
    public function index(){
        return $this->fetch('Login/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 20:40
     * @Description: 登录
     */
    public function login(){
        $name=$_POST['logname'];
        $password=$_POST['logpass'];
        $afterPass=enctypePw($password);
        $table=Db::name('admin');
        $whereName['admin_name']=$name;
        $whereName['admin_isdelete']=0;
        $isName=$table
            ->where($whereName)
            ->find();
        if($isName){
            $pass=$isName['admin_password'];
            $author=$isName['admin_authority'];
            if($afterPass==$pass){
                $json = ['errno'=>0,'errmsg'=>'登录成功！'];
                $time = date("Y-m-d H:i",time());
                $table
                    ->where($whereName)
                    ->update(['admin_lastTime'=>$time]);
                session('adminAuthority',$author);
                session('adminId','admin'.$isName['admin_id']);
            }else{
                $json = ['errno'=>1,'errmsg'=>'密码错误'];
                echo json_encode($json,JSON_UNESCAPED_UNICODE);
                exit;
            }
        }else{
            $json = ['errno'=>2,'errmsg'=>'用户名不存在'];
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
            exit;
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 21:20
     * @Description: 退出登录
     */
    public function logout(){
        session('adminAuthority',0);
        $this->redirect('Login/index');
    }
}