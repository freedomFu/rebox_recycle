layui.use(['element', 'table', 'layer', 'jquery'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$,
        layer_user;
    table.render({
        elem: '#users'
        , id: 'users'
        , height: 488
        , url: base_admin+'/User/searchUser' //数据接口
        , method: 'post'
        , cellMinWidth: 60
        , page: true //开启分页
        , cols: [[ //表头
            { field: 'kid', title: 'ID', sort: true }
            , { field: 'user_username', title: '用户名' }
            , { field: 'user_name', title: '姓名' }
            , { field: 'user_credit', title: '信誉积分' }
            , { field: 'user_lastTime', title: '上次登录时间' }
            , { align: 'center', toolbar: '#operation-bar', fixed: 'right', width: 178 }
        ]]
    });

    $('.btn-wrap .layui-btn').on('click', function () {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });
    table.on('tool(users)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确认删除么', function(index){
                layui.layer.close(index);
                var json={}
                json.id=data.user_id;
                console.log(json.id+"--"+data.user_id);
                return base_ajax(base_admin+"/User/delUser",json,function () {
                    table.reload('users', {
                        url: base_admin+"/User/searchUser"
                    });
                });
            });
        }
    });

})