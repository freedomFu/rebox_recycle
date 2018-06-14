<?php
namespace app\index\controller;
use think\Db;
use app\index\controller\Base;

class Index extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 16:12
     * @Description: 显示主页
     */
    public function index()
    {
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
        return $this->fetch('Main/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 17:06
     * @Description: 显示关于我们页面
     */
    public function aboutUs(){
        $data=Db::name('info')
            ->select();
        $infoPhone=$data[0]['info_value'];
        $infoWorkTime=$data[1]['info_value'];
        $infoEmail=$data[2]['info_value'];
        $this->assign("infoPhone",$infoPhone);
        $this->assign("infoWorkTime",$infoWorkTime);
        $this->assign("infoEmail",$infoEmail);
        return $this->fetch('About/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 17:07
     * @Description: 显示联系我们页面
     */
    public function contactUs(){
        $data=Db::name('info')
            ->select();
        $infoPhone=$data[0]['info_value'];
        $infoWorkTime=$data[1]['info_value'];
        $infoEmail=$data[2]['info_value'];
        $this->assign("infoPhone",$infoPhone);
        $this->assign("infoWorkTime",$infoWorkTime);
        $this->assign("infoEmail",$infoEmail);
        return $this->fetch('Contact/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 17:08
     * @Description: 显示敬请期待界面
     */
    public function anti(){
        $data=Db::name('info')
            ->select();
        $infoPhone=$data[0]['info_value'];
        $infoWorkTime=$data[1]['info_value'];
        $infoEmail=$data[2]['info_value'];
        $this->assign("infoPhone",$infoPhone);
        $this->assign("infoWorkTime",$infoWorkTime);
        $this->assign("infoEmail",$infoEmail);
        return $this->fetch('Main/anti');
    }
}
