layui.use(['element', 'table', 'layer', 'jquery','form'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$,
        layer_createBox;
    table.render({
        elem: '#createBox'
        , id: 'createBox'
        , height: 400
        , url: base_admin+'/Box/jsonCreateBox' //数据接口
        , method: 'post'
        , cellMinWidth: 60
        , page: true //开启分页
        , limit: 6 //每一页6个
        , limits: [6,12,18,24,30,36]
        , cols: [[ //表头
            { type: 'checkbox'}
            , { field: 'kid', title: 'ID', sort: true }
            , { field: 'carton_time', title: '生产时间' }
            , { field: 'carton_id', title: '箱子编号'}
        ]]
    });

    //监听表格复选框选择
    table.on('checkbox(createBox)', function(obj){
        console.log(obj)
    });


    $("#confirmCreate").click(function(){ //生产箱子
        var checkStatus = table.checkStatus('createBox')
            ,data = checkStatus.data;
        var len = data.length;
        if(data.length>6){
            layer.msg('您选择的箱面数多于6个！',{icon:5});
        }else if(data.length<6){
            layer.msg('您选择的箱面数少于6个！',{icon:5});
        }else{
            var jsonArr=[];
            for(var i=0;i<len;i++){
                jsonArr[i]=data[i]['carton_id'];
            }
            console.log(data);
            console.log(jsonArr);
            var jsonStr=jsonArr.join(",");
            // layer.alert(JSON.stringify(jsonArr));
            var convertArr={
                "carton_id": jsonStr
            };
            console.log(convertArr);
            return base_ajax(base_admin+"/Box/createBox",convertArr,function () {
                table.reload('createBox', {
                    url: base_admin+"/Box/jsonCreateBox"
                });
            });
        }
    });
})