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
        <i class="fl">学生编辑</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/update/" onsubmit="return check();"  enctype="multipart/form-data">
        <ul class="add_option">
            <li>
                <label>账号：</label>
                <input class="fl" type="text" name="a_account" value="<?php echo ($vo["a_account"]); ?>"/>
            </li>
            <li>
                <label>真实姓名：</label>
                <input class="fl" type="text" name="a_nickname" value="<?php echo ($vo["a_nickname"]); ?>"/>
            </li>
            <li>
                <label>所属班级：</label>
                <div class="fl">
                <?php if(is_array($classInfo)): foreach($classInfo as $key=>$evClass): ?><p class='mselect' csId="<?php echo ($evClass["c_id"]); ?>">
                        <select name="s_id" attr="<?php echo ($evClass["s_id"]); ?>" csId="<?php echo ($evClass["c_id"]); ?>" disabled >
                            <option attr="" value="0">请选择</option>
                            <?php if(is_array($school)): foreach($school as $key=>$evSchool): ?><option value="<?php echo ($evSchool["s_id"]); ?>"  attr="<?php echo ($evSchool["s_type"]); ?>" <?php if(($evSchool["s_id"]) == $evClass["s_id"]): ?>selected<?php endif; ?> ><?php echo ($evSchool["s_name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <select name="c_type" csId="<?php echo ($evClass["c_id"]); ?>" >
                            <option value="0">请选择</option>
                            <?php if(is_array($evClass["scType"])): foreach($evClass["scType"] as $scVa=>$scType): ?><option value="<?php echo ($scVa); ?>" <?php if(($scVa) == $evClass["c_type"]): ?>selected<?php endif; ?> ><?php echo ($scType); ?></option><?php endforeach; endif; ?>
                        </select>
                        <select name="ma_id" csId="<?php echo ($evClass["c_id"]); ?>" >
                            <option value="0">请选择</option>
                            <?php if(is_array($evClass["major"])): $i = 0; $__LIST__ = $evClass["major"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vos["ma_id"]); ?>" <?php if(($vos["ma_id"]) == $evClass["ma_id"]): ?>selected<?php endif; ?> ><?php echo ($vos["ma_title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select name="c_grade" csId="<?php echo ($evClass["c_id"]); ?>" >
                            <option value="0">请选择</option>
                            <?php if(is_array($evClass["course"])): foreach($evClass["course"] as $covo=>$course): ?><option value="<?php echo ($covo); ?>" <?php if(($covo) == $evClass["c_grade"]): ?>selected<?php endif; ?> ><?php echo ($course); ?></option><?php endforeach; endif; ?>
                        </select>
                        <select name="c_id" csId="<?php echo ($evClass["c_id"]); ?>" >
                            <option value="0">请选择</option>
                            <?php if(is_array($evClass["class"])): foreach($evClass["class"] as $clkey=>$evclass): ?><option value="<?php echo ($clkey); ?>" <?php if(($evclass) == $evClass["c_title"]): ?>selected<?php endif; ?> >(<?php echo ($evclass); ?>)班</option><?php endforeach; endif; ?>
                        </select>
                     </p>
                    <div class="classObj">
                        <span class="classname"  csId="<?php echo ($evClass["c_id"]); ?>" ><?php echo ($evClass["className"]); ?></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        <span class="cname modify_cname">修改</span>
                        <span class="cname delete_cname" csId="<?php echo ($evClass["c_id"]); ?>">删除</span>
                    </div><?php endforeach; endif; ?>

                <p class='mselectAdd' csId="add">
                    <select name="s_id"  csId="add" >
                        <option attr="" value="0">请选择</option>
                        <?php if(is_array($school)): foreach($school as $key=>$evSchool): ?><option value="<?php echo ($evSchool["s_id"]); ?>"  attr="<?php echo ($evSchool["s_type"]); ?>" <?php if(($evSchool["s_id"]) == $vo["s_id"]): ?>selected<?php endif; ?> ><?php echo ($evSchool["s_name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                    <select name="c_type" csId="add" >
                        <option value="0">请选择</option>
                    </select>
                    <select name="ma_id" csId="add" >
                        <option value="0">请选择</option>
                    </select>
                    <select name="c_grade"  csId="add" >
                        <option  value="0" >请选择</option>
                    </select>
                    <select name="c_id"  csId="add" >
                        <option value="0" >请选择</option>
                    </select>
                </p>
                <div class="clear"></div>
                <span style="cursor:pointer; font-size:14px;" id="addClass"><font size="" color="#3967D5">添加</font></span>
                </div>
            </li>
            <li class="rad_sex examine_edit">
                <label><i></i>性别：</label>
                <input type="radio" name="a_sex" style="width:auto; margin-top:2px;" value="1" <?php if($vo["a_sex"] == 1): ?>checked<?php endif; ?>>
                <span>男</span>
                <input type="radio" name="a_sex" style="width:auto; margin-top:2px;" value="2" <?php if($vo["a_sex"] == 2): ?>checked<?php endif; ?>>
                <span>女</span>
            </li>
            <li class="course_name">
                <label><i></i>生日：</label>
                <input class="fl" type="text"  onclick="WdatePicker();"  name="a_birthday" value="<?php echo ($vo["a_birthday"]); ?>"/>

            </li>

            <li>
                <label><i></i>籍贯：</label>
                <span id="province"></span>
                <input type="hidden" name="a_region" value="<?php echo ($vo["a_region"]); ?>"/>
            </li>
            <li>
                <label><i></i>手机：</label>
                <input class="fl" type="text" name="a_tel" value="<?php echo ($vo["a_tel"]); ?>"/>
            </li>
            <li>
                <label>简介：</label>
                <textarea placeholder="内容不能超过300字符" name="a_note"><?php echo ($vo["a_note"]); ?></textarea>
            </li>
            <li>
                <label><i></i>状态：</label>
                <select name="a_status">
                    <option value="1" <?php if($vo["a_status"] == 1): ?>selected<?php endif; ?> >启用</option>
                    <option value="9" <?php if($vo["a_status"] == 9): ?>selected<?php endif; ?> >禁用</option>
                </select>
            </li>
            <li class="coBtn">
                <input type="hidden" value="<?php echo ($vo["a_id"]); ?>" name="a_id"/>
                <button class="save fin" value="" type="submit">保存</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>

<script type="text/javascript">
$(function(){

    // 隐藏专业
    $('select[name=ma_id]').hide();

    // 籍贯
    setProvince("province", "a_region", <?php echo ($vo["a_region2"]); ?>);

    $(".mselect").hide();
    $('.mselectAdd').hide();

    // 获取学段
    var sType = $('select[name=s_id] option:selected').attr('attr');
    var str = '<option value="0">请选择</option>';
    if (sType != '') {
        var typeArr = sType.split(',');

        for (var p in typeArr) {
            str += '<option value="'+typeArr[p]+'">'+$('.schoolType span').eq(typeArr[p] - 1).html()+'</option>';
        }
    }

    $('select[name=c_type][csId="add"]').html(str);

    // 修改班级名称
    $(document).on('click', '.modify_cname', function(){

        // 判断名称，来实现专业的展现
        if ($(this).prev().text().slice(0,1) == '大') {
            $('select[name=ma_id]').show();
        }

        $(this).parent().prev().show();

        //var chooseClass = $(".mselect").show();
        var btn = "<p><span class='confirm' id='confirmChange'>确定</span><span class='cancel' id='cancelChange'>取消</span></p>";

        $('.cname').each(function() {
            $(this).removeClass('modify_cname');
        })

        $(this).prev().hide();
        $(this).next().hide();
        $(this).after(btn);
        //$(this).after(chooseClass);
        $(this).hide();
        $('.delete_cname').hide();
    })

    // 取消班级修改
    $(document).on('click', '#cancelChange', function(){
        $(this).parent().parent().prev().hide();
        $(this).parent().hide();
        $(this).parent().prev().show();
        $(this).parent().prev().prev().show();
        $('.delete_cname').show();
        $('.cname:not(.delete_cname)').addClass('modify_cname');
        $('select[name=ma_id]').hide();
    })

    // 确定修改班级
    $(document).on('click', '#confirmChange', function(){
        var csid = $(this).parent().parent().prev().attr('csId'), obj = $('select[name=ma_id][csid='+csid+']');
        if($('select[name = c_id][csid = '+ csid +']').val() == 0){
            alert('请选择班级');
            return false ;
        }

        if (obj.css('display') != 'none' && obj.val() == 0) {
            alert('请选择专业');
            return false ;
        }

        // 获取数据
        var newCsid = $('select[csId='+csid+'][name=c_id]').val();console.log(csid+' '+newCsid);
        var studentId = $('input[name=a_id]').val();
        $(this).parent().hide();
        $(this).parent().parent().prev().hide();
        $(this).parent().prev().show();
        $(this).parent().prev().prev().show();
        $('.delete_cname').show();

        $.post('__URL__/modifyStudentToClass', 's_id='+$('select[name=s_id]').attr('attr')+'&curCid='+csid+'&c_id='+newCsid+'&a_id='+studentId, function(msg){
            if (msg.status){
                $('.classname[csId = '+csid+']').html(msg.info);
                $('.mselect[csId = '+csid+']').attr('csId',msg.c_id);
                $('select[csId='+csid+'][name=c_id]').attr('csId',msg.c_id);
                $('select[csId='+csid+'][name=s_id]').attr('csId',msg.c_id);
                $('select[csId='+csid+'][name=ma_id]').attr('csId',msg.c_id);
                $('select[csId='+csid+'][name=c_type]').attr('csId',msg.c_id);
                $('select[csId='+csid+'][name=c_grade]').attr('csId',msg.c_id);
                $('.classname[csId = '+csid+']').attr('csId',msg.c_id);
                alert("修改成功");
            } else {
                alert("此学生已属于此班级");
            }

        },'json')

        // 修改班级
        $('.cname:not(.delete_cname)').addClass('modify_cname');
    })

    // 添加班级
    $('#addClass').click(function(){

        var btn = "<p><span class='confirm' name='sureAdd'>确定</span><span class='cancel' name='canceAdd'>取消</span></p>";

        $('.cname').each(function() {
            $(this).removeClass('modify_cname');
        })

        $(this).prev().hide();
        $(this).next().hide();
        $(this).after(btn);
        //$(this).after(chooseClass);
        $(this).hide();
        $('.mselectAdd').show();

    })

    // 取消添加
    $(document).on('click', 'span[name=canceAdd]', function() {
        $(this).parent().hide();
        $('.mselectAdd').hide();
        $('#addClass').show();

        $('.cname:not(.delete_cname)').addClass('modify_cname');
    })

    // 确定添加
    $(document).on('click', 'span[name=sureAdd]', function() {

        //csid = $(this).parent().parent().prev().attr('csId');
        if($('select[name = c_id][csid = add]').val() == 0){
            alert('请选择班级');
            return false ;
        }

        var c_id = $('select[name=c_id][csid=add]').val();
        var a_id = $('input[name=a_id]').val();
        me = $(this);

        $.post('__URL__/modifyStudentToClass', 's_id='+$('select[name=s_id]').val()+'&c_id='+c_id+'&a_id='+a_id, function(msg){
            switch(msg){
                case 0:
                    alert("不能重复添加");
                break;
                case 2:
                    alert("添加失败，请重试");
                break;
                default:

                    me.parent().prev().prev().after('<div class="classObj"><span class="classname" csid="'+msg.c_id+'">'+msg.info+'</span></div>');

                    alert(msg.info+'添加成功');
            }
        }, 'json')

        $('.cname:not(.delete_cname)').addClass('modify_cname');

        $(this).parent().hide();
        $('.mselectAdd').hide();
        $('#addClass').show();
    })

    // 删除班级
    $(document).on('click', '.delete_cname', function(){
        if(confirm("确定要删除吗？")){

            var a_id = $('input[name=a_id]').val();
            var c_id = $(this).attr('csId');
            var me = $(this);
            $.post('__URL__/deleteClass/a_id/'+a_id+'/c_id/'+c_id, function(msg){
                if (msg == '1'){
                    me.parent().hide();
                    alert('删除成功');
                }
                if (msg == '0'){
                    alert('不可删除，至少要有一个班级');
                }
                if (msg == '2'){
                    alert('删除失败，请重试');
                }

            })
        }
    })

    // 通过学校获取学段
    $('select[name=s_id]').change(function() {
        var csId = $(this).attr('csId');

        $('select[name=c_type][csId='+csId+']').html('<option value="0">请选择</option>');
        $('select[name=ma_id][csId='+csId+']').hide();
        $('select[name=c_grade][csId='+csId+']').html('<option value="0">请选择</option>');
        $('select[name=c_id][csId='+csId+']').html('<option value="0">请选择</option>');

        // 获取学段
        var sType = $('select[name=s_id][csId='+csId+'] option:selected').attr('attr');
        var str = '<option value="0">请选择</option>';
        if (sType != '') {
            var typeArr = sType.split(',');

            for (var p in typeArr) {

                str += '<option value="'+typeArr[p]+'">'+$('.schoolType span').eq(typeArr[p] - 1).html()+'</option>';
            }
        }

        $('select[name=c_type][csId='+csId+']').html(str);
    })

    // 通过学段获取年级
    $('select[name=c_type]').change(function() {
        var csId = $(this).attr('csId');
        if ($(this).val() == 4) {
            $('select[name=ma_id][csId='+csId+']').show();
            MylistGradeOption($('select[name=s_id][csId='+csId+']').val(), $(this).val(), 'ma_id', csId);
        } else {
            $('select[name=ma_id][csId='+csId+']').hide();
            MylistGradeOption($('select[name=s_id][csId='+csId+']').val(), $(this).val(), 'c_grade', csId);
        }
    })

    // 选择专业下的
    $(document).on('change', 'select[name=ma_id]', function () {
        var csId = $(this).attr('csId');
        MylistGradeOption($('select[name=s_id][csId='+csId+']').val(), $(this).val(), 'c_grade', csId, 0, 'major');
    })

    // 通过年级获取班级
    $('select[name=c_grade]').change(function() {
        var csId = $(this).attr('csId'), ma_id = 0, obj = $('select[name=ma_id][csId='+csId+']');
        if (obj.css('display') != 'none') {
            ma_id = obj.val();
        }
        MylistClass($('select[name=s_id][csId='+csId+']').val(), $('select[name=c_type][csId='+csId+']').val(), $(this).val(), 'c_id', csId, 0, ma_id);
    })
})

// 验证
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

/*
 * listGradeOption
 * 根据学制列出年级
 * $param string choose_type 学制ID
 * $param string objName 传值select name
 * $param string choose_grade 默认选中
 * $param string major 专业
 *
 */
function MylistGradeOption(sid, choose_type, objName, csId, choose_grade, major) {

    var str = '<option value="0">请选择</option>';

    if (choose_type) {

        $.post('/Ilc/Public/getGradeByType', 'id=' + choose_type + '&s_id=' + sid + '&type='+ major, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<option value="' + json[i]['key'] + '"';

                    if (choose_grade > 0 && choose_grade == json[i]['key']) {

                        str += 'selected';
                    }

                    str += '>' + json[i]['value'] + '</option>';
                }
            }


            $("select[name=" + objName + "][csId="+csId+"]").html(str);

        }, 'json');

    } else {

        $("select[name=" + objName + "]").html(str);
    }
}

/*
 * listClass
 * 根据学制年级列出班级
 * $param string choose_type 学制ID
 * $param string choose_grade 年级ID
 * $param string objName 传值select name
 * $param string choose_class 默认选中
 * $param string ma_id 专业ID
 *
 */
function MylistClass(s_id, choose_type, choose_grade, objName, csId, choose_class, ma_id) {

    var str = '<option value="0">请选择</option>';

    if (s_id && choose_type && choose_grade) {

        $.post('/Ilc/Class/listByTypeAndGrade', 's_id='+s_id+'&c_type=' + choose_type + '&c_grade=' + choose_grade + '&is_ajax=1&ma_id='+ma_id, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<option value="' + json[i]['c_id'] + '"';

                    if (choose_class > 0 && choose_class == json[i]['c_id']) {

                        str += 'selected';
                    }

                    str += '>(' + json[i]['c_title'] + ')班</option>';
                }
            }

            $("select[name=" + objName + "][csId="+csId+"]").html(str);

        }, 'json');

    } else {

        $("select[name=" + objName + "]").html(str);
    }
}
</script>
<div class="schoolType" style="display:none;">
    <?php if(is_array($schoolType)): $i = 0; $__LIST__ = $schoolType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoolType): $mod = ($i % 2 );++$i;?><span><?php echo ($schoolType); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
</div>