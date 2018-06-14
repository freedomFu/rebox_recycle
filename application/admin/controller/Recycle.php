<?php
namespace app\admin\controller;
use think\Db;
use app\admin\controller\Base;
/**
 * @Author:      fyd
 * @DateTime:    2018/6/13 13:30
 * @Description: 描述信息
 */
class Recycle extends Base{
    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 13:3显示信息页面
     */
    public function index(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        return $this->fetch("Recycle/recycle");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 13:39
     * @Description: 获取回收站点的json数据
     */
    public function jsonRecycle(){
        $table=Db::name('recycle');
        $commonWhere['recycle_isdelete']=0;

        $page=input('page');
        $limit=input('limit');
        $count=$table
            ->where($commonWhere)
            ->count();
        $recycleList=$table
            ->where($commonWhere)
            ->limit(($page-1)*$limit,$limit)
            ->select();

        $counts = count($recycleList);
        for($i=1;$i<=$counts;$i++){
            $recycleList[$i-1]['kid'] = $i;
        }

        if($count){
            echo json(['code'=>0,'count'=>$count,'msg'=>'','data'=>$recycleList])->getcontent();
        }else{
            echo json(['code'=>1,'count'=>$count])->getcontent();
        }
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 15:19
     * @Description: 展示添加页面
     */
    public function showAdd(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        return $this->fetch("Recycle/add");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 16:35
     * @Description: 添加回收站点
     */
    public function addRecycle(){
        $capacity=input('recycle_capacity');
        $position=input('recycle_position');
        if($capacity>1000){
            falsePro(2,'站点容量不能超过1000个');exit;
        }
        $table=Db::name('recycle');
        $where['recycle_isdelete']=0;
        $where['recycle_position']=$position;
        $isPos=$table
            ->where($where)
            ->find();
        if($isPos){
            falsePro(3,'该站点已存在');exit;
        }
        $data['recycle_capacity']=$capacity;
        $data['recycle_position']=$position;
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
     * @DateTime:    2018/6/13 16:51
     * @Description: 删除站点
     */
    public function delRecycle(){
        $id=input('id');
        $where['recycle_id']=$id;
        $where['recycle_isdelete']=0;
        $data['recycle_isdelete']=1;
        $table=Db::name('recycle');
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
     * @DateTime:    2018/6/13 17:01
     * @Description: 显示修改界面
     */
    public function showEdit(){
        if(session('adminAuthority')==0 || is_null(session('adminAuthority'))){
            $this->redirect("Login/index");
        }
        $id=input('id');
        $where['recycle_isdelete']=0;
        $where['recycle_id']=$id;
        $table=Db::name('recycle');
        $data=$table
            ->where($where)
            ->find();
        $this->assign('recycleData',$data);
        return $this->fetch("Recycle/edit");
    }

    /**
     * @Author:      fyd
     * @DateTime:    2018/6/13 17:17
     * @Description: 修改
     */
    public function editRecycle(){
        $id=input('recycle_id');
        $table=Db::name('recycle');
        $position=input('recycle_position');
        $capacity=input('recycle_capacity');
        $where1['recycle_position']=$position;
        $where1['recycle_isdelete']=0;
        $where2['recycle_id']=$id;
        $where2['recycle_isdelete']=0;
        $oldData=$table  //oldData中的位置和输入的位置不同才可以比较是不是重复
            ->where($where2)
            ->find();
        $tableData=$table
            ->where($where1)
            ->find();
        $isOnePos=(($oldData['recycle_position']!=$position) && $tableData);
        if($capacity>1000){
            falsePro(2,'站点容量不能超过1000个');exit;
        }
        if($isOnePos){
            falsePro(3,'站点已存在');exit;
        }
        $data['recycle_position']=$position;
        $data['recycle_capacity']=$capacity;
        $res=$table
            ->where($where2)
            ->update($data);
        if($res){
            $json = ['errno'=>0,'errmsg'=>'修改成功'];
        }else{
            $json = ['errno'=>1,'errmsg'=>'修改失败'];
        }
        echo json_encode($json,JSON_UNESCAPED_UNICODE);

    }
}