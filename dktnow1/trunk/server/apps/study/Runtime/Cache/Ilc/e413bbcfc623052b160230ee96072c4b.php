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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/user.css" /><script type="text/javascript" src="__PUBLIC__/Js/Public/public.js"></script>
<script type="text/javascript" src="/Public/Js/Public/Ymd/WdatePicker.js"></script><script type="text/javascript" src=" /Public/Js/Public/provincesCity.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesdata.js"></script>
<script type="text/javascript">
$(function(){

    // 籍贯
    setProvince("province", "a_region", <?php echo ($vo["a_region2"]); ?>);

    // 根据学制选择年级
    $(document).on('change', 'select[name=a_school_type]', function() {
        var choose_type = $(this).val()
        if (choose_type == 4) {
            $('.major').show();
            listGradeOption(choose_type, 'a_major', 0);
            $("select[name=a_grade]").html('<select name="a_grade"><option value="0">请选择</option></select>');
        } else {
            $('.major').hide();
            listGradeOption(choose_type, 'a_grade', 0);
        }
    })

    // 专业
    $(document).on('change', 'select[name=a_major]', function() {
        var choose_type = $("select[name=a_major] option:selected").attr('value');
        if (choose_type != 0) {
            listGradeOption(choose_type, 'a_grade', 0, 'major');
        }
    });

    // 根据年级选择班级
    $(document).on('change', 'select[name=a_grade]', function() {
        if ($(this).val() == 0) {
            $('select[name=c_id]').html('<option value="0">请选择</option>');
            return;
        }
        var type = $('select[name=a_school_type]').val();
        var obj = $('select[name=a_major]');
        var ma_id = 0;
        if (obj.css('display') != 'none') {
            ma_id = obj.val();
        }
        listClass(type, $(this).val(), 'c_id', 0, ma_id);
    })

    $('input[name=modify]').click(function(){
        $('.reChoose').show();
    });

    // 修改班级名称
    $(document).on('click','.modify_cname',function(){

        var curCid = $(this).parent().attr('attr');
        var obj = $(this).parent();

        var chooseClass = "<p class='mselect'><select name='a_school_type'><option value='0'>选择学制</option></select><select class='major' name='a_major' style='display:none;'><option value='0'>选择专业</option></select><select name='a_grade'><option value='0'>选择年级</option></select><select name='c_id'><option value='0'>选择班级</option></select></p>";
        var btn = "<p class='mbtn'><span class='confirm'>确定</span><span class='cancel'>取消</span></p>";



        getSchoolType(obj);

        $('.cname').each(function() {
            $(this).removeClass('modify_cname');
        })

        $(this).prev().hide();
        $(this).next().hide();

        $(this).after(btn);
        $(this).after(chooseClass);

        $(this).hide();

    })

    // 取消班级修改
    $(document).on('click','.cancel',function(){

        $(this).parent().hide();
        $(this).parent().prev().hide();
        $(this).parent().prev().prev().show();
        $(this).parent().prev().prev().prev().show();
        $('.cname').addClass('modify_cname');

    })

    // 点击确定修改所属班级
    $(document).on('click', '.confirm', function(){

        var obj = $(this).parent().parent();
        var curCid = obj.attr('attr');
        var selectCid = $('select[name=c_id]').val();

        if (curCid == selectCid) {
            $('.classObj').children().show();
            $('.classObj').find('.mselect').hide();
            $('.classObj').find('.mbtn').hide();
            $('.classObj').find('.cname').addClass('modify_cname');

        } else {


                if (selectCid != 0) {

                    $.post('__URL__/modifyStudentToClass', 'curCid='+curCid+'&c_id='+selectCid+'&a_id='+<?php echo ($vo["a_id"]); ?>, function(json){
                        if (json.status) {

                                $(obj).html('<span>'+json.info+'</span><span class="cname modify_cname">修改</span>');
                                $(obj).attr('attr', selectCid);

                                if (curCid == undefined) {

                                    $(obj).append('<div><p class="classObj"><span style="border:none"></span><span class="cname modify_cname" >添加</span></p></div>');
                                }

                        } else {
                            showMessage(json.info);
                        }
                    }, 'json');
                }
        }

    });

    // 前端验证  姓名
    $('input[name=a_nickname]').blur(function() {
        var oVal = $(this).val();
        var ntwVal = /((^[\u4E00-\u9FA5]{2,6}$)|(^[a-zA-Z]+[\s\.]?([a-zA-Z]+[\s\.]?){0,4}[a-zA-Z]$))/g;
        if (oVal.length > 0) {
            if (ntwVal.test(oVal) == true) {
                $(this).next().text('');
                $(this).next().append("<image src='__APPURL__/Public/Images/Home/true.png'/>");
            } else {
                $(this).addClass('new_red');
                $(this).next().text('不能包含数字，请重新输入').css('color','red');
                return false;
            }
        } else {
             $(this).next().text('');
             return false;
        }
    })
    $('input[name=a_nickname]').focus(function() {
        $(this).removeClass('new_red');
        $(this).next().text('姓名至少2位，最多6位').css('color','gray');
    })

    //前端验证 生日
    $('input[name=a_birthday]').blur(function() {
        var myDate = new Date();
        myDate.getFullYear();    //获取完整的年份(4位,1970-????)
        myDate.getMonth();       //获取当前月份(0-11,0代表1月)
        myDate.getDate();       //获取当前日(1-31)
        var nowDate = (myDate.getFullYear()) + '-' + '0' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
        //nowDate = myDate.toLocaleDateString()
        var oldVal = $(this).val();
        var newVal = oldVal.replace(/[-]/g,'');
        var newDate = nowDate.replace(/[-]/g,'');
        var ntwVal = oldVal.replace(/[-]/g,'');
        if (newVal == '') {
            $(this).next().text('');
            $(this).removeClass('new_red');
            $(this).children().remove();
            return false;
        }
        if (newVal > newDate) {
            $(this).addClass('new_red');
            $(this).next().text('请输入正确的生日').css('color','red');
        } else {
            $(this).removeClass('new_red');
            $(this).next().text('');
        }
    })
    $('input[name=a_birthday]').focus(function(){
        //$(this).next().text('未满7周岁，不可输入').css('color','gray');
        $(this).removeClass('new_red');
        $(this).next().text('')
    })

    //前端验证 手机号码
    $('input[name=a_tel]').blur(function() {
        var tVal = $(this).val();
        var tPar = /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/;
        var newVal = tVal.replace(/[ ]/g,'');
        if (newVal == '') {
            $(this).next().text('');
            $(this).next().children().remove();
        } else {
            if (tPar.test(tVal)) {
                $(this).next().text('');
                $(this).next().append("<image src='__APPURL__/Public/Images/Home/true.png'/>");
            } else {
                $(this).addClass('new_red')
                $(this).next().text('你输入的手机格式不正确，请重新输入').css('color','red');
                return false;
            }
        }
    })
    $('input[name=a_tel]').focus(function() {
        $(this).removeClass('new_red');
        $(this).next().text('请输入正确的手机号码').css('color','gray')
    })

    //前端验证 简介
    $('.addTeacher textarea').blur(function() {
        var tVal = $(this).val();
        var newVal = tVal.replace(/[ ]/g,'').length;
        if (newVal > 300) {
            showMessage('内容不能超过300字符');
            $(this).focus();
            return false;
        }
    })
})

function getSchoolType(obj) {

    // 获取当前学校的学制
    $.post('__URL__/getSchoolType', '', function(json){
        if (json) {
            str = '';
            for (var i = 0; i< json.length; i++) {
                str += '<option value="'+json[i]['id']+'">'+json[i]['name']+'</option>';
            }

            $(obj).find('select[name=a_school_type] option').after(str);

        }
    }, 'json');

}

// 验证
function check() {

    var tTrue = true;
    var oNum = true;

    //姓名验证
    var tVal = $('input[name=a_nickname]').val();
    var ntwVal = /((^[\u4E00-\u9FA5]{2,6}$)|(^[a-zA-Z]+[\s\.]?([a-zA-Z]+[\s\.]?){0,4}[a-zA-Z]$))/g;
    if (tVal.length > 0) {
        if (ntwVal.test(tVal) == true) {
            $('input[name=a_nickname]').next().text('');
            $('input[name=a_nickname]').next().append("<image src='__APPURL__/Public/Images/Home/true.png'/>");
        } else {
            $('input[name=a_nickname]').addClass('new_red');
            $('input[name=a_nickname]').next().text('你输入的姓名格式不正确，请重新输入姓名').css('color','red');
            oNum = false;
            tTrue = false;
        }
    } else {
        $('input[name=a_nickname]').addClass('new_red');
        $('input[name=a_nickname]').next().text('请输入姓名').css('color','red');
        oNum = false;
        tTrue = false;
    }

    //生日验证
    var oldVal = $('input[name=a_birthday]').val();
    var myDate = new Date();
    myDate.getFullYear();    //获取完整的年份(4位,1970-????)
    myDate.getMonth();       //获取当前月份(0-11,0代表1月)
    myDate.getDate();       //获取当前日(1-31)
    var nowDate = (myDate.getFullYear()) + '-' + '0' + (myDate.getMonth() + 1) + '-' + myDate.getDate();
    var newVal = oldVal.replace(/[-]/g,'');
    var newDate = nowDate.replace(/[-]/g,'');
    var ntwVal = oldVal.replace(/[-]/g,'');
    if (newVal > newDate) {
        $('input[name=a_birthday]').addClass('new_red');
        $('input[name=a_birthday]').next().text('请输入正确的生日').css('color','red');
        tTrue = false;
    }

    //手机验证
    var pVal = $('input[name=a_tel]').val();
    var reVal = pVal.replace(/[ ]/g,'');
    var tPar = /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/;
    if (reVal == '') {
    } else {
        if (!tPar.test(pVal)) {
            $('input[name=a_tel]').addClass('new_red');
            $('input[name=a_tel]').next().text('你输入的手机格式不正确，请重新输入').css('color','red');
            tTrue = false;
        }
    }

    //简介验证
    var adVal = $('.addStudent textarea').val();
    if (adVal) {
        var nadLen = adVal.replace(/[ ]/g,'').length;
        if (nadLen > 300) {
            showMessage('简介内容不能超过300字符');
            $('.addStudent textarea').focus();
            tTrue = false;
        }
    }
    //判断
    if (tTrue == false) {
        if(!oNum == true){
            $("html, body").animate({ scrollTop: 50 }, 500);
        }
        return false;
    }

    return true;
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
    <div class="addBox">
        <div class="title"><i class="fl">学生添加</i><a href="__URL__/index" class="fr">返回列表</a></div>
        <form action="__URL__/update" method="post" onsubmit="return check();" enctype="multipart/form-data">
        <input type="hidden" name="a_type" value="1" />
            <ul class="addOption addStudent">
                <li>
                    <label>账号：</label>
                    <label class="lang_agao"><?php echo ($vo["a_account"]); ?></label>
                </li>
                <li>
                    <label>真实姓名：</label>
                    <input type="text" name="a_nickname" value="<?php echo ($vo["a_nickname"]); ?>" class="fl"/>
                    <p class="fl"></p>
                </li>
                <li class="oclass">
                    <label>所属班级：</label>

                    <div class="oclass_div">
                        <?php if(is_array($classInfo)): $i = 0; $__LIST__ = $classInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$class): $mod = ($i % 2 );++$i;?><p class="classObj" attr="<?php echo ($class["c_id"]); ?>">
                                <span><?php echo ($class["c_name"]); ?></span>
                                <span class="cname modify_cname">修改</span>
                                <!--span class="cname del_cname">删除</span-->
                            </p><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>

                    <strong class="clear"></strong>

                    <div class="oclass_div oclass_obj" style="padding-left:90px">
                        <p class="classObj">
                            <span style="border:none"></span>
                            <span class="cname modify_cname" >添加</span>
                        </p>
                    </div>

                </li>
                <li class="ssex">
                    <label>性别：</label>
                    <input class="boy" type="radio" name="a_sex" value="0" <?php if(($vo["a_sex"]) == "0"): ?>checked<?php endif; ?>><i>保密</i>
                    <input class="boy" type="radio" name="a_sex" value="1"  <?php if(($vo["a_sex"]) == "1"): ?>checked<?php endif; ?>><i>男</i>
                    <input class="girl" type="radio" name="a_sex" value="2"  <?php if(($vo["a_sex"]) == "2"): ?>checked<?php endif; ?>><i>女</i>
                </li>
                <li>
                    <label>生日：</label>
                    <input type="text" onclick="WdatePicker();" value="<?php echo (todate($vo["a_birthday"],'Y-m-d')); ?>" name="a_birthday" class="fl"/>
                    <p class="fl"></p>
                </li>
                <li class="tnative">
                    <label>籍贯：</label>
                    <span id="province"></span>
                    <input type="hidden" name="a_region" value="<?php echo ($vo["a_region"]); ?>"/>
                </li>
                <li>
                    <label>手机：</label>
                    <input type="text" name="a_tel" value="<?php echo ($vo["a_tel"]); ?>" class="fl"/>
                    <p class="fl"></p>
                </li>
                <li>
                    <label>头像：</label>
                    <img src="<?php echo ($vo["a_avatar"]); ?>" /><input type="file" name="a_avatar">
                </li>
                <li>
                    <label>简介：</label>
                    <textarea name="a_note" placeholder="内容不能超过300字符"><?php echo ($vo["a_note"]); ?></textarea>
                </li>
                <li>
                    <label>状态：</label>
                    <div>
                        <select name="a_status">
                            <option value="1" <?php if(($vo["a_status"]) == "1"): ?>selected<?php endif; ?>>启用</option>
                            <option value="9" <?php if(($vo["a_status"]) != "1"): ?>selected<?php endif; ?>>禁用</option>
                        </select>
                    </div>
                </li>
                <li class="coBtn">
                    <input type="hidden" name="a_id" value="<?php echo ($vo["a_id"]); ?>">
                    <button type="submit" value="" class="button save">保存</button>
                    <button type="reset" value="" class="button reset">清除</button>
                </li>
            </ul>
        </form>
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