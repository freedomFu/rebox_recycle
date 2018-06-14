layui.use(['element', 'table', 'layer', 'jquery','form'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$,
        layer_recycle;
    table.render({
        elem: '#recycle'
        , id: 'recycle'
        , height: 488
        , url: base_admin+'/Recycle/jsonRecycle' //数据接口
        , method: 'post'
        , cellMinWidth: 60
        , page: true //开启分页
        , cols: [[ //表头
            { field: 'kid', title: 'ID', sort: true }
            , { field: 'recycle_position', title: '回收站点位置' }
            , { field: 'recycle_capacity', title: '回收站点容量' }
            , { field: 'recycle_time', title: '添加时间' }
            , { align: 'center', toolbar: '#operation-bar', fixed: 'right', width: 178 }
        ]]
    });
    //获取省市县
    $("#confirm_ssx").click(function(){
        var province = $("#provid option:selected").text();
        var city = $("#cityid option:selected").text();
        var area = $("#areaid option:selected").text();
        $("#recycle_position").val(province+"-"+city+"-"+area);

    });
    $('.btn-wrap .layui-btn').on('click', function () {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });
    table.on('tool(recycle)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确认删除么', function(index){
                layui.layer.close(index);
                var json={}
                json.id=data.recycle_id;
                console.log(json.id+"--"+data.recycle_id);
                return base_ajax(base_admin+"/Recycle/delRecycle",json,function () {
                    table.reload('recycle', {
                        url: base_admin+"/Recycle/jsonRecycle"
                    });
                });
            });
        } else if(obj.event === 'edit'){
            layer.confirm('确认进行该操作么', function(index){
                var recycleid = data.recycle_id;
                window.location.href=base_admin+"/Recycle/showEdit/id/"+recycleid;
                layui.layer.close(index);
            });
        }
    });
    form.on('submit(add-recycle)', function(data){//新建站点
        return base_ajax(base_admin+"/Recycle/addRecycle",data.field,function () {
            console.log("success");
        });
    });

    form.on('submit(edit-recycle)', function(data){//修改站点
        return base_ajax(base_admin+"/Recycle/editRecycle",data.field,function () {
            console.log("success");
        });
    });
})