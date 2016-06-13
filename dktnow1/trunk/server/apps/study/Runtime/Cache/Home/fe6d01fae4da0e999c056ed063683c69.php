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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/upgrade.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<script type="text/javascript" src="/Public/Js/Public/provincesCity.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesdata.js"></script>
    <script type="text/javascript">
    <!--
        $(function() {

            // 用户申请
            $(document).on('click', '.apply', function(){

                var type = $(this).attr('type');
                var c_id = $(this).attr('attr');
                var s_id = $(this).attr('rel');

                $.post('__APPURL__/ApplyAuth/applyTo', 's_id='+s_id+'&c_id='+c_id+'&type='+type, function(json){

                    if (json.status) {

                            showMessage('申请成功', 1);

                    } else {
                        showMessage(json.info);
                    }
                }, 'json');

            });

            $('.applyClass').click(function() {
                location.href = '__APPURL__/ApplyClass/index/s_id/'+$('.out').attr('attr');
            })

            // 班级tab切换
            $(document).on('click', ".class_tab li", function(){

                $(this).addClass("current").siblings().removeClass("current");
                $('.class_list_box .eb').eq($(this).index()).show().siblings().hide();
            })

            // 选择学校窗口
            $("input[name='chooseSchool']").focusin(function(){

                $('.choose_list').show();
            })

            // 关闭选择学校窗口
            $('.close_choose_list').click(function(){

                $(this).parent().parent().hide();
            })

            // 学校所在地联动
            setProvince("province", "a_region", '');

            // 异步加载学校
            $("input[name=confirm]").click(function(){
                ShowSchool();
            })

            // 选择学段异步加载学校
            $('input[name=choose_school]').click(function(){

                if ($('.choose_list').css('display') == 'block') {
                    ShowSchool();
                }
            });

            // 选择学校获取班级
            $(document).on('click','.school', function(){

                // 输入框显示选择的学校
                $('input[name=chooseSchool]').val($(this).html());

                // 设置当前选择学校的id
                $('.out').attr('attr', $(this).attr('attr'));

                // 显示班级
                searchClass($(this).attr('attr'), $(this).attr('type'));
                $('.close_choose_list').click();
            });

            //批量注册 a 连接
            $('.applyClass a').click(function(){
                  document.location.href="javascript:void(0);";
            })
        })

        // 显示学校
        function ShowSchool(){

            // 获取学段,和地区
            var xueduan = $('input[name=choose_school]:checked').attr('attr');
            var region = $('input[name=a_region]').val();

            if(region == '######'){
                region = '';
            }

            $('.out').attr('attr', '');

            // 获取学校数据
            $.post('__APPURL__/School/getSchoolByRegion', 'xueduan='+xueduan+'&region='+region, function(json) {

                var str = '';
                var obj = json.list;
                if (json.status == 1) {

                    str += '<ul>';
                    if (obj) {
                        for (var i = 0; i < obj.length; i++) {
                            str += '<li attr="'+obj[i]['s_id']+'" type="'+obj[i]['s_type']+'" class="school">'+obj[i]['s_name']+'</li>';
                        }
                    }
                    str += '</ul>';
                    $('.out').attr('attr', obj[0]['s_id']);
                } else {
                    str += json.info;
                }

                $('.search_result').html('');
                $('.search_result').append(str);

            }, 'json');
        }

        // 显示班级
        function searchClass(s_id, s_type){

            var classList = "";
            $('.applyClass').show();

            if(s_id && s_type){

                // 获取班级数据
                var classList = '';
                $('.insert_list').html('');
                $.post('__APPURL__/Class/lists', 's_id='+s_id+'&s_type='+s_type+'&g_order=1&is_ajax=1', function(json){

                    if (!json) {
                        $('.applyClass').show();$('.class_tab li').eq(0).click();return false;
                    }

                    // 列举年级
                    classList += '<ul class="class_tab">';

                    for (var i = 0; i < json.length; i++) {
                        classList += '<li>'+json[i]['name']['name']+'</li>';
                    }

                    classList += '</ul>';
                    classList += '<div class="class_list_box">'

                    // 列举班级

                    for (var j = 0; j <json.length; j++ ) {

                        classList += '<div class="eb hide"><ul>';
                        var obj = json[j]['lists'];
                        for (var k = 0; k < obj.length; k ++) {
                            classList += '<li><div class="box_left fl"><a href="#"><img src="'+obj[k]['classLogo']+'"/></a><p class="class_name"><a href="#">'+obj[k]['c_name']+'</a><span>'+obj[k]['c_name']+'</span></p></div><p class="box_right fr"><a href="javascript:void(0);" type="2" class="apply" attr="'+obj[k]['c_id']+'"  rel="'+s_id+'">申请为老师</a>';

                            if (<?php echo ($authInfo['a_type']); ?> != 2) {
                                classList += '<a href="javascript:void(0);" type="1" class="apply"  attr="'+obj[k]['c_id']+'" rel="'+s_id+'">申请为学生</a>';
                            }

                            //classList += '<a href="#">查看资料</a></p></li>';
                        }
                        classList += '</ul></div>';
                    }


                    classList += '</div>';

                    $('.insert_list').html('');
                    $('.insert_list').append(classList);

                    $('.class_tab li').eq(0).click();

                }, 'json');

            }

        }

    //-->
    </script>

    <div class="warp">
        <input type="hidden" value="" id="dialog_c_id" />
        <input type="hidden" value="" id="dialog_s_id" />
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
        <div class="mainBox fl">
            <a class="proof_back fl">&nbsp;&nbsp;&nbsp;&nbsp;申请加入</a>
            <?php if(!empty($flag)): ?><a class="proof_back fl" style="float:right" href="__APPURL__/Class/">&nbsp;&nbsp;&nbsp;&nbsp;返回</a><?php endif; ?>

            <div class="choose_school fl">
                <h1>选择学校</h1>
                <div class="in">
                    <label>所在学校：</label>
                    <p class="school_list">
                        <?php if(is_array($schoolType)): $i = 0; $__LIST__ = $schoolType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoolType): $mod = ($i % 2 );++$i;?><span><input type="radio" name="choose_school" attr="<?php echo ($key); ?>"><?php echo ($schoolType); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                    </p>
                </div>
                <div class="out">
                    <label>选择学校：</label>
                    <input type="text" name="chooseSchool">
                </div>
                <div class="choose_list">
                    <p>
                        <span class="close_choose_list"></span>
                        <span><cite>选择学校</cite> - 学校按名称的拼音顺序排列，同按ctrl+F键可进行搜索</span>
                    </p>
                    <div class="school_address fl">
                        <label class="addr">学校所在地：</label>
                        <span id="province"></span>
                        <input type="button" name="confirm" value="确定" />
                        <input type="hidden" name="a_region"/>
                    </div>
                    <div class="search_result fl">

                    </div>

                    <a class="addlink fr" href="__APPURL__/ApplySchool/">还没有我的学校，<cite>申请添加</cite></a>
                </div>

            </div>

            <div class="choose_class fl">
                <div class="tit">
                    <h1><span>选择班级</span></h1>
                    <p class="applyClass" style="display:none"><a>没有我的班级？<cite>点击批量注册</cite></a><p>
                </div>
                <div class="insert_list">

                </div>
                <div style="clear:both"></div>
                <p class='noclass' style="display:none">该年级还没有老师建立班级！请联系你的老师.</p>
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