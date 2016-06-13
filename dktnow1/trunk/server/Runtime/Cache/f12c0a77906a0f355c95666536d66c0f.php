<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/public.css" /><script type="text/javascript" src=" __PUBLIC__/Js/Public/jquery-1.9.1.js"></script>
    <title>大课堂互动教学</title>
</head>
<body>
    <div id="header">
        <div>
            <a href="/" id="logo"></a>
            <a href="/Client/download" class="download">客户端下载</a>
        </div>
    </div>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/onload.css" />
    <div id="main">
    <!--背景图切换开始-->
        <ul class="main_bg">
            <li class="a1 fl"></li>
            <li class="a2 fl"></li>
            <li class="a3 fl"></li>
        </ul>
        <div class="clear"></div>
        <!--背景图切换结束-->
        <div class="content">
            <!--中间(左)内容开始-->
            <div class="content_left fl">
                <p><span>贯穿</span>学习全过程，课前课堂课后，<br />深入教育信息化核心环节！</p>
                <!--左1开始-->
                <div class="conlef_div fl">
                    <div class="conlef_div_d1"></div>
                    <div class="conlef_div_d2 conlef_div_d21 fl"></div>
                    <div class="conlef_div_d3 conlef_div_d31 fl">
                        <div class="conlef_div_img"></div>
                        <p class="conlef_div_p">资源平台</p>
                    </div>
                    <div class="conlef_div_d4 fl">
                        <span>实现多资源库融合接入与管理，将各级资源库资源进行统一的管理和检索。建立面向教学应用的资源服务模式，以资源为载体，开放资源接口服务，为第三方应用、为师生提供随需的资源服务。</span>
                    </div>
                </div>
                <!--左1结束-->
                <!--左2开始-->
                <div class="conlef_div fl">
                    <div class="conlef_div_d1"></div>
                    <div class="conlef_div_d2 conlef_div_d22 fl"></div>
                    <div class="conlef_div_d3 conlef_div_d32 fl">
                        <div class="conlef_div_img conlef_div_img1"></div>
                        <p class="conlef_div_p">学习平台</p>
                    </div>
                    <div class="conlef_div_d4 fl">
                        <span>建立一个以集课前、课堂、课后为一体的互动教学平台，让信息技术与教学深度融合，服务于日常教学，同时为自主探究学习提供平台支撑，逐步建立一个开放性学习社区与资源共享平台，通过多种机制，实现跨校区资源共享。</span>
                    </div>
                </div>
                <!--左2结束-->
                <!--左3开始-->
                <!--div class="conlef_div fl">
                    <div class="conlef_div_d1"></div>
                    <div class="conlef_div_d2 conlef_div_d23 fl"></div>
                    <div class="conlef_div_d3 conlef_div_d33 fl">
                        <div class="conlef_div_img conlef_div_img2"></div>
                        <p class="conlef_div_p">云储存</p>
                    </div>
                    <div class="conlef_div_d4 fl">
                        <span class="left_span">请检查您输入的网址是否正确。</span><span>如果您不能确认网址是否正确，请点击返回首页浏览其他页面。</span><span>如果您无法载入任何页面，请检查您计算机的网络连接。</span>
                    </div>
                </div-->
                <!--左3结束-->
            </div>
            <!--中间(左)内容结束-->
            <!--中间(右)内容开始-->
            <div class="content_right fr">
                <form method="post" name="form1" id="form1" action="">
                    <p class="conrig_p1">用户登录</p>
                    <div class="conrig_name">
                        <input type="text" value="" name="account" class="name_inp"/>
                    </div>
                    <div class="conrig_name">
                         <input type="password" value="" name="password" class="name_inp" onkeydown="keydown(event)"/>
                    </div>

                    <div id="verify" style="display:none">
                          <input type="text" style="width:100px; height:40px; float:left; margin-right:5px;" id="verify" name="verify" onkeydown="keydown(event)" onkeydown="keydown(event)"/></li>
                          <img src="/Public/verify/" class="verifyImg" onclick="fleshVerify();" style="float:left;cursor:pointer;" width="100" height="40" border="0">
                    </div>
                    <div style="clear:both"></div>
                    <div class="conrig_error"></div>
                    <div class="conrig_remember fl">
                        <input name="remember" type="checkbox" class="fl" />记住密码
                    </div>
                    <a href="#" class="conrig_remember fr">忘记密码？</a>
                    <div class="clear"></div>
                    <a href="javascript:void(0)" class="conrig_land fl"></a>
                    <a href="/Public/register" class="conrig_enroll fr"></a>
                </form>
            </div>
        </div>
    </div>
            <!--中间(右)内容结束-->

            <script>
            <!--
                $(function() {

                    //背景图
                    $('.main_bg li').eq(0).show().siblings().hide();
                    var oLeight = $(".main_bg li").size();
                    var i = 0;
                    var time;
                    time=setInterval(function(){
                        i = i+1;
                        $(".main_bg li").hide();
                        $(".main_bg li").eq(i).fadeIn(500);
                        if(i == oLeight - 1)
                        {
                            i= - 1;
                        }
                    },3000);

                    // 页面加载账号文本框获取焦点
                    $("input[name=account]").focus();

                    // 点击登录
                    $(".conrig_land").click(function(){

                        var account = $("input[name=account]").val();
                        var password = $("input[name=password]").val();

                        // 是否记住登录状态
                        var remember = $("input[name=remember]:checked").size();

                        if (account==''){

                            $(".conrig_error").html("请正确输入账号");
                            $(".conrig_error").show().fadeOut(2000);
                            $("input[name=name]").focus();
                            return false;
                        }

                        if (password==''){
                            $(".conrig_error").html("请输入密码");
                            $(".conrig_error").show().fadeOut(2000);
                            $("input[name=password]").focus();
                            return false;
                        }

                        // 验证码
                        var verify = '';
                        if ($("#verify").css('display') == 'none') {
                            verify = 0;
                        } else {
                            verify = $("input[name=verify]").val();
                            if (!verify) {
                                $(".conrig_error").html("请输入验证码");
                                $(".conrig_error").show().fadeOut(2000);
                                $("input[name=verify]").focus();
                                return false;
                            }
                        }

                        $(".conrig_error").fadeOut(1000);

                        // ajax登录
                        $.post("/Public/authLogin", 'account='+account+'&password='+password+'&remember='+remember+'&verify='+verify, function(json) {

                            if (json.status == 0) {
                                $(".conrig_error").html(json.message);
                                $("#verify").show();
                                $(".conrig_error").show().fadeOut(3000);
                            } else {
                                $(".conrig_error").fadeOut(3000);
                                window.location.href = '/apps/'+json.url;
                            }
                        }, 'json')
                    });
                })

            // 判断回车
            function keydown(e){
                var e = e || event;
                if (e.keyCode==13) {
                    $(".conrig_land").click();
                }
            }

            // 重载验证码
            function fleshVerify(){
                $(".verifyImg").attr('src', '/Public/verify/'+ Math.random());
            }

            //-->
        </script>
    <div class="clear"></div>