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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/user.css" />
<script type="text/javascript">
// 表格排列
var listObj =  {"_checkbox": '2%',"_a_id": "7%" ,"_a_account": "13%","_a_nickname": "10%","_course": "15%",'_phone':"8%",'_a_login_count':"12%",'_loginIp':'12%','_status':'8%','_toperate':'12%','_edit':'6%','_a_status':'8%','_statusText':'6%'};
$(function(){

    $('.search_btn').click(function(){
        getList();
    });

    // 启用禁用
    $(document).on('click', '._statusText', function() {
        var status = $(this).parents('.plist').find('._status').attr('attr');
        var id = $(this).parents('.plist').attr('attr');
        status = status == 1 ? 1 : 0;
        forbid(id, status);
    })

    // 编辑
    $(document).on('click', '._edit', function() {
        location.href = '__URL__/edit/id/'+$(this).parents('.plist').attr('attr');
    })

    // 排序
    $('._ptitle li.sortby').click(function(){

        // 点击选中
        if ($(this).attr('attr') != undefined) {
            if ($(this).hasClass('on')) {
                $(this).removeClass('on');
            } else {
                $(this).addClass('on');
            }
        }

        getList();


    }).eq(0).click();

    setWidth(listObj);

});

// 获取教师列表
function getList(p) {

    p = p ? p :1;

    // 教师名称
    var a_nickname = $('input[name=a_nickname]').val();

    // 遍历获取排序
    var order = '';
    $('._ptitle li.sortby').each(function(){

        if ($(this).attr('attr') != undefined) {

            if ($(this).hasClass('on')) {
                order += ',' + $(this).attr('attr') + ' ASC';
            } else {
                order += ',' + $(this).attr('attr') + ' DESC';
            }
        }

    });

    order = order.slice(1);

    $.post('__URL__/lists', 'p='+p+'&is_ajax=1&a_nickname='+a_nickname+'&order='+order, function(json){
        var obj = json.list;
        var str = '';

        if (obj) {

            for (var i = 0; i < obj.length; i ++) {
                str += '<div class="_caption _List plist" attr="'+obj[i]['a_id']+'"><ul><li class="_checkbox"><input type="checkbox"  name="key" value="'+obj[i]['a_id']+'"></li><li class="_a_id" title='+obj[i]['a_id']+'>'+obj[i]['a_id']+'</li><li class="_a_account" title='+obj[i]['a_account']+'>'+obj[i]['a_account']+'</li><li class="_a_nickname" title='+obj[i]['a_nickname']+'>'+obj[i]['a_nickname']+'</li><li class="_course" title='+obj[i]['t_subject']+'>'+obj[i]['t_subject']+'</li><li class="_phone" title='+obj[i]['a_tel']+'>'+obj[i]['a_tel']+'</li><li class="_a_login_count" title='+obj[i]['a_login_count']+'>'+obj[i]['a_login_count']+'</li><li class="_loginIp" title='+obj[i]['a_last_login_ip']+'>'+obj[i]['a_last_login_ip']+'</li><li class="_status"  attr="'+obj[i]['a_status']+'">';

                if (obj[i]['a_status'] == 1) {
                    str += '<img src="__APPURL__/Public/Images/Ilc/qy_status.png" />';
                } else {
                    str += '<img src="__APPURL__/Public/Images/Ilc/jy_status.gif" />';
                }

                str += '</li><li class="_statusText">';

                if (obj[i]['a_status'] == 1) {
                    str += '禁用';
                } else {
                    str += '启用';
                }

                str += '</li><li class="_edit">编辑</li></ul></div>';

            }
        } else {
            str += '<div class="pli_null">暂无数据</div>';
        }

        $('#data').html('');
        $('#data').html(str);
        $('.page').html(json.page);

        setWidth(listObj);

    }, 'json');


}

// 判断回车
function keydown(e){
    var e = e || event;
    if (e.keyCode==13) {
        $(".search_btn").click();
    }
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
    <div class="user fl">
        <div class="user_menu">
            <?php if(is_array($secondList)): $i = 0; $__LIST__ = $secondList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$selist): $mod = ($i % 2 );++$i;?><a href="__APPURL__<?php echo ($selist['sn_url']); ?>" <?php if(($selist["sn_name"]) == "Teacher"): ?>class="selected"<?php endif; ?>><?php echo ($selist['sn_title']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="filter">
            <div class="tools fl">
                <span class="add"><i></i>新增</span>
                <span class="edit"><i></i>编辑</span>
                <span class="del"><i></i>删除</span>
                <span class="forbidden"><i></i>禁用</span>
                <span class="resume"><i></i>启用</span>
            </div>
            <p>
                <label>教师姓名：</label>
                <input type="text" name="a_nickname" onkeydown="keydown(event)">
                <a class="search_btn" href="javascript:void(0);"></a>
                <input type="hidden" name="_order" value="" />
            </p>
        </div>

        <div class="AuthTeacher_list fl"  id="teacherList">
            <div class="_caption _ptitle t_caption">
                <ul>
                    <li class="_check ch_nav"><input type="checkbox" onclick="CheckAll('teacherList')"></li>
                    <li class="_a_id sortby" attr="a_id">编号</li>
                    <li class="_a_account" attr="a_account">用户名</li>
                    <li class="_a_nickname">姓名</li>
                    <li class="_course">教授课程</li>
                    <li class="_phone">手机</li>
                    <li class="_a_login_count" attr="a_login_count">登陆次数</li>
                    <li class="_loginIp">登陆IP</li>
                    <li class="_a_status">状态</li>
                    <li class="_toperate">操作</li>
                </ul>
            </div>
            <div id="data">


            </div>
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