<?php

// 获取状态
function getStatus($status, $imageShow=true) {
    switch($status) {
        case 9:
            $showText = '禁用';
            $showImg  = '<img src="__APPURL__/Public/Images/Ilc/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
            break;
        case 2:
            $showText = '待审';
            $showImg  = '<img src="__APPURL__/Public/Images/Ilc/checkin.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
            break;
        case 3:
            $showText = '发布';
            $showImg  = '<img src="__APPURL__/Public/Images/Ilc/record.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="发布">';
            break;
        case -1:
            $showText = '删除';
            $showImg  = '<img src="__APPURL__/Public/Images/Ilc/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
            break;
        case 1:
        default:
            $showText = '正常';
            $showImg  = '<img src="__APPURL__/Public/Images/Ilc/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

    }
    return ($imageShow===true)? ($showImg) : $showText;
}

// 状态显示
function showStatus($status, $id) {
    switch($status) {
        case 9:$info = '<a href="javascript:resume(' . $id . ')">启用</a>';break;
        case 2:$info = '<a href="javascript:pass(' . $id . ')">批准</a>';break;
        case 1:$info = '<a href="javascript:forbid(' . $id . ')">禁用</a>';break;
        case -1:$info= '<a href="javascript:recycle(' . $id . ')">还原</a>';break;
    }
    return $info;
}

// 获取角色名称
function getGroupName($id) {
    if($id==0) {
        return '无上级组';
    }
    $list = M("Role")->field('r_id,r_name')->select();
    foreach ($list as $vo) {
        $nameList[$vo['r_id']] = $vo['r_name'];
    }
    $name = $nameList[$id];
    return $name;
}

// 获取后台管理员账号
function getUserAccount($id) {
    return M('User')->where(array('u_id' => $id))->getField('u_account');
}

// 通过ID显示用户名
function getAuthNameById($id) {

    if ($id) {
        return M('Auth')->where(array('a_id' => $id))->getField('a_nickname');
    } else {
        return '';
    }
}

function pwdHash($password, $type = 'md5') {
    return hash($type, $password);
}
?>