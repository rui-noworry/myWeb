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

    if (className) {
        //var str = '<label class="'+className+'">年级：</label><span class="hand on" attr="0" >不限</span>';
    } else {
        var str = '<label class="la">年级：</label>';
    }

    if (choose_type) {

        $.post('/Public/getGradeByType', 'id=' + choose_type, function(json) {

            if (json) {

                for (var i = 0; i < json.length; i ++ ) {

                    str += '<span rel="' + json[i]['key'] + '" class="hand ';

                    if (!className) {

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
 *
 */
function listGradeOption(choose_type, objName, choose_grade) {

    var str = '<option value="0">请选择</option>';

    if (choose_type) {

        $.post('/Public/getGradeByType', 'id=' + choose_type, function(json) {

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
        $(".warp").after('<div class="showMessage_cover"><div class="messageWin"><p></p></div></div>');
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
