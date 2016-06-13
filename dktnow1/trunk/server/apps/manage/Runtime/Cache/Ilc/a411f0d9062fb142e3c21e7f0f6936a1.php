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
 var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "10%","data_explain": "14%","data_default": "10%","data_sort": "12%","data_status": "15%","data_hand": "10%","data_tel": "10%","data_time": "15%"};
 setWidth(listObj);
})

function apply(id) {
    if (confirm('确认通过吗？')) {
        location.href = '__URL__/apply/id/'+id;
    }
}
</script>
    <form name="form" method="post" action="__URL__/index">
        <div class="model_search">
            <a>学校名称：</a>
            <input TYPE="text" title="名称查询" name="as_name" >
            <a class="search">搜索</a>
        </div>
    </form>
    <div class="tools fl">
        <a class="del">
            <i></i>
            删除
        </a>

    </div>
    <div id="system_list">
        <div>
            <ul class="list_hea">
                <li>
                    <input class="data_check" type="checkbox" name="allcheck" onclick="CheckAll('system_list')"/>
                    <span class="data_id">编号</span>
                    <span class="data_name">学校名</span>
                    <span class="data_explain">地区</span>
                    <span class="data_default">地址</span>
                    <span class="data_status">校长电话</span>
                    <span class="data_time">申请人</span>
                    <span class="data_tel">申请人电话</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["as_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["as_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["as_title"]); ?></span>
                        <span class="data_explain"><?php echo ($list["as_region"]); ?></span>
                        <span class="data_default"><?php echo ($list["as_address"]); ?></span>
                        <span class="data_status"><?php echo ($list["as_header_tel"]); ?></span>
                        <span class="data_time"><?php echo (getauthnamebyid($list["a_id"])); ?></span>
                        <span class="data_tel"><?php echo ($list["as_my_tel"]); ?></span>
                        <span class="data_hand"><a href="javascript:apply(<?php echo ($list["as_id"]); ?>)">通过</a></span>
                        <span class="data_del"><a href="javascript:del(<?php echo ($list["as_id"]); ?>)">删除</a></span>
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