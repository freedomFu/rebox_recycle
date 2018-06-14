<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;

class Index extends Base
{

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 20:56
     * @Description: 显示主页
     */
    public function index()
    {
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        return $this->fetch('Main/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/14 15:30
     * @Description: 显示设置页面
     */
    public function showSet(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        $phone=Db::name('info')
            ->where('info_name','phone')
            ->find();
        $workTime=Db::name('info')
            ->where('info_name','workTime')
            ->find();
        $email=Db::name('info')
            ->where('info_name','email')
            ->find();

        $infoPhone=$phone['info_value'];
        $infoWorkTime=$workTime['info_value'];
        $infoEmail=$email['info_value'];
        $this->assign("infoPhone",$infoPhone);
        $this->assign("infoWorkTime",$infoWorkTime);
        $this->assign("infoEmail",$infoEmail);
        return $this->fetch("Main/set");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/14 15:53
     * @Description: 成功设置
     */
    public function set(){
        $table=Db::name('info');
        $phone=input('phone');
        $email=input('email');
        $workTime=input('workTime');
        $wherePhone['info_name']="phone";
        $whereWorkTime['info_name']="workTime";
        $whereEmail['info_name']="email";

        $resPhone=$table
            ->where($wherePhone)
            ->update(['info_value'=>$phone]);
        $resWorkTime=$table
            ->where($whereWorkTime)
            ->update(['info_value'=>$workTime]);

        $resEmail=$table
            ->where($whereEmail)
            ->update(['info_value'=>$email]);

        if($resPhone | $resEmail | $resWorkTime){
            $json = ['errno'=>0,'errmsg'=>'设置成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'设置失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

}
