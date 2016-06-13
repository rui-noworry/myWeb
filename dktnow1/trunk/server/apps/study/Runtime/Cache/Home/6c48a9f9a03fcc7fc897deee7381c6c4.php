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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/info.css" />
<script type="text/javascript" src="/Public/Js/Public/Ymd/WdatePicker.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesCity.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesdata.js"></script>
<script>
$(function(){
    //姓名判断
    $('input[name=a_nickname]').blur(function(){
        var oVal = $(this).val();
        String.prototype.trim = function(){
            return this.replace(/[ ]/g,'')
        }
        var newVal = oVal.trim();
        if (newVal == '') {
            $(this).removeClass('new_red')
            $(this).next().text('');
            return false;
        }

        if (newVal.length < 7  && newVal.length > 1) {
            $(this).next().text('');
            $(this).next().append("<image src='__APPURL__/Public/Images/Home/true.png'/>");
        } else {
            $(this).addClass('new_red');
            $(this).next().text('输入的格式不正确，请重新输入').css('color','red');
            return false;
        }
    })
    $('input[name=a_nickname]').focus(function(){
        $(this).removeClass('new_red')
        $(this).next().text('姓名至少2位，最多6位').css('color','gray');
    })

    //前端验证 生日
    $('input[name=a_birthday]').blur(function(){
        var myDate = new Date();
        myDate.getFullYear();    //获取完整的年份(4位,1970-????)
        myDate.getMonth();       //获取当前月份(0-11,0代表1月)
        myDate.getDate();       //获取当前日(1-31)
        var nowDate = (myDate.getFullYear()) + '-' + '0' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
        //nowDate = myDate.toLocaleDateString()
        var oldVal = $(this).val();
        var newVal = oldVal.replace(/[-]/g,'');
        var newDate = nowDate.replace(/[-]/g,'');
        var ntwVal = oldVal.replace(/[-]/g,'');
        if (newVal == '') {
            $(this).removeClass('new_red');
            $(this).next().text('');
            $(this).children().remove();
            return false;
        }
        if (newVal > newDate) {
            $(this).addClass('new_red');
            $(this).next().text('请输入正确的生日').css('color','red');
        } else {
            $(this).next().text('');
        }
    })

    $('input[name=a_birthday]').focus(function(){
        $(this).removeClass('new_red');
        $(this).next().text('');
    })

    //前端验证 手机号码
    $('input[name=a_tel]').blur(function(){
        var tVal = $(this).val();
        var tPar = /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/;
        var newVal = tVal.replace(/[ ]/g,'');
        if (newVal == '') {
            $(this).next().text('');
            $(this).next().children().remove();
        } else {
            if (tPar.test(tVal)) {
                $(this).next().text('');
                $(this).next().append("<image src='__APPURL__/Public/Images/Home/true.png'/>");
            } else {
                $(this).addClass('new_red')
                $(this).next().text('你输入的手机格式不正确，请重新输入').css('color','red');
                return false;
            }
        }
    })
    $('input[name=a_tel]').focus(function(){
        $(this).removeClass('new_red');
        $(this).next().text('');
    })

    //前端验证 简介
    $('.infolist textarea').focus(function(){
        if ($(this).val() == "内容不能超过300字符") {
            $(this).val('');
        }
    })
    $('.addTeacher textarea').blur(function(){
        var tVal = $(this).val();
        var newVal = tVal.replace(/[ ]/g,'').length;
        if (newVal == 0) {
            $(this).val('内容不能超过300字符');
        }
        if (newVal > 300) {
            showMessage('内容不能超过300字符');
            $(this).focus();
            return false;
        }
    })

    // 籍贯
    setProvince("province", "a_region", <?php echo ($a_region2); ?>);
})

// 验证
function check() {

    var tTrue = true;

    //姓名验证
    var tVal = $('input[name=a_nickname]').val();
    var ntwVal = tVal.replace(/[ ]/g,'');
    if (ntwVal == '') {
        $('input[name=a_nickname]').addClass('new_red');
        $('input[name=a_nickname]').next().text('请输入姓名').css('color','red');
        oNum = false;
        tTrue = false;
    } else {
        if (ntwVal.length < 7 && ntwVal.length > 1) {
            $('input[name=a_nickname]').next().text('');
            $('input[name=a_nickname]').next().append("<image src='__APPURL__/Public/Images/Home/true.png'/>");
        } else {
            $('input[name=a_nickname]').addClass('new_red');
            $('input[name=a_nickname]').next().text('你输入的姓名格式不正确，请重新输入姓名');
            oNum = false;
            tTrue = false;
        }
    }

    //生日验证
    var oldVal = $('input[name=a_birthday]').val();
    var myDate = new Date();
    myDate.getFullYear();    //获取完整的年份(4位,1970-????)
    myDate.getMonth();       //获取当前月份(0-11,0代表1月)
    myDate.getDate();       //获取当前日(1-31)
    var nowDate = (myDate.getFullYear()) + '-' + '0' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
    var newVal = oldVal.replace(/[-]/g,'');
    var newDate = nowDate.replace(/[-]/g,'');
    var ntwVal = oldVal.replace(/[-]/g,'');
    if (newVal > newDate) {
        $('input[name=a_birthday]').addClass('new_red');
        $('input[name=a_birthday]').next().text('请输入正确的生日').css('color','red');
        tTrue = false;
    }

    //手机验证
    var pVal = $('input[name=a_tel]').val();
    var reVal = pVal.replace(/[ ]/g,'');
    var tPar = /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/;
    if (reVal == '') {
    } else {
        if (!tPar.test(pVal)) {
            $('input[name=a_tel]').addClass('new_red');
            $('input[name=a_tel]').next().text('你输入的手机格式不正确，请重新输入').css('color','red');
            tTrue = false;
        }
    }

    //简介验证
    var adVal = $('textarea[name=t_intro]').val();
    if (adVal) {
        var nadLen = adVal.replace(/[ ]/g,'').length;
        if (nadLen > 300) {
            showMessage('简介内容不能超过300字符');
            $('textarea[name=t_intro]').focus();
            tTrue = false;
        }
    }
    //判断
    if (tTrue == false) {
        return false;
    }

    return true;
}
</script>
<div class="warp">
    <div class="body_left fl">
        <ul class="info">
            <li class="info_text"></li>
            <li class="info_password" onclick="javascript:location.href='__URL__/password'"></li>
        </ul>
    </div>
    <div class="body_right fr">
        <div class="title">
            <i class="fl"></i><span class="fl">个人信息</span><a href="/Index" class='fr'>返回</a>
        </div>
        <div class="info_center">
            <form method="post" action="__URL__/update" class="qqx" onsubmit="return check();">
                <img src="<?php echo ($a_avatar); ?>" onclick="javascript:location.href='__URL__/avatar'" width="96" height="96" class="info_myTmg"/>
                <div class="userList">
                    <ul class="infolist" >
                        <li class="libox coSemester">
                            <label class="ila">账号：</label>
                            <label><?php echo ($authInfo["a_account"]); ?></label>
                        </li>
                        <li class="libox">
                            <label class="ila">姓名：</label>
                            <input type="text" name="a_nickname" value="<?php echo ($authInfo["a_nickname"]); ?>">
                            <span></span>
                        </li>
                        <li class="libox">
                            <label class="ila">性别：</label>
                            <input type="radio" name="a_sex" value="1" <?php if(($authInfo["a_sex"]) == "1"): ?>checked<?php endif; ?>>
                            <label>男</label>
                            <input type="radio" name="a_sex" value="2" <?php if(($authInfo["a_sex"]) == "2"): ?>checked<?php endif; ?>>
                            <label>女</label>
                        </li>
                        <li class="libox">
                            <label class="ila">生日：</label>
                            <input type="text" onclick="WdatePicker();" value="<?php echo (todate($authInfo["a_birthday"],'Y-m-d')); ?>" name="a_birthday">
                            <span></span>
                        </li>
                        <li class="libox">
                            <label class="ila">籍贯：</label>
                            <span id="province"></span>
                            <input type="hidden" name="a_region" value="<?php echo ($authInfo["a_region"]); ?>"/>
                        </li>
                        <li class="libox">
                            <label class="ila">简介：</label>
                            <textarea name="t_intro" name="a_note" value="<?php echo ($authInfo["a_note"]); ?>"></textarea>
                        </li>
                        <li class="libox">
                            <label class="ila">手机号码：</label>
                            <input type="text" name="a_tel" value="<?php echo ($authInfo["a_tel"]); ?>">
                            <span></span>
                        </li>
                        <li class="sub libox">
                            <input type="submit" name="save" value="保存信息">
                        </li>
                    </ul>
                </div>
            </form>
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