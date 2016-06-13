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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/index.css" />
<script type="text/javascript">
<!--
    $(function() {

        // 选中
        $('.search_box span').click(function(){

            $(this).addClass('on').siblings().removeClass('on');
        })

        // 显示高级搜索
        $('.common_search .gj').click(function(){

            $('.high_search li').each(function() {
                $(this).find('span').eq(0).click();
            })

            $('.ks').toggle();
            $('.high_search').slideToggle();

        })

        // 高级搜索 点击取消 收起
        $('.cancel').click(function(){

            $(this).parent().parent().slideUp();
            $('.ks').show();
        })

        $('.ks').click(function() {
            search();
        })

        $('.confirm').click(function(){
            search();
        })

        // 资源模块切换
        $(".res_tab li").click(function(){
            $(this).show();
            $(this).addClass("current").siblings().removeClass("current");
            var index =  $(".res_tab li").index(this);
            $(".res_box div.mould").eq(index).show().siblings().hide();
        })

        if ($('.res_tab li').size() == 4) {
            $(".res_tab li").eq(0).click();
        }

        $.getJSON('__APPURL__/html/new.txt', function(json) {
            setTabList(json, 1);
        })

        $.getJSON('__APPURL__/html/hot.txt', function(json) {
            setTabList(json, 2);
        })

        $.getJSON('__APPURL__/html/recommend.txt', function(json) {
            setTabList(json, 3);
        })

    })

    function setTabList(json, order) {

        if (json.length > 0) {

            var str = '<a class="more fr" href="__APPURL__/Search/index/order/' + order + '">更多</a><ul>';
            $.each(json, function(i, data){

                str += '<li><a class="res_cover" href="__APPURL__/Resource/index/id/'+data['re_id']+'" target="_blank"><img src="'+data['re_img']+'" width="100" height="75"/></a><a class="res_title" target="_blank" href="__APPURL__/Resource/index/id/'+data['re_id']+'" title="'+data['re_title']+'">'+data['re_title']+'</a><span>'+data['a_nickname']+'</span><span><i>'+data['re_download_points']+'</i>积分</span></li>';
            })

            str += '</ul>';

            $('.res_tab').next().find('.show' + order).html(str);
            $('.res_tab .show' + order).show();

            if (!$('.res_tab li').eq(0).hasClass('current')) {
                $('.res_tab li').eq(0).click();
            }
        } else {
            $('.res_tab').next().find('.show' + order).remove();
            $('.res_tab .show' + order).remove();
        }
    }

    function search() {
        var keywords = $('input[name=keywords]').val();
        var resFrom = $('.resFrom span.on').attr('attr');
        var resType = $('.resType span.on').attr('attr');

        var str = '';

        if (keywords) {
            str += 'keywords/' + keywords + '/';
        }

        if (resFrom > 0) {
            str += 'resFrom/' + resFrom + '/';
        }

        if (resType > 0) {
            str += 'resType/' + resType + '/';
        }

        str = str.slice(0, -1);

        if (!str) {
            return false;
        }

        location.href="__APPURL__/SchoolSearch/index/" + str;
    }
//-->
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
        <div class="res_right">
        <p class="file_num">当前已有<cite><?php echo ($total); ?></cite>份文档</p>
        <!-- 搜索开始 -->
        <div class="search_box">
            <div class="common_search">
                <input type="text" value="<?php echo ($keywords); ?>" name="keywords" placeHolder="资源名称">
                <a class="gj"></a>
                <a class="ks"></a>
            </div>
            <div class="high_search hide">
                <ul>
                    <!--li class="resFrom">
                        <label>来源</label>
                        <div>
                            <span class="hand on" attr="1">系统</span>
                            <span class="hand" attr="2">数字学校</span>
                        </div>
                    </li-->
                    <li class="resType">
                        <label>资源类型</label>
                        <div>
                            <span class="hand on" attr="0">全部</span>
                            <?php if(is_array($model)): $i = 0; $__LIST__ = $model;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$model): $mod = ($i % 2 );++$i;?><span class="hand" attr="<?php echo ($model["m_id"]); ?>"><?php echo ($model["m_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </li>
                </ul>
                <div class="btn">
                    <a class="confirm">确定</a>
                    <a class="cancel">取消</a>
                </div>
            </div>
        </div>
        <!-- 搜索结束 -->
        <!-- 资源列表开始 -->
        <div class="res_list fl">
            <ul class="res_tab">
                <?php if(($cate) != ""): ?><li class="current">订阅资源</li><?php endif; ?>
                <li class="show1">最新资源</li>
                <li class="show2">最热资源</li>
                <li class="show3">推荐资源</li>
            </ul>
            <div class="res_box fl">
                <?php if(($cate) != ""): ?><div class="mould">
                        <?php if(is_array($cate)): $i = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i; if(($cate["lists"]) != ""): ?><ul class="column1">
                                    <div class="column_title"><?php echo ($cate["rc_title"]); ?><a class="more fr" href="__APPURL__/SchoolSearch/index/rc/<?php echo ($cate["rc_id"]); ?>">更多</a></div>
                                    <?php if(is_array($cate["lists"])): $i = 0; $__LIST__ = $cate["lists"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clist): $mod = ($i % 2 );++$i;?><li>
                                            <a class="res_cover" href="__APPURL__/Resource/index/id/<?php echo ($clist["re_id"]); ?>" target="_blank"><img src="<?php echo ($clist["re_img"]); ?>" width="100" height="75"/></a>
                                            <a class="res_title" target="_blank" href="__APPURL__/Resource/index/id/<?php echo ($clist["re_id"]); ?>" title="<?php echo ($clist["re_title"]); ?>"><?php echo ($clist["re_title"]); ?></a>
                                            <span><?php echo ($clist["a_nickname"]); ?></span>
                                            <span><i><?php echo ($clist["re_download_points"]); ?></i>积分</span>
                                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            <?php else: ?>
                                <ul class="column2">
                                    <div class="column_title"><?php echo ($cate["rc_title"]); ?></div>
                                    <span class="alert">本栏目下暂无资源</span>
                                </ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </div><?php endif; ?>
                <div class="mould hide show1"></div>
                <div class="mould hide show2"></div>
                <div class="mould hide show3"></div>
            </div>
        </div>
        <!-- 资源列表结束 -->
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