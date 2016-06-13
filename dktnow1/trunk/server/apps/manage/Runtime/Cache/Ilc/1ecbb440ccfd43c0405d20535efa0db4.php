<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>『<?php echo (C("web_name")); ?>管理平台』</title>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/header.css" /><link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/Ilc/public.css" />
    <script type="text/javascript" src="/Public/Js/Public/jquery-1.9.1.js"></script><script type="text/javascript" src="/Public/Js/Public/jquery-ui.js"></script><script type="text/javascript" src="/Public/Js/Public/public.js"></script>
    <script type="text/javascript">
        $(function(){

            var x,y,tt_left,new_if;
            var flag = false;

            //浏览器窗口发生改变时;
            $(window).resize(function(){
                if ($('.a_max').css('display') == 'none') {
                    $('.m_right').width(document.body.scrollWidth-110);
                    $('.right_submenu li').css('left',document.body.scrollWidth-65);
                    $('.right_submenu .on').css('left',0);
                    var man_wid = document.documentElement.clientWidth - 2;
                    var man_hei = window.document.body.scrollHeight+87;
                    $('.drag_on').width(man_wid).height(man_hei);
                    $('.drag .title').width(man_wid);
                    $('iframe').width(man_wid);
                }
            });
            //弹窗拖拽
            //$(".drag").draggable({ appendTo: 'body' });

            //弹窗定义 left，top 值
            var mar_top = (window.document.body.scrollHeight - 430) / 2;
            var mar_left = (window.document.body.scrollWidth - 1000) / 2;
            $('.drag').css({'left':mar_left,'top':mar_top});

            //弹窗最小化
            $(document).on('click','.drag .a_min',function(){

                $(this).parents('.drag').remove();
            })
            //弹窗最大化
            $(document).on('click','.drag .a_max',function(){

                left = $(this).parents('.drag').css('left');
                topp = $(this).parents('.drag').css('top');
                max(this);
            })
            //弹窗还原
            $(document).on('click','.drag .a_revert',function(){

                revert(this);
            })
            //弹窗关闭
            $(document).on('click','.a_close',function(){
                $(this).parents('.drag').remove();
            })
            //弹窗头部点击 最大化 还原
            $(document).on('dblclick','.drag .title',function(){

                if($(this).parent('div').hasClass('drag_on')) {
                    revert($(this).children('.a_revert'));
                }else{
                    left = $(this).parents('.drag').css('left');
                    topp = $(this).parents('.drag').css('top');
                    max($(this).children('.a_max'));
                }
            })

            //弹窗点击在最上面
            $(document).on('mousedown','.warp .drag',function(){
                $('.warp .drag').css({'z-index':1});
                $(this).css({'z-index':3});
            })

            //头部 Li 滑过事件
            $(".m_header li").hover(function(){

                if (!$(this).hasClass("on")) {
                    var s_index = $(this).index();
                    $(this).addClass("lii" + s_index);
                }
            },function(){

                var s_index = $(this).index();
                $(this).removeClass("lii" + s_index);
            })

            //头部 li切换
            $(".m_header li").click(function(){

                var s_index = $(this).index();
                show(s_index);
            })

            //左侧 li切换
            $(".m_left li").click(function(){

                var s_index = $(this).index();
                show(s_index);
            })

            //头部拖动
            $(".m_header").draggable({containment:'parent'});

            //页面的高度设置
            $('.warp').height(window.document.body.scrollHeight+6);

            //右部分宽度设置
            $('.m_right').width(document.body.scrollWidth-110);

            //子菜单互换位置
            $( ".right_submenu li" ).sortable();
            $( ".right_submenu li" ).disableSelection();

            // 图像点击
            $('.hea a').click(function(){
                var new_num = "img";
                new_pop(new_num);
            })

            // 小导航默认第一个被选中
            $('.hea ul li:eq(0)').addClass('on');

            // 左侧导航默认第一个被选中
            $('.m_left ul li:eq(0)').addClass('on');

            // 第二个li增加on样式
            $('.right_submenu li:eq(0)').addClass('on');

            //窗口拖拽
            $(document).on('mousedown','.title_on',function(even){
                oDrag = $(this).parent('.drag');
                $('.warp .drag').css({'z-index':1});
                oDrag.css({'z-index':3});
                var e = even || window.event;
                disX = e.clientX - parseInt(oDrag.css('left'));
                disY = e.clientY - parseInt(oDrag.css('top'));

                document.onmousemove = function (even)
                {
                    var e = even || window.event;
                    var iL = e.clientX - disX;
                    var iT = e.clientY - disY;
                    //控制拖放范围
                    //iL <= 0 && (iL = 0);
                    iT <= 0 && (iT = 0);
                    //iL >= maxL && (iL = maxL);
                    //iT >= maxT && (iT = maxT);
                    oDrag.css('left',iL);
                    oDrag.css('top',iT);
                    return false
                };

                document.onmouseup = function ()
                {
                    document.onmousemove = null;
                    document.onmouseup = null;
                    this.releaseCapture && this.releaseCapture()
                };
                this.setCapture && this.setCapture();
                return false
            })

        })

        function show(index) {

            $('.warp .drag').remove();
            $('.m_header li').eq(index).addClass("on").siblings().removeClass("on");
            $('.m_left li').eq(index).addClass("on").siblings().removeClass("on");
            var r_index = $('.right_submenu .on').index();
            if (r_index > index) {
                $('.right_submenu li').eq(r_index).stop().animate({left: document.body.scrollWidth-65},500);
                $('.right_submenu li').eq(index).css('left','-720' + 'px');
                $('.right_submenu li').eq(index).stop().animate({left: 0 , opacity: '1'},500);
            } else {
                if (r_index != index) {
                    $('.right_submenu li').eq(r_index).stop().animate({left: -720},500);
                    $('.right_submenu li').eq(index).css('left',document.body.scrollWidth-65);
                    $('.right_submenu li').eq(index).stop().animate({left: 0},500);
                }
            }
            $('.right_submenu li').eq(index).addClass("on").siblings().removeClass("on");
        }
    </script>
</head>

<body>
    <div class="warp fl">
    <image src="__APPURL__/Public/Images/Ilc/1.jpg" class="warp_bg"/>
    <!--头部-->
    <div class="m_header">
        <div class="hea">
            <a class="fl">
                <img src="http://pic.dkt.com/AuthAvatar/96/default.jpg"/>
            </a>
            <ul class="fl">
                <li class="li0"><i></i></li>
                <?php $i = 1; ?>
                <?php if(is_array($nodeGroupList)): $i = 0; $__LIST__ = $nodeGroupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$node): $mod = ($i % 2 );++$i;?><li class="li<?php echo ($i); ?>"><i></i></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <span class="fr" onclick="javascript:window.location.href='__GROUP__/Public/logout/'"></span>
        </div>
    </div>
<script>
    $(function(){

        $(document).on('click','.right_submenu li dl',function(){
            t_return = true;
            var li_attr = $(this).parent().index();
            var this_attr = $(this).attr('attr');
            var new_num = this_attr;
            var src = $(this).attr('rel');
            new_pop(new_num, src);
        })

    })

</script>
<!--左部-->
    <div class="m_left fl">
        <ul>
            <li title="首页"><label></label><span>首页</span></li>
            <?php if(is_array($nodeGroupList)): $i = 0; $__LIST__ = $nodeGroupList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$node): $mod = ($i % 2 );++$i;?><li title="<?php echo ($node["g_title"]); ?>"><label></label><span><?php echo ($node["g_title"]); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
        <ul>
    </div>
    <!--右部-->
    <div class="m_right fl">
        <ul class="right_submenu fl">
            <li>

            </li>
            <?php if(is_array($menuArr)): $i = 0; $__LIST__ = $menuArr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                    <?php if(is_array($menu)): $key = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menuChild): $mod = ($key % 2 );++$key; if(($menuChild["access"]) == "1"): ?><dl attr="<?php echo ($menuChild["n_title"]); ?>" rel="<?php echo ($menuChild["n_name"]); ?>">
                                <dt>
                                    <a href="javascript:void(0);" class="fl">
                                        <img src="__APPURL__/Public/Images/Ilc/<?php echo floor(rand(0, 9)); ?>.png" title="<?php echo ($menuChild["n_title"]); ?>" />
                                    </a>
                                </dt>
                                <dd><?php echo ($menuChild["n_title"]); ?></dd>
                            </dl><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
<script type="text/javascript">
    $(function(){
        for (i=0;i<$('.m_left li').size();i++ ) {
            $('.m_left li').eq(i).addClass('left_li' + i);
        }
    })
</script>
            </div>
        </div>
    </div>
</div>
</body>
</html>