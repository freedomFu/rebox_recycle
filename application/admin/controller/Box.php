<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/13 22:14
 * @Description: 箱子类
 */
class Box extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 22:15
     * @Description: 显示箱子列表
     */
    public function showBox(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        return $this->fetch("Box/box");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 22:24
     * @Description: 获取箱子json
     */
    public function jsonBox(){
        $table=Db::name('box');
        $commonWhere['box_isdelete']=0;

        $page=input('page');
        $limit=input('limit');
        $count=$table
            ->where($commonWhere)
            ->count();
        $boxList=$table
            ->where($commonWhere)
            ->limit(($page-1)*$limit,$limit)
            ->select();

        $counts = count($boxList);
        for($i=1;$i<=$counts;$i++){
            $boxList[$i-1]['kid'] = $i;
        }

        if($count){
            echo json(['code'=>0,'count'=>$count,'msg'=>'','data'=>$boxList])->getcontent();
        }else{
            echo json(['code'=>1,'count'=>$count])->getcontent();
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 21:37
     * @Description: 私有公共函数
     */
    private function ex($name, $id, $flag){
        $table=Db::name('box');
        $where['box_id']=$id;
        $where['box_isdelete']=0;
        if($flag===true){
            $data[$name]=1;
        }elseif($flag===false){
            $data[$name]=0;
        }
        $isExist=$table
            -> where($where)
            -> find();

        if($isExist){
            $res = $table
                -> where($where)
                -> update($data);

            if($res){
                $json = ['errno'=>0,'errmsg'=>'修改成功'];
            }else{
                $json = ['errno'=>1,'errmsg'=>'修改失败'];
            }
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
        }else{
            $json = ['errno'=>2,'errmsg'=>'记录不存在！'];
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 21:38
     * @Description: 是否在使用
     */
    public function isUsing(){
        $id = input('id');
        $flag=input('box_isUsing');
        if($flag=="false"){
            $flag=false;
        }elseif($flag=="true"){
            $flag=true;
        }else{
            $json = ['errno'=>3,'errmsg'=>'修改失败'];
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
            exit;
        }
        $this->ex('box_isUsing',$id,$flag);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/14 8:19
     * @Description: 生产箱子界面
     */
    public function showCreate(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        return $this->fetch("Box/createBox");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/14 8:31
     * @Description: 生成可用箱面的json数据
     */
    public function jsonCreateBox(){
        $table=Db::name('carton');
        $where['carton_isdelete']=0;
        $where['carton_isUseful']=1;
        $where['carton_isUsing']=0;

        $page=input('page');
        $limit=input('limit');
        $count=$table
            ->where($where)
            ->count();
        $cartonUseList=$table
            ->where($where)
            ->limit(($page-1)*$limit,$limit)
            ->select();

        $counts = count($cartonUseList);
        for($i=1;$i<=$counts;$i++){
            $cartonUseList[$i-1]['kid'] = $i;
        }

        if($count){
            echo json(['code'=>0,'count'=>$count,'msg'=>'','data'=>$cartonUseList])->getcontent();
        }else{
            echo json(['code'=>1,'count'=>$count])->getcontent();
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/14 10:22
     * @Description: 生产箱子
     */
    public function createBox(){
        $carton=Db::name('carton');
        $box=Db::name('box');
        $cartonIdStr=input('carton_id');
//        dump($cartonIdArr);
//        $cartonIdArr=[13,14,15,16,17,18];
        $cartonIdArr=explode(",",$cartonIdStr);
        $len=count($cartonIdArr);
        for($i=0;$i<$len;$i++){
            $whereUp['carton_id']=$cartonIdArr[$i];
            $whereUp['carton_isdelete']=0;
            $carton
                ->where($whereUp)
                ->update(['carton_isUsing'=>1,'carton_isUseful'=>0]);
        }
        switch (true){
            case ($len<6):
                falsePro(2,'所选箱面过少');exit;
            case ($len>6):
                falsePro(3,'所选箱面过多');exit;
        }
        $data['box_isUsing']=0;
        $data['box_cartonId']=$cartonIdStr;
        $res=$box
            ->insert($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'生成成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'生成失败'];
            for($i=0;$i<$len;$i++){
                $whereUp['carton_id']=$cartonIdArr[$i];
                $whereUp['carton_isdelete']=0;
                $carton
                    ->where($whereUp)
                    ->update(['carton_isUsing'=>0,'carton_isUseful'=>1]);
            }
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/14 11:19
     * @Description: 分解箱子
     */
    public function delBox(){
        // 获取当前箱子的状态  如果在使用就不能修改
        $box=Db::name('box');
        $carton=Db::name('carton');
        $box_id=input('id');
//        $box_id=1;
        $boxFiled="box_id,box_isUsing,box_cartonId";
        $whereBox['box_id']=$box_id;
        $whereBox['box_isdelete']=0;
        $boxInfo=$box
            ->field($boxFiled)
            ->where($whereBox)
            ->find();
        $boxState=$boxInfo['box_isUsing'];
        $boxCartonId=$boxInfo['box_cartonId'];
        if($boxState==1){
            falsePro(2,'箱子正在使用中，不能分解！');exit;
        }elseif($boxState==0){
            $res=$box
                ->where($whereBox)
                ->update(['box_isdelete'=>1]);
            if($res){
                $json = ['errno'=>0,'errmsg'=>'分解成功'];
                $cartonIdArr=explode(",",$boxCartonId);
                $len=count($cartonIdArr);
                for($i=0;$i<$len;$i++){
                    $whereUp['carton_id']=$cartonIdArr[$i];
                    $whereUp['carton_isdelete']=0;
                    $carton
                        ->where($whereUp)
                        ->update(['carton_isUsing'=>0,'carton_isUseful'=>1]);
                }
            }else{
                $json = ['errno'=>1,'errmsg'=>'分解失败'];
            }
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);

    }
}