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

<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/system_add.css" />
    <div class="title_hea">
        <i class="fl">班级修改</i>
        <a href="__URL__/">返回列表</a>
    </div>
    <form method="post" action="__URL__/update/" onsubmit="return check();">
        <ul class="add_option">
            <li>
                <label>学校：</label>
                 <select name="s_id">
                    <option attr="" value="0">请选择</option>
                    <?php if(is_array($school)): $i = 0; $__LIST__ = $school;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?><option value="<?php echo ($school["s_id"]); ?>" attr="<?php echo ($school["s_type"]); ?>" <?php if($school["s_id"] == $vo["s_id"] ): ?>selected<?php endif; ?> ><?php echo ($school["s_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </li>
            <li>
                <label>学制：</label>
                <select name="c_type" id="schoolType">
                    <option >请选择...</option>
                    <?php if(is_array($schoolType)): foreach($schoolType as $key=>$vos): ?><option value="<?php echo ($key); ?>" <?php if($key == $vo["c_type"] ): ?>selected<?php endif; ?>><?php echo ($vos); ?></option><?php endforeach; endif; ?>
                </select>
            </li>
            <li class="major">
                <label>专业：</label>
                <select name="ma_id">
                    <option >请选择...</option>
                    <?php if(is_array($major)): foreach($major as $key=>$vos): ?><option value="<?php echo ($vos["ma_id"]); ?>" <?php if($vos["ma_id"] == $vo["ma_id"] ): ?>selected<?php endif; ?>><?php echo ($vos["ma_title"]); ?></option><?php endforeach; endif; ?>
                </select>
            </li>
            <li>
                <label>学年：</label>
                <select name="c_grade" id="gradeType">
                    <?php if(is_array($gradeType)): foreach($gradeType as $key=>$vos): ?><option value="<?php echo ($key); ?>" <?php if($key == $vo["c_grade"] ): ?>selected<?php endif; ?>><?php echo ($vos); ?></option><?php endforeach; endif; ?>
                </select>

            </li>

            <li>
                <label>班级名称：</label>
                  <select name="c_id">
                      <option value=0>请选择</option>
                      <?php if(is_array($classNames)): foreach($classNames as $key=>$evClass): ?><option value="<?php echo ($evClass); ?>" <?php if($evClass == $vo["c_title"] ): ?>selected<?php endif; ?>><?php echo ($evClass); ?></option><?php endforeach; endif; ?>
                 </select>
            </li>

            <li class="coBtn">
                <input type="hidden" value="<?php echo ($vo["c_id"]); ?>" name="cId"/>
                <button class="save fin" value="" type="submit">保存</button>
                <button class="reset fin" value="" type="reset">清除</button>
            </li>
        </ul>
    </form>

    <script type="text/javascript">

    $(function () {

        // 隐藏专业ID
        var ma_id = <?php echo ($vo["ma_id"]); ?>;
        if (ma_id == 0) {
            $('.major').hide();
        }

        // 通过学校获取学段
        $('select[name=s_id]').change(function() {
            $('select[name=c_type]').html('<option value="0">请选择</option>');
            $('select[name=c_grade]').html('<option value="0">请选择</option>');
            $('.major').hide();
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

        // 通过学段获取专业、年级
        $('select[name=c_type]').change(function() {
            if ($(this).val() == 4) {
                $('.major').show();
                listGradeOption($('select[name=s_id]').val(), $(this).val(), 'ma_id');
            } else {
                $('.major').hide();
                listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_grade');
            }
        })

        // 通过专业获取年级
        $(document).on('change', 'select[name=ma_id]', function () {
            listGradeOption($('select[name=s_id]').val(), $(this).val(), 'c_grade', 0, 'major');
        })

        // 获得未增加的班级
        $('select[name=c_grade]').change(function(){
            $('select[name=c_id]').html('<option value="0">请选择</option>');

            // 获取数据
            var s_id = $('select[name = s_id]').val();
            var c_type = $('select[name = c_type]').val();
            var c_grade = $('select[name = c_grade]').val();
            var ma_id = $('.major').css('display') == 'none' ? 0 : $('select[name = ma_id]').val();

            $.post('__URL__/getClass/s_id/'+s_id+'/c_type/'+c_type+'/c_grade/'+c_grade+'/ma_id/'+ma_id, function(msg){
                $('select[name=c_id]').html('<option value=0>请选择</option>');
                for(i = 0 ; i < msg.length ; i ++){
                    $('select[name=c_id]').append('<option value= '+ msg[i] +'>'+ msg[i] +'</option>');
                }
            },'json')
        })
    })

    //验证
    function check(){
        if($('select[name = c_id]').val() == 0){
            alert('请选择班级');
            return false ;
        }
    }

    </script>


<div class="schoolType" style="display:none;">
    <?php if(is_array($schoolType)): $i = 0; $__LIST__ = $schoolType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$schoolType): $mod = ($i % 2 );++$i;?><span><?php echo ($schoolType); ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
</div>