<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv='Refresh' content="">
    <script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/error.css" />
    <title>大课堂互动教学后台</title>
</head>
<body>
<script type="text/javascript">
    <!--
        $(function(){
            setTimeout('fun('+<?php echo ($waitSecond); ?>+')',1000);
        })
        function fun(num){
            num--;
            if(num==0){
                if($("#res").attr('href')!=''){
                    location.href=$("#res").attr('href');
                }else{
                    history.back(-1);
                }
            }else{
                $(".wait").html(num);
                setTimeout('fun('+num+')',1000);
            }
        }
    //-->
</script>
<div id="body">
    <div class="box">
        <div class="box_left box_success"></div>
        <div class="clear"></div>
        <div class="box_right">
            <p class="success"><?php echo ($message); ?></p>
            <p>系统将在 <span style="color:blue;font-weight:bold" class="wait"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <a href="<?php echo ($jumpUrl); ?>" id="res">这里</a> 跳转</p>
        </div>
        <ul>
            <li><a class="return" href="<?php echo ($jumpUrl); ?>">返回上一页</a></li>
            <li><a class="index" href="<?php echo ($jumpUrl); ?>">网站首页</a></li>
        </ul>
    </div>
</div>