<?php
/**
 * IndexAction
 * 首页
 *
 * 作者:  肖连义 (xiaoly@mink.com.cn)
 * 创建时间: 2013-5-9
 *
 */
class IndexAction extends CommonAction{

    /*
     * 学校系统统计
     * 1 => '课程',
     * 2 => '资源',
     * 3 => '班级',
     * 4 => '群组',
     * 5 => '教师',
     * 6 => '学生',
     */
    public function index() {

        /* 课程类型 */

        // 获取课程配置
        $subject = C('COURSE_TYPE');

        // 课程数据
        $course = M('SchoolSystem')->where(array('s_id' => $this->authInfo['s_id'], 'ss_type' => 1))->limit('0, 4')->order('ss_value DESC')->select();

        foreach ($course as $ck => $cv) {

            if ($cv['ss_name'] == 0) {
                $courseSum = $cv['ss_value'];
            } else {
                $courseArr[$ck]['ss_name'] = $cv['ss_name'];
                $courseArr[$ck]['ss_title'] = $subject[$cv['ss_name']];
                $courseArr[$ck]['ss_value'] = $cv['ss_value'];
            }
        }

        $courseSum = $courseSum ? $courseSum : 0;
        $this->assign('courseArr', $courseArr);
        $this->assign('courseSum', $courseSum);

        /* 资源类型 */
        $model = reloadCache('model');
        $model = setArrayByField($model, 'm_id');

        // 资源数据
        $resource = M('SchoolSystem')->where(array('s_id' => $this->authInfo['s_id'], 'ss_type' => 2))->limit('0, 5')->order('ss_value DESC')->select();

        foreach ($resource as $rk => $rv) {

            if ($rv['ss_name'] == 0) {
                $resourceSum = $rv['ss_value'];
            } else {
                $resourceArr[$rk]['ss_name'] = $rv['ss_name'];
                $resourceArr[$rk]['ss_title'] = $model[$rv['ss_name']]['m_title'];
                $resourceArr[$rk]['ss_value'] = $rv['ss_value'];
            }
        }

        $resourceSum = $resourceSum ? $resourceSum : 0;
        $this->assign('resourceArr', $resourceArr);
        $this->assign('resourceSum', $resourceSum);


        /* 班级类型 */
        $class = M('SchoolSystem')->where(array('s_id' => $this->authInfo['s_id'], 'ss_type' => 3))->order('ss_name ASC')->select();

        foreach ($class as $clk => $clv) {

            if ($clv['ss_name'] == 0) {
                $classSum = $clv['ss_value'];
            } else {
                $classArr[$clk]['ss_name'] = $clv['ss_name'];
                $classArr[$clk]['ss_title'] = replaceClassTitle($this->authInfo['s_id'], $clv['ss_name'][0], $clv['ss_name'][1]);
                $classArr[$clk]['ss_value'] = $clv['ss_value'];
            }
        }

        $classSum = $classSum ? $classSum : 0;
        $this->assign('classArr', $classArr);
        $this->assign('classSum', $classSum);

        /* 群组类型 */
        $crowd = M('SchoolSystem')->where(array('s_id' => $this->authInfo['s_id'], 'ss_type' => 4))->order('ss_name DESC')->select();

        $croId = getValueByField($crowd, 'ss_name');

        $crowdInfo = M('Crowd')->where(array('cro_id' => array('IN', $croId)))->field('cro_title, cro_id')->select();
        $crowdInfo = setArrayByField($crowdInfo, 'cro_id');

        foreach ($crowd as $crk => $crv) {

            if ($crv['ss_name'] == 0) {
                $crowdSum = $crv['ss_value'];
            } else {
                $crowdArr[$crk]['ss_name'] = $crv['ss_name'];
                $crowdArr[$crk]['ss_title'] = $crowdInfo[$crv['ss_name']]['cro_title'];
            }
        }

        $crowdSum = $crowdSum ? $crowdSum : 0;
        $this->assign('crowdArr', $crowdArr);
        $this->assign('crowdSum', $crowdSum);


        /* 教师类型 */
        $teacher = M('SchoolSystem')->where(array('s_id' => $this->authInfo['s_id'], 'ss_type' => 5))->order('ss_value DESC')->limit('0, 5')->select();

        foreach ($teacher as $tk => $tv) {

            if ($tv['ss_name'] == 0) {
                $teacherSum = $tv['ss_value'];
            } else {
                $teacherArr[$tk]['ss_name'] = $tv['ss_name'];
                $teacherArr[$tk]['ss_title'] = $subject[$tv['ss_name']];
                $teacherArr[$tk]['ss_value'] = $tv['ss_value'];
            }
        }

        foreach ($teacherArr as $tak => $tav) {
            $teacherArr[$tak]['ss_persent'] = floor(($tav['ss_value'] / $teacherSum) * 100) . '%';
        }

        $teacherSum = $teacherSum ? $teacherSum : 0;
        $this->assign('teacherArr', $teacherArr);
        $this->assign('teacherSum', $teacherSum);


        /* 学生类型 */
        $student = M('SchoolSystem')->where(array('s_id' => $this->authInfo['s_id'], 'ss_type' => 6))->order('ss_value DESC')->select();

        $aId = getValueByField($student, 'ss_name');

        $studentInfo = M('Auth')->where(array('a_id' => array('IN', $aId)))->field('a_id, a_nickname')->select();
        $studentInfo = setArrayByField($studentInfo, 'a_id');

        $i = 0;

        foreach ($student as $sk => $sv) {

            if ($sv['ss_name'] == 0) {
                $studentSum = $sv['ss_value'];
            } else {

                $i ++;
                $studentArr[$i]['ss_value'] = $sv['ss_value'];
                $studentArr[$i]['ss_title'] = $studentInfo[$sv['ss_name']]['a_nickname'];
            }
        }

        $studentSum = $studentSum ? $studentSum : 0;
        $this->assign('studentArr', $studentArr);
        $this->assign('studentSum', $studentSum);

        $this->display();
    }
}
?>