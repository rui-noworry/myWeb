<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/public.css" /><script type="text/javascript" src=" /Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src=" /Public/Js/Public/public.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
    <script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
    <!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/Js/Public/png.js" ></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#logo,.cShare,.cEdit,.cIn,.cClone,.cExport,.cDel,.fw_baoming_left,.fw_btn,.anli_ico_link,.anli_ico,.selected,.selected_green,.selected_gray,.to-left,.to-right,.current,.mt_tab li,.classhomework_top li,.choose_class,.current img,.res_click,.res_scan,.res_frame.png,#main_bg li img,.jCal .left,.jCal .right');
    </script>
    <![endif]-->
    <title>大课堂互动教学</title>
    <script><!--//

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

            // 登陆弹出窗口
            /*$("#login").dialog({
                draggable: true,
                resizable: true,
                autoOpen: false,
                position :'center',
                stack : true,
                modal: true,
                bgiframe: true,
                width: '450',
                height: 'auto',

                show: {
                    effect: "blind",
                    duration: 500
                },
                hide: {
                  effect: "explode",
                  duration: 500
                },
                overlay: {
                    backgroundColor: '#000',
                    opacity: 0.5
                },
                buttons: {
                    确定: function() {
                        $(this).dialog('close');
                    },
                    取消: function() {
                        $(this).dialog('close');
                    }
                }
            });

            $('.loginin').on('click',function(){
                $("#login").dialog("open");
            })*/

            // 弹出登陆窗口
            $(".loginin").on('click',function () {
                popCenterWindow();
            });

            $('input[name=account]').blur(function() {
                $(".conrig_error span").hide();
            })

            $('input[name=password]').blur(function() {
                $(".conrig_error span").hide();
            })

            // 登陆验证
            $('.conrig_land').click(function(){

                // 用户名不能为空判断
                var account = $("input[name=account]").val();
                if (account=='') {
                    $(".conrig_error span").html("请输入用户名");
                    $(".conrig_error span").show();
                    $("input[name=name]").focus();
                    return false;
                } else {
                    $(".conrig_error span").hide();
                }

                // 密码不能为空判断
                var password = $("input[name=password]").val();
                if (password=='') {
                    $(".conrig_error span").html("请输入密码");
                    $(".conrig_error span").show();
                    $("input[name=password]").focus();
                    return false;
                } else {
                    $(".conrig_error span").hide();
                }

                // 是否记住登录状态
                var remember = $("input[name=remember]:checked").size();

                // 验证码
                var verify = '';
                if ($("#verify").css('display') == 'none') {
                    verify = 0;
                } else {
                    verify = $("input[name=verify]").val();
                    if (!verify) {
                        $(".conrig_error span").html("请输入验证码");
                        $(".conrig_error span").show();
                        $("input[name=verify]").focus();
                        return false;
                    }
                }

            })

            if ($('#header .exit').size() == 0) {
                // ajax登录
                $.post("/Public/checkLogin", 'num='+Math.random(), function(json) {

                    if (json.status == 0) {
                        $(".conrig_error").html(json.message);
                        $("#verify").show();
                        $('#login').height(340);
                    } else {

                        $('#header .nav').next().attr('class', 'exit').attr('href', '__APPURL__/Public/logout').html('[退出]');
                        $('<a class="member" href="__APPURL__/School">会员中心</a>').insertBefore($('.download'));
                        $('.closeWin').click();

                    }
                }, 'json')
            }

            // 关闭登陆窗口
            /*$('.closeWin').click(function(){
                $('#login').dialog('close');
            })*/


            // 设置登陆窗口遮罩层的宽和高
            $("#Win_cover").css({
                height: function () {
                    return $(document).height();
                },
                width: function () {
                    return $(document).width();
                }
            })
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
            $(".verifyImg").attr('src', '__APPURL__/Public/verify/'+ Math.random());
        }
        //-->

        //获取窗口的宽度
        var windowWidth;
        //获取窗口的高度
        var windowHeight;
        //获取弹窗的宽度
        var popWidth;
        //获取弹窗高度
        var popHeight;
        function init(){
            windowWidth = $(window).width();
            windowHeight = $(window).height();
            popWidth = $("#login").width();
            popHeight = $("#login").height();
        }
        //关闭窗口的方法
        function closeWindow(){
            $(".closeWin").click(function(){
                $(this).parent().fadeOut("slow");
                $("#Win_cover").hide();
            });
        }
        //定义弹出居中窗口的方法
        function popCenterWindow(){
            init();

            //计算弹出窗口的左上角Y的偏移量
            var popY = (windowHeight - popHeight) / 2;
            var popX = (windowWidth - popWidth) / 2;
            //alert(popX+"@@@@@@@@"+popY);
            //设定窗口的位置
            $("#Win_cover").show();
            $("#login").css("top",popY).css("left",popX).slideToggle("slow");
            closeWindow();
       }

    </script>
</head>
<body id="body">
    <div id="header">
        <div>
            <a href="__APPURL__/Index/" id="logo"></a>
            <ul class="nav">
                <?php if(($studyOn) != ""): ?><li><a <?php if(($bannerOn) == "1"): ?>class="on"<?php endif; ?> href="<?php echo ($studyOn); ?>/Course">课程中心</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "2"): ?>class="on"<?php endif; ?> href="<?php echo ($resourceOn); ?>">资源中心</a></li>
                <?php if(($studyOn) != ""): ?><li><a <?php if(($bannerOn) == "3"): ?>class="on"<?php endif; ?> href="<?php echo ($studyOn); ?>/Space">我的空间</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "4"): ?>class="on"<?php endif; ?> href="javascript:;">应用中心</a></li>
            </ul>
            <?php if((intval($authInfo['a_id'])) != "0"): ?><a href="/Public/logout" class="exit">[退出]</a>
                <a class="member" href="__APPURL__/School">会员中心</a>
            <?php else: ?>
                <a class="loginin">登陆</a><?php endif; ?>
            <a href="/Client/download" title="客户端下载" class="download">客户端下载&nbsp;&nbsp;</a>
        </div>
    </div>
<!-- 登陆窗口 -->
<div id="Win_cover">
    <div id="login" title="用户登陆">
        <div class="closeWin"></div>
        <form method="post" name="form1" id="form1" action="">
            <div class="conrig_error"><span></span></div>
            <div class="conrig_name">
                <input type="text" value="" name="account" class="name_inp" placeholder="用户名"/>
            </div>
            <div class="conrig_name">
                 <input type="password" value="" name="password" class="name_inp" onkeydown="keydown(event)" placeholder="密码"/>
            </div>
            <div id="verify" style="display:none">
                  <input type="text" id="verify" name="verify" onkeydown="keydown(event)" placeholder="验证码"/></li>
                  <img src="__APPURL__/Public/verify/" class="verifyImg" onclick="fleshVerify();" border="0">
            </div>
            <div style="clear:both"></div>

            <div class="conrig_remember fl">
                <input name="remember" type="checkbox" class="fl" />记住密码
            </div>
            <a href="#" class="conrig_forget fr">忘记密码？</a>
            <div class="clear"></div>
            <a href="javascript:void(0)" class="conrig_land fl"></a>
            <a href="__APPURL__/Public/register" class="conrig_enroll fl"></a>
        </form>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/res_publish.css" />
<script>
$(function(){

    // 默认属性选中
    $('.pub_res span').each(function(){

        if ($(this).hasClass('on')) {
            $(this).parent().find('input:first').val($(this).text());
            $(this).parent().find('input:last').val($(this).attr('attr'));
        }
    });

    //发布到我的资源库 点击
    $('.goOn').click(function(){
        $('.pub_main').slideToggle('slow');
    })

    //名称 点击
    $(document).on('click','.pub_on .i_look',function(){
        $(this).parent().next('.pub_cor').slideDown().end().parents('.pub_ch').siblings().children('.pub_cor').slideUp();
    })

    //属性点击
    $(document).on('click','.pub_res span',function(){
        if ($(this).hasClass('on')){
            $(this).removeClass('on');
            $(this).prev().val('');
            $(this).next().val('');
        } else {
            $(this).addClass('on');
            $(this).siblings('span').removeClass('on');
            $(this).parent().find('input:first').val($(this).text());
            $(this).parent().find('input:last').val($(this).attr('attr'));
        }
    })

    // 栏目选择
    $(document).on('click', '.pub_cor .choose', function() {
        $(this).parents('.pub_ch').addClass('on').siblings().removeClass('on');
        getChild(0, 1);
    })

    // 获取子栏目
    $(document).on('click', '.list li', function() {

        var level = $(this).attr('level');
        $('.list li').each(function() {
            if ($(this).attr('level') > level) {
                $(this).remove();
            }
        })
        $(this).addClass('on').siblings().removeClass('on');
        getChild($(this).attr('attr'));
    })

    $('.pub_pub').click(function(){
        location.href="__URL__/";
    });

})

// 提交验证
function check() {

    var flag = false;
    $('.pub_res input').each(function() {

        if ($(this).attr('attr') == 1 && $(this).val() == '') {
            showMessage('您有未填写的属性值');
            flag = true;
        }

    });

    if (flag) {
        return false;
    }

    $('.points').each(function(){

        if (!/^\d+$/g.test($(this).val()) || $(this).val() > 50 || $(this).val() < 0) {
            showMessage('请正确的填写下载积分');
            flag = true;
        }
    });

    if (flag) {
        return false;
    }

    $('.rc_id').each(function(){

        if ($(this).val() == '') {
            showMessage('请选择资源所属栏目');
            flag = true;
        }
    });

    if (flag) {
        return false;
    }

    return true;

}

function getChild(id, type) {

    var str = '';
    if (type == 1) {
        if ($('.pub_ch.on .list li').size() == 0) {
            if (<?php echo ($sch); ?>) {
                str += '<li attr="0" level="0" type="<?php echo ($sch); ?>">本校栏目</li>';
            }
            if (<?php echo ($sys); ?>) {
                str += '<li attr="0" level="0" type="0">系统栏目</li>';
            }

            $('.pub_ch.on .list ul').html(str);
            $('.pub_ch.on .list').show();
            return;
        } else {
            $('.pub_ch.on .list').show();
        }
    } else {

        if ($('.pub_ch.on .list').css('display') == 'none') {
            $('.pub_ch.on .list').show();
        } else {
            $.post('__URL__/findSub', 'id='+id, function(json) {
                var str = '';

                if (json) {
                    $.each(json, function(i, data){

                        if (data['rc_pid'] == id && data['s_id'] == $('.pub_ch.on .list li.on').attr('type')) {
                            str += '<li attr="' + data['rc_id'] + '" level="' + data['rc_level'] + '" type="' + data['s_id'] + '">' + getLevel(data['rc_level']) + data['rc_title'] + '</li>';
                        }
                    })
                }

                if (!str) {
                    var t_val = $('.pub_ch.on .list li.on').html();
                    var reg=/&nbsp;/g;
                    var new_val = t_val.replace(reg,'');
                    $('.pub_ch.on .list li.on').parents('.list').prev().children('.choose').html(new_val);
                    $('.pub_ch.on .list li.on').parents('.list').prev().prev().val($('.pub_ch.on .list li.on').attr('attr'));
                    $('.pub_ch.on .list.on').removeClass('on');$('.pub_ch.on').removeClass('on');
                    $('.list').hide();
                } else {
                    $(str).insertAfter($('.pub_ch.on .list li.on'));
                    $('.pub_ch.on .list').show();
                }

            }, 'json')
        }
    }
}

function getLevel(level) {

    var result = '';
    for (var i = 0; i < level; i ++) {

        result += '&nbsp;&nbsp;&nbsp;&nbsp;';
    }

    return result;
}
</script>
<div class="warp">
    <div id="left_sider">
    <div class="main_user">
        <a href="#"><img src="<?php echo (getauthavatar($authInfo["a_avatar"],$authInfo['a_type'],$authInfo['a_sex'],96)); ?>"/></a>
        <div class="info">
            <a href="__APPURL__/Auth/index" class="name fl" title="<?php echo ($authInfo["a_nickname"]); ?>"><?php echo ($authInfo["a_nickname"]); ?></a>
            <a href="__APPURL__/Auth/index" class="university fl"><?php echo ($school); ?></a>
            <a href="__APPURL__/Auth/index" class="mody_data fl"><span>修改资料</span></a>
        </div>
    </div>

    <div class="main_app">
        <p class="title">
            <cite></cite>
            <span>我的应用</span>
        </p>
       <ul>
            <?php if(is_array($myapps)): $i = 0; $__LIST__ = $myapps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$myapp): $mod = ($i % 2 );++$i; if(($myapp["title"]) != ""): ?><li><a href="<?php echo ($myapp["url"]); ?>"><?php echo ($myapp["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <!-- <li><a id="add_app">添加</a></li> -->
        </ul>
    </div>

    <?php if(!empty($crowds)): ?><div class="main_group">
            <p class="title">
                <cite></cite>
                <span>我的群组</span>
            </p>

            <?php if(is_array($crowds)): $i = 0; $__LIST__ = $crowds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crow): $mod = ($i % 2 );++$i;?><ul class="group">
                    <li>
                        <a href='javascript:void(0);'><img src="__APPURL__/Images/Tmp/groupAvatar.png"></a>
                        <a href="javascript:void(0);" class="gname"><?php echo ($crow["cro_title"]); ?></a>
                        <a href="javascript:void(0);" class="gtag">创建时间：<?php echo (date("Y-m-d",$crow["cro_created"])); ?></a>
                    </li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>

            <a class="addGroup" href="Crowd/"></a>
            <span class="clear"></span>
        </div><?php endif; ?>
</div>

    <div class="pub_right">
        <?php if(($dis) == "0"): ?><h3>待发布的资源</h3>
        <button style="margin-left:20px;" class="pub_pub">继续上传</button>
        <button class="goOn">发布到资源库</button>
        <div class="clear"></div>
        <div class="pub_main" style="display:none;">
        <?php else: ?>
        <div class="pub_main"><?php endif; ?>
            <form action="__URL__/publish" name="form" onSubmit="return check();" method="post">

                <?php if(is_array($authResource)): $k = 0; $__LIST__ = $authResource;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$resource): $mod = ($k % 2 );++$k;?><div class="pub_ch">


                        <input name="ar_id[]" value="<?php echo ($resource["ar_id"]); ?>" type="hidden" />

                        <div class="pub_name pub_on">
                            <input type="hidden" value="<?php echo ($resource["ar_title"]); ?>" class="re_title"/>
                            <label>文件名称：</label>
                            <p class="pub_p"><?php echo ($resource["ar_title"]); ?></p>
                        </div>
                        <div class="pub_cor">
                            <input name="rc_id[]" class="rc_id" value="" type="hidden" />
                            <div class="pub_name">
                                <label>栏目：</label>
                                <span class="choose">选择</span>
                            </div>
                            <div class="list">
                                <ul>

                                </ul>
                            </div>

                            <div class="two_level" style="display:block">

                            </div>
                            <div class="pub_name">
                                <label>类型：</label>
                                <i class="pub_model" attr="<?php echo ($resource["m_id"]); ?>"><?php echo ($resource["m_title"]); ?></i>
                            </div>
                            <?php if(is_array($attribute)): $key1 = 0; $__LIST__ = $attribute;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$atr): $mod = ($key1 % 2 );++$key1; if(in_array(($atr["at_id"]), is_array($resource["m_list"])?$resource["m_list"]:explode(',',$resource["m_list"]))): ?><div class="pub_name pub_res">
                                    <label><?php echo ($atr["at_title"]); if(($atr["at_is_required"]) == "1"): ?><cite style="color:red">*</cite><?php endif; ?>：</label>
                                        <?php if(($atr["at_type"]) == "1"): ?><input type='text' name="text[<?php echo ($k); ?>][<?php echo ($key1); ?>][are_value]" value='<?php echo ($atr["at_value"]); ?>' attr='<?php echo ($atr["at_is_required"]); ?>'/>
                                            <input type='hidden' name="text[<?php echo ($k); ?>][<?php echo ($key1); ?>][are_name]" value='<?php echo ($atr["at_name"]); ?>'/><?php endif; ?>
                                        <?php if(($atr["at_type"]) == "2"): ?><input type='hidden' name="text[<?php echo ($k); ?>][<?php echo ($key1); ?>][are_value]" value=''/>
                                            <?php if(is_array($atr["at_extra"])): $i = 0; $__LIST__ = $atr["at_extra"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$extra): $mod = ($i % 2 );++$i;?><span attr="<?php echo ($atr["at_name"]); ?>" <?php if(($extra) == $atr["at_value"]): ?>class="on"<?php endif; ?>><?php echo ($extra); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                                            <input type='hidden' name="text[<?php echo ($k); ?>][<?php echo ($key1); ?>][are_name]" value=''/><?php endif; ?>
                                    </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                            <div class="pub_name pub_res">
                                <label>下载积分：</label>
                                <input type="text" name="re_points[]" value="0" class="points" />
                            </div>

                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                <button class="pub_hold pub_ser">发布</button>
            </form>
        </div>

    </div>

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