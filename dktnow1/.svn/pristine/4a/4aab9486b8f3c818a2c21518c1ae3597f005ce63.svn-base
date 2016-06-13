<?php
/**
 * SchoolAction
 * 学校模块
 *
 * 作者:  黄蕊
 * 创建时间: 2013-5-6
 *
 */
class SchoolAction extends BaseAction{

    public function index() {

        $this->assign('authInfo', $this->authInfo);
        $this->display('index');

    }

    // 通过学段和地区获取学校数据
    function getSchoolByRegion() {

        // 获取参数
        $s_type = intval($_POST['xueduan']);
        $region = trim($_POST['region']);

        if ($s_type) {
            $where['s_type'] = array('LIKE', '%'.$s_type.'%');
        }
        if ($region) {
            $where['s_region'] = $region;
        }
        // 如果已经属于某个学校，则只显示本校信息
        if ($this->authInfo['s_id']) {
            $where['s_id'] = $this->authInfo['s_id'];
        }

        // 获取数据
        $res = M('School')->where($where)->field('s_id, s_name, s_type')->select();
        if ($res) {
            $result['list'] = $res;
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
            $result['info'] = '该区域没有学校';
        }

        echo json_encode($result);
    }
}
?>