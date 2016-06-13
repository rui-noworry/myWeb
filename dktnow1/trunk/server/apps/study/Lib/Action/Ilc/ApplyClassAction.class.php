<?php
/**
 * ApplyClassAction
 * 班级审核
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class ApplyClassAction extends CommonAction{

    // 初始化
    public function _initialize() {

        parent::_initialize();

        // 获取配置参数
        $type = C('SCHOOL_TYPE');
        $school = loadCache('school');
        $allowType = explode(',', $school[$this->authInfo['s_id']]['s_type']);

        // 获取该学校学制类型
        $co_type = array();
        foreach ($allowType as $key => $value) {
            if ($value) {
                $co_type[$value] = $type[$value];
            }
        }

        if (!$co_type) {
            $this->error('本学校未设置学制');
        }

        $this->co_type = $co_type;

    }

    public function index() {

        $this->display();
    }

    // 列表
    public function lists() {

        // 接收参数
        $c_grade = intval($_POST['c_grade']);
        $c_type = intval($_POST['c_type']);
        $ma_id = intval($_POST['ma_id']);

        $s_id = $this->authInfo['s_id'];

        // 查询条件
        if ($c_grade) {
            $where['ac_grade'] = GradeToYear($c_grade, $s_id);
        }

        if ($c_type) {
            $where['ac_type'] = $c_type;
        }

        if ($ma_id) {
            $where['ma_id'] = $ma_id;
        }

        $where['s_id'] = $s_id;
        $where['ac_is_pass'] = 0;

        // 获取数据
        $result = getListByPage('ApplyClass', 'ac_id DESC', $where, 10, 1, intval($_POST['p']));

        foreach ($result['list'] as $key => $value) {

            $grade = YearToGrade($value['ac_grade'], $s_id);

            $result['list'][$key]['type'] = getTypeNameById($value['ac_type'], 'SCHOOL_TYPE');
            $result['list'][$key]['grade'] = getGradeByType($value['ac_type'], $s_id, $grade);
            $result['list'][$key]['c_name'] = replaceClassTitle($s_id, $value['ac_type'], $grade, $value['ac_title'], 0, $value['ma_id']);
        }

        echo json_encode($result);
    }

    // 通过审核
    public function pass() {

        $id = intval($_POST['ac_id']);

        if (!$id) {
            echo 0;exit;
        }

        // 验证
        $ac = M('ApplyClass')->where(array('ac_id' => $id, 's_id' => $this->authInfo['s_id']))->find();

        if (!$ac) {
            echo 0; exit;
        }

        $data['s_id'] = $this->authInfo['s_id'];
        $data['c_type'] = $ac['ac_type'];
        $data['ma_id'] = $ac['ma_id'];
        $data['c_grade'] = $ac['ac_grade'];
        $data['c_title'] = $ac['ac_title'];
        $data['c_graduation'] = intval(count(getGradeByType($ac['ac_type'], $this->authInfo['s_id']))) - intval(YearToGrade($ac['ac_grade'], $this->authInfo['s_id'])) + intval($ac['ac_grade']) + 1;

        if (!$result['status'] = M('Class')->where($data)->getField('c_id')) {
            $data['c_created'] = time();
            $result['status'] = M('Class')->add($data);
            $result['info'] = '审核通过';
        } else {
            $result['info'] = '该班级已存在，审核不通过';
        }
        M('ApplyClass')->where(array('ac_id' => $id))->save(array('ac_is_pass' => $result['status']));
        M('ApplyAuth')->where(array('ac_id' => $id))->save(array('c_id' => $result['status']));

        echo json_encode($result);
    }

    // 删除
    public function delete() {
        $res = M('ApplyClass')->where(array('ac_id' => intval($_POST['id']), 's_id' => $this->authInfo['s_id']))->delete();
        echo intval($res);
    }
}
?>