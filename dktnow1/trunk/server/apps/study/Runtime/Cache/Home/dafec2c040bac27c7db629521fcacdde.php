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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/homework.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Home/jquery-ui.css" />
<script type="text/javascript" src="/Public/Js/Home/jquery-ui.js"></script>
<script type="text/javascript">
<!--
    $(function() {

        // 附件隐藏
        $('.download').hide();

        // 评分 拖动
        var handle = $(".draghandle");
        var dragbar = $(".dragbar");
        dragbar.css("width","310px");
        handle.css({"width":"10px","top":'0',"left":'0'});
        var maxlen = parseInt(dragbar.width()) - parseInt(handle.outerWidth());
        handle.bind("mousedown",function(e){

            // 记录鼠标指针初始位置
            var x = e.pageX;
            // 获取拖动条相对于父元素的左偏移
            var hx = parseInt(handle.position().left);

            $(document).bind("mousemove",function(e){
                // 设置拖动左偏移
                var left = e.pageX - x + hx < 0 ? 0 : (e.pageX - x + hx >= maxlen ? maxlen : e.pageX - x + hx );
                handle.css({left:left,top : '0'});
                // 显示得分
                percent = (left / maxlen * 100).toFixed(0);
                $('.get_score i').html(percent);
                // 已评分 背景变色
                $('.bar_cover').width(left);
                return false;
            });

            // 释放鼠标
            $(document).bind("mouseup",function(){
                $(this).unbind("mousemove");
            })
        })

        // 题目的收起与展开
        $('.top a').click(function(){

            var _arrow =  $(this);
            if(_arrow.attr('class') == "retract"){
                _arrow.removeClass('retract').addClass('deploy');
            }else {
                _arrow.removeClass('deploy').addClass('retract');
            }

            $(this).parent().siblings().slideToggle();
        })

        // 翻页查看
        $('.slideleft,.slideright').hover(
            function () {
                var $this = $(this);
                var $slidelem = $this.prev();
                $slidelem.stop().animate({'width':'150px'},300);
                $slidelem.find('span').stop(true,true).fadeIn();
            },
            function () {
                var $this = $(this);
                var $slidelem = $this.prev();
                $slidelem.stop().animate({'width':'40px'},200);
                $slidelem.find('span').stop(true,true).fadeOut();
            }
        );

        // 选择班级 弹出窗口
        $("#chooseClass").dialog({
            draggable: true,
            resizable: true,
            autoOpen: false,
            position :'center',
            modal: true,
            width: '525',
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
                    var hitClass = $('#chooseClass li.on').text();
                    $('.classhomework_top li').text(hitClass);

                    // 获取当前活动ID
                    var ap_id = $('input[name=ap_id]').val();

                    // 获取班级或者群组里的学生
                    listStudent(ap_id);

                    $(this).dialog('close');
                },
                取消: function() {
                    $(this).dialog('close');
                }
            }
        });

        // 选择班级
        $('.choose_class').on('click',function(){

            $("#chooseClass").dialog("open");
        })

        // 选中班级学生
        $(document).on('click', '.dsp_cs li', function(){

            if ($(this).attr('attr') == 'undefined') {
                showMessage('此学生未提交作业', 1);
            } else {
                $(this).addClass('on').siblings().removeClass('on');
                $(this).find('.photo img').addClass('on').parent().parent().siblings().find('.photo img').removeClass('on');

                // 切换学生姓名
                $('.info .name').html($(this).find('.name').html());

                // 切换学生头像
                $('.main_user').find('img').attr('src', $('.dsp_cs img.on').attr('src').replace(/\d+/g,'96'));

                changeStudent($('.dsp_cs li.on').attr('attr'));
            }
        })

        // 查看下一个学生
        $('#downBtn').click(function(){

            if ($(".dsp_cs li.on").next().size() > 0) {

                if ($('.dsp_cs li.on').next().attr('attr') == 'undefined') {
                    showMessage('此学生未提交作业', 1);
                } else {
                    changeStudent($('.dsp_cs li.on').next().attr('attr'));
                }

            } else {
                showMessage('已经是最后一个了', 1);
            }
        })

        // 查看上一个学生
        $('#upBtn').click(function(){
            if ($(".dsp_cs li.on").prev().size() > 0) {

                if ($('.dsp_cs li.on').prev().attr('attr') == 'undefined') {
                    showMessage('此学生未提交作业', 1);
                } else {
                    changeStudent($('.dsp_cs li.on').prev().attr('attr'));
                }
            } else {
                showMessage('已经是第一个了', 1);
            }
        })

        $('#chooseClass li[rel='+$('input[name=field]').val()+']').each(function(){

            if ($(this).attr('attr') == $('input[name=value]').val()) {

                $(this).addClass('on').siblings().removeClass('on');

                $('.classhomework_top li').html($('#chooseClass li.on').text());

            }

        });

        // 选中班级
        $('#chooseClass li').click(function(){

            $(this).addClass('on').siblings().removeClass('on');

            $('.classhomework_top li').html($('#chooseClass li.on').text());

        });

        // 提交
        $('.coBtn button').click(function() {

            // 修改学生的作业状态
            var status = $(this).attr('attr');

            $('.dsp_cs li.on').attr('status', status);

            // 作业题目统计
            var stat = $('input[name=ad_stat]').val();

            // 给简答题判断正误
            if ($('.shortAnw').length > 0) {
                var shortAnw = {};
                var n = 0;

                $('.shortAnw').each(function(){
                    var anwC = $(this).prop('checked');
                    if (anwC == true) {
                       n++;
                       var attr = $(this).parent().prev().attr('attr');
                       var rel = $(this).val();
                       shortAnw[attr] = rel;
                    }

                });

                shortAnw = JSON.stringify(shortAnw);

                if ($('.shortAnw').length/2 != n) {
                    showMessage('请给简答题判断正误！');
                    return false;
                }
            }

            $.post('__URL__/setStatus', 'short_answer='+shortAnw+'&ad_stat='+stat+'&ajax=1&ad_id='+$('.dsp_cs li.on').attr('attr')+'&ad_persent='+parseInt($('.bar i').html())+'&ad_remark='+$("textarea[name=ad_remark]").val()+'&ad_status='+status+'&a_id='+$('.dsp_cs li.on').attr('rel')+'&ad_score='+parseInt($('.get_score i').html()), function(json){

                if (json.status == 1) {
                    $('#downBtn').click();
                    $('.coBtn').hide();
                } else {
                    showMessage('请重新批阅');
                }

            }, 'json')

            if ($('.dsp_cs li.on').nextAll('li').length == 0) {
                showMessage('提交作业的学生您已全部批阅', 1);
            } else {
                showMessage('正在提交您的批语，系统会自动切换到下一个学生，请稍候', 1);
            }

        });

        // 页面加载的时候列举班级/群组学生列表

        // 获取当前活动ID
        var ap_id = $('input[name=ap_id]').val();

        // 获取班级或者群组里的学生
        listStudent(ap_id);

        // 单机未转码的资源，自动下载
        $(document).on('click', '.ListFiles', function () {
            var rel = $(this).attr('rel');
            if ($(this).attr('trans') == 0) {
                if (confirm('该附件未转码，是否下载该附件？')) {
                    location.href = "__APPURL__/Activity/download/?id=" + rel;
                }
            } else {
                $(this).attr({'target':'_blank', 'href':'__APPURL__/AuthResource/show/ar_id/' + rel});
            }
        })

    })

    // 获取班级或群组里的学生
    function listStudent(ap_id) {

        // 初始化数据
        $('.s_answer').find('label').eq(1).html('');
        $('.down').html('');
        $('.bar i').css('width', '0');
        $('.bar i').html('');


        if (!ap_id) {
            return false;
        }

        $.post('__URL__/listStudent', 'ap_id='+ap_id+'&'+$('#chooseClass ul li.on').attr('rel')+'='+$('#chooseClass ul li.on').attr('attr'), function(json){

            var str = '';

            if (json) {

                for (var i = 0; i < json.length; i ++) {

                    str += '<li attr="'+json[i]['ad_id']+'" rel="'+json[i]['a_id']+'" status="'+json[i]['ad_status']+'"><a class="photo"><img src="'+json[i]['a_avatar']+'"/></a><a href="#" class="name">'+json[i]['a_nickname']+'</a><span>';

                    if (json[i]['ad_status'] != undefined && json[i]['ad_status'] > 0) {
                        str += '已提交';
                    } else {
                        str += '未提交';
                    }

                    str += '</span></li>';
                }

            } else {
                str += '暂无学生';
            }

            $('.dsp_cs').html(str);

            // 令已提交作业的第一个学生被选中
            var i = 0;
            $(".dsp_cs li").each(function(){

                if ($(this).attr('status') != 'undefined' && $(this).attr('status') != 0) {
                    i ++;
                    $(this).addClass('on');
                    $(this).find('img').addClass('on');
                    return false;
                }

            });

            changeStudent($(".dsp_cs li.on").attr('attr'));

        }, 'json');
    }

    // 学生改变
    function changeStudent(ad_id) {

        // 清空正确题目个数
        $('.right').html('');

        // 分数宽度归0
        $('.draghandle').css('left', '0px');

        // 标记下一个学生
        $(".dsp_cs li").each(function() {
            if ($(this).attr('attr') == ad_id) {
                $(this).addClass('on').siblings().removeClass('on');
                $(this).find('.photo img').addClass('on').parent().parent().siblings().find('.photo img').removeClass('on');
            }
        })

        var currentName = $(".dsp_cs li.on").find('.name').html();

        // 切换学生姓名
        $('.info .name').html(currentName);

        var num = 0;

        $('.dsp_cs img').each(function() {
            if ($(this).hasClass('on')) {
                num ++;
            }
        });


        // 切换头像
        if (num != 0) {
            $('.main_user').find('img').attr('src', $('.dsp_cs img.on').attr('src').replace(/\d+/g,'96'));
        }

        var obj = $(".dsp_cs li.on");

        $('textarea[name=ad_remark]').val('');

        // 若已经批阅完毕 则不允许再批阅
        if ($('.dsp_cs li.on').attr('status') == 4 || $('.dsp_cs li.on').attr('status') == 2 || $('.dsp_cs li').length == 0) {

            $('.coBtn').css('display','none');
            $('.draghandle').css('display', 'none');
        } else {
            $('.coBtn').css('display', 'block');
            $('.draghandle').css('display', 'block');
        }

        var id = $('#chooseClass li.on').attr('attr');
        var field = $('#chooseClass li.on').attr('rel');

        $.post("__APPURL__/ActivityData/getActivityData", 'act_id='+<?php echo ($homework["act_id"]); ?>+'&field='+field+'&id='+id+'&ajax=1&a_id='+obj.attr('rel'), function(json) {
            if (json.status == 1) {

                // 显示得分
                var score = json.info.ad_score;

                // 获取学生上传的附件
                var homework = '';

                // 题目统计
                var stat = new Array();

                if (json.info.files != null) {
                    for (var ii in json.info.files) {
                        homework += '<a href="javascript:void(0);" rel="' + ii + '" rel="' + json.info.files[ii]['ar_id'] + '" trans="' + json.info.files[ii]['ar_is_transform'] + '" class="ListFiles">' + json.info.files[ii]['ar_title'] + '</a>';
                    }
                } else {
                    homework = '';
                }

                $('.down').html(homework);

                if ($('.down').html() != '') {
                    $('.download').show();
                }

                // 显示条宽度
                var width = Math.floor(score * parseInt($('.dragbar').css('width'))/100);

                $('.bar_cover').css('width', width);
                $('.get_score i').html(score);

                // 学生作业答案
                if (json.info.ad_answer == null || json.info.ad_answer == 'undefined') {
                    $('.top span').removeClass('answer_error');
                    $('.s_answer').each(function () {
                        $(this).find('label').eq(1).html('');
                    })
                    //$('.s_answer').next().find('label')html('');
                    return false;
                } else {
                    var data = json.info.ad_answer;
                }

                // 简答题答案
                var shortAnw = json.info.ad_shortanswer;
                var eid,typ,ans,eth,exact,tk;
                var pd = new Array('ok', 'err');
                var co = 0;
                var rightTopic = '';

                if (json.info.ad_remark) {
                    $('textarea[name=ad_remark]').val(json.info.ad_remark);
                }

                $(".s_answer").each(function(fj) {

                    var ans = '';


                    var flag = true;
                    eid = $(this).attr('attr');
                    typ = $(this).attr('rel');

                    // 题目统计
                    stat[eid] = 0;

                    if (typ == 1) {

                        if (!isNaN(parseInt(data[eid]))) {
                            ans = infor(data[eid]);
                        } else {
                            ans = '';
                        }
                    }

                    if (typ == 2) {
                        if (!isNaN(parseInt(data[eid]))) {
                            eth = data[eid].length;
                            for (var p = 0; p < (eth + 1)/2; p ++) {
                                ans += ',' + infor(data[eid][p*2]);
                            }
                            ans = ans.slice(1);
                        } else {
                            ans = '';
                        }
                    }
                    if (typ == 3) {
                        tk = $(this).prev().find('label').find('span');

                        for (var p = 0; p <data[eid].length; p ++) {

                            var strTmp = data[eid][p];

                            var inTmp = '';
                            if (strTmp != tk.eq(p).text()) {
                                flag = false;
                                if (data[eid][p]) {
                                    inTmp = '<span class="error">' + data[eid][p] + '</span>';
                                } else {
                                    inTmp = '<span class="error">[未提交]</span>';
                                }
                            } else {
                                inTmp = '<span class="exact">' + data[eid][p] + '</span>';
                            }
                            ans += '、' + inTmp;
                        }
                        if (flag) {
                            $(this).addClass('exact');
                            $(this).parent().prev().find('span').removeClass('answer_error');
                            co ++;
                        } else {
                            $(this).parent().prev().find('span').addClass('answer_error');

                        }

                        ans = ans.slice(1);
                    }

                    if (typ == 5 || typ ==3) {

                        //简答题 已选择的选中
                        var check = $('.shortAnw');

                        check.each(function(){

                            if ($(this).val() == json.info.ad_stat[eid] && $(this).parent().prev().attr('attr') == eid) {

                                $(this).prop('checked', true);


                                if ($(this).val() == 0) {
                                    $(this).parent().parent().parent().find('span').eq(0).addClass('answer_error');
                                } else {
                                    $(this).parent().parent().parent().find('span').eq(0).removeClass('answer_error');

                                    co++;

                                    rightTopic += (fj+1) + ' ';
                                }

                            }
                        });

                        if (typ == 5) {

                            tk = $(this).prev().find('span');

                            var strTmp = data[eid];
                            if (strTmp != tk.text()) {
                                $(this).parent().prev().find('span').addClass('answer_error');
                                if (data[eid]) {
                                    inTmp = '<span class="error">' + data[eid] + '</span>';
                                } else {
                                    inTmp = '<span class="error">[未提交]</span>';
                                }
                            } else {
                                $(this).parent().prev().find('span').removeClass('answer_error');
                                inTmp = '<span class="exact">' + data[eid] + '</span>';
                            }

                            ans += ',' + inTmp;
                            ans = ans.slice(1);
                        }

                    }

                    if (typ == 4) {
                        ans = '<img width="25" height="25" border="0" src="__APPURL__/Public/Images/Home/'+pd[data[eid]]+'.png">';
                    }

                    if (typ != 3 && typ != 5) {
                        exact = $(this).parent().prev().find('.exact').val();

                        if (data[eid] == exact) {
                            co ++;
                            $(this).parent().prev().find('span').removeClass('answer_error');

                            rightTopic += (fj+1) + ' ';



                            stat[eid] += 1;

                        } else {
                            $(this).parent().prev().find('span').addClass('answer_error');
                        }
                    }

                    $(".right").html(rightTopic);
                    $(this).find('label').eq(1).html(ans);

                })

                var animateWidth = Math.ceil(co / $(".s_answer").size() * 100)+'%';
                $('.bar i').animate({width: animateWidth}, "slow");
                $('.bar i').html(animateWidth);

            } else {
                $(".s_answer").html('');
                $(".s_answer").parent().prev().find('span').removeClass('answer_error');
                $('.bar i').animate({width: '0%'}, "slow");
                $('.bar i').html('0%');
            }

            // 作业题目答对人数统计
            var tongji = '';
            for (var i in stat) {
                tongji += ',"'+i+'":'+stat[i];
            }

            tongji = '{'+tongji.slice(1)+'}';
            $('input[name=ad_stat]').val(tongji);

        }, 'json')

    }

    function infor(i){
        var arr = new Array();
        arr[0] = 'A';
        arr[1] = 'B';
        arr[2] = 'C';
        arr[3] = 'D';
        arr[4] = 'E';
        arr[5] = 'F';
        return arr[i % 6];
    }
//-->
</script>

<div class="warp">
<input type="hidden" name="ap_id" value="<?php echo ($homework["ap_id"]); ?>" />
<input type="hidden" name="ad_stat" value='' />
<input type="hidden" name="field" value='<?php echo ($homework["field"]); ?>' />
<input type="hidden" name="value" value='<?php echo ($homework[$homework[field]]); ?>' />

    <div id="left_sider">
        <div class="main_user">
            <a href="javascript:void(0);"><img src="/apps/Uploads/AuthAvatar/96/default.jpg"/></a>
            <div class="info">
                <a href="javascript:void(0);" class="name fl"></a>
                <a href="javascript:void(0);" class="university fl"><?php echo ($school); ?></a>
            </div>
        </div>
        <div class="class_students">
            <p class="title">
                <img src="__APPURL__/Public/Images/Home/class_students.png"/>
                <span>班级学生</span>
            </p>
            <ul class="dsp_cs">

            </ul>
        </div>
    </div>

    <ul class="classhomework_top fl">
        <a class="back fr" href="javascript:history.go(-1);">返回</a>
        <a class="choose_class fr">选择班级</a>
        <li></li>
    </ul>
    <div class="classhw_box">
        <div class="hw_score">
            <div>
                <label>正确率：</label>
                <span class="bar"><i></i></span>
            </div>
            <div>
                <label>正确题目：</label>
                <span class="right"></span>
            </div>
            <div class="download">
                <label>附件：</label>
                <span class="down">

                </span>
            </div>
            <div class="button_wrap">
                <a class="upShow" id="upShow"><span>查看上一个学生</span></a>
                <a class="upBtn slideleft" id="upBtn"><span><img src="__APPURL__/Public/Images/Home/l.png" width='15' height='20'></span></a>

                <a class="downShow" id="downShow"><span>查看下一个学生</span></a>
                <a class="downBtn slideright" id="downBtn"><span><img src="__APPURL__/Public/Images/Home/r.png" width='15' height='20'></span></a>
            </div>
        </div>

        <?php $showKey = 0; ?>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; if(($list["to_type"]) == "1"): ?><div class="topicObj fl">
                    <p class="top">
                        <span><?php echo ($showKey+1); ?>.单选题</span><input type="hidden" class='exact' value="<?php echo ($list["to_answer"]); ?>">
                        <a class="retract"></a>
                    </p>
                    <div>
                        <p class="content"><?php echo ($list["to_title"]); ?></p>
                        <p class="answer">
                            <label>答案：</label>
                            <?php $showOption = 1; ?>
                            <?php if(is_array($list["to_option"])): $i = 0; $__LIST__ = $list["to_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><input type="radio" name="single to_answer<?php echo ($list["to_id"]); ?>" value="0" <?php if((intval($list["to_answer"])) > "-1"): if(($showOption-1) == $list["to_answer"]): ?>checked="checked"<?php endif; endif; ?>><label class="option" style="cursor: pointer;"><?php echo ($tit[$showOption-1]); ?></label>

                                <?php $showOption ++; endforeach; endif; else: echo "" ;endif; ?>
                        </p>
                        <p class="s_answer" attr="<?php echo ($list["to_id"]); ?>" rel="1">
                            <label>学生答案：</label>
                            <label></label>
                        </p>
                    </div>
                </div><?php endif; ?>
            <?php if(($list["to_type"]) == "2"): ?><div class="topicObj fl">
                    <p class="top">
                        <span><?php echo ($showKey+1); ?>.多选题</span><input type="hidden" class='exact' value="<?php echo (implode($list["to_answer"],',')); ?>">
                        <a class="retract"></a>
                    </p>
                    <div>
                        <p class="content"><?php echo ($list["to_title"]); ?></p>
                        <p class="answer">
                            <label>答案：</label>
                            <?php $showOption = 1; ?>
                            <?php if(is_array($list["to_option"])): $i = 0; $__LIST__ = $list["to_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($i % 2 );++$i;?><input type="checkbox" name="multiple to_answer" value="0" rel="<?php echo ($showOption-1); ?>" <?php if(($list["to_answer"]) != ""): if(in_array(($showOption-1), is_array($list["to_answer"])?$list["to_answer"]:explode(',',$list["to_answer"]))): ?>checked="checked"<?php endif; endif; ?>><label class="option" style="cursor: pointer;"><?php echo ($tit[$showOption-1]); ?></label>
                                <?php $showOption ++; endforeach; endif; else: echo "" ;endif; ?>
                        </p>
                        <p class="s_answer" attr="<?php echo ($list["to_id"]); ?>" rel="2">
                            <label>学生答案：</label>
                            <label></label>
                        </p>
                    </div>
                </div><?php endif; ?>
            <?php if(($list["to_type"]) == "3"): ?><div class="topicObj fl">
                    <p class="top">
                        <span><?php echo ($showKey+1); ?>.填空题</span>
                        <a class="retract"></a>
                    </p>
                    <div>
                        <p class="content"><?php echo ($list["to_title"]); ?></p>
                        <p class="answer">
                            <label>答案：</label>
                            <label>
                                <?php $showOption = 1; ?>
                                 <?php if(is_array($list["to_option"])): $kkk = 0; $__LIST__ = $list["to_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$option): $mod = ($kkk % 2 );++$kkk;?><span style="padding-right:10px"><?php echo ($list['to_answer'][$kkk-1]); ?></span>
                                    <?php $showOption ++; endforeach; endif; else: echo "" ;endif; ?>
                             </label>
                        </p>
                        <p class="s_answer" attr="<?php echo ($list["to_id"]); ?>" rel="3">
                            <label>学生答案：</label>
                            <label></label>
                        </p>
                        <p class="answer">
                            <label>批改</label>
                            <input type="radio" class="shortAnw" name="shortAnw<?php echo ($list["to_id"]); ?>" value="1"><label class="option" style="cursor: pointer;"><img src="__APPURL__/Public/Images/Home/ok.png" width="20" height="20" border="0"></label>
                            <input type="radio" class="shortAnw" name="shortAnw<?php echo ($list["to_id"]); ?>" value="0"><label class="option" style="cursor: pointer;"><img src="__APPURL__/Public/Images/Home/err.png" width="20" height="20" border="0"></label>
                        </p>
                    </div>
                </div><?php endif; ?>
            <?php if(($list["to_type"]) == "4"): ?><div class="topicObj fl">
                    <p class="top">
                        <span><?php echo ($showKey+1); ?>.判断题</span><input type="hidden" class='exact' value="<?php echo ($list["to_answer"]); ?>">
                        <a class="retract"></a>
                    </p>
                    <div>
                        <p class="content"><?php echo ($list["to_title"]); ?></p>
                        <p class="answer">
                            <label class="la">答案：</label>
                            <input type="radio" name="judge to_answer<?php echo ($list["to_id"]); ?>"  <?php if(($list["to_answer"]) == "0"): ?>checked="checked"<?php endif; ?>><label class="option" style="cursor: pointer;" attr="0"><img src="__APPURL__/Public/Images/Home/ok.png" width="20" height="20" border="0"></label>
                            <input type="radio" name="judge to_answer<?php echo ($list["to_id"]); ?>"  <?php if(($list["to_answer"]) == "1"): ?>checked="checked"<?php endif; ?>><label class="option" style="cursor: pointer;" attr="1"><img src="__APPURL__/Public/Images/Home/err.png" width="20" height="20" border="0"></label>
                        </p>
                        <p class="s_answer" attr="<?php echo ($list["to_id"]); ?>" rel="4">
                            <label>学生答案：</label>
                            <label></label>
                        </p>
                    </div>
                </div><?php endif; ?>

            <?php if(($list["to_type"]) == "5"): ?><div class="topicObj fl">
                    <p class="top">
                        <span><?php echo ($showKey+1); ?>.简答题</span>
                        <a class="retract"></a>
                    </p>
                    <div>
                        <p class="content"><?php echo ($list["to_title"]); ?></p>
                        <p class="answer">
                            <label class="la">答案：</label>
                            <span><?php echo ($list["to_answer"]); ?></span>

                        </p>
                        <p class="s_answer" attr="<?php echo ($list["to_id"]); ?>" rel="5">
                            <label>学生答案：</label>
                            <label></label>
                        </p>

                        <p class="answer">
                            <label>批改</label>
                            <input type="radio" class="shortAnw" name="shortAnw<?php echo ($list["to_id"]); ?>" value="1" ><label class="option" style="cursor: pointer;"><img src="__APPURL__/Public/Images/Home/ok.png" width="20" height="20" border="0"></label>
                            <input type="radio" class="shortAnw" name="shortAnw<?php echo ($list["to_id"]); ?>" value="0"><label class="option" style="cursor: pointer;"><img src="__APPURL__/Public/Images/Home/err.png" width="20" height="20" border="0"></label>
                        </p>
                    </div>
                    <?php if(is_array($list["picture_answer"])): $i = 0; $__LIST__ = $list["picture_answer"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pic): $mod = ($i % 2 );++$i;?><img src="<?php echo ($pic); ?>" /><?php endforeach; endif; else: echo "" ;endif; ?>
                </div><?php endif; ?>


            <?php $showKey ++; endforeach; endif; else: echo "" ;endif; ?>

        <div class="remarks fl">
            <div class="give_score">
                <label>作业得分：</label>
                <div class="dragbar">
                    <div class="draghandle"></div>
                    <span class="bar_cover"></span>
                </div>
                <span class="get_score"><i>0</i>分<span>
            </div>
            <label>批语：</label>
            <textarea name="ad_remark"></textarea>

        </div>
        <div class="coBtn" style="display:none">
            <button class="confirm" type="submit" attr="4">批阅完成</button>
            <button class="cancel" type="submit" attr="2">发回重做</button>
        </div>
    </div>
<div class="clear"></div>
</div>
<div id="chooseClass" title="选择班级">
    <ul>
        <?php if(is_array($class)): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i;?><li attr="<?php echo ($class["c_id"]); ?>" rel="c_id"><?php echo ($class["c_name"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
        <?php if(is_array($crowd)): $i = 0; $__LIST__ = $crowd;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crowd): $mod = ($i % 2 );++$i;?><li attr="<?php echo ($crowd["cro_id"]); ?>" rel="cro_id"><?php echo ($crowd["cro_title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
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