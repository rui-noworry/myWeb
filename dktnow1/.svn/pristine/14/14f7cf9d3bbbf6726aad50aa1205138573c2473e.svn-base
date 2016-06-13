<?php
/**
 * AuthModel
 * 用户模型
 *
 * 作者:  黄蕊
 * 创建时间: 2013-6-5
 *
 */
class AuthModel extends CommonModel{

    // 增加一个学生
    // $aid 学生ID  $class 班级数据
    public function addStudent($aid, $class, $oldCid = 0) {

        unset($_POST);

        if (intval($_POST['curCid'])) {
            $oldCid = intval($_POST['curCid']);
        }

        // 如果转班级
        if ($oldCid) {

            // 删除班级关系
            M('ClassStudent')->where(array('c_id' => $oldCid, 'a_id' => $aid, 's_id' => $class['s_id']))->delete();

            // 原班级人数减少
            M('Class')->where(array('c_id' => $oldCid, 's_id' => $class['s_id']))->setDec('c_peoples');
        }

        // 现班级人数增加
        M('Class')->where(array('c_id' => $class['c_id'], 's_id' => $class['s_id']))->setInc('c_peoples');

        $_POST['a_id'] = $aid;
        $_POST['c_id'] = $class['c_id'];
        $_POST['s_id'] = $class['s_id'];

        // 增加新的班级关系
        M("ClassStudent")->add($_POST);
        // 条件
        $where['a_id'] = $aid;
        $s_id = $where['s_id'] = $_POST['s_id'] = $class['s_id'];
        $where['as_end_time'] = 0;
        $where['c_id'] = $oldCid;

        // 查询学生在校记录
        $authSchool = M("AuthSchool")->where($where)->field('as_id,c_id,as_type,as_start_time,as_start_grade')->find();

        if ($authSchool) {

            // 学生班级变化
            if ($authSchool['c_id'] != $class['c_id']) {

                unset($_POST);

                // 更新原记录
                $as_end_grade = GradeToYear(intval($authSchool['as_start_grade']) + intval(date('Y', time())) - intval(date('Y', $authSchool['as_start_time'])), $s_id);

                if ($class['c_type'] != $authSchool['as_type']) {

                    if ($class['c_type'] < $authSchool['as_type']) {
                        // 开除
                        $as_status = 5;
                    }

                    if ($class['c_type'] > $authSchool['as_type']) {
                        // 毕业
                        $as_status = 1;
                    }

                } else {
                    // 调班
                    if ($class['c_grade'] == $as_end_grade) {
                        $as_status = 2;
                    }

                    // 留级
                    if ($class['c_grade'] < $as_end_grade) {
                        $as_status = 3;
                    }

                    // 跳级
                    if ($class['c_grade'] > $as_end_grade) {
                        $as_status = 4;
                    }
                }

                unset($_POST);

                $_POST['as_id'] = $authSchool['as_id'];
                $_POST['as_end_time'] = time();
                $_POST['as_status'] = $as_status;

                M("AuthSchool")->save($_POST);
            }
        }

        // 增加新记录
        unset($_POST);

        $_POST['a_id'] = $aid;
        $_POST['s_id'] = $s_id;
        $_POST['c_id'] = $class['c_id'];
        $_POST['as_type'] = $class['c_type'];
        $_POST['as_start_grade'] = YearToGrade($class['c_grade'], $s_id);
        $_POST['as_start_time'] = time();

        M("AuthSchool")->add($_POST);
    }
}

?>