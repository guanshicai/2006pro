<?php
include "pdo.php";
$uname = $_POST['uname'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$pwd1 = $_POST['pwd1'];
$pwd2 = $_POST['pwd2'];
//echo $uname,$tel,$email,$pwd1,$pwd2;die;
//判断两次密码是否一致
if($pwd1 != $pwd2){
    $response = [
        'errno' => 40001,
        'msg' => "两次密码不一致"
    ];
    die(json_encode($response));
}
//验证密码长度  > 6
if(strlen($pwd1)<6){
    $response = [
        'errno' => 40002,
        'msg' => '密码长度不够'
    ];
    die(json_encode($response));
}

//验证用户是否存在
$pdo = getPdo();
$sql = "select * from user where uname = '$uname' or tel = '$tel' or email = '$email'";
$res = $pdo->query($sql);
$arr = $res->fetch(PDO::FETCH_ASSOC);
//验证用户名
if($arr){
    $response = [
        'errno' => 40003,
        'msg' => '用户名已存在'
    ];
    die(json_encode($response));
}
//验证邮箱
if($arr){
    $response = [
        'errno' => 40004,
        'msg' => '邮箱已存在'
    ];
    die(json_encode($response));
}
//验证手机号
if($arr){
    $response = [
        'errno' => 40005,
        'msg' => '手机号已存在'
    ];
    die(json_encode($response));
}
//用户信息入库
//生成用户密码
$password = password_hash($pwd1,PASSWORD_BCRYPT);
$now = time();
$sql = "insert into user(uname,tel,email,password,reg_time)values('$uname','$tel','$email','$password','$now')";
$res = $pdo->exec($sql);
$id = $pdo->lastInsertId();//获取自增id
if($id>0){//成功
    $response = [
        'errno' => 0,
        'msg' => 'ok'
    ];

}else{//失败
    $response = [
        'errno' => 50002,
        'msg' => '注册失败，请重试'
    ];
}
echo json_encode($response);