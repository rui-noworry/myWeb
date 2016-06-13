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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/adviser.css" />
<script>
$(function(){
    // 窗口隐藏
    $('.hon_message').hide();

    // 窗口显示
    $('.group_content').mouseover(function(){
        $(this).children('.hon_message').show();
        $(this).css('z-index', '99');
    })

    // 窗口隐藏
    $('.group_content').mouseout(function(){
        $(this).children('.hon_message').hide();
        $(this).css('z-index', '0');
    })

    //周，月，总切换
    $('.honor_among li').click(function(){
        $(this).addClass('honor_click').siblings().removeClass('honor_click');
        $('.hon_tab').children('.hon_center').eq($(this).index()).show().siblings().hide();
    }).eq(0).click();

    $('.tab_return').click(function() {
        location.href = "__URL__";
    })
})
</script>
<div class="warp">
    <?php if($authInfo['a_type'] == 2): ?><div id="left_sider">
    <div class="main_user">
        <a href="#"><img src="<?php echo (getauthavatar($authInfo["a_avatar"],$authInfo['a_type'],$authInfo['a_sex'],96)); ?>"/></a>
        <div class="info">
            <a href="__APPURL__/Auth/index" class="name fl" title="<?php echo ($authInfo["a_nickname"]); ?>"><?php echo ($authInfo["a_nickname"]); ?></a>
            <a href="javascript:void(0);" class="university fl"><?php echo ($authInfo["s_info"]["s_name"]); ?></a>
            <a href="__APPURL__/Auth/index" class="mody_data fl"><span>修改资料</span></a>
        </div>
    </div>

    <div class="main_app">
        <p class="title">
            <cite></cite>
            <span>我的应用</span>
        </p>
        <ul>
            <?php if(is_array($myapps)): $i = 0; $__LIST__ = $myapps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$myapp): $mod = ($i % 2 );++$i; if(($myapp["title"]) != ""): ?><li><a href="<?php echo ($myapp["url"]); ?>" title=<?php echo ($myapp["title"]); ?>><?php echo ($myapp["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            <!-- <li><a id="add_app">添加</a></li> -->
        </ul>
    </div>

    <!--div class="main_nav">
        <p class="title">
            <cite></cite>
            <span>快捷导航</span>
        </p>
        <ul>
            <?php if(is_array($navs)): $i = 0; $__LIST__ = $navs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$na): $mod = ($i % 2 );++$i;?><li><a href='<?php echo ($na["na_url"]); ?>' target="_blank"><?php echo ($na["na_title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div-->

    <?php if(!empty($crowds)): ?><div class="main_group">
            <p class="title">
                <cite></cite>
                <span>我的群组</span>
            </p>

            <?php if(is_array($crowds)): $i = 0; $__LIST__ = $crowds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crow): $mod = ($i % 2 );++$i;?><ul class="group">
                    <li>
                        <a href='javascript:void(0);'><img src="__APPURL__/Public/Images/Tmp/groupAvatar.png"></a>
                        <a href="javascript:void(0);" class="gname"><?php echo ($crow["cro_title"]); ?></a>
                        <a href="javascript:void(0);" class="gtag">创建时间：<?php echo (date("Y-m-d",$crow["cro_created"])); ?></a>
                    </li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>

            <a class="addGroup" href="Crowd/"></a>
            <span class="clear"></span>
        </div><?php endif; ?>
</div>
<?php else: ?>
<div id="left_sider">
    <div class="main_user">
        <a href="#"><img src="<?php echo (getauthavatar($authInfo["a_avatar"],$authInfo['a_type'],$authInfo['a_sex'],96)); ?>"/></a>
        <div class="info">
            <a href="__APPURL__/Auth/index" class="name fl"><?php echo ($authInfo["a_nickname"]); ?></a>
            <a href="__APPURL__/Auth/index" class="university fl"><?php echo ($school); ?></a>
            <a href="__APPURL__/Auth/index" class="mody_data fl">修改资料</a>
        </div>
    </div>

    <div class="main_app">
        <p class="title">
            <em></em>
            <span>我的老师</span>
        </p>
        <ul class="students_app">
            <?php if(is_array($myTeacher)): $i = 0; $__LIST__ = $myTeacher;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$teacher): $mod = ($i % 2 );++$i;?><li title=<?php echo ($teacher["a_nickname"]); ?> class='my_nou'><a href="javascript:void(0);"><?php echo ($teacher["a_nickname"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <div class="main_app main_nav">
        <p class="title">
            <em class="students_use"></em>
            <span>我的应用</span>
        </p>
        <ul>
            <?php if(is_array($myapps)): $i = 0; $__LIST__ = $myapps;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$myapp): $mod = ($i % 2 );++$i; if(($myapp["title"]) != ""): ?><li><a href="<?php echo ($myapp["url"]); ?>" title=<?php echo ($myapp["title"]); ?>><?php echo ($myapp["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>

    <?php if(!empty($crowds)): ?><div class="main_group">
            <p class="title">
                <cite></cite>
                <span>我的群组</span>
            </p>

            <?php if(is_array($crowds)): $i = 0; $__LIST__ = $crowds;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crow): $mod = ($i % 2 );++$i;?><ul class="group">
                    <li>
                        <a href='javascript:void(0);'><img src="__APPURL__/Public/Images/Tmp/groupAvatar.png"></a>
                        <a href="javascript:void(0);" class="gname"><?php echo ($crow["cro_title"]); ?></a>
                        <a href="javascript:void(0);" class="gtag">创建时间：<?php echo (date("Y-m-d",$crow["cro_created"])); ?></a>
                    </li>
                </ul><?php endforeach; endif; else: echo "" ;endif; ?>

            <a class="addGroup" href="Crowd/"></a>
            <span class="clear"></span>
        </div><?php endif; ?>
</div><?php endif; ?>
    <ul class="top_tab_menu fl">
        <li class="tab_return">返回</li>
        <li class="tab_name"><?php echo (replaceclasstitle($class["s_id"],$class['c_type'],$class['c_grade'],$class['c_title'],$class['is_graduation'])); ?></li>
        <li onclick="javascript:location.href='__URL__/student/id/<?php echo ($class["c_id"]); ?>'">同学们</li>
        <li onclick="javascript:location.href='__URL__/group/id/<?php echo ($class["c_id"]); ?>'">小组</li>
        <?php if(($isBeanOpen) == "1"): ?><li class="selected">光荣榜</li><?php endif; ?>
        <li onclick="javascript:location.href='__URL__/syllabus/id/<?php echo ($class["c_id"]); ?>'">课程表</li>
    </ul>
    <div class="creat_c_mainBox">
        <ul class="honor_among">
            <li class="honor_click">
                <span>本周排行</span>
            </li>
            <li>
                <span>月排行</span>
            </li>
            <li>
                <span>总排行</span>
            </li>
        </ul>
        <div class="clear"></div>
        <div class="hon_tab">
            <!--tab1-->
            <div class="hon_center">
                <?php if(is_array($weekBean)): $i = 0; $__LIST__ = $weekBean;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$wb): $mod = ($i % 2 );++$i;?><div class="group_content">
                        <div class="hon_message">
                            <div class="group_con">
                                <h3><span><?php echo ($wb["a_nickname"]); ?></span>在本周：</h3>
                                <?php if(is_array($wb["list"])): $i = 0; $__LIST__ = $wb["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$wl): $mod = ($i % 2 );++$i;?><p><?php echo ($wl); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                        <img src="<?php echo (getauthavatar($wb["a_avatar"],$wb['a_type'],$wb['a_sex'],96)); ?>" class="fl"/>
                        <div class="hon_stu">
                            <p class="stu_bold"><?php echo ($wb["a_nickname"]); ?></p>
                            <p>本周新增：<span class="hon_content"><?php echo ($wb["total"]); ?>颗</span>&nbsp;智慧豆</p>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <!--tab2-->
            <div class="hon_center">
                <?php if(is_array($monthBean)): $i = 0; $__LIST__ = $monthBean;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$mb): $mod = ($i % 2 );++$i;?><div class="group_content">
                        <div class="hon_message">
                            <div class="group_con">
                                <h3><span><?php echo ($mb["a_nickname"]); ?></span>在本月：</h3>
                                <?php if(is_array($mb["list"])): $i = 0; $__LIST__ = $mb["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ml): $mod = ($i % 2 );++$i;?><p><?php echo ($ml); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                        <img src="<?php echo (getauthavatar($mb["a_avatar"],$mb['a_type'],$mb['a_sex'],96)); ?>" class="fl"/>
                        <div class="hon_stu">
                            <p class="stu_bold"><?php echo ($mb["a_nickname"]); ?></p>
                            <p>本月新增：<span class="hon_content"><?php echo ($mb["total"]); ?>颗</span>&nbsp;智慧豆</p>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <!--tab3-->
            <div class="hon_center">
                <?php if(is_array($allBean)): $i = 0; $__LIST__ = $allBean;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ab): $mod = ($i % 2 );++$i;?><div class="group_content">
                        <div class="hon_message">
                            <div class="group_con">
                                <h3><span><?php echo ($ab["a_nickname"]); ?></span></h3>
                                <?php if(is_array($ab["list"])): $i = 0; $__LIST__ = $ab["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$al): $mod = ($i % 2 );++$i;?><p><?php echo ($al); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                        <img src="<?php echo (getauthavatar($ab["a_avatar"],$ab['a_type'],$ab['a_sex'],96)); ?>" class="fl"/>
                        <div class="hon_stu">
                            <p class="stu_bold"><?php echo ($ab["a_nickname"]); ?></p>
                            <p>共新增：<span class="hon_content"><?php echo ($ab["total"]); ?>颗</span>&nbsp;智慧豆</p>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
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