
<?php

    include "pdo.php";
session_start();
    $pdo = getPdo();
    $uname = $_POST['uname'];     //用户名 Email Mobile
    $pwd = $_POST['pwd'];


    // 验证用户名是否存在
    $sql = "select * from user where uname='{$name}' ";
    $res = $pdo->query($sql);
    $data = $res->fetch(PDO::FETCH_ASSOC);

    if($data)       //查询到用户
    {
        //验证密码
        if(password_verify($pass,$data['password'])){       //密码正确 登录成功
           //发送cookie
            setcookie('uname',$data['uname'],time()+86400*7);
            setcookie('id',$data['id'],time()*86400*7);
            //设置session
            $_SESSION['uname'] = $data['uname'];
            $_SESSION['id'] = $data['id'];
            //更新最后登录时间
            $now = time();
            $sql = "update user set last_login = {$now} where id = {$data['id']}";
            $pdo ->exec($sql);
            //跳转至个人中心 my.html
            header("location:my.html");
        }
    }
    header('Refresh:2,url = login.html');
    echo "用户名或密码错误,正在跳转至登录页面";
//    echo json_encode($response);

