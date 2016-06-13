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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<script type="text/javascript" src="/Public/Js/Home/public.js"></script>
    <script type="text/javascript">
    <!--
        $(function() {

            // 隐藏专业
            $('.coMajor').hide();

            // 班级ID，在点击标准课程时，才起作用
            var cl_id = 0;

            var chooseFlag = 0;
            var grade = <?php echo ($course["co_grade"]); ?>;
            var co_type = <?php echo ($course["co_type"]); ?>;
            var major = <?php echo ($course["ma_id"]); ?>;

            // 处理编辑状态，则把自定义和标准课程那一栏给去掉
            $('.top_tab_menu li').remove();

            // 判断是标准课时还是自定义课程
            if ($('.c_taga span').size() == 1) {
                $('input[name=flag]').val(0);
                $('.switchbox ul li').eq(1).hide();
                $('.top_tab_menu li').eq(0).addClass('select_ed').siblings().removeClass('select_ed');
            } else {

                // 页面开始加载时便加载选中的年级，同时操作DOM选中指定的年级
                if (co_type== 4) {
                    chooseFlag = 1;
                    $('.coMajor').show();
                    listGrade(co_type, 'coMajor', major);
                } else {
                    listGrade(co_type, 'coGrade', grade);
                }

                $('input[name=flag]').val(1);
                $('.switchbox ul li').eq(0).hide();
                $('.top_tab_menu li').eq(1).addClass('select_ed').siblings().removeClass('select_ed');
            }

            // 单击标准课程中已选好的课程，那么页面上的dom节点则自动选中相关的数据
            $(document).on('click', '.schoolType', function () {

                var _this = $(this);

                // 选中学段
                $('.coType .hand').each(function () {
                    if ($(this).attr('rel') == _this.attr('type')) {
                        $(this).addClass('on').siblings().removeClass('on');
                    }
                })

                // 判断学段是否为大学，是的话需要加专业
                if ($('.coType .hand.on').attr('rel') == 4) {
                    $('.coGrade').html('');
                    $('.coMajor').show();
                    listGrade(4, 'coMajor', _this.attr('ma_id'));
                    cl_id = _this.attr('grade');
                } else {

                    $('.coMajor').html('');
                    $('.coMajor').hide();

                    // 选中年级
                    $('.coGrade .hand').each(function () {
                        if ($(this).attr('rel') == _this.attr('grade')) {
                            $(this).addClass('on').siblings().removeClass('on');
                        }
                    })
                }

                // 选中学期
                $('.coSemester .hand').each(function () {
                    if ($(this).attr('rel') == _this.attr('semester')) {
                        $(this).addClass('on').siblings().removeClass('on');
                    }
                })

                // 选中科目
                $('.coSubject .hand').each(function () {
                    if ($(this).attr('rel') == _this.attr('subject')) {
                        $(this).addClass('on').siblings().removeClass('on');
                    }
                })
            })

            // 单击自定义的课程时，一切复原
            $(document).on('click', '.ownerType', function () {

                var _this = $(this);

                // 选中学段
                $('.coType .hand').each(function (i) {
                    if (i == 0) {
                        $(this).addClass('on').siblings().removeClass('on');

                        // 同时还得是其相关的年级发生变化
                        listGrade($('.coType span.on').attr('rel'), 'coGrade', 1);
                    }
                })

                // 选中专业
                $('.coMajor .hand').each(function (i) {
                    if (i == 0) {
                        $(this).addClass('on').siblings().removeClass('on');
                    }
                })

                // 选中年级
                $('.coGrade .hand').each(function (i) {
                    if (i == 0) {
                        $(this).addClass('on').siblings().removeClass('on');
                    }
                })

                // 选中学期
                $('.coSemester .hand').removeClass('on');

                // 选中科目
                $('.coSubject .hand').removeClass('on');
            })

            // 添加标签 弹出窗口
            $(".tag_list").dialog({
                draggable: true,
                resizable: true,
                autoOpen: false,
                position :'center',
                stack : true,
                modal: true,
                bgiframe: true,
                width: '750',
                height: 'auto',
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

                        if ($('.coSemester .on').text() == '') {
                            showMessage('请选择学期');
                            return;
                        }

                        if ($('.coSubject .on').text() == '') {
                            showMessage('请选择学科');
                            return;
                        }

                        // 如果选择了大学，则把专业的值放入隐藏域里
                        if ($('.coType .hand.on').attr('rel') == 4) {
                            $('input[name=ma_id]').val($('.coMajor .hand.on').attr('rel'));
                        } else {
                            $('input[name=ma_id]').val(0);
                        }

                        // 选中的标签，都贴到课程标签右侧
                        var html = '', count = 0;
                        $('.tag_tag .on').each(function (i) {
                            if (i != 0  && $(this).parent().css('display') != 'none') {
                                html += '<span rel="' + $(this).attr('rel') + '">' + $(this).text() + '</span>';

                                // 给隐藏的域里赋值
                                if (!$(this).parent().hasClass('coMajor')) {
                                    count++;
                                    $('.hiddenTag input[type=hidden]').eq(count-1).val($(this).attr('rel'));
                                }
                            } else {

                                // 班级教师授课表的id
                                $('input[name=cst_id]').val($(this).attr('cst_id') ? $(this).attr('cst_id') : 0);
                            }
                        })

                        $('.c_taga').html('<span class="add_tag add_taga">+添加标签</span>');
                        $('.c_taga').append(html);
                        $(this).dialog('close');
                    },
                    取消: function() {
                        $(this).dialog('close');
                    }
                }
            });

            // 添加标签
            $(document).on('click','.add_taga',function(){
                $('.tag_list').dialog("open");
            })

            // 选中年级
            $(document).on('click', ".coGrade .hand", function() {
                $(this).addClass('on').siblings().removeClass('on');
            })

            // 根据学制选择年级
            $(".coType .hand").click(function() {
                $(this).addClass('on').siblings().removeClass('on');
                if ($(this).attr('rel') == 4) {
                    $('.coGrade').html('');
                    $('.coMajor').show();
                    listGrade($(this).attr('rel'), 'coMajor', 1);
                } else {
                    $('.coMajor').hide();
                    listGrade($(this).attr('rel'), 'coGrade', 1);
                }
            })

            // 依据专业选择年级
            $(document).on('click', '.coMajor span.on', function () {
                if (chooseFlag == 1) {
                    listGrade($(this).attr('rel'), 'coGrade', grade, 'major');
                    chooseFlag++;
                } else if (cl_id != 0) {
                    listGrade($(this).attr('rel'), 'coGrade', cl_id, 'major');
                    cl_id = 0;
                } else {
                    listGrade($(this).attr('rel'), 'coGrade', 1, 'major');
                }
            })

            // 选中专业
            $(document).on('click', '.coMajor .hand', function() {
                $(this).addClass('on').siblings().removeClass('on');
            })

            $('.tag_tag .hand').on('click', function() {
                $(this).addClass('on').siblings().removeClass('on');
            })

            $(document).on('click','.list_hand .hand',function(){
                var tVal = $(this).text();
                var rel = $(this).attr('rel');

                if ($('.list_append span').size() < 5) {

                    var flag = true;
                    $('.list_append span').each(function(){

                        // 如果有相同的rel属性，则说明，已经添加过该标签
                        if ($(this).attr('rel') == rel) {
                            flag = false;
                            return false;
                        }
                    })

                    if (flag) {
                        $('.list_append').append("<span class='hand' rel=" + rel + ">"+tVal+"<a class='flex_close cDel'></a></span>");

                        // 更新
                        $.post('__APPURL__/Term/update', {te_id:rel}, function (json) {}, 'json');
                    }
                } else {
                       showMessage('最多可添加5个标签!');
                }
 
            })

            // 开始备课
            $('.start_bk').click(function () {
                var oVal = $('input[name=co_title]').val();
                var newVal = oVal.replace(/[ ]/g,'');

                if(newVal.length > 20){
                    showMessage('课程名称过长，请重新输入');
                    return false;
                }

                var oVal = $($('input[name=co_count]')).val();
                var reg=/^\d{1,3}$/; 
                var newVal =(reg.test(oVal));
                if(newVal == false){
                    showMessage('请重新输入课时名');
                    $($('input[name=co_count]')).focus();
                    return false;
                }

                $('input[name=co_flag]').val(1);
                if(checkInfo()) {
                    $("form:first").attr("action","__APPURL__/Course/update").submit();
                }
            })

            // 保存，休息一会儿
            $('.save_cg').click(function () {
                var oVal = $('input[name=co_title]').val();
                var newVal = oVal.replace(/[ ]/g,'');

                if(newVal.length > 20){
                    showMessage('课程名称过长，请重新输入');
                    $('input[name=co_title]').focus();
                    return false;
                }

                var oVal = $($('input[name=co_count]')).val();
                var reg=/^\d{1,3}$/; 
                var newVal =(reg.test(oVal));
                if(newVal == false){
                    showMessage('请重新输入课时名');
                    $('input[name=co_count]').focus();
                    return false;
                }

                $('input[name=co_flag]').val(2);
                if(checkInfo()) {
                    $("form:first").attr("action","__APPURL__/Course/update").submit();
                }
            })

            // 检查输入的信息
            function checkInfo() {

                // 自定义
                if ($('input[name=co_semester]').val() == 0) {

                    // 1是自定义课程
                    $('input[name=flag]').val(1);

                    if ($(".c_tagb span").size() < 2) {
                        showMessage('请添加标签');
                        return false;
                    }

                    // 储存选中标签id
                    var str = '';
                    $('.c_tagb span').each(function () {
                        if ($(this).index() > 0) {
                            str += $(this).attr('rel') + ','
                        }
                    })
                    $('input[name=termId]').val(str);
                    
                    // 自定义状态下，.hiddenTag隐藏的属性数值皆为0
                    $('.hiddenTag input[type=hidden]').val(0);
                } else {
                    if ($(".c_taga span").size() < 2) {
                        showMessage('请添加标签');
                        return false;
                    }
                }

                if ($('input[name="co_title"]').val().trim() == '') {
                    //showMessage('请输入课程名称');
                    $('input[name="co_title"]').next().html('请输入课程名称');
                    $('input[name="co_title"]').next().css('color','red');
                    $('input[name="co_title"]').focus();
                    return false;
                }
                if (!$('input[name="co_count"]').val()) {
                    //showMessage('请输入课程课时');
                    $('input[name="co_count"]').next().html('请输入课程课时');
                    $('input[name="co_count"]').next().css('color','red');
                    $('input[name="co_count"]').focus();
                    return false;
                } else if (!/^\d{1,3}$/g.test($('input[name="co_count"]').val())) {
                    //showMessage('课程课时必须为数字');
                    $('input[name="co_count"]').next().html('必须为3位以内的数字');
                    $('input[name="co_count"]').next().css('color','red');
                    $('input[name="co_count"]').focus();
                    return false;
                }
                return true;
            }

            // 自定义添加标签
            $(document).on('click','.add_tagb',function(){
                $('.tagcustom').dialog("open");
            })

            //换一批
            var tlen=1;
            $('.append_list li').eq(0).show().siblings().hide();
            $(document).on('click','.list_lab',function(){
                if(tlen == $('.append_list li').size()){tlen=0}
                $('.append_list li').eq(tlen).show().siblings().hide();
                tlen++
            })

            // 添加标签 弹出窗口
            $(".tagcustom").dialog({
                draggable: true,
                resizable: true,
                autoOpen: false,
                position :'center',
                stack : true,
                modal: true,
                bgiframe: true,
                width: '600',
                height: 'auto',
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

                        // 把确定按钮里的字清空
                        $('.tag_input').val('');
                        
                        var html = '<span class="add_tag add_tagb">+添加标签</span>';
                        html += $('.list_append').html();
                        $('.c_tagb').html(html);
                        $(this).dialog('close');
                    },
                    取消: function() {
                
                        // 把确定按钮里的字清空
                        $('.tag_input').val('');
                
                        // 取消时，清空已选中的标签
                        $('.list_append').html('')
                        
                        $(this).dialog('close');
                    }
                }
            });

            //弹窗删除按钮
            $(document).on('click','.cDel',function(){
                if (confirm("确定要删除该课程吗？")){
                    $(this).parent().remove();
                    $.post('__APPURL__/Term/update', {te_id:$(this).parent().attr('rel'), flag:1}, function (json) {}, 'json');
                }
                else
                {
                    return false;
                }
            })

            //弹窗删除按钮 显示，隐藏
            $(document).on('mouseover','.tag_room span',function(){
                $(this).children('a').show();
            }).on('mouseout','.tag_room span',function(){
                $(this).children('a').hide();
            })

            $(document).on('mouseover','.list_append span',function(){
                $(this).children('a').show();
            }).on('mouseout','.list_append span',function(){
                $(this).children('a').hide();
            })

            //弹窗添加按钮
            $(document).on('click','.tag_append',function(){
                var tVal = $(this).prev().val();
                String.prototype.trim = function () {
                    return this.replace(/[ ]/g, '');
                }
                var tLen = tVal.trim();

                if (!tLen == "") {

                    if ($('.list_append span').size() < 5) {

                        var flag = true;
                        $('.list_append span').each(function(){

                            if ($(this).text() == tLen) {
                                flag = false;
                                return false;
                            }
                        })

                        if (flag) {
                            $('.list_append').append("<span class='hand' ref=''>"+tLen+"<a class='flex_close cDel'></a></span>");
                            $.post('__APPURL__/Term/insert', {te_title:tLen}, function (json) {
                                if (json['status']) {
                                    // 把返回的标签id赋值给rel属性
                                    $('.list_append span').last().attr('rel', json['info']);
                                }
                            }, 'json')
                        }
                        $(this).prev().val('');
                    } else {
                           showMessage('最多可添加5个标签!');
                    }

                }
            })

            //课程名称判断
            $('input[name=co_title]').focus(function(){
                $(this).next().html('请输入20位以内的字符');
                $('input[name=co_title]').next().css('color','gray');
            })

            $('input[name=co_title]').blur(function(){
                var oVal = $(this).val();
                var newVal = oVal.replace(/[ ]/g,'');
                if(newVal == ''){
                    $(this).next().text('请输入课程名称。');
                    $('input[name=co_title]').next().css('color','red');
                    return false
                }
                if(newVal.length > 20){
                    $(this).next().text('名称过长重新输入');
                    $('input[name=co_title]').next().css('color','red');
                    return false;
                }else{
                    $('input[name=co_title]').next().text('')
                }
            })

            //课时判断
            $('input[name=co_count]').focus(function(){
                $(this).next().text('请勿超过3 个字符');
                $('input[name=co_count]').next().css('color','gray');
            })

            $('input[name=co_count]').blur(function(){
                var oVal = $(this).val();
                var reg=/^\d{1,3}$/; 
                var newVal =(reg.test(oVal));
                if(newVal == false){
                    $(this).next().text('请重新输入课时名');
                    $('input[name=co_count]').next().css('color','red');
                    return false;
                }else{
                    $('input[name=co_count]').next().text('')
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
        <ul class="top_tab_menu fl">
            <li class="selected">自定义课程</li>
            <li>标准课程</li>
            <a class="back fr" href="__APPURL__/Course">返回</a>
        </ul>

        <div class="creat_c_mainBox">
            <div class="switchbox">
                <form method="post" action="" enctype="multipart/form-data">
                    <ul>
                        <li class="libox">
                            <label>课程标签</label>
                            <div class="c_tag c_tagb">
                                <span class="add_tag add_tagb">+添加标签</span>
                                <?php if(is_array($term)): $i = 0; $__LIST__ = $term;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["te_id"]); ?>" class="hand"><?php echo ($vo["te_title"]); ?><a class="flex_close cDel"></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </li>
                        <li class="libox">
                            <label>课程标签</label>
                            <div class="c_tag c_taga">
                                <span class="add_tag add_taga">+添加标签</span>
                                <?php if(is_array($choosedTag)): $i = 0; $__LIST__ = $choosedTag;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="" class="hand"><?php echo ($vo); ?><a class="flex_close cDel"></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </li>
                        <li class="libox input">
                            <label>课程名称</label>
                            <div>
                                <input type="text" name="co_title" value="<?php echo ($course["co_title"]); ?>" />
                                <p class='fl'></p>
                            </div>
                        </li>
                        <li class="libox input">
                            <label>课程课时</label>
                            <div>
                                <input type="text" name="co_count" value="<?php echo ($course["co_count"]); ?>" />
                                <p class='fl'></p>
                            </div>
                        </li>
                        <li class="libox file">
                            <label>课程封面</label>
                            <div>
                                <img src="<?php echo (getcoursecover($course["co_cover"],$course['co_subject'])); ?>" />
                                <input type="file" name="co_cover" />
                            </div>
                        </li>
                        <li class="libox">
                            <label>课程描述</label>
                            <div>
                                <textarea id="desc" name="co_note"><?php echo ($course["co_note"]); ?></textarea>
                            </div>
                        </li>
                        <li class="sub libox">
                            <div>
                                <a href="#" class="save_cg">保存&gt;&gt;休息一会</a>
                                <a href="#" class="start_bk">保存&gt;&gt;开始备课</a>
                            </div>
                        </li>
                    </ul>
                    <div class="hiddenTag">
                        <input type="hidden" autocomplete="off" name="co_type"  value="<?php echo ($course["co_type"]); ?>"/>
                        <input type="hidden" autocomplete="off" name="co_grade" value="<?php echo ($course["co_grade"]); ?>"/>
                        <input type="hidden" autocomplete="off" name="co_semester" value="<?php echo ($course["co_semester"]); ?>"/>
                        <input type="hidden" autocomplete="off" name="co_subject" value="<?php echo ($course["co_subject"]); ?>"/>
                        <input type="hidden" autocomplete="off" name="co_version" value="<?php echo ($course["co_version"]); ?>"/>
                    </div>
                    <!--termId标签ID-->
                    <input type="hidden" autocomplete="off" name="termId"/>
                    <!--记录是自定义课程还是标准课时-->
                    <input type="hidden" autocomplete="off" name="flag" />
                    <!--记录是保存休息会儿还是直接备课-->
                    <input type="hidden" autocomplete="off" name="co_flag" />
                    <!--记录班级教师授课id-->
                    <input type="hidden" autocomplete="off" name="cst_id" />
                    <!--记录课程id-->
                    <input type="hidden" autocomplete="off" name="co_id" value='<?php echo ($course["co_id"]); ?>'/>
                    <!--记录选中的标签id组成的字符-->
                    <input type="hidden" autocomplete="off" name="preStr" value='<?php echo ($preStr); ?>'/>
                    <!--记录封面的-->
                    <input type="hidden" autocomplete="off" name="co_cover_bak"  value='<?php echo ($course["co_cover"]); ?>'/>
                    <!--专业ID-->
                    <input type="hidden" autocomplete="off" name="ma_id" value='<?php echo ($course["ma_id"]); ?>'/>
                </form>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <!-- 标准标签添加窗口 -->
    <div class="tag_list" title="添加标签">
        <ul class="tag_tag">
            <li class="bztaglist twotype">
                <label>班级：</label>
                <div>
                <span class="hand on ownerType">自定义</span>
                    <?php if(is_array($data["cst"])): $i = 0; $__LIST__ = $data["cst"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="hand schoolType" rel="<?php echo ($vo["c_id"]); ?>" grade="<?php echo ($vo["c_grade"]); ?>" semester="<?php echo ($vo["cst_xq"]); ?>" type="<?php echo ($vo["c_type"]); ?>" subject="<?php echo ($vo["cst_course"]); ?>" cst_id="<?php echo ($vo["cst_id"]); ?>" ma_id="<?php echo ($vo["ma_id"]); ?>"><?php echo ($vo["c_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </li>
            <li class="bztaglist coType">
                <label>学段：</label>
                <div>
                    <?php if(is_array($data["school"])): $i = 0; $__LIST__ = $data["school"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="hand <?php if(($course["co_type"]) == $vo): ?>on<?php endif; ?>" rel="<?php echo ($vo); ?>"><?php echo (gettypenamebyid($vo,'SCHOOL_TYPE')); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </li>
            <li class="bztaglist coMajor"></li>
            <li class="bztaglist coGrade"></li>
            <li class="bztaglist coSemester">
                <label>学期：</label>
                <div>
                    <span class="hand <?php if(($course["co_semester"]) == "1"): ?>on<?php endif; ?>" rel="1">上学期</span>
                    <span class="hand <?php if(($course["co_semester"]) == "2"): ?>on<?php endif; ?>" rel="2">下学期</span>
                </div>
            </li>
            <li class="bztaglist coSubject">
                <label>学科：</label>
                <div>
                    <?php if(is_array($data["subject"])): $i = 0; $__LIST__ = $data["subject"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="hand <?php if(($course["co_subject"]) == $vo): ?>on<?php endif; ?>" rel="<?php echo ($vo); ?>" ><?php echo (gettypenamebyid($vo,'COURSE_TYPE')); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </li>
            <li class="bztaglist coVersion">
                <label>版本：</label>
                <div>
                    <span class="hand <?php if(($course["co_version"]) == "1"): ?>on<?php endif; ?>" rel="1">北师大版</span>
                    <span class="hand <?php if(($course["co_version"]) == "3"): ?>on<?php endif; ?>" rel="3">苏教版</span>
                    <span class="hand <?php if(($course["co_version"]) == "5"): ?>on<?php endif; ?>" rel="5">人教版</span>
                    <span class="hand <?php if(($course["co_version"]) == "7"): ?>on<?php endif; ?>" rel="7">北京版</span>
                    <span class="hand <?php if(($course["co_version"]) == "16"): ?>on<?php endif; ?>" rel="16">人教B版</span>
                </div>
            </li>
        </ul>
    </div>

    <!--自定义课程 添加标签窗口 -->
    <div class="tagcustom" title="我的标签">
        <ul>
           <li class="taglist">
                <label class="la fl">已添加</label>
                <div class="list_append">
                    <?php if(is_array($term)): $i = 0; $__LIST__ = $term;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span rel="<?php echo ($vo["te_id"]); ?>" class="hand"><?php echo ($vo["te_title"]); ?><a class="flex_close cDel"></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </li>
            <li class="taglist">
                <label class="la fl">添加标签：</label>
                <input type="text" value="" class="tag_input"/>
                <a class="tag_append">添加</a>
            </li>
            <li class="taglist">
                <label class="la fl">推荐：</label>
                <label class="fr list_lab">换一批</label>
            </li>
        </ul>
        <ul class="append_list list_hand">
            <?php if(is_array($data["tag"])): $i = 0; $__LIST__ = $data["tag"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><li class="taglist">
                <label class="la fl">&nbsp;</label>
                <div class="tag_room">
                        <?php if(is_array($tag)): $i = 0; $__LIST__ = $tag;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="hand" rel="<?php echo ($vo["te_id"]); ?>"><?php echo ($vo["te_title"]); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                <div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
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