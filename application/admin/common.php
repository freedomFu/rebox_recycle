<?php
/**
 * @Author:      fyd
 * @DateTime:    2018/6/9 10:57
 * @Description: 公共函数
 */

/**
 * @Author:      fyd
 * @DateTime:    2018/6/9 10:57
 * @Description: 判断密码是否合法
 */
function isPw($password){
    $flag = false;
    $match="/^[a-zA-Z0-9]{4,16}$/";
    if(preg_match($match,$password)){
        $flag = true;
    }
    return $flag;
}

/**
 * @Author:      fyd
 * @DateTime:    2018/6/10 15:50
 * @Description: 判断用户名是不是符合规范
 */
function isUser($name){
    $flag = false;
    $match='/^[_a-zA-Z0-9]{3,16}+$/';
    if(preg_match($match,$name)){
        $flag = true;
    }
    return $flag;
}

function isPhone($phone){
    $flag = false;
    $match='/13[123569]{1}\d{8}|15[1235689]\d{8}|18\d{9}|177\d{8}/';
    if(preg_match($match,$phone)){
        $flag = true;
    }
    return $flag;
}

function isName($name){
    $flag = false;
    $match='/^([\xe4-\xe9][\x80-\xbf]{2}){2,4}$/';
    if(preg_match($match,$name)){
        $flag = true;
    }
    return $flag;
}

/**
 * @Author:      fyd
 * @DateTime:    2018/6/10 15:19
 * @Description: 加密密码
 */
function enctypePw($password){
    $sha1 = sha1($password); // sha1加密
    $base64 = base64_encode($sha1); //base64编码加密
    return md5($base64); //md5加密
}
/**
 * @Author:      fyd
 * @DateTime:    2018/6/10 16:05
 * @Description: 执行错误代码
 */
function falsePro($errno, $errmsg){
    $json = ['errno'=>$errno,'errmsg'=>$errmsg];
    echo json_encode($json,JSON_UNESCAPED_UNICODE);
}