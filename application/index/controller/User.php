<?php
namespace app\index\controller;
use think\Db;
use app\index\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/8 22:04
 * @Description: 用户的操作类
 */
class User extends Base
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
     * @DateTime:    2018/6/8 22:20
     * @Description: 登录
     */
    public function index(){
        $name = $_POST['username'];
        $pass = $_POST['password'];
        $table = Db::name('user');
        $password = enctypePw($pass);
        $where['user_username']=$name;
        $where['user_isdelete']=0;
        $isUser = $table
            ->where($where)
            ->find();
        if($isUser){
            $isrightPw = $table
                ->where($where)
                ->find();
            $pw = $isrightPw['user_password'];
            if($pw==$password){
                $json = ['errno'=>0,'errmsg'=>'登录成功'];
                $time = date("Y-m-d H:i",time());
                $table
                    ->where('user_username',$name)
                    ->update(['user_lastTime'=>$time]);
                session('reboxUser',0);
                session('reboxUser',$isrightPw['user_id']);
                session('reboxUserIdentity',$isrightPw['user_id'].'user');
                session('reboxUsername',$isrightPw['user_username']);
                session('reboxName',$isrightPw['user_name']);
            }else{
                $json = ['errno'=>1,'errmsg'=>'密码错误'];
            }
        }else{
            $json = ['errno'=>2,'errmsg'=>'不存在此用户'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/10 15:16
     * @Description: 用户注册
     */
    public function regis(){
        $name = $_POST['username'];
        $pw1 = $_POST['pw1'];
        $pw2 = $_POST['pw2'];
        $phone = $_POST['phone'];
        $table = Db::name('user');
        $where['user_username']=$name;
        $where['user_isdelete']=0;
        $where1['user_phone']=$phone;
        $where1['user_isdelete']=0;
        $isOne = $table
            ->where($where)
            ->find();
        $isOnePhone = $table
            ->where($where1)
            ->find();

        switch (true){
            case $isOne:
                falsePro(5,'该用户名已存在！');exit;
            case $isOnePhone:
                falsePro(7,'该手机号已存在！');exit;
            case !isUser($name):
                falsePro(4,'用户名可以由英文、数字以及下划线组成！');exit;
            case $pw1!=$pw2:
                falsePro(2,'两次密码输入不一致');exit;
            case !isPw($pw1) || !isPw($pw2):
                falsePro(3,'密码必须由6-16位的数字字母组合而成！');exit;
            case !isPhone($phone):
                falsePro(6,'手机号格式不正确');exit;
            default:
                break;
        }

        $data['user_password'] = enctypePw($pw1);
        $data['user_username'] = $name;
        $data['user_phone'] = $phone;
        $res = $table
            ->insert($data);

        if($res){
            $json = ['errno'=>0,'errmsg'=>'注册成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'注册失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/10 21:40
     * @Description: 退出登录
     */
    public function logout(){
        session('reboxUser',0);
        $this->redirect('Index/index');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 14:49
     * @Description: 显示用户个人中心
     */
    public function personalCenter(){
        $this->show();
        $data=Db::name('info')
            ->select();
        $infoPhone=$data[0]['info_value'];
        $infoWorkTime=$data[1]['info_value'];
        $infoEmail=$data[2]['info_value'];
        $this->assign("infoPhone",$infoPhone);
        $this->assign("infoWorkTime",$infoWorkTime);
        $this->assign("infoEmail",$infoEmail);
        $id = session('reboxUser');
        $where['user_id']=$id;
        $where['user_isdelete']=0;
        $table=Db::name('user');
        $userInfo = $table
            ->where($where)
            ->find();
        $this->assign('userInfo',$userInfo);
        return $this->fetch('User/personalCenter');
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 14:50
     * @Description: 修改个人信息
     */
    public function exInfo(){
        $id=session('reboxUser');
        $username=$_POST['username'];
        $name=$_POST['name'];
        $phone=$_POST['tel'];
        $table = Db::name('user');
        $oldData=$table
            ->where('user_id',$id)
            ->where('user_isdelete',0)
            ->find();
        $where1['user_username']=$username;
        $where1['user_isdelete']=0;
        $isOne=$table
            ->where($where1)
            ->find();
        $isRec1=!($username==$oldData['user_username']);
        $where2['user_phone']=$phone;
        $where2['user_isdelete']=0;
        $isOnePhone=$table
            ->where($where2)
            ->find();
        $isRecPhone=!($phone==$oldData['user_phone']);

        switch (true){
            case $isRec1&&$isOne:
                falsePro(5,'该用户名已存在！');exit;
            case $isRecPhone&&$isOnePhone:
                falsePro(7,'该手机号已存在！');exit;
            case !isUser($username):
                falsePro(4,'用户名可以由英文、数字以及下划线组成！');exit;
            case !isPhone($phone):
                falsePro(6,'手机号格式不正确');exit;
            case !isName($name):
                falsePro(8,'姓名格式不正确');exit;
            default:
                break;
        }
        $data['user_name']=$name;
        $data['user_username']=$username;
        $data['user_phone']=$phone;
        $where['user_id']=$id;
        $res = $table
            ->where($where)
            ->update($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'修改成功'];
            session('reboxUsername',$username);
            session('reboxName',$name);
        }else{
            $json = ['errno'=>1,'errmsg'=>'修改失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 15:53
     * @Description: 显示使用记录模块
     */
    public function showUseRecord(){
        $this->show();
        $id=session('reboxUser');
        $table=Db::name('userecord');
        $field="useRecord_id,useRecord_boxId,useRecord_state,useRecord_time";
        $where['useRecord_userId']=$id;
        $where['useRecord_isdelete']=0;
        $data=$table
            ->field($field)
            ->where($where)
            ->paginate(5);
        $this->assign("data",$data);
        return $this->fetch("Record/useRecord");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 15:53
     * @Description: 显示抽奖记录模块
     */
    public function showAwardRecord(){
        $this->show();
        $id=session('reboxUser');
        $table=Db::name('awardrecord');
        $field="awardRecord_id,awardRecord_curCredit,awardRecord_state,awardRecord_time";
        $where['awardRecord_userId']=$id;
        $where['awardRecord_isdelete']=0;
        $data=$table
            ->field($field)
            ->where($where)
            ->paginate(5);
        $this->assign("data",$data);
        return $this->fetch("Record/awardRecord");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/12 16:20
     * @Description: 修改密码
     */
    public function exPass(){
        $id=session('reboxUser');
        $table = Db::name('user');
        $oldPass=enctypePw($_POST['oldPass']);
        $pw1=$_POST['pw1'];
        $pw2=$_POST['pw2'];
        $where['user_id']=$id;
        $where['user_isdelete']=0;
        $oldData=$table
            ->where($where)
            ->find();

        switch (true){
            case ($oldPass!=$oldData['user_password']):
                falsePro(5,'原密码输入错误');exit;
            case $pw1!=$pw2:
                falsePro(2,'两次密码输入不一致');exit;
            case !isPw($pw1) || !isPw($pw2):
                falsePro(3,'密码必须由6-16位的数字字母组合而成！');exit;
            default:
                break;
        }
        $data['user_password']=enctypePw($pw1);
        $res = $table
            ->where($where)
            ->update($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'修改成功，下次登录生效'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'修改失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }
}