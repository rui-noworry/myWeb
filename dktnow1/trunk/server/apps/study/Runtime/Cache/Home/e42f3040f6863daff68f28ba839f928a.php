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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public//Js/Home/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/group_class.css" />
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
        <li class="tab_return" onclick="javascript:location.href='/Class'">返回</li>
        <li class="tab_name"><?php echo (replaceclasstitle($class["s_id"],$class['c_type'],$class['c_grade'],$class['c_title'],$class['is_graduation'])); ?></li>
        <li onclick="javascript:location.href='__URL__/student/id/<?php echo ($class["c_id"]); ?>'">同学们</li>
        <li class="selected">小组</li>
        <?php if(($isBeanOpen) == "1"): ?><li onclick="javascript:location.href='__URL__/honor/id/<?php echo ($class["c_id"]); ?>'">光荣榜</li><?php endif; ?>
        <li onclick="javascript:location.href='__URL__/syllabus/id/<?php echo ($class["c_id"]); ?>'">课程表</li>
    </ul>
    <div class="group_right fr">
        <div class="gr_left fr">

            <div class="left_main">
                <a class="left_add">＋添加小组</a>
                <div class="clear"></div>
                <a class="left_make"></a>
            </div>
            <div class="left_over">
                <p>添加成功</p>
                <a class="over_go"></a>
            </div>
        </div>
        <div class="gr_right fl">
            <div class="rig_main">
                <p>已创建的小组</p>
                <ul>
                    <li class="rige_name">小组名称</span>
                    <li class="rige_edit">编辑</span>
                    <li class="rige_remove">删除</span>
                    <li class="rige_found">创建人</span>
                    <li class="rige_poh">成员</span>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="rig_cure" title="成员">
    <label>小组成员：</label>
    <div class="ruge_on">

    </div>
    <div class="clear"></div>
    <div class="all">
    <label>班级成员：</label>
        <div class="ruge_none">
            <?php if(is_array($students)): $i = 0; $__LIST__ = $students;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$student): $mod = ($i % 2 );++$i;?><span attr="<?php echo ($student["a_id"]); ?>"><?php echo ($student["a_nickname"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<script>
$(function(){

    $('.left_over').hide();

    $('.tab_return').click(function() {
        location.href = "__URL__";
    })

    var str = '';
    for(i = 0; i < 40; i++){
        str += "<a class=sol_"+i+"></a>";
    }

    // 点击选择图标显示
    $(document).on('click','.map_sor',function(){
        if($(this).hasClass('left_click')){
            $(this).removeClass('left_click');
            $(this).children('div').hide();
        }else{
            $(this).addClass('left_click');
            $(this).children('div').show();
        }
    })

    // 滑离选择图标隐藏
    $(document).on('mouseout','.map_sor',function(){
        $(this).removeClass('left_click');
        $(this).children('div').hide();
    })

    $(document).on('mouseover','.map_sor div',function(){
        $(this).parent().addClass('left_click');
        $(this).show();
    })

    // 选择图标
    $(document).on('mouseover','.sorll a',function(){
        $(this).addClass('sor_hover').siblings().removeClass('sor_hover');
    })

    $(document).on('click','.sorll a',function(){
        var t_class = $(this).attr('class');
        var t_name = $(this).parents('div').siblings('.left_null').children('a');
        t_name.attr('class' , '').attr('style' , '').addClass('left_map').addClass(t_class);
    })

    //＋添加小组
    $(document).on('click','.left_add',function(){

        var tLength = $('.gr_right>div').eq(0).children('div').size();
        var tNav = $(this).parent().children('.main_gor').size();
        var tNum = tLength + tNav;

        if(tNum < 15){
            $(this).before("<div class='main_gor'><div class='left_null fl'><a class='left_map'></a></div><div class='fl'><span class='map_sor'><div class='sorll'>"+str+"</div></span><input type='text' name='' value='' class='class_input'/><a class='input_select'></a></div></div>");
            $('.left_main .main_gor').last().children().children('input').focus();
        }else{
            showMessage('最多能添加15个小组');
        }

    })

    //删除未添加的小组
    $(document).on('click','.input_select',function(){
        if (confirm('确定要删除该小组吗？'))
        {
            $(this).parents('.main_gor').remove();
        } else {
            return false;
        }
    })

    //删除已添加的小组
    $(document).on('click','.edit_select',function(){

        if(confirm("确定要删除该小组吗？")){

            $(this).parents('.right_gor').remove();

            // 删除小组
            $.post('__APPURL__/ClassGroup/del', 'cg_id='+$(this).parents('.right_gor').attr('attr'), function(json){
                if (!json) {
                    showMessage('删除失败');
                }
            }, 'json');

            if (parseInt($('.right_gor').size()) == 0) {
                $('.rig_main').find('p').eq(0).html('暂无小组');
                $('.rig_main ul').hide();
            }

        } else {
            return false;
        }
    })

    // 编辑小组
    $(document).on('click','.edit_add',function(){
        $(this).parent().hide().siblings('div').show();
        $(this).parent().hide().siblings('.gor_hide').find('.class_input').val($(this).prev().text());
    })

    // 编辑删除按钮
    $(document).on('click','.rig_select',function(){
        $(this).parent().hide().siblings('div').show();
    })

    // 编辑发布按钮
    $(document).on('click','.rig_add',function(){

        var tVal = $(this).siblings('input').val();
        String.prototype.trim = function () {
            return this.replace(/[ ]/g, '');
        }
        var tRim = tVal.trim();
        if (tRim == ""){
            showMessage('请输入小组名称');
            return false;
        }

        if (tRim.length > 6) {
            showMessage('小组名称最多6个字符');
            return false;
        }

        var obj = $(this).parent();

        var cg_id = $(this).parents('.right_gor').attr('attr');
        var cg_logo = '';
        var cg_title = tRim;
        if (obj.find('.map_sor').find('.sor_hover').attr('class') != undefined) {
            cg_logo = obj.find('.map_sor').find('.sor_hover').attr('class').replace(/[^0-9]+/ig, "");
        }

        $.post('__APPURL__/ClassGroup/update', 'cg_logo='+cg_logo+'&cg_id='+cg_id+'&cg_title='+cg_title, function(json){

            if (!json) {
                showMessage('编辑失败');
            } else {
                obj.next().find('i').text(tRim);
                obj.hide().siblings('div').show();
            }
        }, 'json');
    })

    // 发布点击
    $(document).on('click','.left_make',function(){

        if ($('.main_gor').size() < 1) {
            return false;
        }

        var sTrue = true;
        var tLength = $('.gr_right>div').eq(0).children('div').size();
        var tNav = $(this).parent().children('.main_gor').size();
        var tNum = tLength + tNav;
        if(tNum > 15){
            showMessage('最多只能添加15个小组');
            sTrue = false;
        }

        var groupTitleStr = '';
        var logoStr = '';

        $('.left_main input').each(function(){
                var tNullee = $(this).parent().siblings('.left_null').html();
                var tVal = $(this).val();
                String.prototype.trim = function () {
                    return this.replace(/[ ]/g, '');
                }
                var tLen = tVal.trim();
                if(tLen == ""){
                    showMessage('不能为空');
                    sTrue = false;
                }else{
                    if(tLen.length > 6){
                        showMessage('名字超过6个字符，请重新输入');
                        sTrue = false;
                    }
                }

                groupTitleStr += ',' + $(this).val();

                var groupLogo = $(this).prev().find('.sorll a.sor_hover').attr('class');

                if (!groupLogo) {
                    groupLogo = 0;
                } else {
                    groupLogo = groupLogo.replace(/[^0-9]+/ig,"");
                }

                logoStr += ',' + groupLogo;


        })

        groupTitleStr = groupTitleStr.slice(1);
        logoStr = logoStr.slice(1);

        if(sTrue == true){

            // 添加班级小组
            $.post('__APPURL__/ClassGroup/insert', 'cg_logo='+logoStr+'&c_id='+<?php echo ($class["c_id"]); ?>+'&groupTitleStr='+groupTitleStr, function(json){

                var groups = styLeft = group = sty = styTop = '';
                if (json) {

                    for (var i = 0; i < json.length; i++) {

                        styLeft = (json[i]['cg_logo'] % 10) * 30 + 4;
                        styTop = (parseInt(json[i]['cg_logo'] / 10) * 32) + 2;
                        sty = 'style="background-position:-'+styLeft+'px -'+styTop+'px;"';

                        groups += "<div class='right_gor' attr='"+json[i]['cg_id']+"'><div class='left_null fl'><a class='left_map' "+sty+"></a></div><div class='gor_hide fl'><span class='map_sor'><div class='sorll'>"+str+"</div></span><input type='text' name='' value='' class='class_input'/><a class='rig_select'></a><a class='rig_add'></a></div><div class='rig_edit'><i>"+json[i]['cg_title']+"</i><a class='edit_add'></a><a class='edit_select'></a><a class='edit_found'>"+json[i]['a_nickname']+"</a><label class='edit_leaguer'></label></div></div>";
                        $('.left_main').hide().siblings('.left_over').show();

                    }

                } else {
                    showMessage('对不起, 您已经添加过, 请更改小组名称');
                }

                $('.rig_main').find('p').eq(0).html('已创建的小组');
                $('.rig_main ul').show();
                $('.gr_right .rig_main').append(groups);

            }, 'json');

       }

    })

    // 继续发布
    $('.over_go').click(function(){
        $('.left_main').show().siblings('.left_over').hide();
        $('.left_main').html("<a class='left_add'>＋添加小组</a><div class='clear'></div><a class='left_make'></a>");
    })

    // 获取班级小组列表
    $.post('__APPURL__/ClassGroup/lists', 'c_id='+<?php echo ($class["c_id"]); ?>, function(json){

        var styLeft = group = sty = styTop = '';
        if (json) {
            for (var i = 0; i < json.length; i++) {

                styLeft = (json[i]['cg_logo'] % 10) * 30 + 4;
                styTop = (parseInt(json[i]['cg_logo'] / 10) * 32) + 2;
                sty = 'style="background-position:-'+styLeft+'px -'+styTop+'px;"';

                group += "<div class='right_gor' attr="+json[i]['cg_id']+"><div class='left_null fl'><a class='left_map' "+sty+"></a></div><div class='gor_hide fl'><span class='map_sor'><div class='sorll'>"+str+"</div></span><input type='text' name='' value='' class='class_input'/><a class='rig_select'></a><a class='rig_add'></a></div><div class='rig_edit'><i title="+json[i]['cg_title']+">"+json[i]['cg_title']+"</i><a class='edit_add'></a><a class='edit_select'></a><a class='edit_found' title="+json[i]['a_nickname']+">"+json[i]['a_nickname']+"</a><label class='edit_leaguer'></label></div></div>";
            }
        }

        $('.gr_right .rig_main').append(group);

        // 如果没有以创建的小组，隐藏菜单
        if ($('.right_gor').size() < 1) {
            $('.rig_main').find('p').eq(0).html('暂无小组');
            $('.rig_main ul').hide();
        }

    }, 'json');


    // 查看成员
    $(document).on('click','.edit_leaguer',function(){

        $('.rig_cure label:first').html($(this).parent().find('i').text()+'成员:');
        var cg_id = $(this).parent().parent().attr('attr');
        $('.ruge_none span').removeClass('on');
        $(this).parent().parent().addClass('on').siblings().removeClass('on');
        $('.ruge_on').html('');
        $.post('__APPURL__/ClassGroupStudent/lists', 'cg_id='+cg_id, function(json){

            if (json) {
                var groupStudentList = '';

                for (var i = 0; i < json.length; i++) {
                    $('.ruge_none span').each(function() {
                        if ($(this).attr('attr') == json[i]) {
                            $(this).click();
                        }
                    })
                }
            }
            $(".rig_cure").dialog("open");
        }, 'json');
    })

    // 查看成员弹窗
    $(".rig_cure").dialog({
        draggable: true,        // 是否允许拖动,默认为 true
        resizable: true,        // 是否可以调整对话框的大小,默认为 true
        autoOpen: false,        // 初始化之后,是否立即显示对话框,默认为 true
        position :'center',       // 用来设置对话框的位置
        stack : true,       // 对话框是否叠在其他对话框之上。默认为 true
        modal: true,       // 是否模式对话框,默认为 false(模式窗口打开后，页面其他元素将不能点击，直到关闭模式窗口)
        bgiframe: true,         // 在IE6下,让后面遮罩层盖住select
        width: '480',
        height: 'auto',

        show: {     // 对话框打开效果
            effect: "blind",
            duration: 400
        },
        hide: {     // //对话框关闭效果
          effect: "explode",
          duration: 400
        },
        overlay: {
            backgroundColor: '#000',
            opacity: 0.5
        },
        buttons: {
            确定: function() {

                var str = '';
                $('.ruge_on span').each(function() {
                    str += ',' + $(this).attr('attr');
                })

                str = str.slice(1);

                var obj = $(this);

                $.post('__APPURL__/ClassGroupStudent/insert', 'c_id='+<?php echo ($class["c_id"]); ?>+'&cg_id='+$('.right_gor.on').attr('attr')+'&a_id='+str, function(json) {
                    if (json.status == 1) {
                        obj.dialog('close');
                    } else {
                        showMessage(json.info);
                    }
                }, 'json')
            },
            取消: function() {
                $(this).dialog('close');
            }
        }
    })

    // 添加成员
    $(document).on('click','.ruge_none span',function(){

        var a_id = $(this).attr('attr');

        if ($(this).hasClass('on')) {
            $(this).removeClass('on');
            $('.ruge_on span').each(function() {
                if ($(this).attr('attr') == a_id) {
                    $(this).remove();
                }
            })
        } else {
            $(this).addClass('on');
            $('.ruge_on').append("<span attr="+a_id+">"+$(this).text()+"<a href='javascript:void(0);' class='flex_close cDel'></a></span>")
        }
    })

    //成员删除按钮 显示
    $(document).on('mouseover','.ruge_on span',function(){
        $(this).children('a').show();
    })

    //成员删除按钮 隐藏
    $(document).on('mouseout','.ruge_on span',function(){
        $(this).children('a').hide();
    })

    //成员删除按钮 删除
    $(document).on('click','.ruge_on a',function(){
        if(confirm('确定要删除该成员吗')){

            var obj = $(this);
            var attr = obj.parent().attr('attr');
            $('.ruge_none span').each(function() {
                if ($(this).attr('attr') == attr) {
                    $(this).removeClass('on');
                }
            })
            obj.parent().remove();
        }
    })
})
</script>
    <div class="clear"></div>
    <div class="foot_bot"></div>
    <div class="foot_top"></div>
    <div id="footer">
        <div class="nav back1"></div>
        Copyright &copy; 2007-2011 北京金商祺移动互联 All Rights Reserved.
    </div>
</body>
</html>