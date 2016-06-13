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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/homework.css" />
<script type="text/javascript">
    
    $(function(){
        // 左侧菜单收缩
        $('.main_left div').click(function(){
            if($(this).hasClass('left_arrow')){
                $(this).removeClass("left_arrow");
                $('.main_left').width("15%");
                $('.main_left ul').show();
                $('.main_right').width('84%');
            }else{
                $(this).addClass("left_arrow");
                $('.main_left').width("0%");
                $('.main_left ul').hide();
                $('.main_right').width('95%');
            }
        })

        // 班级作业提交情况切换
        $(".main_right_right .tab li").click(function(){

            $(this).addClass("on").siblings().removeClass("on");
            var index =  $(".main_right_right .tab li").index(this);
            $(".box > ul").eq(index).show().siblings().hide();
        })
    })

</script>
<div class="warp">
    <div class="homework_stat">
        <div class="title">
            <a href="javascript:void(0);" class="mould_name"><?php echo ($ap["act_title"]); ?></a>
            <a href="__APPURL__/Homework" class="back fr">返回</a>
        </div>

        <div class="main_box">
            <div class="main_right fl" style="width:100%;">
                <div class="main_right_left">
                    <div class="main_r_l_t">
                        <div class="good">
                            <label>得分最高的同学：</label>
                            <?php if(is_array($upper)): $i = 0; $__LIST__ = array_slice($upper,0,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$up): $mod = ($i % 2 );++$i;?><span><?php echo ($up["a_nickname"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <div class="poor">
                            <label>得分最低的同学：</label>
                            <?php if(is_array($lower)): $i = 0; $__LIST__ = $lower;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ow): $mod = ($i % 2 );++$i;?><span><?php echo ($ow["a_nickname"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <div class="delay">
                            <label>没有按时提交作业的同学：</label>
                            <p>
                                <?php if(is_array($student)): $i = 0; $__LIST__ = $student;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$stu): $mod = ($i % 2 );++$i; if(($stu["ad_status"]) == "0"): ?><span><?php echo ($stu["a_nickname"]); ?></span><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="main_r_l_b">
                        <ul>
                            <?php if(is_array($topic)): $i = 0; $__LIST__ = $topic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$to): $mod = ($i % 2 );++$i;?><li>
                                <label>第<?php echo ($key+1); ?>题</label>
                                <span><i style="width:<?php echo ($to["person"]); ?>%" rel="<?php echo ($to["person"]); ?>%"><?php echo ($to["stat"]); ?></i></span>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="main_right_right fl">
                    <ul class="tab">
                        <li class="on">全部学生</li>
                        <li>已提交</li>
                        <li>未提交</li>
                    </ul>
                    <div class="box class_students">
                        <ul>
                            <?php if(is_array($student)): $i = 0; $__LIST__ = $student;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$stu): $mod = ($i % 2 );++$i;?><li attr="<?php echo ($stu["ad_status"]); ?>"><a href="javascript:void(0);" class="photo"><img <?php if(($stu["ad_status"]) != "0"): ?>class="on"<?php endif; ?> src="<?php echo ($stu["a_avatar"]); ?>"/></a><a href="javascript:void(0);"><?php echo ($stu["a_nickname"]); ?></a><span><?php if(($stu["ad_status"]) == "0"): ?>未提交<?php else: ?>已提交<?php endif; ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                        <ul class="hide">
                            <?php if(is_array($student)): $i = 0; $__LIST__ = $student;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$stu): $mod = ($i % 2 );++$i; if(($stu["ad_status"]) != "0"): ?><li attr="<?php echo ($stu["ad_status"]); ?>"><a href="javascript:void(0);" class="photo"><img class="on" src="<?php echo ($stu["a_avatar"]); ?>"/></a><a href="javascript:void(0);"><?php echo ($stu["a_nickname"]); ?></a><span>已提交</span></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                        <ul class="hide">
                            <?php if(is_array($student)): $i = 0; $__LIST__ = $student;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$stu): $mod = ($i % 2 );++$i; if(($stu["ad_status"]) == "0"): ?><li attr="<?php echo ($stu["ad_status"]); ?>"><a href="javascript:void(0);" class="photo"><img src="<?php echo ($stu["a_avatar"]); ?>"/></a><a href="javascript:void(0);"><?php echo ($stu["a_nickname"]); ?></a><span>未提交</span></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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