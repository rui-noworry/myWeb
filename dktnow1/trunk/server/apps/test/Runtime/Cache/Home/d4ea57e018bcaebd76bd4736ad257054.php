<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title></title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/index.css" />
    <script src="/Public/Js/Public/jquery-1.9.1.js"></script>
    <script type="text/javascript">
    <!--
        $(function(){
            $('select[name=controller]').change(function() {
                $('.require').html('');
                if ($(this).val() != '') {
                    $('select[name=function]').html('');
                    $.post('__URL__/getFunction', 'con='+$(this).val(), function(json) {

                        var str = '<option value="">请选择</option>';
                        for(var key in json) {
                            str += '<option value="'+key+'">'+json[key]['title']+'</option>';
                        }
                        $('select[name=function]').html(str);
                    }, 'json');
                } else {
                    $('select[name=function]').html('<option value="">请选择</option>');
                }
            })

            $('select[name=function]').change(function() {

                if ($(this).val() != '') {
                    $.post('__URL__/getParam', 'con='+$('select[name=controller]').val()+'&fun='+$(this).val(), function(json) {
                        var str = '';
                        for(var key in json) {
                            if (json[key]['required'] == 1) {
                                //str += json[key]['title'] + '：<input type="text" name="require['+key+']">';
                                if (json[key]['type'] != 'file') {
                                    str += '<label>' + key + '：</label>' + '<input type="text" name="require['+key+']">';
                                }
                            }
                        }

                        $('.require').html(str);
                    }, 'json');
                }
            })

            $('.now').click(function() {
                $('.right .str').html('');
                $('.right .obj').html('');
                $.post('__URL__/api', $('.left form').serialize(), function(json) {
                    $('.right .str').html(json.str);
                    $('.right .obj').html(json.obj);
                    $('.right .pre').html(json.param1 + '<hr>' + json.param2);
                }, 'json')
            });

            //右侧切换
            $('.right li').click(function(){
                $(this).addClass('on').siblings().removeClass('on');
                $('.news .show').eq($(this).index()).show().siblings().hide();
            }).eq(2).click();
        })
    //-->
    </script>
</head>
<body>
    <div class="left">
        <form>
            <div class="con">
                <label>类名：</label>
                <select name="controller">
                    <option value="">请选择</option>
                    <?php if(is_array($controller)): $i = 0; $__LIST__ = $controller;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($params['controller']) == $key): ?>selected<?php endif; ?>><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <div class="fun">
                <label>方法名：</label>
                <select name="function">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="require">

            </div>
            <div class="param">
                <label>参数：</label>
                <textarea rows="5" cols="30" name="param"></textarea>
                <p>
                    *非必填参数每行一个，以这样的样式填写</br>
                    例如：</br>
                    co_id=21</br>
                    a_id=1</br>
                </p>
            </div>
            <div class="submit">
                <input type="button" class="now" value="确定">
            </div>
        </form>

        <input type="hidden" name="fun" value="<?php echo ($params["function"]); ?>">
    </div>
    <div class="right">
        <ul>
            <li>传递前数据</li>
            <li>树形列表</li>
            <li>JSON数据</li>
        </ul>
        <div class="news">
            <div class="show pre"></div>
            <div class="show obj"></div>
            <div class="show str"></div>
        </div>
    </div>
</body>
</html>