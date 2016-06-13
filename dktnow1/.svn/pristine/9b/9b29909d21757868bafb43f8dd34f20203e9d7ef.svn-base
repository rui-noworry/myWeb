<?php
/**
 * ApplySchoolAction
 * 学校审核
 *
 */
class ApplySchoolAction extends CommonAction {

    // 过滤
    public function _filter(&$map) {

       $map['as_is_pass'] = 0;
    }

    // 通过
    public function apply() {

        $as_id = intval($_GET['id']);

        // 获取申请学校数据
        $data = M('ApplySchool')->find($as_id);

        $school['s_name'] = $data['as_title'];
        $school['s_region'] = $data['as_region'];
        $school['s_address'] = $data['as_address'];
        $school['s_type'] = $data['as_type'];
        $school['s_created'] = time();
        $school['s_status'] = 1;

        // 写入学校表
        $res = M('School')->add($school);

        // 更改状态为通过后的学校ID
        $applySchool = M('ApplySchool')->where(array('as_id' => $as_id))->save(array('as_is_pass' => $res));

        // 更改申请班级对应的学校ID
        M('ApplyClass')->where(array('as_id' => $as_id))->save(array('s_id' => $res));
        @unlink(DATA_PATH . '~school.php');
        $this->success();
    }
}
?>
