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

    var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "10%","data_type": "8%","data_major": "12%","data_grade": "8%","data_sem": "10%","data_version": "8%","data_subject": "6%","data_status": "6%","data_hand": "4%","data_edit": "4%","data_add": "4%","data_child": "8%"};
    setWidth(listObj);

    // 选中学制，年级
    if ('<?php echo ($choose_type); ?>') {
        listGradeOption('', '<?php echo ($choose_type); ?>', 'd_grade', '<?php echo ($choose_grade); ?>');
    }

    // 根据学制选择年级
    $("select[name=d_type]").change(function() {
        listGradeOption('', $(this).val(), 'd_grade');
    })
})

function check() {

    if ($("select[name=d_type]").val() != 0 || $("select[name=d_grade]").val() != 0 || $("select[name=d_semester]").val() != 0) {
        if ($("select[name=d_type]").val() == 0) {
                alert('请选择学制', 0);
                return false;
        }

        if ($("select[name=d_grade]").val() == 0) {
                alert('请选择年级', 0);
                return false;
        }
    }

}

function child(id){
    location.href = URL+"/index/d_pid/"+id;
}

function points(id){

    location.href = '__APPURL__/Ilc/TagRelation/index/d_id/'+id;
}

function add(id) {
    location.href = URL+"/add/d_pid/"+id;
}

</script>
    <h2 class="res"><?php if(($d_level) == "1"): ?>单元列表<?php else: ?>课文列表<a href="javascript:history.go(-1);" class="fr">返回上一级</a><?php endif; ?></h2>
    <form name="form" method="post" action="__URL__">
        <div id="mode_ind" class="model_search">
            <a>学制：</a>
            <select name="d_type" class="fl">
                <option value="0">请选择</option>
                <?php if(is_array($d_type)): $tk = 0; $__LIST__ = $d_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($tk % 2 );++$tk;?><option value="<?php echo ($tk); ?>" <?php if(($choose_type) == $tk): ?>selected<?php endif; ?>><?php echo ($type); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a>年级：</a>
            <select name="d_grade" class="fl">
                <option value="0">请选择</option>
            </select>
            <a>学期：</a>
            <select name="d_semester" class="fl">
                <?php if(is_array($d_semester)): $i = 0; $__LIST__ = $d_semester;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$semester): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($choose_semester) == $key): ?>selected<?php endif; ?>><?php echo ($semester); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a>学科：</a>
            <select name="d_subject" class="fl">
                <option value="">请选择</option>
                <?php if(is_array($d_course)): $ck = 0; $__LIST__ = $d_course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($ck % 2 );++$ck;?><option value="<?php echo ($ck); ?>" <?php if(($choose_subject) == $ck): ?>selected<?php endif; ?>><?php echo ($course); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a>版本：</a>
            <select name="d_version" class="fl">
                <option value="">请选择</option>
                <?php if(is_array($d_version)): $vk = 0; $__LIST__ = $d_version;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$version): $mod = ($vk % 2 );++$vk;?><option value="<?php echo ($vk); ?>" <?php if(($choose_version) == $vk): ?>selected<?php endif; ?>><?php echo ($version); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <a class="search" class="fl">搜索</a>
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
        <a class="del">
            <i></i>
            删除
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
                    <span class="data_name">名称</span>
                    <span class="data_type">所属学制</span>
                    <span class="data_major">所属专业</span>
                    <span class="data_grade">所属年级</span>
                    <span class="data_sem">学期</span>
                    <span class="data_version">版本</span>
                    <span class="data_subject">所属学科</span>
                    <span class="data_status">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["d_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["d_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["d_name"]); ?></span>
                        <span class="data_type"><?php echo (shownamebydirectorycode($list["d_code"],1)); ?></span>
                        <span class="data_major"><?php echo ($list["ma_title"]); ?></span>
                        <span class="data_grade"><?php echo (shownamebydirectorycode($list["d_code"],2)); ?></span>
                        <span class="data_sem"><?php echo (shownamebydirectorycode($list["d_code"],3)); ?></span>
                        <span class="data_version"><?php echo (gettypenamebyid($list["d_version"],'VERSION_TYPE')); ?></span>
                        <span class="data_subject"><?php echo (gettypenamebyid($list["d_subject"],'COURSE_TYPE')); ?></span>
                        <span class="data_status"><?php echo (getstatus($list["d_status"])); ?></span>
                        <span class="data_hand"><?php echo (showstatus($list["d_status"],$list['d_id'])); ?></span>
                        <span class="data_edit"><a href="javascript:edit(<?php echo ($list["d_id"]); ?>)">编辑</a></span>
                        <span class="data_add"><a href="javascript:add(<?php echo ($list["d_id"]); ?>)">新增</a></span>
                        <?php if(($d_level) < "2"): ?><span class="data_child"><a href="javascript:child(<?php echo ($list["d_id"]); ?>)">子节点</a></span>
                        <?php else: ?>
                            <span class="data_child"><a href="javascript:points(<?php echo ($list["d_id"]); ?>)">知识点</a></span><?php endif; ?>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>