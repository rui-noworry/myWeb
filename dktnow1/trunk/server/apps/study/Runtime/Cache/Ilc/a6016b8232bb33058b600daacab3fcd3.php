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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/system.css" />
<script type="text/javascript">

// 表格排列
var listObj =  {"_check": '5%',"_period": "15%" ,"_grade": "15%","_subject": "20%","_person": "10%","_time": "15%","_status":"10%","_manage": "10%","_edit": "10%"};
$(function(){

    // 编辑
    $(document).on('click','._edit',function(){
        location.href = '__URL__/edit/id/'+$(this).parents('.plist').attr('attr');
    })

    // 根据学制选择年级
    $(".gcType span").click(function() {
        if ($(this).attr('rel') == 0) {
            $('.gcGrade').html('');
            $('.gcMajor').html('');
        } else {
            if ($(this).attr('rel') == 4) {
                $('.gcGrade').html('');
                listGrade($(this).attr('rel'), 'gcMajor', 1);
            } else {
                $('.gcMajor').html('');
                listGrade($(this).attr('rel'), 'gcGrade', 1);
            }
        }
    })

    // 依据专业选择年级
    $(document).on('click', '.gcMajor span', function () {
        listGrade($(this).attr('rel'), 'gcGrade', 1, 'major');
    })

    // 查询
    $('.search_btn').click(function() {
        getList();
    }).click();

    // 选中
    $(document).on('click','.hand',function(){
        $(this).addClass('on').siblings().removeClass('on');
    })

    setWidth(listObj);
})
function getList(p) {
    var gc_type = $('.gcType .hand.on').attr('rel');
    var p = p ? p : 1;
    var ma_id = $('.gcMajor').css('display') == 'none' ? 0 : $(".gcMajor .hand.on").attr('rel'), ma_id = ma_id ? ma_id : 0;
    var str = 'ma_id='+ma_id+'&p='+p;
    if (gc_type) {
        var gc_grade = $('.gcGrade .hand.on').attr('rel');
        str += '&gc_type='+gc_type+'&gc_grade='+gc_grade;
    }console.log(str);
    $.post('__URL__/lists', str, function(json) {
        $('#gradeCourse .plist').remove();
        $('.page').html('');
        var obj = json.list;
        var res = '<div class="_caption t_caption"><ul><li class="_check"><input type="checkbox" onclick="CheckAll(\'gradeCourse\')"></li><li class="_period">学段</li><li class="_grade">年级</li><li class="_subject">学科</li><li class="_person">创建人</li><li class="_time">创建时间</li><li class="_status">状态</li><li class="_manage">管理</li></ul></div>';
        if (obj) {
            for (var i = 0; i < obj.length; i ++) {
                res += '<div class="_caption _List plist" attr="'+obj[i]['gc_id']+'"><ul><li class="_check"><input name="key" type="checkbox" value="'+obj[i]['gc_id']+'"></li><li class="_period">'+obj[i]['type']+'</li><li class="_grade">'+obj[i]['grade']+'</li><li class="_subject" title='+obj[i]['course']+'>'+obj[i]['course']+'</li><li class="_person">'+obj[i]['a_nickname']+'</li><li class="_time">'+obj[i]['gc_created']+'</li><li class="_status" attr="'+obj[i]['gc_status']+'">';

                if (obj[i]['gc_status'] == 1) {
                    res += '<img src="__APPURL__/Public/Images/Ilc/qy_status.png" />';
                } else {
                    res += '<img src="__APPURL__/Public/Images/Ilc/jy_status.gif" />';
                }

                res += '</li><li class="_edit">编辑</li></ul></div>';
            }
            $('.page').html(json.page);
        } else {
            res += '<div class="pli_null">暂无数据</div>';
        }

        $('#gradeCourse').html(res);
        setWidth(listObj);
    }, 'json')

}
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
    <div class="system fl">
        <div class="sys_menu">
            <?php if(is_array($secondList)): $i = 0; $__LIST__ = $secondList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$selist): $mod = ($i % 2 );++$i;?><a href="__APPURL__<?php echo ($selist['sn_url']); ?>" <?php if(($selist["sn_name"]) == "GradeCourse"): ?>class="selected"<?php endif; ?>><?php echo ($selist['sn_title']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="filter">
            <div class="tools fl">
                <span class="add"><i></i>新增</span>
                <span class="forbidden"><i></i>禁用</span>
                <span class="resume"><i></i>恢复</span>
            </div>
            <div class="search fl">
                <ul class="more_search fl">
                    <li class="gcType">
                        <label>学制：</label>
                        <div>
                            <span class="hand on" rel="0">全部</span>
                            <?php if(is_array($gc_type)): $i = 0; $__LIST__ = $gc_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><span class="hand" rel="<?php echo ($key); ?>"><?php echo ($type); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </li>
                    <li class="gcMajor"></li>
                    <li class="gcGrade"></li>
                    <li class="searchBtn">
                        <a href="#" class="search_btn"></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="_list fl" id="gradeCourse">
        </div>
        <div class="page"></div>
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