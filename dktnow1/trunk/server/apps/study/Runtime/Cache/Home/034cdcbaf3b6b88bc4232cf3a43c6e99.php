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
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/course.css" />

    <script type="text/javascript">
    <!--
        $(function() {

            // 隐藏专业
            $('.coMajor').hide();

            // 课程tab切换
            $(".mainBox_tab_menu li").click(function(){

                $(this).addClass("selected").siblings().removeClass("selected");
                var index =  $(".mainBox_tab_menu li").index(this);
                $(".mainBox_tab_box .course_list").eq(index).show().siblings().hide()
            })

            // 字数超出显示省略号
            $('.courseInfo').each(function(){

                var cclass = $(this).find('p').last().html();
                if(cclass.length > 30){
                    var long_word = $(this).find('p').last().text();
                    var cor_word = long_word.substr(0,26) + '．．．';
                    $(this).find('p').last().text(cor_word);
                }
            })

            // 课程操作动画效果
            $(document).on('mouseover', '.mainBox_tab_box ul li.listCourse', function(){
                $(this).addClass('exportCourse').siblings().removeClass('exportCourse');
                $(this).find('.course_cover').stop().animate({top:11},'fast');
            })
            $(document).on('mouseout', '.mainBox_tab_box ul li.listCourse', function(){
                $(this).find('.course_cover').stop().animate({top:-143},'fast');
            })

            // 删除课程
            $(document).on('click', '.cDel', function(){
                var _this = $(this);
                if (confirm("确定要删除该课程吗？")) {
                    $.post('__APPURL__/Course/delete', {co_id:$(this).attr('rel'), co_cover:$(this).attr('attr')}, function (info) {
                        if (info['status'] == 0) {
                            showMessage(info['info']);
                            return;
                        } else {
                            _this.closest('div').parent().remove();
                        }
                    }, 'json');
                } else {
                    return false;
                }
            })


            // 分享课程+导出课程 弹出窗口
            $("#share_course,#export_course").dialog({
                draggable: true,        // 是否允许拖动,默认为 true
                resizable: true,        // 是否可以调整对话框的大小,默认为 true
                autoOpen: false,        // 初始化之后,是否立即显示对话框,默认为 true
                position :'center',       // 用来设置对话框的位置
                stack : true,       // 对话框是否叠在其他对话框之上。默认为 true
                modal: true,       // 是否模式对话框,默认为 false(模式窗口打开后，页面其他元素将不能点击，直到关闭模式窗口)
                bgiframe: true,         // 在IE6下,让后面遮罩层盖住select
                width: '378',
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
                        var _this = $(this);
                        var typ = $('input[name=exportFile]:checked').val();
                        if (typ == undefined) {
                            showMessage('请选择课程导出的格式');
                            return false;
                        }

                        location.href="__APPURL__/Course/export?co_id="+ $('.myCourse .exportCourse').attr('rel') + '&type=' + typ;
                        _this.dialog('close');
                    },
                    取消: function() {
                        $(this).dialog('close');
                    }
                }
            });

            $(document).on('click', '.cShare', function(){

                $("#share_course").dialog("open");
            })

            // 导出课程
            $(document).on('click', '.cExport', function(){

                $("#export_course").dialog("open");
            })

            // 分享课程tab切换
            $(".s_c_tabmenu li").click(function(){

                $(this).addClass("current").siblings().removeClass("current");
                var index =  $(".s_c_tabmenu li").index(this);
                $(".s_c_tabbox > ul").eq(index).show().siblings().hide();
            })

            // 克隆课程
            $(document).on('click', '.cClone', function (json) {

                if (confirm('您确定要克隆此课程吗？')) {
                    // 清除上次克隆课程的newAddCourse状态
                    $('.myCourse li').removeClass('newAddCourse');

                    // 弹出请等待遮罩层
                    Loading();

                    $.post('__APPURL__/Course/cloneCourse', 'co_id=' + $('.myCourse .exportCourse').attr('rel'), function (json) {
                        if (json.status == 1) {
                            // 动态加载课程节点
                            var str = '';
                            str += '<li class="listli listCourse newAddCourse" rel="' + json.info + '" attr="" group="" >'
                                 + $('.myCourse .exportCourse').html()
                                 +  '</li>'

                            // 动态添加节点
                            $(str).insertAfter($('.myCourse li').eq(0));

                            // 清除上个dom节点数据
                            $('.newAddCourse .courseInfo p.xin_cur').html('使用班级：<cite>请选择</cite>')
                            $('.newAddCourse .tool_list .cIn').attr('href', '__APPURL__/Lesson/index/course/' + json.info)
                            $('.newAddCourse .tool_list .cEdit').attr('href', '__APPURL__/Course/edit/id/' + json.info)
                            $('.newAddCourse .tool_list .cDel').attr('rel', json.info)


                            // 处理成功，关闭遮罩层
                            close_Loading();
                            showMessage('克隆课程成功', 1);

                        } else {

                            // showMessage 错误信息 并关闭遮罩层
                            close_Loading();
                            showMessage('克隆课程失败');
                        }
                    }, 'json');
                }
            })

            // 按班级，群组切换
            $('.xin_noe>div').eq(0).show().siblings().hide();
            $(document).on('click','.xin_add li',function(){
                $(this).addClass('xin_uli').siblings().removeClass('xin_uli');
                $('.xin_noe>div').eq($(this).index()).show().siblings().hide();
            })

            // 班级弹窗
            $(document).on('click','.xin_cur',function(){

                // 初始化清空样式
                $('.allMyClasses span').removeClass('xin_ds');
                $('.allMyGroup span').removeClass('xin_dss');

                // 获取当前点击课程
                var obj = $(this).parents('.listli');

                // 当前课程添加ON样式
                obj.addClass('on').siblings().removeClass('on');

                // 获取各参数
                var co_id = obj.attr('rel');
                var group = obj.attr('group');
                var c_id = obj.attr('attr');

                // 当前课程班级处理
                var bindClass = c_id ? (',' + c_id + ',') : '';
                var html = '';
                $('.allMyClasses span').each(function () {
                    var tmp = ',' + $(this).attr('rel') + ',';
                    if (bindClass.indexOf(tmp)!= -1) {
                        $(this).addClass('xin_ds');
                        html += '<span rel="' + $(this).attr('rel') + '">' + $(this).text() + '</span>';
                    }
                })
                $('.bindClass').html(html);

                // 当前课程群组处理
                var bindGroup = group ? (',' + group + ',') : '';
                html = '';
                $('.allMyGroup span').each(function () {
                    var tmp = ',' + $(this).attr('rel') + ',';
                    if (bindGroup.indexOf(tmp)!= -1) {
                        $(this).addClass('xin_dss');
                        html += '<span rel="' + $(this).attr('rel') + '">' + $(this).text() + '</span>';
                    }
                })
                $('.bindGroup').html(html);
                $('.xin_add').dialog("open");
            })

            // 班级弹窗
            $(".xin_add").dialog({
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
                hide: {     // 对话框关闭效果
                  effect: "explode",
                  duration: 400
                },
                overlay: {
                    backgroundColor: '#000',
                    opacity: 0.5
                },
                buttons: {
                    确定: function() {

                        // 单击确定时，查找bindClass和bindGroup两个里是否有DOM值，有的话，就判断是班级还是群组，异步提交
                        var c_id = '';
                        var cro_id = '';
                        var str = '';

                        if ($('.bindClass').children().length > 0) {
                            $('.bindClass span').each(function () {
                                c_id += ',' + $(this).attr('rel') ;
                                str += ',' + $(this).text();
                            })
                            c_id = c_id.slice(1);
                            str = str.slice(1);
                        }

                        if ($('.bindGroup').children().length > 0) {
                            str += ' ';
                            $('.bindGroup span').each(function () {
                                str += $(this).text() + ',';
                                cro_id += ',' + $(this).attr('rel');
                            })
                            cro_id = cro_id.slice(1);
                            str = str.slice(0, -1);
                        }

                        var obj = $('.myCourse .listli.on');
                        var dialog = $(this);
                        $.post('__APPURL__/Course/sync', {c_id: c_id, cro_id: cro_id, co_id: obj.attr('rel')}, function (json) {
                            if (json['status'] == 0) {
                                showMessage(json['info']);
                            } else {
                                obj.attr('attr', c_id);
                                obj.attr('group', cro_id);
                                showMessage(json['info'], 1);
                                // 在点击确定按钮时，要实时的把已绑定的班级和群组，用DOM操作写到指定的课程中去
                                $('.myCourse .listli.on .xin_cur').html('使用班级：' + str);
                                dialog.dialog('close');
                            }
                        }, 'json');
                    },
                    取消: function() {
                        $(this).dialog('close');
                    }
                }
            });

            //新增群组
            $(document).on('click','.xin_sch',function(){
                $('.select_add').dialog("open");
            })

            // 群组弹窗
            $(".select_add").dialog({
                draggable: true,        // 是否允许拖动,默认为 true
                resizable: true,        // 是否可以调整对话框的大小,默认为 true
                autoOpen: false,        // 初始化之后,是否立即显示对话框,默认为 true
                position :'center',       // 用来设置对话框的位置
                stack : true,       // 对话框是否叠在其他对话框之上。默认为 true
                modal: true,       // 是否模式对话框,默认为 false(模式窗口打开后，页面其他元素将不能点击，直到关闭模式窗口)
                bgiframe: true,         // 在IE6下,让后面遮罩层盖住select
                width: '680',
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

                        // 群组名称
                        var tVal = $('.select_right .right_input').val();

                        // 群组成员数量
                        var tCh = $('.select_right ul').children().size();

                        String.prototype.trim = function (){
                            return this.replace(/[ ]/g,'');
                        }
                        var tNav = true;
                        var tTim = tVal.trim();

                        if(tTim.length > 6){
                            showMessage('群组名称超过6个字符，请重新输入');
                            tNav = false;
                        }

                        if(tTim.length <= 0){
                            showMessage('群组名不能为空');
                            tNav = false;
                        }

                        if(tCh <= 0){
                            showMessage('请添加成员');
                            tNav = false;
                        }

                        if(tNav == true){
                            var _this = $(this);
                            var aid = returnAid();
                            aid = aid.slice(1, -1);
                            // 异步添加组以及成员
                            $.post('__APPURL__/Course/insertCrowd', {a_id: aid, cro_title:tTim}, function (json) {

                                // 把返回的群组id赋值给rel属性
                                if (json['status'] == 1) {
                                    $('.xin_adae .sa_click').append("<span rel='" + json['info'] + "'>"+tVal+"</span>");
                                    _this.dialog('close');
                                } else {
                                    showMessage(json['info']);
                                }
                            });
                        }
                    },
                    取消: function() {
                        $(this).dialog('close');
                    }
                }
            });

            function returnAid() {
                var a_id = ',';
                $('.select_right ul li').each(function () {
                    a_id +=  $(this).attr('rel') + ',';
                })
                return a_id;
            }

            // 默认显示第一个学段 年纪，班级隐藏
            $('.sel_ul li').eq(0).show().siblings().hide();
             $('.select_left .select_num').hide();

            // 点击学制时显示相关的年级
            $(document).on('click','.coType span',function(){

                if ($(this).attr('rel') == 4) {
                    $('.coGrade').html('');
                    $('.coMajor').show();
                    listGrade($(this).attr('rel'), 'coMajor', 1);
                } else {
                    $('.coMajor').hide();
                    listGrade($(this).attr('rel'), 'coGrade', 1);
                }

                $('.coGrade').show();
            })

            // 依据专业选择年级
            $(document).on('click', '.coMajor span', function () {
                $(this).addClass('xin_ds').siblings().removeClass('on xin_ds');
                listGrade($(this).attr('rel'), 'coGrade', 1, 'major');
            })


            // 点击年级时，异步显示相关的班级
            $(document).on('click','.coGrade span', function () {

                $(this).addClass('xin_ds').siblings().removeClass('on xin_ds');
                var ma_id = $('.coMajor').css('display') == 'none' ? 0 : $('.coMajor .xin_ds').attr('rel');
                $.post('__APPURL__/Course/searchClass', {c_type: $('.coType .xin_ds').attr('rel'), c_grade: $('.coGrade .xin_ds').attr('rel'), ma_id: ma_id}, function (json) {
                    var html = '';
                    if (json) {

                        for (var i = 0; i < json.length; i++) {
                            html += '<div class="aaa">'
                                 +  '<span c_id="' + json[i]['c_id'] + '" c_grade="' + json[i]['c_grade'] + '" c_type="' + $('.coType .xin_ds').attr('rel') + '">' + json[i]['c_name'] + '</span>'
                                 +  '<div></div>'
                                 +  '</div>'
                        }
                        $('.num_remove').html(html);
                    } else {
                        $('.num_remove').html(html);
                    }
                }, 'json')
            })


            // 成员点击
            $(document).on('click','.sel_ul li span',function(){

               $(this).addClass('xin_ds').siblings().removeClass('xin_ds');

                if ($(this).parents('li').index() == ($('.sel_ul li').size()-1)){
                    $('.select_left .select_num').show();
                } else {
                    if ($(this).parents('li').hasClass('coMajor') == undefined) {
                        $(this).parents('li').next().show();
                    }
                }
            })

            // 成员选择
            $(document).on('click','.num_remove span',function(){

                var _this = $(this);

                if (_this.attr('class') == 'xin_ds'){
                    $('.num_remove span').removeClass('xin_ds');
                    $('.num_remove ul').hide();
                } else {

                    //  显示班级
                    $.post('__APPURL__/Course/searchMember', {c_grade: _this.attr('c_grade'), c_type: _this.attr('c_type'), c_id: _this.attr('c_id')}, function (json) {
                        var html = '<ul>';
                        var aId = ',' + returnAid() + ',';
                        var tmp = '';
                        if (json) {

                            for (var i = 0; i < json.length; i++) {
                                tmp = ',' + json[i]['a_id'] +','
                                html += '<li rel="' + json[i]['a_id'] + '" class="' + (aId.indexOf(tmp) != -1 ? 'xin_reli' : '') + '">'
                                + json[i]['a_nickname'] +  '</li>'
                            }
                            html +=  '</ul>';
                        }
                        _this.next().html(html);
                        $('.num_remove span').removeClass('xin_ds');
                        $('.num_remove ul').hide();
                        _this.addClass('xin_ds');
                        _this.next().find('ul').show();
                    } , 'json')
                }

            })

            // 绑定班级添加
            $(document).on('click','.xin_sain .sa_click span',function(){
                var tText = $(this).text();
                $('.xin_sain .sa_opend span').each(function(){
                        if($(this).text() == tText){
                            $(this).remove();
                        }
                })
                if($(this).hasClass('xin_ds')){
                    $(this).removeClass('xin_ds');
                    $('.xin_sain .sa_opend span').each(function(){
                        if($(this).text() == tText){
                            $(this).remove();
                        }
                    })
                }else {
                    $(this).addClass('xin_ds');
                    $(this).parents('.xin_sain').children('.sa_opend').append("<span rel='" + $(this).attr('rel') + "'>"+tText+"</span>");
                }
            })

            // 绑定群组添加
            $(document).on('click','.xin_adae .sa_click span',function(){
                var tText = $(this).text();
                $('.xin_adae .sa_opend span').each(function(){
                        if($(this).text() == tText){
                            $(this).remove();
                        }
                })
                if($(this).hasClass('xin_dss')){
                    $(this).removeClass('xin_dss');
                    $('.xin_adae .sa_opend span').each(function(){
                        if($(this).text() == tText){
                            $(this).remove();
                        }
                    })
                }else{
                    $(this).addClass('xin_dss');
                    $(this).parents('.xin_adae').children('.sa_opend').append("<span rel='" + $(this).attr('rel') + "'>"+tText+"</span>");
                }
            })

            // 新增群组成员 滑过
            $(document).on('mouseover','.select_right ul li',function(){
                $(this).addClass('on').children('a').show();
            })

            // 新增群组成员 滑离
            $(document).on('mouseout','.select_right ul li',function(){
                $(this).removeClass('on').children('a').hide();
            })

            // 班级成员点击
            $(document).on('click','.num_remove ul li',function(){
                if(!$(this).hasClass('xin_reli')){
                    if($(this).hasClass('xin_relian')){
                        $(this).removeClass('xin_relian');
                    }else{
                        $(this).addClass('xin_relian');
                    }
                }
            })

            // 新组成员添加
            $(document).on('click','.select_cen a',function(){
                $('.num_remove ul li').each(function(){
                    if($(this).hasClass('xin_relian')){
                        var tText = $(this).text();
                        $('.select_right ul').append("<li rel='"+$(this).attr('rel')+"'>"+tText+"<a href='#' class='flex_close cDel'></a></li>");
                        $(this).removeClass('xin_relian').addClass('xin_reli');
                    }
                })
            })

            // 新组成员删除
            $(document).on('click','.select_right ul .flex_close',function(){
                if(confirm('你确定要删除吗')){
                    var oVal = $(this).parent().text();
                    $('.num_remove li').each(function(){
                        if($(this).text() == oVal){
                            $(this).removeClass('xin_reli')
                        }
                    })
                    $(this).parent().remove();
                }else{
                    return false;
                }
            })
        })
    //-->
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
        <div class="mainBox">
            <ul class="mainBox_tab_menu fl">
                <li class="selected">我的课程</li>
                <!--li>我分享的课程</li>
                <li>分享我的课程</li-->
            </ul>
            <div class="mainBox_tab_box fl">
                <ul class="course_list myCourse">
                    <li class="listli nobg"><a href="__APPURL__/Course/add"><img src="__APPURL__/Public/Images/Home/addCourse.png"/></a></li>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="listli listCourse" rel="<?php echo ($vo["co_id"]); ?>" attr="<?php echo ($vo["c_id"]); ?>" group="<?php echo ($vo["cro_id"]); ?>" >
                        <div class="pic"><img src="<?php echo (getcoursecover($vo["co_cover"],$vo['co_subject'])); ?>"/></div>
                        <div class="course_cover">
                            <ul class="tool_list">
                                <!--li><a class="cShare" href="#"><i>分享课程</i></a></li-->
                                <li><a class="cEdit" href="__APPURL__/Course/edit/id/<?php echo ($vo["co_id"]); ?>"><i>编辑课程</i></a></li>
                                <li><a class="cIn" href="__APPURL__/Lesson/index/course/<?php echo ($vo["co_id"]); ?>"><i>进入课程</i></a></li>
                                <li><a class="cClone" href="#"><i>克隆课程</i></a></li>
                                <li><a class="cExport" href="#"><i>导出课程</i></a></li>
                                <li><a class="cDel" rel='<?php echo ($vo["co_id"]); ?>' attr='<?php echo ($vo["co_cover"]); ?>' href="#"><i>删除课程</i></a></li>
                            </ul>
                        </div>
                        <div class="courseInfo">
                            <a class="title" title="<?php echo ($vo["co_title"]); ?>" href="__APPURL__/Lesson/index/course/<?php echo ($vo["co_id"]); ?>" title="<?php echo ($vo["co_title"]); ?>"><?php echo ($vo["co_title"]); ?></a>
                            <p title="<?php echo (gettypenamebyid($vo["co_version"],'VERSION_TYPE')); ?>">版本 ：<?php echo (gettypenamebyid($vo["co_version"],'VERSION_TYPE')); ?></p>
                            <p title="" class="xin_cur">使用班级：<?php if($vo["c_name"] != '' or $vo["cro_name"] != ''): echo ($vo["c_name"]); ?> <?php echo ($vo["cro_name"]); else: ?><cite>请选择</cite><?php endif; ?></p>
                        </div>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <ul class="course_list hide"></ul>    <!-- 我分享的课程 -->
                <ul class="course_list hide"></ul>    <!-- 分享我的课程 -->
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <div id="share_course" title="分享课程" class="hide">
        <form method="post" action="" onsubmit="">
            <p class="export_num">
                <input type="radio" name="" value="" />读共享：可以浏览课程、克隆课程、导出课程<br/>
                <input type="radio" name="" value="" />写共享：可以直接编辑课程、浏览课程、克隆课程、导出课程
            </p>
            <p>
                <span class="share_to">分享给：</span>
                <ul class="s_c_tabmenu">
                    <li class="current">您的群组</li>
                    <li>您的同事</li>
                    <li>您的班级</li>
                </ul>
                <div class="s_c_tabbox">
                    <ul class="current">
                        <li><input type="checkbox" name="">语文教研组</li>
                        <li><input type="checkbox" name="">选修一班</li>
                        <li><input type="checkbox" name="">选修二班</li>
                    </ul>
                    <ul class="hide">
                        <li><input type="checkbox" name="">语文老师1</li>
                        <li><input type="checkbox" name="">语文老师2</li>
                        <li><input type="checkbox" name="">语文老师3</li>
                    </ul>
                    <ul class="hide">
                        <li><input type="checkbox" name="">初一（1）班</li>
                        <li><input type="checkbox" name="">初一（2）班</li>
                        <li><input type="checkbox" name="">初一（3）班</li>
                    </ul>
                </div>
            </p>
        </form>
    </div>
    <div id="export_course" title="导出课程" class="hide">
        <form method="post" action="" onsubmit="">
            <p>您将导出“<cite>初一上学期语文（人教版）</cite>”</p>
            <p class="export_num">
                <input type="radio" name="exportFile" value="1">导出为Xml格式</input><br/>
                <input type="radio" name="exportFile" value="2">导出Html格式</input><br/>
                <!--input type="radio" name="exportFile" value="3">导出Html格式（包括基本数据和素材）</input-->
            </p>
        </form>
    </div>
    <div class="xin_add hide" title="添加成员">
        <ul>
            <li class="xin_uli">按班级</li>
            <li>按群组</li>
        </ul>
        <div class="clear"></div>
        <div class="xin_noe">
            <div class="xin_sain">
                <label class="fl">已绑定的班级:</label>
                <div class="fl sa_opend bindClass">
                </div>
                <div class="clear"></div>
                <label class="fl">未绑定的班级:</label>
                <div class="fl sa_click allMyClasses">
                    <?php if(is_array($class["cst"])): $i = 0; $__LIST__ = $class["cst"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["c_id"]); ?>"><?php echo ($vo["c_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
            <div class="xin_adae">
                <label class="fl">已绑定的群组:</label>
                <div class="fl sa_opend bindGroup">

                </div>
                <a class="xin_sch fr">新增群组</a>
                <div class="clear"></div>
                <label class="fl">未绑定的群组:</label>
                <div class="fl sa_click allMyGroup">
                    <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["cro_id"]); ?>"><?php echo ($vo["cro_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="select_add hide" title="新增群组">
        <div class="select_left fl">
            <ul class="sel_ul">
                <li>
                    <label>学制：</label>
                    <div class="coType">
                        <?php if(is_array($class["school"])): $i = 0; $__LIST__ = $class["school"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span  rel="<?php echo ($vo); ?>"><?php echo (gettypenamebyid($vo,'SCHOOL_TYPE')); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </li>
                <li class="coMajor"></li>
                <li class="coGrade"></li>
            </ul>
            <div class="select_num fl">
                <label>班级：</label>
                <div class="num_remove"></div>
            </div>
        </div>
        <div class="select_cen">
            <a title='添加'>>></a>
        </div>
        <div class="select_right fr">
            <label>群组名</label>
            <input type='text' name='' value='' class="right_input"/>
            <div class="clear"></div>
            <label>成员</label>
            <ul></ul>
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