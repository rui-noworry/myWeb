<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/public.css" /><script type="text/javascript" src=" /Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src=" /Public/Js/Public/public.js"></script><script type="text/javascript" src="/Public/Js/Ilc/common.js"></script>
    <!--[if IE 6]>
    <script type="text/javascript" src="/Public/Js/Public/png.js" ></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#logo,.cShare,.cEdit,.cIn,.cClone,.cExport,.cDel,.fw_baoming_left,.fw_btn,.anli_ico_link,.anli_ico,.selected,.selected_green,.selected_gray,.to-left,.to-right,.current,.mt_tab li,.classhomework_top li,.choose_class,.current img,.res_click,.res_scan,.res_frame.png,#main_bg li img,.jCal .left,.jCal .right,.class_li');
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

            })
            var URL = '__URL__';
            var APP = '__GROUP__';
            var PUBLIC = '__PUBLIC__';
            var APPURL = '__APPURL__';
        //-->
    </script>
</head>
<body id="body">
    <div id="header">
        <div>
            <a href="__APPURL__/Index/" id="logo"></a>
            <ul class="nav" id="class_nav">
                <li><a <?php if(($bannerOn) == "1"): ?>class="on"<?php endif; ?> href="__APPURL__/Course">课程超市</a></li>
                <?php if(($resourceOn) != ""): ?><li><a <?php if(($bannerOn) == "2"): ?>class="on"<?php endif; ?> href="<?php echo ($resourceOn); ?>">资源中心</a></li><?php endif; ?>
                <li><a <?php if(($bannerOn) == "3"): ?>class="on"<?php endif; ?> href="__APPURL__/Space">我的空间</a></li>
                <li><a <?php if(($bannerOn) == "4"): ?>class="on"<?php endif; ?> href="javascript:;">应用中心</a></li>
            </ul>
            <a href="/Public/logout" class="exit">[退出]</a>
        </div>
    </div>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/panel.css" />
<script type="text/javascript">
$(function(){


    var  _panel_one_up = $(".diagram.one.course li");

    // 获取课程(其他)数据
    var courseSum = <?php echo ($courseSum); ?>;

    var courseNum = 0;

    _panel_one_up.each(function(){

        var num = parseInt($(this).find('span cite i').html());

        courseNum += num;

    })

    $(".diagram.one.course li:last").find('span cite i').html(courseSum-courseNum);

    // 课程的动态显示
    _panel_one_up.each(function(){

        var num = parseInt($(this).find('span cite i').html());

        $(this).find('span cite').animate({height: (num / courseSum) * 100 + '%'}, "slow");
    })

    // 资源的动态显示
    var resourceSum = <?php echo ($resourceSum); ?>;
    $(".diagram.one.resource li").each(function(){

        var num = parseInt($(this).find('span cite i').html());

        $(this).find('span cite').animate({height: (num / resourceSum) * 100 + '%'}, "slow");
    })

    // 班级动态显示
    var classSum = <?php echo ($classSum); ?>;

    var  _panel_one_down = $(".ranking.two.class li");
    _panel_one_down.each(function(){
        var num = parseInt($(this).find('label cite').html());
        $(this).find('span i').animate({width: (num / classSum) * 100 + '%'}, "slow");

    })

    // 教师动态显示
    $('.ranking.two.teacher li').each(function(){
        var num = parseInt($(this).find('label cite').html());
        $(this).find('span i').animate({width: num}, "slow");

    })


})
</script>
<div class="warp">
            <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/left.css" />
        <script language="javascript">
        $(function(){
            $(".class_left a").click(function(){
                $(this).parent("li").addClass("class_li").siblings().removeClass("class_li");
            })
        })
        </script>
        <div class="class_left fl">
            <ul>
                <?php if(is_array($allowNode)): $i = 0; $__LIST__ = $allowNode;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$node): $mod = ($i % 2 );++$i; if(($node["sn_name"]) == "Resource"): if(($resourceOn) != ""): ?><li <?php if(($node["sn_id"]) == $leftOn): ?>class="class_li"<?php endif; ?>>
                                <a href="<?php echo ($resourceOn); echo ($node["sn_url"]); ?>">
                                    <span class="<?php echo ($node["sn_name"]); ?>"></span>
                                    <p><?php echo ($node["sn_title"]); ?></p>
                                </a>
                            </li><?php endif; ?>
                    <?php else: ?>
                        <li <?php if(($node["sn_id"]) == $leftOn): ?>class="class_li"<?php endif; ?>>
                            <a href="__APPURL__<?php echo ($node["sn_url"]); ?>">
                                <span class="<?php echo ($node["sn_name"]); ?>"></span>
                                <p><?php echo ($node["sn_title"]); ?></p>
                            </a>
                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <!--li>
                    <a href="javascript:;">
                        <span class="group"></span>
                        <p>群组管理</p>
                    </a>
                </li-->
            </ul>
        </div>
    <div class="panel fl">
        <div class="panel_box">
            <div class="stat course_stat">
                <div class="stat_box">
                    <p>本校已创建了<?php if(!empty($courseSum)): echo ($courseSum); else: ?>0<?php endif; ?>个课程：</p>
                    <div class="diagram one course">
                        <ul class="oneul">
                            <?php if(is_array($courseArr)): $i = 0; $__LIST__ = $courseArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($i % 2 );++$i;?><li>
                                    <span class="chinese"><cite><i><?php echo ($course["ss_value"]); ?></i></cite></span>
                                    <label title="<?php echo ($course["ss_title"]); ?>"><?php echo ($course["ss_title"]); ?></label>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            <li>
                                <span class="other"><cite><i>0</i></cite></span>
                                <label>其他</label>
                            </li>
                        </ul>
                    </div>
                </div>

                 <div class="stat_box">
                    <p>本校已创建了<?php if(!empty($resourceSum)): echo ($resourceSum); else: ?>0<?php endif; ?>个资源：</p>
                    <div class="diagram one resource">
                        <ul class="oneul">
                            <?php if(is_array($resourceArr)): $i = 0; $__LIST__ = $resourceArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$resource): $mod = ($i % 2 );++$i;?><li>
                                    <span class="chinese"><cite><i><?php echo ($resource["ss_value"]); ?></i></cite></span>
                                    <label title="<?php echo ($resource["ss_title"]); ?>"><?php echo ($resource["ss_title"]); ?></label>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>

                </div>

                <div class="stat_box">
                    <p>本校已创建<?php if(!empty($classSum)): echo ($classSum); else: ?>0<?php endif; ?>个班级：</p>
                    <div class="ranking two class">
                        <ul>
                            <?php if(is_array($classArr)): $i = 0; $__LIST__ = $classArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i;?><li>
                                    <label><?php echo ($class["ss_title"]); ?></label>
                                    <span class="hd"><i></i></span>
                                    <label class="count">（<cite><?php echo ($class["ss_value"]); ?></cite>）</label>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>

                    <p>本校已创建了<?php if(!empty($crowdSum)): echo ($crowdSum); else: ?>0<?php endif; ?>个群组：</p>
                    <div class="diagram two">
                        <ul class="twoul">    <!-- jq控制前三名的颜色 -->
                        <?php if(is_array($crowdArr)): $i = 0; $__LIST__ = $crowdArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crowd): $mod = ($i % 2 );++$i; if(($key) < "3"): ?><li><span class="serialNum_good"><?php echo ($key+1); ?></span><?php echo ($crowd["ss_title"]); ?></li>
                            <?php else: ?>
                                <li><span class="serialNum"><?php echo ($key+1); ?></span><?php echo ($crowd["ss_title"]); ?></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>

                </div>

                <div class="stat_box">
                    <p>本校共有教师<?php if(!empty($teacherSum)): echo ($teacherSum); else: ?>0<?php endif; ?>个：</p>
                    <div class="ranking two teacher">
                        <ul>
                            <?php if(is_array($teacherArr)): $i = 0; $__LIST__ = $teacherArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$teacher): $mod = ($i % 2 );++$i;?><li>
                                    <label><?php echo ($teacher["ss_title"]); ?></label>
                                    <span class="hd"><i></i></span>
                                    <label class="count">（<cite><?php echo ($teacher["ss_persent"]); ?></cite>）</label>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div>
                    <div class="clear"></div>
                    <p>本校共有学生<?php if(!empty($studentSum)): echo ($studentSum); else: ?>0<?php endif; ?>个：</p>
                    <div class="ranking4">
                        <ul>
                            <?php if(is_array($studentArr)): $i = 0; $__LIST__ = $studentArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$student): $mod = ($i % 2 );++$i;?><li><span><?php echo ($key); ?>.</span><?php echo ($student["ss_title"]); ?>：<cite><?php echo ($student["ss_value"]); ?></cite>个豆</li><?php endforeach; endif; else: echo "" ;endif; ?>
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
        Copyright © 2007-2011 北京金商祺移动互联 All Rights Reserved.
    </div>
</body>
</html>