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
        var listObj = {"data_check": "2%","data_id": "8%" ,"data_name": "20%","data_explain": "24%","data_default": "14%","data_sort": "12%","data_hand": "20%"};
     setWidth(listObj);

       // 栏目选择
        $('.choose').click(function(){
            getChild(0, 1);
        });

        // 获取子栏目

        $(document).on('click', '.listCategory li', function(){
            var level = $(this).attr('level');
            $('.listCategory li').each(function() {
                if ($(this).attr('level') > level) {
                    $(this).remove();
                }
            })
            $(this).addClass('on').siblings().removeClass('on');
            getChild($(this).attr('attr'));
        });
    })

    function getChild(id, type) {

        var str = '';

        if (type == 1) {
            if ($('.listCategory li').size() == 0) {
                str += '<li attr="0" level="0" type="0">系统栏目</li>';
                $('.listCategory ul').html(str);return;

            }
        } else {

            if ($('.listCategory').css('display') == 'none') {
                $('.listCategory').show();
            } else {
                $.post('__URL__/findSub', 'id='+id, function(json) {
                    var str = '';

                    if (json) {
                        $.each(json, function(i, data){

                            if (data['rc_pid'] == id && data['s_id'] == $('.listCategory li.on').attr('type')) {
                                str += '<li attr="' + data['rc_id'] + '" level="' + data['rc_level'] + '" type="' + data['s_id'] + '">' + getLevel(data['rc_level']) + data['rc_title'] + '</li>';
                            }
                        })
                    }

                    if (!str) {
                        var t_val = $('.listCategory li.on').html();
                        var reg=/&nbsp;/g;
                        var new_val = t_val.replace(reg,'');
                        $('.choose').html(new_val);
                        $('input[name=rc_id]').val($('.listCategory li.on').attr('attr'));
                        $('.listCategory.on').removeClass('on');
                        $('.listCategory').hide();
                    } else {
                        $(str).insertAfter($('.listCategory li.on'));
                        $('.listCategory').show();
                    }

                }, 'json')
            }
        }
    }

    function getLevel(level) {

        var result = '';
        for (var i = 0; i < level; i ++) {

            result += '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        return result;
    }

    // 审核资源是否通过
    function resPass(type) {

        keyValue = getSelectCheckboxValues();

        if (!keyValue) {
            showMessage('请选择操作项！');
            return false;
        }

        location.href = URL+'/resPass/id/'+keyValue+'/type/'+type;

    }

</script>

    <form name="form" method="post" action="__URL__/index">
        <input type="hidden" value="" name="rc_id" />
        <div class="model_search">
            <a>名称：</a>
            <input TYPE="text" title="名称查询" name="n_name" >
            <a class="search">搜索</a>
            <div class="clear"></div>
            <a>栏目：</a>
            <span class="choose">选择</span>
            <div class="listCategory">
                <ul>

                </ul>
            </div>
        </div>
    </form>
    <div class="tools fl">
        <a onclick="resPass(1)">
            <i></i>
            通过
        </a>
        <a onclick="resPass(0)">
            <i></i>
            不通过
        </a>
    </div>
    <div id="system_list">
        <div>
            <ul class="list_hea">
                <li>
                    <input class="data_check" type="checkbox" name="allcheck" onclick="CheckAll('system_list')"/>
                    <span class="data_id">编号</span>
                    <span class="data_name">名称</span>
                    <span class="data_explain">所属栏目</span>
                    <span class="data_sort">状态</span>
                    <span class="data_hand">操作</span>
                </li>
            </ul>
        </div>
        <div>
            <ul class="list_list list_hea" id="list_main">

                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li>
                        <input class="data_check" type="checkbox" value="<?php echo ($list["re_id"]); ?>" name="key"/>
                        <span class="data_id"><?php echo ($list["re_id"]); ?></span>
                        <span class="data_name"><?php echo ($list["re_title"]); ?></span>
                        <span class="data_explain"><?php echo ($list["rc_title"]); ?></span>
                        <span class="data_sort"><?php echo (getresourcestatus($list["re_is_pass"])); ?></span>
                        <span class="data_hand"><a href="javascript:edit(<?php echo ($list["re_id"]); ?>)">进入</a></span>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>
        </div>
        <div class="page">
            <?php echo ($page); ?>
        </div>
    </div>