<?php
/**
 * CourseAction
 * 课程接口
 *
 * 作者:  徐少龙
 * 创建时间: 2013-7-2
 *
 */
class CourseAction extends OpenAction {

    // 获取用户当前的班级下的课程列表（教师，学生身份）
    public function lists() {

        // 拆分接收的参数
        extract($_POST['args']);

        // 校验
        if (!intval($a_id) || !intval($s_id)) {
            $this->ajaxReturn($this->errCode[2]);
        }

        // 获取用户信息
        $this->auth = getAuthInfo($this->auth);

        $data = array();

        // 学生
        if ($this->auth['a_type'] == 1) {

            // 获取我所在的班级
            $cidArr = $this->auth['c_id'];

            // 获取我在本学年所学课程
            $xq = getXq($this->auth['s_id']);

            $where['c_id'] = array('IN', implode(',', $cidArr));
            $where['cst_year'] = $xq['cc_year'];
            $where['cst_xq'] = $xq['cc_xq'];

            // 获取标准课程
            $result = M('ClassSubjectTeacher')->where($where)->field('cst_created,cst_updated', TRUE)->select();

            foreach ($result as $ras) {
                if ($ras['co_id']) {
                    $coIds[] = $ras['co_id'];
                }
            }

            $coIds = implode(',', $coIds);

            $map = '';
            if ($coIds) {
                $map = 'co_id IN (' . $coIds . ') OR ';
            }

            $map .= '(';
            if ($cidArr) {
                foreach ($cidArr as $value) {
                    $map .= " c_id like '%,".$value.",%' OR ";
                }
            }

            $map = substr($map, 0, -3);
            // 获取所在群组
            $croidArr = M('AuthCrowd')->where(array('a_id' => $this->auth['a_id'], 's_id' => $this->auth['s_id']))->getField('cro_id', TRUE);

            // 组织查询条件
            if ($croidArr) {
                foreach ($croidArr as $value) {
                    $map .= " OR cro_id like '%,".$value.",%'";
                }
            }

            $map .= ')';

            // 获取课程信息
            $course = M('Course')->where($map)->field('co_id,a_id,s_id,co_title,co_subject,co_cover,cro_id,c_id,co_version')->select();

            $course = setArrayByField($course, 'co_id');

            // 获取班级信息
            $class = M('Class')->where(array('c_id' => array('IN', strval(implode(',', $cidArr)))))->select();
            $class = setArrayByField($class, 'c_id');

            // 获取群组信息
            $crowd = M('Crowd')->where(array('cro_id' => array('IN', strval(implode(',', $croidArr)))))->select();
            $crowd = setArrayByField($crowd, 'cro_id');

            // 获取教师信息
            $auth = getDataByArray('Auth', $course, 'a_id');

            // 获取学科
            $subjects = C('COURSE_TYPE');

            // 处理数据
            foreach ($result as $key => $value) {

                // 学科
                if ($value['cst_course']) {
                    $result[$key]['subject'] = $subjects[$value['cst_course']];
                }

                // 已指定教师
                if ($value['a_id']) {
                    $result[$key]['teacher_name'] = $auth[$value['a_id']]['a_nickname'];
                } else {
                    $result[$key]['teacher_name'] = '';
                }

                // 已指定教案
                if ($value['co_id']) {
                    $result[$key]['co_title'] = $course[$value['co_id']]['co_title'];
                    $result[$key]['co_cover'] = getCourseCover($course[$value['co_id']]['co_cover'], $course[$value['co_id']]['co_subject'], 210, 2);
                } else {
                    $result[$key]['co_cover'] = getCourseCover('', $value['cst_course'], 210, 2);
                }

                $result[$key]['c_name'] = replaceClassTitle($class[$value['c_id']]['s_id'], $class[$value['c_id']]['c_type'], YearToGrade($class[$value['c_id']]['c_grade'], $class[$value['c_id']]['s_id']), $class[$value['c_id']]['c_title'], $class[$value['c_id']]['c_is_graduation']);
            }

            $tmpArray = array();

            // 非标准课程
            foreach ($course as $cKey => $cValue) {

                $tmp = array();
                $tmp = $cValue;

                $tmp['co_cover'] = getCourseCover($cValue['co_cover'], $cValue['co_subject'], 210, 2);
                $tmp['teacher_name'] = $auth[$cValue['a_id']]['a_nickname'];
                $tmp['subject'] = $subjects[$cValue['co_subject']];

                if ($cValue['co_version'] == 0) {

                    if ($cValue['c_id'] != ',') {
                        $tmpCid = explode(',', substr($cValue['c_id'], 1, -1));
                        foreach ($tmpCid as $tcValue) {
                            if (in_array($tcValue, $cidArr)) {
                                $tmp['c_id'] = $tcValue;
                                $tmp['cro_id'] = 0;
                                $tmp['cro_name'] = '';
                                $tmp['c_name'] = replaceClassTitle($class[$tcValue]['s_id'], $class[$tcValue]['c_type'], YearToGrade($class[$tcValue]['c_grade'], $class[$tcValue]['s_id']), $class[$tcValue]['c_title'], $class[$tcValue]['c_is_graduation']);
                                $tmpArray[] = $tmp;
                            }
                        }
                    }

                    if ($cValue['cro_id'] != ',') {
                        $tmpCroId = explode(',', substr($cValue['cro_id'], 1, -1));
                        foreach ($tmpCroId as $tcoValue) {
                            if (in_array($tcoValue, $croidArr)) {
                                $tmp['c_id'] = 0;
                                $tmp['cro_id'] = $tcoValue;
                                $tmp['c_name'] = '';
                                $tmp['cro_name'] = $crowd[$tcoValue]['cro_title'];
                                $tmpArray[] = $tmp;
                            }
                        }
                    }
                } else {
                    if ($cValue['cro_id'] != ',') {
                        $tmpCroId = explode(',', substr($cValue['cro_id'], 1, -1));

                        foreach ($tmpCroId as $tcoValue) {
                            if (in_array($tcoValue, $croidArr)) {
                                $tmp['c_id'] = 0;
                                $tmp['cro_id'] = $tcoValue;
                                $tmp['c_name'] = '';
                                $tmp['cro_name'] = $crowd[$tcoValue]['cro_title'];
                                $tmpArray[] = $tmp;
                            }
                        }
                    }
                }
            }
            $data = array_merge($result, $tmpArray);
            $data = $this->procStudent($data);

        // 教师
        } elseif ($this->auth['a_type'] == 2) {

            // 获取教师授课列表
            $course = M('Course')->where(array('a_id' => $this->auth['a_id'], 's_id' => $this->auth['s_id'], 'c_id|cro_id' => array('neq', '')))->order('co_id DESC')->field('co_updated,co_created,co_html_time,co_xml_time,co_is_bind,co_count,co_note,co_object_id,co_grade,co_type,co_share,co_semester,co_version', TRUE)->select();

            // 获取课程指定的班级
            $course = D('Course')->getBindClass($course);
            $data = $this->procTeacher($course);
        }

        if (!$data) {
            $array['status'] = 0;
            $array['info'] = '无匹配数据';
        } else {
            $array['status'] = 1;
            $array['info'] = array('list' => $data);
        }

        $this->ajaxReturn($array);
    }

    // 处理教师字段
    public function procTeacher($data) {

        foreach ($data as $key => &$value) {
            if ($value['cro_name']) {
                $value['c_id'] = 0;
                $value['co_title'] = $value['cro_name'] . ' ' . $value['co_title'];
                unset($value['cro_name']);
                unset($value['c_name']);
            }
            if ($value['c_name']) {
                $value['cro_id'] = 0;
                $value['co_title'] = $value['c_name'] . ' ' . $value['co_title'];
                unset($value['c_name']);
                unset($value['cro_name']);
            }
            $value['co_cover'] = getCourseCover($value['co_cover'], $value['co_subject'], 210, 2);
            $value['co_subject'] = $value['co_subject'];
        }

        return $data;
    }

    // 处理学生字段
    public function procStudent($data) {
        foreach ($data as $key => &$value) {
            if ($value['cst_id']) {
                unset($value['cst_id']);
                unset($value['cst_year']);
                unset($value['cst_xq']);
                $value['co_subject'] = $value['cst_course'];
                unset($value['cst_course']);
                if ($value['c_name']) {
                    $value['cro_id'] = 0;
                    $value['co_title'] = $value['c_name'] . ' ' . $value['subject'];
                    unset($value['subject']);
                    unset($value['c_name']);
                    unset($value['cro_name']);
                }
                if ($value['cro_name']) {
                    $value['c_id'] = 0;
                    $value['co_title'] = $value['cro_name'] . ' ' . $value['subject'];
                    unset($value['subject']);
                    unset($value['c_name']);
                    unset($value['cro_name']);
                }
            } else {
                if ($value['cro_name']) {
                    $value['c_id'] = 0;
                    $value['co_title'] = $value['cro_name'] . ' ' . $value['co_title'];
                    unset($value['cro_name']);
                    unset($value['c_name']);
                }
                if ($value['c_name']) {
                    $value['cro_id'] = 0;
                    $value['co_title'] = $value['c_name'] . ' ' . $value['co_title'];
                    unset($value['c_name']);
                    unset($value['cro_name']);
                }

                unset($value['subject']);
            }
        }
        return $data;
    }
}