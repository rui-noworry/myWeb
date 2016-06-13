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
    <div class="title_hea">
        <i class="fl">学生添加</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/insert/" onsubmit="return check();"  enctype="multipart/form-data">
        <ul class="add_option">
            <li>
                <label>账号：</label>
                <input class="fl" type="text" name="a_account" value=""/>
            </li>
            <li>
                <label>真实姓名：</label>
                <input class="fl" type="text" name="a_nickname" value=""/>
            </li>
            <li>
                <label>所属班级：</label>
                <select name="s_id">
                    <option attr="" value="0">请选择</option>
                    <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><option value="<?php echo ($school["s_id"]); ?>" attr="<?php echo ($school["s_type"]); ?>"><?php echo ($school["s_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="c_type">
                    <option value="0">请选择</option>
                </select>
                <select name="ma_id">
                    <option value="0">请选择</option>
                </select>
                <select name="c_grade">
                    <option  value="0" >请选择</option>
                </select>
                <select name="c_id">
                    <option value="0" >请选择</option>
                </select>
            </li>
            <li class="rad_sex examine_edit">
                <label><i></i>性别：</label>
                <input type="radio" name="a_sex" value="1" style="width:auto; margin-top:2px;"/>
                <span>男</span>
                <input type="radio" name="a_sex" value="2" style="width:auto; margin-top:2px;"/>
                <span>女</span>
            </li>
            <li class="course_name">
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
                <button class="save fin" value="" type="submit">添加</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>

 <script type="text/javascript">

    $(function(){

        // 籍贯
        setProvince("province", "a_region", '');

        // 隐藏专业
        $('select[name=ma_id]').hide();

        //验证
        $("input[name=a_account]").blur(function(){
            $.get('__URL__/check/a_account/'+$("input[name=a_account]").val(),function(msg){
                if(msg != 'null' && $("input[name=a_account]").val() != ''){
                    alert("帐号已存在");
                    $("input[name=a_account]").focus();
                }
            })
        })

        // 通过学校获取学段
        $('select[name=s_id]').change(function() {

            $('select[name=c_type]').html('<option value="0">请选择</option>');
            $('select[name=ma_id]').hide();
            $('select[name=c_grade]').html('<option value="0">请选择</option>');
            $('select[name=c_id]').html('<option value="0">请选择</option>');

            // 获取学段
            var sType = $('select[name=s_id] option:selected').attr('attr');
            var str = '<option value="0">请选择</option>';
            if (sType != '') {
                var typeArr = sType.split(',');

                for (var p in typeArr) {

                    str += '<option value="'+typeArr[p]+'">'+$('.schoolType span').eq(typeArr[p] - 1).html()+'</option>';
                }
            }
            $('select[name=c_type]').html(str);
        })

        // 通过学段获取年级
        $('select[name=c_type]').change(function() {
            if ($(this).val() == 4) {
                $('select[name=ma_id]').show();
                listGradeOption($('select[name=s_id]').val(), $(this).val(), 'ma_id');
            } else {
                $('select[name=ma_id]').hide();
                listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_grade');
            }
        })

        // 选择专业下的
        $(document).on('change', 'select[name=ma_id]', function () {
            listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_grade', 0, 'major');
        })

        // 通过年级获取班级
        $('select[name=c_grade]').change(function() {
            var ma_id = 0, obj = $('select[name=ma_id]');
            if (obj.css('display') != 'none') {
                ma_id = obj.val();
            }
            listClass($('select[name=s_id]').val(), $('select[name=c_type]').val(), $(this).val(), 'c_id', 0, ma_id);
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

        if ($("select[name=s_id]").val() == 0) {
            alert("必须选择学校");
            $("select[name=s_id]").focus();
            return false;
        }
        if ($("select[name=c_type]").val() == 0) {
            alert("必须选择学制");
            $("select[name=c_type]").focus();
            return false;
        }
        if ($('select[name=ma_id]').css('display') != 'none' && $('select[name=ma_id]').val() == 0) {
            alert("必须选择专业");
            $("select[name=ma_id]").focus();
            return false;
        }
        if ($("select[name=c_grade]").val() == 0) {
            alert("必须选择年级");
            $("select[name=c_grade]").focus();
            return false;
        }
        if ($("select[name=c_id]").val() == 0) {
            alert("必须选择班级");
            $("select[name=c_id]").focus();
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
    </script>
    <div class="schoolType" style="display:none;">
        <?php if(is_array($schoolType)): $i = 0; $__LIST__ = $schoolType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoolType): $mod = ($i % 2 );++$i;?><span><?php echo ($schoolType); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>