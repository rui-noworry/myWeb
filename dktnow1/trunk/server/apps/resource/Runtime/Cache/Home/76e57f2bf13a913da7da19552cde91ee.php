<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta name="keywords" content="<?php echo ($head["keywords"]); ?>" />
    <meta name="description" content="<?php echo ($head["description"]); ?>" />
    <meta http-equiv='Refresh' content='<?php echo ($waitSecond); ?>;URL=<?php echo ($jumpUrl); ?>'>
    <script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/error.css" />
    <!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/Js/Home/png.js" ></script>
    <script type="text/javascript">   DD_belatedPNG.fix('.back1');   </script>
    <![endif]-->
    <title>大课堂互动教学</title>
    <script>
        <!--//
        $(function(){
            //搜索
            $('.searchinput').focus(function(){
                var val = $(this).val() == '输入关键字' ? '' : $(this).val();
                $(this).val(val);
            }).blur(function(){
                var val = $(this).val() == '' ? '输入关键字' : $(this).val();
                $(this).val(val);
            })

            //导航
            $('.banner ul li').mouseover(function() {
                $(this).css({'border-bottom':'#4ca2f6 solid 5px'}).siblings().css({'border-bottom':''});
            }).mouseout(function() {
                $(this).css({'border-bottom':''});
            })

            // 验证登录状态
            $.post("/Public/checkLogin", function(json){
                if(json){
                    if (json['status'] != 0){
                        $(".top").html('<a href="__APPURL__/Public/logout">退出</a><span><a href="__APPURL__/Member/index">欢迎您，' + json['data'] + '</a></span>');
                    } else {
                        $(".top").html('<a href="javascript:void(0);" class="login">登录</a><span>欢迎您来到大课堂互动教学平台</span>');
                    }
                }
            }, 'json');

            $(".banner ul li").eq(<?php echo ($bannerOn); ?>).addClass('on').siblings().removeClass('on');
            setTimeout('fun('+<?php echo ($waitSecond); ?>+')',1000);
        });

        // 显示登录窗口
        function showLogin() {
            //弹出层
            pop("560","402");
            //弹出登陆窗口
            $('.floatBox').load('__APPURL__/Public/login/'+Math.random(), function(){});
        }

        // 是否登录
        function loginRedirect(url) {

            if ($(".top").text().indexOf('退出') != -1) {
                location.href = url;
            } else {
                showLogin();
            }
        }

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
</head>
<body>
<div id="body">
    <div class="box">
        <div class="box_left box_error"></div>
        <div class="clear"></div>
        <div class="box_right">
            <p class="error"><?php echo ($error); ?></p>
            <p>系统将在 <span class="wait"><?php echo ($waitSecond); ?></span> 秒后自动跳转,如果不想等待,直接点击 <a href="<?php echo ($jumpUrl); ?>" id="res">这里</a> 跳转</p>
        </div>
        <ul>
            <li><a class="return" href="<?php echo ($jumpUrl); ?>">返回上一页</a></li>
            <li><a class="index" href="/">网站首页</a></li>
        </ul>
    </div>
</div>