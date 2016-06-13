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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/image.css" />
<script type="text/javascript">
<!--

    $(function() {

        // 下载按钮鼠标移上样式
        $('.btn-download').mouseover(function(){
            $(this).find('.button-right').css("background","url('__APPURL__/Public/Images/Home/pic-tool.png') 0px -11px");
        }).mouseout(function(){
            $(this).find('.button-right').css("background","url('__APPURL__/Public/Images/Home/pic-tool.png') 0px 0px");
        })
        // 全屏显示鼠标移上样式
        $('.full-screen').mouseover(function(){
            $(this).find('.button-right').css("background","url('__APPURL__/Public/Images/Home/pic-tool.png') -11px -11px");
        }).mouseout(function(){
            $(this).find('.button-right').css("background","url('__APPURL__/Public/Images/Home/pic-tool.png') -11px 0px");
        })

        // 推荐标签 鼠标移过样式
        $('.tagRP li').mouseover(function(){
            if(!$(this).hasClass('hot-on')){
                $(this).addClass('hot-on').removeClass('off');
            }
        }).mouseout(function(){
            if(!$(this).hasClass('hot')) {
                $(this).removeClass('hot-on').addClass('off');
            }
        })

        //点击小图预览大图
        $(".imgList li").click(function(){
            var msrc = $(this).children('img').attr("src");
            msrc = msrc.replace('/100/', '/600/');
            $('.bigPics .picWarp li').html("<img src="+msrc+" width='600' height='450'>");
        }).eq(0).click()

        var line = 1;
        var speed = 300;
        var lineW = $('.imgList').find("li:first").width();
        var m = line;    //用于计算的变量
        var count = $('.imgList li').length;    //总共的<li>元素的个数
        var lineB = 6;    //一屏显示的个数
        var step = 13;
        var i = 1;
        var target = <?php echo ($jsPath); ?>;

        // 左翻
        $('.slidePre').click(function(){
            if (!$('.smallImgs').is(":animated")) {
                if (m > line) {
                    m -= line;
                    $('.smallImgs').animate({left: "+=" + (lineW + step) + "px" }, speed);
                }else {
                    showMessage("已经是第一张了");
                }
            }
        })

        // 右翻
        $('.slideNext').click(function(){
            if (!$('.smallImgs').is(":animated")) {
                if (m < count - lineB) {
                    m += line;
                    $('.smallImgs').animate({left: "-=" + (lineW + step) +"px" }, speed);
                }else {
                    showMessage("已经是最后一张了");
                }
            }
        })

        //大图向左翻看
        $('.to-left').click(function(){
            // 小图移动
            if (m > line) {
                m -= line;
                $('.smallImgs').animate({left: "+=" + (lineW + step) +"px" }, speed);
            }

            // 大图移动
            if(i > 1) {
                if (/\/100\//.test(target[i-2])) {
                    target[i-2] = target[i-2].replace('100', '600');
                }
                $('.picWarp li img').attr("src", target[i-2]);
                i --;
                return false;
            }else {
                showMessage("已经是第一张了");
            }
        })

        //大图向右翻看
        $('.to-right').click(function(){
            // 小图移动
            if (m < count - lineB) {
                m += line;
                $('.smallImgs').animate({left: "-=" + (lineW + step) + "px" }, speed);
            }

            // 大图移动
            if(i < count) {
                if (/\/100\//.test(target[i])) {
                    target[i] = target[i].replace('100', '600');
                }
                $('.picWarp li img').attr("src", target[i]);
                i ++;
                return false;
            }else {
                showMessage("已经是最后一张了");
            }
        })

        // 收藏
        $(document).on('click', '.addfav-btn .button-label', function () {

            // 查看是否登录
            if ($('.exit').html()) {
                $.post('__APPURL__/ResourceCollect/insert/', {re_id:$('input[name=re_id]').val()}, function (json) {
                    showMessage(json.info, 1);
                }, 'json');
            } else {

                popCenterWindow();
            }
        })

        // 下载
        $(document).on('click', '.btn-download .button-label', function () {

            // 查看是否登录
            if ($('.exit').html()) {
                location.href = "__APPURL__/MyResource/download?id=" + $('input[name=re_id]').val();
            } else {

                popCenterWindow();
            }
        })

        // 资源排行
        $.getJSON('/html/today.txt', function (json) {
            if (json) {
                var htm = '';
                for (var i=0, len=json.length; i<len; i++) {
                        htm += '<li>'
                            +  '<span class="good">' + (i+1) + '</span>'
                            +  '<label title="' + json[i]['re_title'] + '"><a href="__APPURL__/Resource/index/id/' + json[i]['re_id'] + '" target="blank">' + json[i]['re_title'] + '</a></label>'
                            +  '<cite class="scro"></cite>'
                            +  '</li>'
                }
                $('.ResourceSort').append(htm);
            }
        });

    })

//-->
</script>
<div class="warp">
    <div class="pic_box fl">
        <div class="main-box">
            <!-- 左侧开始 -->
            <div class="main-left">
                <!-- 头部 -->
                <div class="pic-top-bar">
                     <ul class="breadcrumb">
                        <li><?php echo ($cate); ?> &gt&gt <?php echo ($res["re_title"]); ?></li>
                        <input type="hidden" name="re_id" value="<?php echo ($res["re_id"]); ?>">
                     </ul>
                     <div class="title-btns">
                        <a class="addfav-btn" href="javascript:void(0);">
                            <span class="button-label">收藏</span>
                            <span class="button-right"></span>
                        </a>
                        <a class="btn-download" href="javascript:void(0);">
                            <span class="button-label">下载</span>
                            <span class="button-right"></span>
                        </a>
                        <!-- <a class="full-screen" href="javascript:void(0);">
                            <span class="button-label">全屏</span>
                            <span class="button-right"></span>
                        </a> -->
                     </div>
                </div>
                <!-- 图片展示开始 -->
                <div class="pic_show">
                    <div class="bigPics fl">
                        <div class="to-left"><span></span></div>
                        <div class="to-right"><span></span></div>
                        <ul class="picWarp fl">
                            <li></li>
                        </ul>
                    </div>
                    <div class="smallPics fl">
                        <div class="slidePre"><span></span></div>
                        <div class="smallImgs">
                            <ul class="imgList">
                                <?php if(is_array($image)): $i = 0; $__LIST__ = $image;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><div class="bg"></div><img src="<?php echo ($vo["re_savename"]); ?>" alt="<?php echo ($vo["re_title"]); ?>" width="100" height="75" border="0"></li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div>
                        <div class="slideNext"><span></span></div>
                    </div>
                </div>
                <!-- 图片展示结束 -->
            </div>
            <!-- 左侧结束 -->
            <!-- 右侧开始 -->
            <div class="main-right">
                <div class="res_ranking">    <!-- 资源排行榜 -->
                    <p class="title">资源排行榜</p>
                    <ul class="ResourceSort">
                    </ul>
                </div>
            </div>
            <!-- 右侧结束 -->
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