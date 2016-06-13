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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/group_home.css" />
<script type="text/javascript">
$(function(){

    // 初始化
    $('.group_right li').click(function(){
        $(this).addClass("group_hover").siblings().removeClass("group_hover");
        getList();
    }).eq(0).click();

    // 创建或加入群组
    $(document).on('click','.gro_app',function(){

        // 清空文本框
        $('.add_input').val('');
        if ($('.group_hover').index() == 0) {

            // 创建群组
            $(".add_class").dialog("open", this);
        } else {

            // 加入群组
            $(".found_class").dialog("open", this);
            $('.add_name').hide();
        }
    });

    // 创建群组
    $(".add_class").dialog({
        draggable: true,
        resizable: true,
        autoOpen: false,
        position :'center',
        stack : true,
        modal: true,
        bgiframe: true,
        width: '385',
        height: 'auto',

        show: {     // 对话框打开效果
            effect: "blind",
            duration: 400
        },
        hide: {     //对话框关闭效果
          effect: "explode",
          duration: 400
        },
        overlay: {
            backgroundColor: '#000',
            opacity: 0.5
        },
        buttons: {
            确定: function () {

                var oVal = $(this).children('input').val();
                String.prototype.trim = function(){
                    return this.replace(/[ ]/g,'')
                }
                var newVal = oVal.trim();
                if (newVal == "") {
                    showMessage('群组名不能为空')
                    return false;
                } else {
                    if (newVal.length > 6) {
                        showMessage('群组名不能超过6个字符')
                    } else {
                        var obj = $(this);
                        $.post("__URL__/insert", 'cro_title=' + oVal, function(json) {

                            if (json.status) {
                                getList();
                                obj.dialog('close');
                            } else {
                                showMessage(json.info);
                            }
                        }, 'json');
                    }
                }
            },
            取消: function() {
                $(this).dialog('close');
            }
        }
    });

    // 加入群组
    $(".found_class").dialog({
        draggable: true,
        resizable: true,
        autoOpen: false,
        position :'center',
        stack : true,
        modal: true,
        bgiframe: true,
        width: '385',
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
            确定: function () {
                var _this = $(this);

                // 申请加入群组
                if ($('.add_name span.on').size()) {
                    $.post('__APPURL__/CrowdCheck/insert', {a_id:$('.add_name span.on').attr('a_id'), cro_id:$('.add_name span.on').attr('cro_id')}, function (json) {
                        showMessage('申请已提交，请等待审核。');
                        _this.dialog('close');
                    }, 'json')
                } else {
                    _this.dialog('close');
                }

            },
            取消: function() {
                $(this).dialog('close');
            }
        }
    });

    // 群组搜索
    $(document).on('click','.groupAdd',function(){

        $.post('__URL__/search', 'cro_title=' + $(this).siblings('input').val(), function(json){

            var str = '';
            if (json['status'] == 1) {

                var obj = json.info;

                for (var i = 0; i < obj.length; i++) {
                    if (obj[i]['is_in'] == 1) {
                        str += '<span class="still" a_id="' + obj[i]['a_id'] + '" cro_id="' + obj[i]['cro_id'] + '">' + obj[i]['cro_title'] + '</span>';
                    }
                    if (obj[i]['is_in'] == 0) {
                        str += '<span a_id="' + obj[i]['a_id'] + '" cro_id="' + obj[i]['cro_id'] + '">' + obj[i]['cro_title'] + '</span>';
                    }
                }
            } else {
                str = '<span>' + json['info'] + '</span>';
            }

            $('.add_name').show();
            $('.add_name').html(str);

        }, 'json');

    })

    /*加入群组*/
    $(document).on('click','#searchResult span',function(){
        if (!$(this).hasClass('still')){
            if ($(this).hasClass('on')) {
                $(this).removeClass('on')
            } else {
                $(this).addClass('on').siblings().removeClass('on');
            }
        }
    })

    // 退出群组
    $(document).on('click', '.exitCrowd', function () {

        if (confirm('您确定要退出群组吗？')) {
            var obj = $(this).parents('.group_content');
            $.post('__URL__/deleteAuth', {authId:$('input[name=a_id]').val(), croId:$(this).attr('cro_id')}, function(json) {
                if (json.status == 1) {
                    obj.remove();
                } else {
                    showMessage(json.info);
                }
            }, 'json')
        }
    });

    // 群组课程
    $(document).on('click', '.enterCrowd', function () {
        if ($(this).attr('co_id') == 0) {
            showMessage('请为该群组指定课程');
        } else {
            $(this).attr({'href': '__APPURL__/Lesson/index/course/' + $(this).attr('co_id') + '/cro_id/' + $(this).attr('cro_id')})
        }
    })
})

function space(id) {
    location.href = "__URL__/space/id/" + id;
}

function getList(p) {

    if(!p){
        p = 1;
    }

    var type = $('.group_hover').index();
    var str = '';
    var text = title = '';
    if (type == 1) {
        title = '加入群组';
        text = '进入群组';
    } else if (type == 0){
        title = '创建群组';
        text = '管理群组';
    }
    str = '<div class="gro_app"><a href="#"><span></span><center>'+title+'</center></a></div>';

    if (type == 2) {
        str = '';
    }

    $.post("__URL__/lists/", 'p='+p+'&type=' + type, function(json) {

        if (json && json.list) {

            var obj = json.list;

            if (type == 1) {
                for (var i = 0; i < obj.length; i ++) {
                    str += '<div class="group_content"><a href="javascript:space(' + obj[i]['cro_id'] + ')"><img src="'+ obj[i]['cro_logo'] +'" width="96" height="96" class="fl" alt="'+ obj[i]['cro_title'] +'" title="'+ obj[i]['cro_title'] +'"/></a><div class="fl"><p class="gro_number" alt="'+ obj[i]['cro_title'] +'" title="'+ obj[i]['cro_title'] +'"><a href="javascript:space(' + obj[i]['cro_id'] + ')">'+ obj[i]['cro_title'] +'</a></p><a href="javascript:space(' + obj[i]['cro_id'] + ')">'+text+'</a><p><a href="javascript:void(0)" cro_id="' + obj[i]['cro_id'] + '" class="exitCrowd">退出群组</a></p></div></div>';
                }
            } else if (type == 0) {
                for (var i = 0; i < obj.length; i ++) {
                    str += '<div class="group_content"><a href="javascript:space(' + obj[i]['cro_id'] + ')"><img src="'+ obj[i]['cro_logo'] +'" width="96" height="96" class="fl" alt="'+ obj[i]['cro_title'] +'" title="'+ obj[i]['cro_title'] +'"/></a><div class="fl"><p class="gro_number" alt="'+ obj[i]['cro_title'] +'" title="'+ obj[i]['cro_title'] +'"><a href="javascript:space(' + obj[i]['cro_id'] + ')">'+ obj[i]['cro_title'] +'</a></p><p alt="群组课程" title="群组课程"><a class="enterCrowd"  co_id="' + obj[i]['co_id'] + '" cro_id="' + obj[i]['cro_id'] + '" href="javascript:void(0);">群组课程</a></p><a href="javascript:space(' + obj[i]['cro_id'] + ')">'+text+'</a></div></div>';
                }
            }

            str += '<div class="clear"></div><div class="page">'+json.page+'</div>';
        }

        $('.group_center').html(str);
    }, "json");

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
    <div class="group_right fr">
        <ul class="fl gro_cluster">
            <li class="group_hover">我的群组</li>
            <li>我参与的群组</li>
            <!--li>审核群组</li-->
            <input type="hidden" name="a_id" value="<?php echo ($a_id); ?>">
        </ul>
        <div class="group_center fl">
            
        </div>
    </div>
</div>
<div class="found_class" title="加入群组">
    <span class="add_span">群组名称：</span>
    <input type="text" class="add_input"value=""/>
    <a class="groupAdd">搜索</a>
    <div id="searchResult" class="add_name">
    </div>
</div>
<div class="add_class"  title="创建群组">
    <span class="add_span">群组名称：</span>
    <input type="text" class="add_input found_input" value=""/>
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