<?php
namespace app\index\controller;
use think\Db;
use app\index\controller\Base;

class Index extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/15 9:00
     * @Description: 传递变量
     */
    private function show(){
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
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 16:12
     * @Description: 显示主页
     */
    public function index()
    {
        $this->show();
        return $this->fetch('Main/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 17:06
     * @Description: 显示关于我们页面
     */
    public function aboutUs(){
        $this->show();
        return $this->fetch('About/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 17:07
     * @Description: 显示联系我们页面
     */
    public function contactUs(){
        $this->show();
        return $this->fetch('Contact/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/8 17:08
     * @Description: 显示敬请期待界面
     */
    public function anti(){
        $this->show();
        return $this->fetch('Main/anti');
    }
}
