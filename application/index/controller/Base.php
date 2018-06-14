<?php
namespace app\index\controller;
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
        /*if(!session('ems_identity')){
            $this->redirect('Login/index');
            // $this->error("请先登录系统！",'Login/index');
        }else{
            $exp_datas = Db::name('lab')->where('isdelete',0)->select();
            $this->assign('exp',$exp_datas);
        }*/
    }
}