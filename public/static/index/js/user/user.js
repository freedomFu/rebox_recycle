layui.use(['form','jquery','layer'],function(){
    var $ = layui.$
        , layer = layui.layer
        , form = layui.form;
    var login_layer,
        regis_layer,
        expass_layer;
    $("#nav_login").click(function(){
        login_layer = layer.open({
            type: 1
            ,title: '用户登录'
            ,content: $('#loginTp').html()
        });
    });

    $("#nav_regis").click(function(){
        regis_layer = layer.open({
            type: 1
            ,title: '用户注册'
            ,content: $('#regisTp').html()
        });
    });
    //登录
    form.on('submit(login)',function(data){
        return base_ajax(base_index+"/User/index",data.field,function(){
            layer.close(login_layer);
            window.location.href=base_index+"/Index/index";
        });
    });
    //注册
    form.on('submit(regis)',function(data){
        return base_ajax(base_index+"/User/regis",data.field,function(){
            layer.close(regis_layer);
        });
    });

    //修改信息
    form.on('submit(exUserInfo)', function (data) {
        return base_ajax(base_index+"/User/exInfo", data.field,function () {
            window.location.href=base_index+"/User/personalCenter";
        });
    });

    $("#personExPass").click(function(){
        expass_layer = layer.open({
            type: 1
            ,title: '修改密码'
            ,content: $('#exPassTp').html()
        });
    });

    form.on('submit(exPass)', function (data) {
        return base_ajax(base_index+"/User/exPass", data.field,function () {
            layer.close(expass_layer);
        });
    });

    $("#goAward").click(function(){
        $("#awardTp").fadeIn();
    });

});

