<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta name="Generator" content="EditPlus">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src="/Public/Js/Public/public.js"></script><script type="text/javascript" src="/Public/Js/Ilc/commonManage.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/public.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/login.css" />
    <title><?php echo (C("web_name")); ?>管理系统登录</title>
    <script>
        $(function(){

            //前端 登录界面颜色选择
            $(".main h2 span").click(function() {

                var s_attr = $(this).attr('attr');
                $(this).closest('div').attr('class','main' + " " + s_attr);

            }).eq(parseInt(Math.random() * 3)).click();

            $('.bo_name input').focus();

            // 前端 用户名 获取焦点
            $('.bo_name input').keyup(function() {

                var s_val = $(this).val();
                if (s_val == "") {
                    $(this).next().hide();
                }else {
                    $(this).next().show();
                }

            })

            //前端 清空用户名
            $('.main_bottom div span').click(function(){
                $(this).hide().prev().val('');
            })

            //表单验证
            $('.login').click(function(){

                var t_val = true;
                var name_val = $('.bo_name input').val();
                var pass_val = $('.bo_password input').val();
                var code_val = $('.code input').val();
                var new_name = name_val.replace(/[ ]/g,'');
                var new_pass = pass_val.replace(/[ ]/g,'');
                var new_code = code_val.replace(/[ ]/g,'');

                //验证 用户名
                if (new_name == "") {
                    $('.main_bottom p').show().text('请输入用户名');
                    return false;

                }

                //验证 密码
                if (new_pass == "") {
                    $('.main_bottom p').show().text('请输入密码').show();
                    return false;
                }

                //验证 验证码
                if (new_code == "") {
                    $('.main_bottom p').show().text('为了保证你的帐号安全，请输入验证码').show();
                    return false;
                }

                $.post('__URL__/checkLogin', $("#login").serialize(), function(json) {
                    if (json.status == 0) {
                        $('.main_bottom p').show().text(json.info);
                    } else {
                        location.href = "__GROUP__";
                    }
                }, 'json')

            });

        })

        // 判断回车
        function keydown(e){
            var e = e || event;
            if (e.keyCode==13) {
                $('.login').click();
            }
        }

    </script>
</head>

<body>
    <div class="main">
        <form id="login" method="post">
            <h2>
                <span class="red fl" attr="ma_red"></span>
                <span class="yellow fl" attr="ma_yellow"></span>
                <span class="green fl" attr="ma_green"></span>
                <i class="fl">后台登录</i>
            </h2>
            <div class="main_bottom">
                <div class="bo_name">
                    <i class="fl">
                        <label></label>
                    </i>
                    <input type="text" name="u_account" class="" placeholder="用户名" value=""/>
                    <span class="fl"></span>
                </div>
                <div class="bo_password">
                    <i class="fl">
                        <label></label>
                    </i>
                    <input type="password" name="u_password" class="" placeholder="密码"/>
                </div>
                <div class="code">
                    <input type="text" name="verify" class="" placeholder="验证码" onkeydown="keydown(event)"/>
                    <img id="verifyImg" src="__URL__/verify/" onclick="fleshVerify()" border="0" alt="点击刷新验证码" align="absmiddle">
                </div>
                <p></p>
                <input class="login" type="button" value="登陆" />
            </div>
        </form>
    </div>
</body>
</html>