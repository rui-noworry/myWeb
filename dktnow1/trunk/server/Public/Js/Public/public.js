var topp,left;
var if_width,if_height;
$(function(){

    //判断IE6浏览器
    if (window.ActiveXObject) {

        var ua = navigator.userAgent.toLowerCase();
        var ie=ua.match(/msie ([\d.]+)/)[1];
        if(ie==6.0){
            alert("您的浏览器版本过低，在本系统中不能达到良好的视觉效果，建议你升级到ie8以上！");
            window.close();//关闭浏览器窗口;
        }
    }

    //窗口向左拖拽
    $(document).on('mousedown','.content_left',function() {

        oDrag = $(this).parent('.drag');
        new_if = $(this).parent('.drag').find('.new_if');

        document.onmousemove = function (e) {

            new_if.show();
            title = oDrag.children('.title');
            oiframe = oDrag.find('iframe');
            t_width = parseInt(oDrag.css('width'));
            var ev = e || window.event;
            c_y = ev.clientY;
            c_x = ev.clientX;
            tt_left = parseInt(oDrag.css('left'));
            num = t_width - (c_x - tt_left);
            if (num <= 190) { return false };
            oDrag.css('width',num + 'px');
            oDrag.css('left',c_x);
            title.css('width',num + 'px');
            oiframe.css('width',(num-2) + 'px');
            return false;
        }

        document.onmouseup = function () {

            new_if.hide();
            document.onmousemove = null;
            document.onmouseup = null;
            this.releaseCapture && this.releaseCapture();
        };
        this.setCapture && this.setCapture();
        return false;
    })

    //窗口向右拖拽
    $(document).on('mousedown','.content_right',function() {

        oDrag = $(this).parent('.drag');
        new_if = $(this).parent('.drag').find('.new_if');

        document.onmousemove = function (e) {

            new_if.show();
            title = oDrag.children('.title');
            oiframe = oDrag.find('iframe');
            var ev = e || window.event;
            c_y = ev.clientY;
            c_x = ev.clientX;
            tt_left = parseInt(oDrag.css('left'));
            num = c_x - tt_left;
            if (num <= 190) { return false };
            oDrag.css('width',num + 'px');
            title.css('width',num + 'px');
            oiframe.css('width',(num-2) + 'px');
            return false;
        }

        document.onmouseup = function () {

            new_if.hide();
            document.onmousemove = null;
            document.onmouseup = null;
            this.releaseCapture && this.releaseCapture();
        };
        this.setCapture && this.setCapture();
        return false;
    })

    //窗口向下拖拽
    $(document).on('mousedown','.content_bottom',function() {

        oDrag = $(this).parent('.drag');
        new_if = $(this).parent('.drag').find('.new_if');

        document.onmousemove = function (e) {

            new_if.show();
            oiframe = oDrag.find('iframe');
            var ev = e || window.event;
            c_y = ev.clientY;
            c_x = ev.clientX;
            tt_top = parseInt(oDrag.css('top'));
            num = c_y - tt_top;
            if (num <= 100) { return false };
            oDrag.css('height',num + 'px');
            oiframe.css('height',(num - 38) + 'px');
            return false;
        }

        document.onmouseup = function () {

            new_if.hide();
            document.onmousemove = null;
            document.onmouseup = null;
            this.releaseCapture && this.releaseCapture();
        };
        this.setCapture && this.setCapture();
        return false;
    })

    //窗口向左下拖拽
    $(document).on('mousedown','.content_leftbottom',function() {

        oDrag = $(this).parent('.drag');
        new_if = $(this).parent('.drag').find('.new_if');

        document.onmousemove = function (e) {

            new_if.show();
            title = oDrag.children('.title');
            oiframe = oDrag.find('iframe');
            var ev = e || window.event;
            c_y = ev.clientY;
            c_x = ev.clientX;
            tt_top = parseInt(oDrag.css('top'));
            t_width = parseInt(oDrag.css('width'));
            tt_left = parseInt(oDrag.css('left'));
            num = c_y - tt_top;
            num_width = t_width - (c_x - tt_left);
            Tnum(num);
            Tnumwidth(num_width)
            return false;
        }

        document.onmouseup = function () {

            new_if.hide();
            document.onmousemove = null;
            document.onmouseup = null;
            this.releaseCapture && this.releaseCapture();
        };
        this.setCapture && this.setCapture();
        return false;
    })
     //窗口向右下拖拽
    $(document).on('mousedown','.content_rightbottom',function() {

        oDrag = $(this).parent('.drag');
        new_if = $(this).parent('.drag').find('.new_if');

        document.onmousemove = function (e) {

            new_if.show();
            title = oDrag.children('.title');
            oiframe = oDrag.find('iframe');
            var ev = e || window.event;
            c_y = ev.clientY;
            c_x = ev.clientX;
            tt_top = parseInt(oDrag.css('top'));
            t_width = parseInt(oDrag.css('width'));
            tt_left = parseInt(oDrag.css('left'));
            num = c_y - tt_top;
            num_width = c_x - tt_left;
            Tnum(num);
            TTnumwidth(num_width)
            return false;
        }

        document.onmouseup = function () {

            new_if.hide();
            document.onmousemove = null;
            document.onmouseup = null;
            this.releaseCapture && this.releaseCapture();
        };
        this.setCapture && this.setCapture();
        return false;
    })

})

function Tnum(num) {
    if (num <= 100) { 
        return false;
    } else {
        oDrag.css('height',num + 'px');
        oiframe.css('height',(num - 38) + 'px');
    };
}

function Tnumwidth(num_width) {
    if (num_width <= 190) {
        return false;
    } else {
        oDrag.css('left',c_x);
        oDrag.css('width',num_width + 'px');
        title.css('width',num_width + 'px');
        oiframe.css('width',(num_width-2) + 'px');
    };
}

function TTnumwidth(num_width) {
    if (num_width <= 190) {
        return false;
    } else {
        oDrag.css('width',num_width + 'px');
        title.css('width',num_width + 'px');
        oiframe.css('width',(num_width-2) + 'px');
    };
}

// 表格宽度
function setWidth(obj) {
    for(var p in obj){
        $('.' + p).css('width', obj[p]);
    }
}
// 获取被选中的所有复选框的值
function getSelectCheckboxValues(){

    var str = '';
    $("input[name=key]:checked").each(function(){
        str += ',' + $(this).val();
    })
    return str.slice(1);
}

// 选中指定ID下的checkbox
function CheckAll(id) {
    var obj = $("#"+id+" :checkbox");

    var flag;
    obj.each(function(i){
        if (i == 0) {
            flag = Boolean($(this).is(':checked'));
        } else {
            $(this).prop("checked", flag);
        }
    })
}
//弹窗 最大化
function max(now) {
    $('.drag').css('margin','0');
    var man_wid = document.documentElement.clientWidth - 2;
    var man_hei = window.document.body.scrollHeight+87;
    if_width = $(now).parents('.drag').find('iframe').width();
    if_height = $(now).parents('.drag').find('iframe').height();
    $(now).parents('.drag').css({'left':'0','top':'0'}).addClass('drag_on');
    $(now).parents('.drag').width(man_wid).height(man_hei);
    $(now).hide().siblings('.a_revert').show();
    $(now).parents('.drag').children('.title').width(man_wid);
    $(now).parents('.drag').find('iframe').width(man_wid);
    $(now).parents('.drag').find('iframe').css('height',man_hei - 137);
    $(now).parents('.drag').children('.content_left').hide();
    $(now).parents('.drag').children('.content_right').hide();
    $(now).parents('.drag').children('.content_bottom').hide();
    $(now).parents('.drag').children('.content_leftbottom').hide();
    $(now).parents('.drag').children('.content_rightbottom').hide();
    $(now).parents('.drag').children('.title').removeClass('title_on');
 }
 //弹窗 还原
 function revert(now) {

    $(now).parents('.drag').width('auto').height('auto');
    $(now).parents('.drag').css({'left':left,'top':topp}).removeClass('drag_on');
    $(now).hide().siblings('.a_max').show();
    $(now).parents('.drag').children('.title').width(if_width);
    $(now).parents('.drag').find('iframe').width(if_width).height(if_height);
    $(now).parents('.drag').children('.content_left').show();
    $(now).parents('.drag').children('.content_right').show();
    $(now).parents('.drag').children('.content_bottom').show();
    $(now).parents('.drag').children('.content_leftbottom').show();
    $(now).parents('.drag').children('.content_rightbottom').show();
    $(now).parents('.drag').children('.title').addClass('title_on');
 }
//弹窗 添加
function new_pop(iframe_src, src) {

    var t_return = true;
    var new_name = iframe_src;
    $('.warp .drag').css({'z-index':1});
    $('.'+new_name).css({'z-index':4}).show();
    $('.warp .drag').each(function(){
        var t_attr = $(this).attr('attr');
        if(t_attr == iframe_src) {
                 t_return = false;
            }
        })
    if (t_return == true) {
        $('.warp').append("<div class='drag "+ new_name +"' attr='"+ iframe_src +"'><div class='content_left'></div><div class='content_right'></div><div class='content_bottom'></div><div class='content_leftbottom'></div><div class='content_rightbottom'></div><div class='title title_on'><h2 class='fl'>"+ new_name +"</h2><a class='a_close' href='javascript:;' title='关闭'></a><a class='a_max' href='javascript:;' title='最大化'></a><a class='a_revert' href='javascript:;' title='还原'></a><a class='a_min' href='javascript:;' title='最小化'></a></div><div class='clear'></div><div class='content'><iframe id='actIframe' frameborder='no' border='0' scrolling='yes' src='/apps/manage/Ilc/" + src + "'></iframe><div class='new_if'></div></div></div>");
        var mar_top = (window.document.body.scrollHeight - 430) / 2;
        var mar_left = (window.document.body.scrollWidth - 1000) / 2;
        $('.'+new_name+'').css({'left':mar_left,'top':mar_top});
        $('.warp .drag').css({'z-index':1});
        $('.'+new_name).css({'z-index':4}).show();
    }
    //$(".drag").draggable();
    //$('.warp').append("<div class='nav11'>dedede</div>");
    //alert($('.nav11').text())
}

/*
 * setProvince
 * 初始化省市区
 * $param string id 省市区HTML id
 * $param string name 传值input name
 * $param string str 初始值
 *
 */
function setProvince(id, name, str) {
    if (id != '' && name != ''){
        if (str) {
            $("#" + id).ProvinceCity(str).children("select").css('color','#333333');
        } else {
            $("#" + id).ProvinceCity("", "", "").children("select").css('color','#333333');
        }
        $("#" + id + " select").change(function() {
            var region,add1,add2,add3,add='###';
            var add1 = $("#" + id + " select").eq(0).val();
            add2 = $("#" + id + " select").eq(1).val();
            add3 = $("#" + id + " select").eq(2).val();
            if (add1 == add2) {
                add2 = '';
            }
            if (add1 == add3) {
                add3 = '';
            }
            city = add1 + add + add2 + add + add3;
            $('input[name=' + name + '][type=hidden]').val(city);
        });
    }
}

/*
 * listGrade
 * 根据学制列出年级
 * $param int sid 学校ID
 * $param string choose_type 学制ID
 * $param string objName 传值select name
 * $param string choose_grade 默认选中
 *
 */
function listGrade(choose_type, objName, choose_grade, className) {

    if (choose_type == 4 && !className) {
        var str = '<label class="la">专业：</label>';
    } else {
        var str = '<label class="la">年级：</label>';
    }

    if (choose_type) {

        $.post('/apps/study/Public/getGradeByType', 'id=' + choose_type + '&type=' + className, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<span rel="' + json[i]['key'] + '" class="hand ';

                    if (!className) {

                        if (choose_grade > 0 && choose_grade == json[i]['key']) {
                            str += 'on';
                        } else if (choose_type == 4 && i == 0) {
                            str += 'on';
                        }
                    } else {
                        if (choose_grade > 0 && choose_grade == json[i]['key']) {
                            str += 'on';
                        }
                    }

                    str += '">' + json[i]['value'] + '</span>';
                }
            }
            $("." + objName).html(str);
            $("." + objName).find('.on').click();
        }, 'json');

    } else {
        $("." + objName).html('');
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
function listGradeOption(choose_type, objName, choose_grade, major) {

    var str = '<option value="0">请选择</option>';

    if (choose_type) {

        $.post('/apps/study/Public/getGradeByType', 'id=' + choose_type + '&type=' + major, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<option value="' + json[i]['key'] + '"';

                    if (choose_grade > 0 && choose_grade == json[i]['key']) {

                        str += 'selected';
                    }

                    str += '>' + json[i]['value'] + '</option>';
                }
            }

            $("select[name=" + objName + "]").html(str);

        }, 'json');

    } else {

        $("select[name=" + objName + "]").html(str);
    }

}

// 提示信息
function showMessage(message, status) {

    if(!$('.showMessage_cover')[0]) {    // 消息窗口不存在
        $(".warp").after('<div class="showMessage_cover"><div class="mess_html"><div class="messageWin"><p></p></div></div></div>');
        $(".messageWin p").html(message);

        if (parseInt(status) == 1) {
             $(".messageWin p").addClass('exactMessage');
        } else {
             $(".messageWin p").addClass('errorMessage');
        }
        $(".messageWin").fadeIn(600);
        $(".messageWin").fadeOut(2000);

        // 3秒后移除节点和遮罩层
        setTimeout("$('#body div').remove('.showMessage_cover')", "2000");

    }else {    // 消息窗口已存在
        return false;
    }

    // 设置遮罩层的宽高
    $(".showMessage_cover").css({
        height: function () {
            return $(document).height();
        },
        width: function () {
            return $(document).width();
        }
    });

    // 设置弹窗的高度

    var t_height = window.document.body.clientHeight;
    var new_height = t_height/2 - 44;
    $(".mess_html").css({
        height: function () {
            return new_height;
        },
        width: function () {
            return $(document).width();
        }
    });

}

// 页面加载中
function Loading() {

    $(".warp").parent().css('position','relative');
    $(".warp").after('<div class="loading_cover"><div class="loading_Win"><img src="__APPURL__/Public/Images/Home/loading.gif" /></div></div>');
    $(".loading_Win").fadeIn(600);
    // 设置loading遮罩层的宽高
    $(".loading_cover").css({
        height: function () {
            return $(document).height();
        },
        width: function () {
            return $(document).width();
        }
    });
}
function close_Loading() {
     $(".loading_Win").fadeOut(600);
     $('#body div').remove('.loading_cover');
}

// 计时器
/*
    sign:正计时和倒计时标记;sing == '+'：表示正计时;sing == '-'：表示倒计时;
    maxTime:作业最长用时
*/
function Timer(sign,maxTime) {
    if (sign == '+' && SysSecond >=0 ) {
        SysSecond = SysSecond + 1;
        var second = Math.floor(SysSecond % 60);
        var mintue = Math.floor((SysSecond / 60) % 60);
        var hour = Math.floor((SysSecond / 3600) % 24);
        if(mintue > maxTime - 1){
            $('.overTime').show();
            var oversecond = Math.floor(SysSecond % 60);
            var overmintue = Math.floor((SysSecond / 60) % 60) - maxTime;
            $(".overTime .overhour").html(hour);
            $(".overTime .overmintue").html(overmintue);
            $(".overTime .oversecond").html(oversecond);
        } else {
            $(".useSeconds .hour").html(hour);
            $(".useSeconds .mintue").html(mintue);
            $(".useSeconds .second").html(second);
        }
    }else {
        SysSecond = SysSecond - 1;
        var second = Math.floor(SysSecond % 60);
        var mintue = Math.floor((SysSecond / 60) % 60);
        var hour = Math.floor((SysSecond / 3600) % 24);
        $(".useSeconds .hour").html(hour);
        $(".useSeconds .mintue").html(mintue);
        $(".useSeconds .second").html(second);
    }
}
// 表格宽度
function setWidth(obj) {
    for(var p in obj){
        $('.' + p).css('width', obj[p]);
    }
}


function closeHTML(str){
    var arrTags=["span","font","b","u","i","h1","h2","h3","h4","h5","h6","p","li","ul","table","div"];
    for (var i = 0; i < arrTags.length; i++) {
        var intOpen = 0;
        var intClose = 0;
        var re = new RegExp("\\<"+arrTags[i]+"( [^\\<\\>]+|)\\>","ig");
        var arrMatch=str.match(re);
        alert(arrMatch);
        if (arrMatch!=null) intOpen=arrMatch.length;
        re = new RegExp("\\<\\/"+arrTags[i]+"\\>","ig");
        arrMatch = str.match(re);
        if (arrMatch!=null) intClose = arrMatch.length;
        for (var j = 0; j < intOpen-intClose; j++) {
            str += "</"+arrTags[i]+">";
        }
    }
    return str;
}

function ProvincePlugin(id, name, str) {

    if(!id || !name){
        return false;
    }

    $('#' + id + ' .jqTransformSelectWrapper ul li a').click(function(){
        var ind = $(this).parents('.jqTransformSelectWrapper').index();
        linkage(ind, $(this).attr('index'));
    })
}

// 联动
function linkage(ind, value) {

    if (ind > 1) {
        return false;
    }

    var obj = $('#province .jqTransformSelectWrapper').eq(ind);
    var optionValue = obj.find('select option').eq(value).html();
    obj.find('select').val(optionValue);
    obj.find('select').change();

    var str = '';
    obj.next().find('select option').each(function(i) {
        if (i == 0) {
            obj.next().find('div span').html($(this).html());
        }
        str += '<li><a index="'+i+'" href="javascript:;" class="selected">'+$(this).html()+'</a></li>';
    })
    obj.next().find('ul').html(str);

    linkage(++ind, 0);
}