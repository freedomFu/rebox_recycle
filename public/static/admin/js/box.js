layui.use(['element', 'table', 'layer', 'jquery','form'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$,
        layer_admin;
    table.render({
        elem: '#box'
        , id: 'box'
        , height: 488
        , url: base_admin+'/Box/jsonBox' //数据接口
        , method: 'post'
        , cellMinWidth: 60
        , page: true //开启分页
        , cols: [[ //表头
            { field: 'kid', title: 'ID', sort: true }
            , { field: 'box_isUsing', templet: '#switch-using', title: '是否在使用', unresize: true, sort: true }
            , { field: 'box_cartonId', title: '箱面id', sort: true }
            , { field: 'box_time', title: '生成时间' }
            , { align: 'center', toolbar: '#operation-bar', fixed: 'right', width: 178 }
        ]]
    });

    form.on('switch(box_isUsing)', function(obj){
        var _this = this;
        layer.confirm('确认该操作么', function(index){
            layer.close(index);
            var json={};
            json.id=_this.value;
            json[_this.name]=obj.elem.checked;
            console.log(json);
            return base_ajax(base_admin+"/Box/isUsing",json);
        }, function(index){
            layer.close(index);
            if(obj.elem.checked == true){
                obj.elem.checked = false;
                form.render();
            }else{
                obj.elem.checked = true;
                form.render();
            }
        });
    });

    table.on('tool(box)', function(obj){
        var data = obj.data;
        if(obj.event === 'del'){
            layer.confirm('确认删除么', function(index){
                layui.layer.close(index);
                var json={}
                json.id=data.box_id;
                console.log(json.id+"--"+data.box_id);
                return base_ajax(base_admin+"/Box/delBox",json,function () {
                    table.reload('box', {
                        url: base_admin+"/Box/jsonBox"
                    });
                });
            });
        }
    });

})