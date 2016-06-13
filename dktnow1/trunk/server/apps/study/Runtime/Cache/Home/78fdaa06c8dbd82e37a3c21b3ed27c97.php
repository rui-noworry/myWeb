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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/course.css" />

    <script type="text/javascript">

        var listObj =  {"hw_num": '8%',"hw_title": "20%" ,"hw_time": "20%","hw_object": "20%","hw_operate": "10%",'hw_goin':"10%"};

        $(function() {

            setWidth(listObj);

            // 删除作业
            $('.hw_del span').mouseover(function(){

                $(this).css('color','#26A75B');
            }).mouseout(function(){

                $(this).css('color','');
            })

            $('.hw_del span').click(function(){

                if(confirm("确定要删除该作业吗？")){

                    $(this).closest('.plist').remove();
                }
            })

            // 表格隔行变色
            $('.hw_list .plist').mouseover(function(){

                $(this).css('background','#fff');
            }).mouseout(function(){

                $(this).css('background','');
            })

            // 搜索
            $('.search').click(function(){
                homeworkList();
            });

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

                homeworkList();

            }).eq(0).click();

        })

        // 获取作业列表
        function homeworkList(p) {

            p = p ? p :1;

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

            // 条件变量
            var parm = '';

            if ($('select[name=part_time]').val() != 0) {
                parm += 'ap_created='+$('select[name=part_time]').val();
            }

            if ($('select[name=class]').val() != 0) {
                parm += '&c_id='+$('select[name=class]').val();
            }

            if ($('select[name=crowd]').val() != 0) {
                parm += '&cro_id='+$('select[name=crowd]').val();
            }

            if (order != undefined) {
                parm += '&order='+order;
            }

            parm += '&act_type=1&p='+p;

            $.post('__URL__/lists', parm, function(json) {

                var list = '';
                if (json.list) {

                    for (var i = 0; i < json.list.length; i ++) {
                        list += '<div class="hw_caption hw_List plist"><ul><li class="hw_num" attr='+json.list[i]['ap_id']+'>'+json.list[i]['ap_id']+'</li><li class="hw_title" title="'+json.list[i]['act_title']+'">'+json.list[i]['act_title']+'</li><li class="hw_time">'+json.list[i]['ap_created']+'</li><li class="hw_object">';

                        if (json.list[i]['c_id'] != 0) {
                            list += json.list[i]['c_name'];
                        }

                        if (json.list[i]['cro_id'] != 0) {
                            list += json.list[i]['cro_name'];
                        }

                        list += '</li><li class="hw_goin" onclick="correct('+json.list[i]['ap_id']+')" ><span>批改作业</span></li><li class="hw_goin" onclick="stat('+json.list[i]['ap_id']+')" ><span>查看统计</span></li></ul></div>';
                    }

                } else {
                    list += '<div class="hw_caption hw_List plist"><ul><li class="hw_num" style="padding-left:300px;">暂无数据</li></ul></div>';
                }

                $('.data').html(list);

                if (json.page == undefined) {
                    $('.page').html('');
                } else {
                    $('.page').html(json.page);
                }

                setWidth(listObj);

            }, 'json');
        }

        function getList(p) {
            homeworkList(p);
        }

        function correct(ap_id) {
            location.href = '__APPURL__/Homework/correct/ap_id/'+ap_id;
        }

        function stat(ap_id) {
            window.open('__APPURL__/Homework/stat/ap_id/'+ap_id);
        }
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
        <div class="t_hwList">
            <div class="filter">
                <div>
                    <label class="space">班&nbsp;&nbsp;级：</label>
                    <select name="class">
                        <option value="0">全部班级</option>
                        <?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i;?><option value="<?php echo ($class["c_id"]); ?>"><?php echo ($class["c_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div>
                    <label>作业布置时间：</label>
                    <select name="part_time">
                        <?php if(is_array($partTimeHomework)): $i = 0; $__LIST__ = $partTimeHomework;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$time): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"><?php echo ($time["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <div>
                    <label>群&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;组：</label>
                    <select name="crowd">
                        <option value="0">全部群组</option>
                        <?php if(is_array($crowd)): $i = 0; $__LIST__ = $crowd;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crowd): $mod = ($i % 2 );++$i;?><option value="<?php echo ($crowd["cro_id"]); ?>"><?php echo ($crowd["cro_title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

                    </select>
                </div>
                <div class="search">
                    <button>搜索</button>
                </div>
            </div>
            <div class="hw_list fl">
                <div class="hw_caption">
                    <ul class="_ptitle">
                        <li class="hw_num sortby" attr="ap_id">序号</li>
                        <li class="hw_title">作业标题</li>
                        <li class="hw_time sortby" attr="ap_created">布置时间</li>
                        <li class="hw_object">对象</li>
                        <li class="hw_operate">操作</li>
                    </ul>
                </div>
                <div class="data">

                </div>
            </div>
            <div class="clear"></div>
            <div class="page"></div>
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