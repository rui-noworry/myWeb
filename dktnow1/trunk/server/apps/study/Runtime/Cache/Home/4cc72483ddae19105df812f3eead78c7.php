<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/public.css" /><script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src=" /Public/Js/Public/public.js"></script>
    <!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/Js/Public/png.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#logo,.cShare,.cEdit,.cIn,.cClone,.cExport,.cDel,.fw_baoming_left,.fw_btn,.anli_ico_link,.selected_green,.selected_gray,.to-left,.to-right,.current,.mt_tab li,.classhomework_top li,.choose_class,.current img,.res_click,.res_scan,.res_frame.png,#main_bg li img,.jCal .left,.jCal .right,.gro_app span,.class_li,.add_add,.flex_close,.main_new li a,.info_text,.info_pass,.body_right .title h2 i,.top_tab_menu .select_ed');
    </script>
    <![endif]-->
    <title>大课堂互动教学</title>
    <script>
        <!--//

            // 导航动画效果
            $(function(){

                $('#header li a').wrapInner('<span class="out"></span>');

                $('#header li a').each(function() {
                    $('<span class="over">' + $(this).text() + '</span>').appendTo(this);
                });

                $('#header li a').hover(function() {
                    $('.out',this).stop().animate({'top':'67px'},200);
                    $('.over',this).stop().animate({'top':'0px'},200);

                }, function() {
                    $('.out',this).stop().animate({'top':'0px'},200);
                    $('.over',this).stop().animate({'top':'-67px'},200);
                });
                
                $('#header li a').click(function(){

                    // 选中菜单项的样式
                    $(this).addClass('on').parent().siblings().find('a').removeClass('on');

                    // 恢复动画
                    $('.out',this).css('top','0px');
                    $('.over',this).css('top','-67px');
                    $(this).parent().siblings().find('a').hover(function(){
                        $('.out',this).stop().animate({'top':'67px'},200);
                        $('.over',this).stop().animate({'top':'0px'},200);
                    }, function() {
                        $('.out',this).stop().animate({'top':'0px'},200);
                        $('.over',this).stop().animate({'top':'-67px'},200);
                    })
                    // 停止当前点击导航的动画效果
                    $(this).hover(function(){
                        $('.out',this).stop();
                        $('.over',this).stop();
                    },function(){
                        $('.out',this).stop();
                        $('.over',this).stop();
                    })
                })

                if ($('#header .exit').size() == 0) {
                    // ajax登录
                    $.post("/Public/checkLogin", 'num='+Math.random(), function(json) {

                        if (json.status == 0) {
                            $(".conrig_error").html(json.message);
                            $("#verify").show();
                            $('#login').height(340);
                        } else {

                            $('#header .nav').next().attr('class', 'exit').attr('href', '/Public/logout').html('[退出]');
                            $('<a class="member" href="__APPURL__/School">会员中心</a>').insertBefore($('.download'));
                            $('.closeWin').click();

                        }
                    }, 'json')
                }
            })
        //-->
    </script>
</head>
<body id="body">
    <div id="header">
        <div>
            <a href="__APPURL__/Index/" id="logo"></a>
            <ul class="nav">
                <li><a <?php if(($bannerOn) == "1"): ?>class="on"<?php endif; ?> href="__APPURL__/Course">课程中心</a></li>
                <?php if(($resourceOn) != ""): ?><li><a <?php if(($bannerOn) == "2"): ?>class="on"<?php endif; ?> href="<?php echo ($resourceOn); ?>">资源中心</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "3"): ?>class="on"<?php endif; ?> href="__APPURL__/Space">我的空间</a></li>
                <li><a <?php if(($bannerOn) == "4"): ?>class="on"<?php endif; ?> href="javascript:;">应用中心</a></li>
            </ul>
            <?php if(($authInfo['a_id']) != "0"): ?><a href="/Public/logout" class="exit">[退出]</a>
            <?php else: ?>
                <a class="loginin">登陆</a><?php endif; ?>
            <a href="/Client/download" title="客户端下载" class="download">客户端下载&nbsp;&nbsp;</a>
        </div>
    </div>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/validator.css" /><script type="text/javascript" src="/Public/Js/Home/FormValidator/formValidator-4.1.3.js"></script><script type="text/javascript" src="/Public/Js/Home/FormValidator/formValidatorRegex.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/info.css" />
<script>
$(function(){

    $.formValidator.initConfig({formID:"form1",theme:"Default",submitOnce:true,
        onError:function(msg,obj,errorlist){
            $("#errorlist").empty();
            $.map(errorlist,function(msg){
                $("#errorlist").append("<li>" + msg + "</li>")
            });
        },
        ajaxPrompt : '有数据正在异步验证，请稍等...'
    });

    // 初始密码
    $("#old_password").formValidator({onShow:"请输入原密码",onFocus:"请输入原密码",onCorrect:"密码正确"}).inputValidator({min:6,onError:"密码错误"}).ajaxValidator({
        dataType : "json",
        type : "get",
        cache : false,
        url : "__URL__/checkPassword",
        success : function(data){

            return !(!data.status);
        },
        buttons: $("#button"),
        error: function(jqXHR, textStatus, errorThrown){showMessage("服务器没有返回数据，可能服务器忙，请重试"+errorThrown);},
        onError : "密码错误",
        onWait : "正在对用户名进行合法性校验，请稍候..."
    });

    // 密码
    $("#a_password").formValidator({onShow:"请输入原密码",onFocus:"至少6位"}).inputValidator({min:6,onError:"密码必须大于6位"}).functionValidator({
        fun:function(val,elem){
            if (/[a-zA-Z]+/.test(val) && /[0-9]+/.test(val) && /\W+\D+/.test(val)) {
                return '<img src="__PUBLIC__/Images/Home/enroll_better.jpg"/>';
            } else if(/[a-zA-Z]+/.test(val) || /[0-9]+/.test(val) || /\W+\D+/.test(val)) {
                if(/[a-zA-Z]+/.test(val) && /[0-9]+/.test(val)) {
                    return '<img src="__PUBLIC__/Images/Home/enroll_middle.jpg"/>';
                }else if(/\[a-zA-Z]+/.test(val) && /\W+\D+/.test(val)) {
                    return '<img src="__PUBLIC__/Images/Home/enroll_middle.jpg"/>';
                }else if(/[0-9]+/.test(val) && /\W+\D+/.test(val)) {
                    return '<img src="__PUBLIC__/Images/Home/enroll_middle.jpg"/>';
                }else{
                    return '<img src="__PUBLIC__/Images/Home/enroll_weak.jpg"/>';
                }
            }
        }
    ,onDktShow:"1"});

    // 重新输入密码
    $("#re_password").formValidator({onShow:"再次输入密码",onFocus:"至少6位",onCorrect:"密码一致"}).inputValidator({min:6,onError:"密码必须大于6位,请确认"}).compareValidator({desID:"a_password",operateor:"=",onError:"俩次密码不一致,请确认"});

    //重置
    $('.sub_cancel').click(function(){
        var t_val = '';
        $('#a_password').next().text(t_val);
        $('#re_password').next().text(t_val);
        $('#old_password').focus();
    })
})
</script>
<div class="warp">
    <div class="body_left fl">
        <ul class="info">
            <li onclick="javascript:location.href='__URL__/index'"></li>
            <li class="info_pass"></li>
        </ul>
    </div>
    <div class="body_right fr">
        <div class="title">
            <i class="fl"></i><span class="fl">修改密码</span><a href="/Index" class='fr'>返回</a>
        </div>
        <div class="info_center">
            <div class="userList">
                <form method="post" name="form1" id="form1" action="__URL__/update">
                    <ul class="infolist infoposs">
                        <li class="libox curr">
                            <label class="ila">当前密码：</label>
                            <input type="password" name="old_password" value="" id="old_password"/>
                            <span id="old_passwordTip">请输入原密码</span>
                        </li>
                        <li class="libox new">
                            <label class="ila">新密码：</label>
                            <input type="password" name="a_password" value="" id="a_password"/>
                            <span id="a_passwordTip">输入新密码</span>
                        </li>
                        <li class="libox new">
                            <label class="ila">再输一次：</label>
                            <input type="password" name="re_password" value="" id="re_password"/>
                            <span id="re_passwordTip">再次输入密码</span>
                        </li>
                        <li class="sub libox">
                            <input type="submit" name="sub_hold" value="保存信息"/>
                            <input type="reset" name="" value="重置" class="sub_cancel"/>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
    <div class="clear"></div>
    <div class="foot_bot"></div>
    <div class="foot_top"></div>
    <div id="footer">
        <div class="nav back1"></div>
        Copyright &copy; 2007-2011 北京金商祺移动互联 All Rights Reserved.
    </div>
</body>
</html>