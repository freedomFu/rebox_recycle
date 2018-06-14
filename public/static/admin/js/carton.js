layui.use(['element', 'table', 'layer', 'jquery','form'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$,
        layer_admin;
    table.render({
        elem: '#carton'
        , id: 'carton'
        , height: 488
        , url: base_admin+'/Carton/jsonCarton' //数据接口
        , method: 'post'
        , cellMinWidth: 60
        , page: true //开启分页
        , cols: [[ //表头
            { field: 'kid', title: 'ID', sort: true }
            , { field: 'carton_isUseful', templet: '#switch-useful', title: '是否可使用', unresize: true, sort: true }
            , { field: 'carton_isUsing', templet: '#switch-using', title: '是否在使用', unresize: true, sort: true }
            , { field: 'carton_time', title: '生成时间' }
        ]]
    });
    $("#createCarton").click(function(){ //添加箱面
        return base_ajax(base_admin+"/Carton/addCarton",null,function () {
            table.reload('carton', {
                url: base_admin+"/Carton/jsonCarton"
            });
        });
    });
    //是否可用
    form.on('switch(carton_isUseful)', function(obj){
        var _this = this;
        layer.confirm('确认该操作么', function(index){
            layer.close(index);
            var json={};
            json.id=_this.value;
            json[_this.name]=obj.elem.checked;
            console.log(json);
            return base_ajax(base_admin+"/Carton/isUseful",json);
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

    form.on('switch(carton_isUsing)', function(obj){
        var _this = this;
        layer.confirm('确认该操作么', function(index){
            layer.close(index);
            var json={};
            json.id=_this.value;
            json[_this.name]=obj.elem.checked;
            console.log(json);
            return base_ajax(base_admin+"/Carton/isUsing",json);
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

})