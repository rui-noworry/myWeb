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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/index.css" />
<script type="text/javascript" src="/Public/Js/Home/scroll.js"></script><script type="text/javascript" src=" /Public/Js/Public/jCal.min.js"></script><link rel="stylesheet" type="text/css" href=" __PUBLIC__/Css/Home/jCal.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<style>
    .dInfo {
        font-family:tahoma;
        font-size:7pt;
        color:#fff;
        padding-top:1px;
        padding-bottom:1px;
        background:rgb(0, 102, 153);
    }
</style>
    <script type="text/javascript">

        var timeList = <?php echo json_encode($reTimeList);?>;
        $(function() {

            // 处理动态分页箭头
            if ($('.m_tab_menu li').size() < 3) {
                $('a.to-left').css('background',"none");
                $('a.to-right').css('background',"none");
            } else {
                $('a.to-left').css('background','__APPURL__/Public/Images/Home/to-left.png');
                $('a.to-right').css('background','__APPURL__/Public/Images/Home/to-right.png');
            }

            // 获取动态
            $(".m_tab_menu li").click(function(){
                $(this).addClass("current").siblings().removeClass("current");
                $('input[name=get_more]').val(0);
                // 获取动态数据
                trend(0, 1);
            }).eq(0).click();

            // 班级向下翻页
            $('.to-right').click(function(){
                var move = $('.m_tab_menu').width();
                var nowpage = parseInt($('.main_middle i').attr('nowpage'));
                var totalpage = parseInt($('.main_middle i').attr('totalpage'));
                if(nowpage >= totalpage){
                    showMessage("已经到最后一页啦");
                }else {
                    nowpage = nowpage + 1;
                    $('.main_middle i').attr('nowpage',nowpage);
                    $('.m_tab_menu ul').stop().animate({left: "-="+move+""}, "fast");
                }
            })

            // 班级向上翻页
            $('.to-left').click(function(){
                var move = $('.m_tab_menu').width();
                var nowpage = parseInt($('.main_middle i').attr('nowpage'));
                var totalpage = parseInt($('.main_middle i').attr('totalpage'));
                if(nowpage && nowpage <= 1){
                    showMessage("已经是第一页啦");
                }else {
                    nowpage = nowpage - 1;
                    $('.main_middle i').attr('nowpage',nowpage);
                    $('.m_tab_menu ul').stop().animate({left: "+="+move+""}, "fast");
                }
            })

            // 点击查看更多
            $(document).on('click', '.get_more', function(){
                $('input[name=get_more]').val(1);
                trend($(this).attr('attr'));
            })

        })

        // 加载动态数据
        function trend(p, typ) {

            if (typ) {
                $("#scroLeft ul").html('');
            }

            p = p ? p : 1;
            var c_id = $('.m_tab_menu li.current').attr('attr');
            var subject = $('.m_tab_menu li.current').attr('subject');

            $.post('__APPURL__/Trend/lists', 'c_id='+c_id+'&subject='+subject+'&is_ajax=1&p='+p, function(json){

                if (json.list) {
                    var str = '';
                    var obj = json.list;
                    var len = obj.length;

                    for (var i = 0; i < len; i++) {

                        str += '<li><a href="#"><img src="'+obj[i]['a_avatar']+'"/></a><p><a href="#" class="name">'+obj[i]['a_nickname']+'</a><a href="#" class="date">'+obj[i]['tr_created']+'</a></p><p>'+obj[i]['tr_action']+'了&nbsp;<cite>'+obj[i]['tr_obj']+'-'+obj[i]['tr_title']+'</cite></p></li>';
                    }
                    if (len == 10) {
                        $(".notrend").html('<a href="javascript:void(0);" attr="'+(json.page+1)+'" class="get_more">点击查看更多</a>');
                    } else {
                        $(".notrend").html('');
                    }
                } else {
                    $(".notrend").html('');
                }

                if ($('input[name=get_more]').val() == 1) {
                    $("#scroLeft ul").append(str);
                } else {
                    $("#scroLeft ul").html(str);
                }

                if (!$("#scroLeft ul li").size()) {
                    $('.notrend').html('<a href="javascript:void(0);">暂无动态</a>');
                }

                $(".notrend").show();

            }, 'json');
        }

    // 改变日历的SIZE
     function changeCalSize (daySize) {
        var daySize = (parseInt(daySize) || 30),
            monthSize = ( daySize + 2 ) * 7,
            titleSize = monthSize - 16,
            titleMsgSize = ( titleSize / 2 ) - 4;
            //showMessage(monthSize);
        $('head:first').append(
            '<style>' +
                '.jCalMo .day,.jCalMo .invday,.jCalMo .pday,.jCalMo .aday,.jCalMo .selectedDay,.jCalMo .dow { width:' + daySize + 'px !important; height:' + daySize + 'px !important; }' +
                '.jCalMo .dow { height:auto !important }' +
                '.jCalMo, .jCalMo .jCal { width:' + monthSize + 'px !important; }' +
                '.jCalMo .month { width:' + titleSize + 'px !important; }' +
                '.jCalMo .month span { width:' + titleMsgSize  + 'px !important; }' +
            '</style>');
    }

    // jcal时间提醒
    $(document).ready(function () {
        //changeCalSize(30);
        $('#calOne').jCal({
        day:        new Date,//默认显示当前时间
        days:       1,
        showMonths: 1,
        monthSelect:true,
        sDate:      new Date(),
        dCheck:     function (day) {
                        if ( day.getTime() == (new Date('8/7/2008')).getTime() ) return false;
                        return (day.getDate() != 50);
                    },
        callback:   function (day, days) {},
        cleckcallback: function(day, days) {
                           var nowtime =day.getFullYear() + '-' + (day.getMonth() + 1 ) + '-' + day.getDate();
                            //数据库时间这读取本月的提醒
                            $.post('__URL__/getRemind', 'remTime=' + nowtime , function(json){

                                if (json['status'] == 1) {
                                    var remTitle = json['remTitle']
                                    $("input[name='tagstr']").val(remTitle);
                                }else {
                                    $("input[name='tagstr']").val('');
                                }
                            }, 'json');

                            // 记录当前时间
                            $("input[name='nowtime']").val(nowtime);

                             // 添加标签 弹出窗口
                            $("#showTag").dialog({
                                draggable: true,
                                resizable: true,
                                autoOpen: false,
                                position :'center',
                                stack : true,
                                modal: true,
                                bgiframe: true,
                                width: '400',
                                height: '190',
                                show: {
                                    effect: "blind",
                                    duration: 400
                                },
                                hide: {
                                  effect: "explode",
                                  duration: 400
                                },
                                overlay: {
                                    backgroundColor: '#000',
                                    opacity: 0.5
                                },
                                buttons: {
                                    确定: function() {
                                        var nowTime = $('input[name=nowtime]').val();
                                        var rData = 'rTime=' + nowTime + '&rStr=' + $("input[name='tagstr']").val();
                                        $.post('__URL__/insertRemind', rData, function(json){

                                            if (json['status'] == 0 || json['delete'] == 1) {
                                                // 失败或删除取消掉选中
                                                $('#c1d_' + (day.getMonth() + 1 ) + '_' + day.getDate() + '_' + day.getFullYear()).removeClass('selectedDay');
                                            }
                                        }, 'json');
                                        // 重新加载
                                        reloadTime(day.getMonth() + 1, day.getFullYear());
                                        $(this).dialog('close');
                                    },
                                    取消: function() {
                                        // 取消掉选中
                                        $('#c1d_' + (day.getMonth() + 1 ) + '_' + day.getDate() + '_' + day.getFullYear()).removeClass('selectedDay');
                                        // 重新加载
                                        reloadTime(day.getMonth() + 1, day.getFullYear());
                                        $(this).dialog('close');
                                    }
                                }
                            });
                            $("#showTag").dialog("open");
                            return true;
                    }
        });
        if (timeList) {
            loadTime(timeList);
        }

        /*$('.jCalMo div').each(function(){

            $(this).mouseover(function(){
                var dayId = $(this).attr('id');
                var strs= new Array();
                var strs=dayId.split("_");
                var year = strs[3];
                var month = strs[1];
                var day = strs[2];
                var nowtime = year+ '-' + month + '-' + day;
                $.post('__URL__/getRemind', 'remTime=' + nowtime , function(json){

                    if (json['status'] == 1) {

                        var remTitle = json['remTitle'];
                        showMessage(remTitle);
                        //$("input[name='tagstr']").val(remTitle);
                    }else {
                        //$("input[name='tagstr']").val('');
                    }
                }, 'json');
            })
        })*/


    });

// 重新指定样式
function loadTime(arr) {
    for(var i = 0; i < arr.length; i++){
       $('#c1d_'+arr[i]).addClass('day selectedDay');
    }
}
function reloadTime(nowMonth, nowYear) {
    // 数据库时间这读取本月的提醒
    $.post('__URL__/readRemind', 'nowMonth=' + nowMonth + '&nowYear=' + nowYear, function(json){

        if (json['status'] == 1) {
            var jsonTime = json['reTimeList']
            //添加之后重新赋值
            loadTime(jsonTime);
        }
    }, 'json');
}
</script>

    <div class="warp">
        <input name="get_more" value="" type="hidden" />
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
        <div class="main_middle fl">
            <a class="arrow to-left fl ml10"></a>
            <a class="arrow to-right fr mr10"></a>
            <div class="m_tab_menu fl">
                <ul>
                    <?php if($authInfo["a_type"] == 2): if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cla): $mod = ($i % 2 );++$i;?><li attr="<?php echo ($cla["c_id"]); ?>" class="current" subject="<?php echo ($cla["subject_id"]); ?>"><?php echo ($cla["c_replace_title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    <?php else: ?>
                        <?php if(is_array($course)): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cours): $mod = ($i % 2 );++$i;?><li class="current" subject="<?php echo ($cours[1]); ?>"><?php echo ($cours[0]); ?></li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </ul>
            </div>
            <i nowpage="1" totalpage="<?php echo ($pages); ?>"></i>
            <ul class="m_tab_box fl">
                <div id="scroll_box" class="class_box">
                    <div id="scroLeft">
                        <ul></ul>
                        <p class="notrend not_not" style="display:none">
                            <a href="javascript:void(0);">暂无动态</a>
                        </p>
                    </div>
                    <div id="scroRight">
                         <div id="scroLine"></div>
                    </div>
                </div>
            </ul>
        </div>
        <div class="right_sider fl">
            <p class="upload_num">当前已有<cite>45,128,966</cite>份文档</p>
            <div class="right_sider_box">
                <a class="upload_doc" href="#"></a>    <!-- 上传文档 -->
                <form method="post" action="" class="">    <!-- 搜索 -->
                    <input placeholder="输入要检索的资源" name="" class="searchinput fl dp">
                    <input type="submit" value="" class="searchsub">
                </form>
                <div class="calendar">    <!-- 日历 -->
                    <table>
                        <tr>
                            <td align="center" id="calOne"  style=" padding:0px;background:#E3E3E3;">
                                loading calendar two...
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="groupDyn">    <!-- 群组动态 -->
                    <ul>
                        <?php if(is_array($trendToMe)): $i = 0; $__LIST__ = $trendToMe;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$trend): $mod = ($i % 2 );++$i;?><li>
                                <a href="javascript:void(0);"><img src="<?php echo ($trend["a_avatar"]); ?>"/></a>
                                <p>
                                    <a href="javascript:void(0);"><?php echo ($trend["a_nickname"]); ?></a>
                                </p>
                                <p><?php echo ($trend["tr_action"]); echo ($trend["tr_obj"]); ?>---<?php echo ($trend["tr_title"]); ?></p>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <!-- <div id="scroRight">
                         <div id="scroLine"></div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
<div id="showTag" style="display:none;" title="添加日历提醒">
    <input type="text" name="tagstr"/>
    <input type="hidden" name="nowtime"/>
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