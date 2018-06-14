layui.use(['form','jquery','layer'],function(){
    var $ = layui.$
        , layer = layui.layer
        , form = layui.form;
    //登录
    form.on('submit(adminLog)',function(data){
        return base_ajax(base_admin+"/Login/login",data.field,function(){
            window.location.href=base_admin+"/Index/index";
        });
    });
});