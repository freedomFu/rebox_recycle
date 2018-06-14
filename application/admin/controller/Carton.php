<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/13 17:36
 * @Description: 箱面类
 */
class Carton extends Base
{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 17:37
     * @Description: 展示箱面列表
     */
    public function showCarton(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        /*$table=Db::name("carton");
        $where['carton_isUseful']=0;
        $where['carton_isdelete']=0;
        $nowtdays=date('Y-m-d H:i:s',strtotime('-3day'));
        $table
            ->where($where)
            ->where('carton_time','lt',$nowtdays)
            ->update(['carton_isdelete'=>1]);*/
        return $this->fetch("Carton/carton");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 20:32
     * @Description: 生成json数据
     */
    public function jsonCarton(){
        $table=Db::name('carton');
        $commonWhere['carton_isdelete']=0;

        $page=input('page');
        $limit=input('limit');
        $count=$table
            ->where($commonWhere)
            ->count();
        $cartonList=$table
            ->where($commonWhere)
            ->limit(($page-1)*$limit,$limit)
            ->select();


        $counts = count($cartonList);
        for($i=1;$i<=$counts;$i++){
            $cartonList[$i-1]['kid'] = $i;
        }

        if($count){
            echo json(['code'=>0,'count'=>$count,'msg'=>'','data'=>$cartonList])->getcontent();
        }else{
            echo json(['code'=>1,'count'=>$count])->getcontent();
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 21:54
     * @Description: 添加箱面，每次添加六块
     */
    public function addCarton(){
        $flag=true;
        $table=Db::name('carton');
        $data['carton_isUseful']=1;
        $data['carton_isUsing']=0;
        for($i=0;$i<6;$i++){
            $res=$table
                ->insert($data);
            if(!$res){
                $flag=false;
                break;
            }
        }
        if($flag){
            $json = ['errno'=>0,'errmsg'=>'添加成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'添加失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 21:37
     * @Description: 私有公共函数
     */
    private function ex($name, $id, $flag){
        $table=Db::name('carton');
        $where['carton_id']=$id;
        $where['carton_isdelete']=0;
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
     * @DateTime:    2018/6/13 21:37
     * @Description: 是否可用
     */
    public function isUseful(){
        $id=input('id');
        $flag=input('carton_isUseful');
        if($flag=="false"){
            $flag=false;
        }elseif($flag=="true"){
            $flag=true;
        }else{
            $json = ['errno'=>3,'errmsg'=>'修改失败'];
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
            exit;
        }
        $this->ex('carton_isUseful',$id,$flag);
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 21:38
     * @Description: 是否在使用
     */
    public function isUsing(){
        $id = input('id');
        $flag=input('carton_isUsing');
        if($flag=="false"){
            $flag=false;
        }elseif($flag=="true"){
            $flag=true;
        }else{
            $json = ['errno'=>3,'errmsg'=>'修改失败'];
            echo json_encode($json,JSON_UNESCAPED_UNICODE);
            exit;
        }
        $this->ex('carton_isUsing',$id,$flag);
    }
}