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
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/resource_index.css" />
<script type="text/javascript">
$(function(){

    var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "10%","data_type": "20%","data_grade": "10%","data_sem": "10%","data_version": "10%","data_subject": "6%","data_status": "8%","data_hand": "4%","data_edit": "4%","data_add": "4%","data_child": "8%"};
    setWidth(listObj);

    // 隐藏专业ID
    var majorFlag= '<?php echo ($prevMajor); ?>';
    if (majorFlag == '0') {
        $('select[name=c_major]').hide();
    }

    // 通过学校获取学段
    $('select[name=s_id]').change(function() {
        $('select[name=c_type]').html('<option value="0">请选择</option>');
        $('select[name=c_major]').hide();
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
        $(this).nextAll().children('option').removeAttr('selected');
        if ($(this).val() == 4) {
            $('select[name=c_major]').show();
            listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_major');
        } else {
            $('select[name=c_major]').hide();
            listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_grade');
        }
    })

    // 通过专业获取年级
    $(document).on('change', 'select[name=c_major]', function () {
        listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_grade', 0, 'major');
    })

    // 通过年级获取班级
    $('select[name=c_grade]').change(function() {
        $(this).nextAll().children('option').removeAttr('selected');
        var ma_id = 0;
        if ($('select[name=c_major]').css('display') != 'none') {
            ma_id = $('select[name=c_major]').val();
        }
        listClass($('select[name=s_id]').val(), $('select[name=c_type]').val(), $(this).val(), 'c_id', 0, ma_id);
    })

})

</script>

    <form name="form" method="post" action="__URL__">
        <div class="model_search">
            <a>姓名：</a>
            <input TYPE="text" title="名称查询" name="a_nickname" >
            <a class="search">搜索</a>
            <div class="clear"></div>
            <select name="s_id" >
                <option attr="" value="0">请选择</option>
                <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><option value="<?php echo ($school["s_id"]); ?>" attr="<?php echo ($school["s_type"]); ?>"  <?php if(($prevsId) == $school["s_id"]): ?>selected<?php endif; ?> ><?php echo ($school["s_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select name="c_type">
                <option value="0">请选择</option>
                <?php if(is_array($csType)): foreach($csType as $typekey=>$type): ?><option value="<?php echo ($typekey); ?>" <?php if(($prevcType) == $typekey): ?>selected<?php endif; ?> ><?php echo ($type); ?></option><?php endforeach; endif; ?>
            </select>
            <select name="c_major" >
                <option value="0">请选择</option>
                <?php if(is_array($major)): $i = 0; $__LIST__ = $major;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["ma_id"]); ?>" <?php if($vo["ma_id"] == $prevMajor ): ?>selected<?php endif; ?> ><?php echo ($vo["ma_title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select name="c_grade" >
                <option value="0">请选择</option>
                <?php if(is_array($grade)): foreach($grade as $gradekey=>$gradename): ?><option value="<?php echo ($gradekey); ?>" <?php if(($prevcGrade) == $gradekey): ?>selected<?php endif; ?> ><?php echo ($gradename); ?></option><?php endforeach; endif; ?>
            </select>
            <select name="c_id"  >
                <option value="0" >请选择</option>
                <?php if(is_array($class)): foreach($class as $classkey=>$classname): ?><option value="<?php echo ($classkey); ?>" <?php if(($prevcId) == $classkey): ?>selected<?php endif; ?> ><?php echo ($classname); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
    </form>
    <div class="tools fl">
        <a class="add">
            <i></i>
            新增
        </a>
        <a class="edit">
            <i></i>
            编辑
        </a>
        <a class="forbidden">
            <i></i>
            禁用
        </a>
        <a class="resume">
            <i></i>
            启用
        </a>
    </div>
    <div id="system_list">
        <div>
            <ul class="list_hea">
                <li>
                    <input class="data_check" type="checkbox" name="allcheck" onclick="CheckAll('system_list')"/>
                    <span class="data_id">编号</span>
                    <span class="data_name">学校</span>
                    <span class="data_type">班级</span>
                    <span class="data_grade">用户名</span>
                    <span class="data_sem">姓名</span>
                    <span class="data_version">手机</span>
                    <span class="data_subject">登陆次数</span>
                    <span class="data_status">登陆IP</span>
                    <span class="data_status">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["a_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["a_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["s_name"]); ?></span>
                        <span class="data_type"><?php echo ($list["c_title"]); ?></span>
                        <span class="data_grade"><?php echo ($list["a_account"]); ?></span>
                        <span class="data_sem"><?php echo ($list["a_nickname"]); ?></span>
                        <span class="data_version"><?php echo ($list["a_tel"]); ?></span>
                        <span class="data_subject"><?php echo ($list["a_login_count"]); ?></span>
                        <span class="data_status"><?php echo ($list["a_last_login_ip"]); ?></span>
                        <span class="data_status"><?php echo (getstatus($list["a_status"])); ?></span>
                        <span class="data_hand"><a href="javascript:edit(<?php echo ($list["a_id"]); ?>)">编辑</a></span>

                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>

    <div class="schoolType" style="display:none;">
        <?php if(is_array($schoolType)): $i = 0; $__LIST__ = $schoolType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoolType): $mod = ($i % 2 );++$i;?><span><?php echo ($schoolType); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>