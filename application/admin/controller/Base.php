<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;

class Base extends Controller
{
    //初始化方法

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/9 15:28
     * @Description: 查看是否已经登录
     */
    public function _initialize(){
        /*if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect('Login/index');
            // $this->error("请先登录系统！",'Login/index');
        }else{
            $test=1;
        }*/
    }
}