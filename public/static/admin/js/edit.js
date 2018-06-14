layui.use(['element', 'table', 'layer', 'jquery','form'], function () {
    var element = layui.element,
        form = layui.form,
        layer = layui.layer,
        table = layui.table,
        $ = layui.$;
    form.on('submit(editAdmin)', function(data){//修改用户信息
        return base_ajax(base_admin+"/Admin/editAdmin",data.field,function () {
            console.log(data.field);
        });
    });
})