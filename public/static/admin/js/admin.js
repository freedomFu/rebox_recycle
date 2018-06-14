layui.use(['element', 'table', 'layer', 'jquery','form'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$,
        layer_admin;
    table.render({
        elem: '#admin'
        , id: 'admin'
        , height: 488
        , url: base_admin+'/Admin/jsonAdmin' //数据接口
        , method: 'post'
        , cellMinWidth: 60
        , page: true //开启分页
        , cols: [[ //表头
            { field: 'kid', title: 'ID', sort: true }
            , { field: 'admin_name', title: '管理员名' }
            , { field: 'admin_authority', title: '管理员权限' }
            , { field: 'admin_lastTime', title: '上次登录时间' }
            , { align: 'center', toolbar: '#operation-bar', fixed: 'right', width: 178 }
        ]]
    });
    var $ = layui.$, active = {
        new: function () {
            layer_admin=layer.open({
                type: 1,
                title: '添加管理员',
                content: $('#add-admin').html()
            });
            form.render();
        }
    };
    $('.btn-wrap .layui-btn').on('click', function () {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });
    table.on('tool(admin)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确认删除么', function(index){
                layui.layer.close(index);
                var json={}
                json.id=data.admin_id;
                console.log(json.id+"--"+data.admin_id);
                return base_ajax(base_admin+"/Admin/delAdmin",json,function () {
                    table.reload('admin', {
                        url: base_admin+"/Admin/jsonAdmin"
                    });
                });
            });
        } else if(obj.event === 'edit'){
            layer.confirm('确认进行该操作么', function(index){
                var adminid = data.admin_id;
                window.location.href=base_admin+"/Admin/showEdit/id/"+adminid;
                layui.layer.close(index);
            });
        }
    });
    form.on('submit(add-admin)', function(data){//新建用户
        return base_ajax(base_admin+"/Admin/add",data.field,function () {
            table.reload('admin', {
                url: base_admin+"/Admin/jsonAdmin"
            });
            layer.close(layer_admin);
        });
    });
})