<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>『<?php echo (C("web_name")); ?>管理平台』</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/header.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/public.css" />
    <script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src="/Public/Js/Public/jquery-ui.js"></script><script type="text/javascript" src="/Public/Js/Public/public.js"></script><script type="text/javascript" src="/Public/Js/Ilc/commonManage.js"></script>
    <script language="JavaScript">
    <!--
        //指定当前组模块URL地址
        var URL = '__URL__';
        var APP = '__GROUP__';
        var PUBLIC = '__PUBLIC__';
    //-->
    </script>
<script type="text/javascript" src="/Public/Js/Public/provincesCity.js"></script><script type="text/javascript" src="/Public/Js/Public/provincesdata.js"></script><script type="text/javascript" src="/Public/Js/Public/Ymd/WdatePicker.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/system_add.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/resource_index.css" />
<script type="text/javascript">
        <!--
        // 验证

    $("input[name=a_account]").blur(function(){
        $.get('__URL__/check/a_account/'+$("input[name=a_account]").val(),function(msg){
            if(msg != 'null' && $("input[name=a_account]").val() != ''){
                alert("帐号已存在");
                $("input[name=a_account]").focus();
            }
        })
    })

    function check() {

        if ($("input[name=a_account]").val() == '') {

            alert("帐号必须填写");
            $("input[name=a_account]").focus();
            return false;

            if($("input[name=a_account]").val().length>20){
                alert("帐号必须20个字符以内");
                $("input[name=a_account]").focus();
                return false;
            }
        }

        if ($("input[name=a_nickname]").val() == '') {
            alert("姓名必须填写");
            $("input[name=a_nickname]").focus();
            return false;
        }

        if ($("select[name=s_id]").val() == '') {
            alert("必须选择学校");
            $("select[name=s_id]").focus();
            return false;
        }

        if ($("select[name=a_year]").val() == '') {
            alert("必须选择入学年份");
            $("select[name=a_year]").focus();
            return false;
        }

        if($('#province select:eq(0)').val() == ''){
            alert("必须选择籍贯");
            $('#province select:eq(0)').focus();
            return false;
        }


        if($("input[name=a_tel]").val() != ''){

            mobile_phone = $("input[name=a_tel]").val();
            var reg = /^0?1[358]\d{9}$/;
            if (!reg.test(mobile_phone)){
                alert("请检查手机号");
                $("input[name=a_tel]").focus();
                return false;
            }
        }

    }

        $(function(){
            // 籍贯
            setProvince("province", "a_region", '');
        })
    //-->
    </script>
    <div class="title_hea">
        <i class="fl">教师添加</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/insert/" onsubmit="return check();"  enctype="multipart/form-data">
        <ul class="add_option">
            <li>
                <label>账号：</label>
                <input class="fl" type="text" name="a_account" value=""/>
            </li>
            <li>
                <label>姓名：</label>
                <input class="fl" type="text" name="a_nickname" value=""/>
            </li>
            <li>
                <label>所属学校：</label>
                <select name="s_id">
                    <option value="0">请选择</option>
                    <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><option value="<?php echo ($school["s_id"]); ?>"><?php echo ($school["s_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </li>
            <li>
                <label>入校时间：</label>
                <select name="a_year">
                    <option value="0">请选择</option>
                    <?php if(is_array($year)): $i = 0; $__LIST__ = $year;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$year): $mod = ($i % 2 );++$i;?><option value="<?php echo ($year); ?>"><?php echo ($year); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </li>
            <li class="course_name">
                <label><i></i>教授课程：</label>
                <div id="subjects">
                    <?php if(is_array($course)): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?><input type="checkbox" value="<?php echo ($key); ?>" name="Courses[]" class="radio_sex"><span class="fl"><?php echo ($co); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>

            </li>
            <li class="rad_sex examine_edit">
                <label><i></i>性别：</label>
                <input type="radio" name="a_sex" value="1" style="width:auto; margin-top:2px;"/>
                <span>男</span>
                <input type="radio" name="a_sex" value="2" style="width:auto; margin-top:2px;"/>
                <span>女</span>
            </li>
            <li>
                <label><i></i>生日：</label>
                <input class="fl" type="text"  onclick="WdatePicker();"  name="a_birthday" value=""/>
            </li>
            <li>
                <label><i></i>籍贯：</label>
                <span id="province"></span>
                <input type="hidden" name="a_region" value=""/>
            </li>
            <li>
                <label><i></i>手机：</label>
                <input class="fl" type="text" name="a_tel" value=""/>
            </li>
            <li>
                <label><i></i>头像：</label>
                <input type="file" name="a_avatar">
            </li>
            <li>
                <label>简介：</label>
                <textarea placeholder="内容不能超过300字符" name="a_note"></textarea>
            </li>
            <li>
                <label><i></i>状态：</label>
                <select name="a_status">
                    <option value="1">启用</option>
                    <option value="9">禁用</option>
                </select>
            </li>
            <li class="coBtn">
                <input type="hidden" value="2" name="a_type"/>
                <button class="save fin" value="" type="submit">添加</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>