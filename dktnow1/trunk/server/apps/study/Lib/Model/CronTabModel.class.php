<?php
/**
 * CronTabModel
 * 自动执行删除模型
 * 作者: 黄蕊
 * 创建时间: 2013-6-3
 *
 */
class CronTabModel extends CommonModel{

    // 自动删除课时, 环节, 活动
    public function autoDelClasshour() {

        // 获取已被删除的课时ID
        $cl_ids = M('Classhour')->where(array('cl_status' => 0))->getField('cl_id', TRUE);

        if (!is_array($cl_ids)) {
            return 0;
        }

        // 删除课时
        $cl_res = M('Classhour')->where(array('cl_id' => array('IN', $cl_ids)))->delete();

        if (!$cl_res) {
            return 0;
        }

        // 删除课时下所有环节
        $ta_res = M('Tache')->where(array('cl_id' => array('IN', $cl_ids)))->delete();

        if (!$ta_res) {
            return 0;
        }

        // 删除环节下的所有活动
        $act_res = M('Activity')->where(array('cl_id' => array('IN', $cl_ids)))->delete();

        if (!$act_res) {
            return 0;
        }

    }

    // 自动毕业
    public function autoGraduate($s_id) {

        // 获取当前学期，从而计算上个学期的毕业情况
        $term = getXq($s_id);

        // 上学期
        if ($term['cc_xq'] == 1) {
            $arr['cc_xq'] = 2;
            $arr['cc_year'] = $term['cc_year'] - 1;

            // 获取本学年上学期结束时间
            $time = M('SchoolYear')->where(array('s_id' => $s_id, 'sy_year' => date('Y')))->getField('sy_up_end');

        // 下学期
        } else {
            $arr['cc_xq'] = 1;
            $arr['cc_year'] = $term['cc_year'];

            // 获取本学年下学期结束时间
            $time = M('SchoolYear')->where(array('s_id' => $s_id, 'sy_year' => date('Y')))->getField('sy_down_end');
        }

        // 更新教师授课表
        M('TeacherCourseLog')->where(array('s_id' => $s_id, 'tc_xq' => $arr['cc_xq'], 'tc_year' => $arr['cc_year']))->save(array('tc_end_time' => time()));

        // 查询该校有多少班级
        $classes = M('Class')->where(array('s_id' => $s_id, 'c_grade' => $arr['cc_year']))->select();
        if ($classes) {
            foreach ($classes as $key => $value) {
                $this->writeClassLog($value, $arr);
            }
        }

        // 重置学校下所有的课程、课时、活动的发布状态
        M('Course')->where(array('s_id' => $s_id))->save(array('c_id' => '', 'cro_id' => '', 'co_is_bind' => 0, 'co_xml_time' => 0, 'co_html_time' => 0));
        M('Classhour')->where(array('s_id' => $s_id))->save(array('c_id' => '', 'cro_id' => '', 'cl_is_published' => 0));
        M('Activity')->where(array('s_id' => $s_id))->save(array('c_id' => '', 'cro_id' => '', 'act_is_published' => 0, 'act_is_auto_publish' => 0));


        // 清空本校下课时发布、活动发布
        M('ClasshourPublish')->where(array('s_id' => $s_id))->delete();
        M('ActivityPublish')->where(array('s_id' => $s_id))->delete();

        // 清空(删除)本校的班级课程教师表数据
        $res = M('ClassSubjectTeacher')->where(array('s_id' => $s_id, 'cst_xq' => $arr['cc_xq'], 'cst_year' => $arr['cc_year']))->delete();

        // 更新dkt_school表的s_next_graduate_time字段并清除学校缓存文件
        M('School')->where(array('s_id' => $s_id))->save(array('s_next_graduate_time' => $time, 's_is_graduating' => 0));
        @unlink(DATA_PATH . '~school.php');
    }

    // 写班级历史表
    public function writeClassLog($data, $term) {

        // 获取班级的学生ID
        $arr = array();
        $arr['clo_student'] = implode(',', M('ClassStudent')->where(array('c_id' => $data['c_id']))->getField('a_id', TRUE));
        $arr['s_id'] = $data['s_id'];
        $arr['s_year'] = $data['c_grade'];
        $arr['s_xq'] = $term['cc_xq'];
        $arr['class_id'] = $data['c_id'];
        $tmp = '';
        $tmp .= $data['a_id'] . ':0,';
        $ClassSubjectTeacher = M('ClassSubjectTeacher')->where(array('c_id' => $data['c_id']))->field('a_id,cst_course,co_id')->select();
        foreach ($ClassSubjectTeacher as $key => $value) {
            $tmp .= $value['a_id'] . ':' . $value['cst_course'] . ',';
        }
        $arr['clo_teacher_info'] = trim($tmp, ',');
        $arr['clo_created'] = time();
        M('ClassLog')->add($arr);

        // 获取课程ID，处理班级课程系列数据
        $co_id = getValueByField($ClassSubjectTeacher, 'co_id');
        if ($co_id) {
            foreach ($co_id as $value) {
                $this->copyClassInfo($value, $arr);
            }
        }

        // 判断班级是否毕业，是的话则把dkt_class表的c_is_graduation字段设为1
        // 同时把班级里的学生都清空，最后把班级的每个毕业的学生写入学生在校历史表
        if ($data['c_graduation'] <= intval(date('Y')) && $term['cc_xq'] == 2 && $data['c_is_graduation'] == 0) {
            M('Class')->where(array('c_id' => $data['c_id']))->save(array('c_is_graduation' => 1));
            M('ClassStudent')->where(array('c_id' => $data['c_id']))->delete();
            M('AuthSchool')->where(array('c_id' => $data['c_id']))->save(array('as_end_time' => time(), 'as_status' => 1));
        }

    }

    // 复制班级相关数据(课程、课文、课时、环节、活动、题目表)
    public function copyClassInfo($co_id, $data) {

        extract($data);

        // 生成课程历史表
        $course = M('Course')->where(array('co_id' => $co_id))->find();
        extract($course);
        M()->query("INSERT INTO dkt_class_course_log VALUES ($s_id,$s_year,$s_xq,$class_id,$co_id,$a_id,'" . addslashes($co_title) . "','$co_count','$co_type','$co_grade','$co_semester','$co_subject','$co_version','$co_cover','$co_note','$co_share','$co_object_id','$cro_id','$c_id','$co_is_bind','$co_xml_time','$co_html_time','$co_created','$co_updated'," . time() . ")");

        // 生成课文历史表
        $lesson = M('Lesson')->where(array('co_id' => $co_id))->select();
        foreach ($lesson as $value) {
            extract($value);
            M()->query("INSERT INTO dkt_class_lesson_log VALUES ($s_id,$s_year,$s_xq,$class_id,$l_id,$d_id,$co_id,$a_id,$l_pid,$l_sort,'" . addslashes($l_title) ."','$l_created'," . time() . ")");
        }

        // 生成课时历史表
        $classhour = M('Classhour')->where(array('co_id' => $co_id, 'c_id' => array('like', '%,' . $class_id . ',%')))->select();
        foreach ($classhour as $value) {
            extract($value);
            M()->query("INSERT INTO dkt_class_classhour_log VALUES ($s_id,$s_year,$s_xq,$class_id,$cl_id,$co_id,$l_id,$a_id,'" . addslashes($cl_title) ."',$cl_sort,'$c_id','$cro_id','$cl_is_published','$cl_status',$cl_created,$cl_updated," . time() . ")");
        }

        // 生成环节历史表
        $tache = M('Tache')->where(array('co_id' => $co_id))->select();
        foreach ($tache as $value) {
            extract($value);
            M()->query("INSERT INTO dkt_class_tache_log VALUES ($s_id,$s_year,$s_xq,$class_id,$ta_id,$a_id,$co_id,$l_id,$cl_id,'" . addslashes($ta_title) . "','$act_id',$ta_created,$ta_updated,$ta_deleted," . time() . ")");
        }

        // 生成活动历史表
        $activity = M('Activity')->where(array('co_id' => $co_id, 'c_id' => array('like', '%,' . $class_id . ',%')))->select();
        foreach ($activity as $value) {
            extract($value);
            M()->query("INSERT INTO dkt_class_activity_log VALUES ($s_id,$s_year,$s_xq,$class_id,$act_id,$a_id,$co_id,$l_id,$cl_id,$ta_id,'$cro_id','$c_id','" . addslashes($act_title) . "','$act_rel',$act_is_comment,'" . addslashes($act_note) . "',$act_type,$act_is_published,'$act_stat',$act_peoples,$act_is_auto_publish,$act_is_attachment,$act_is_homework,$act_is_delay,$act_status,$act_created,$act_updated,$act_deleted," . time() . ")");
        }

        // 生成题目历史表
        $toId = getValueByField($activity, 'act_rel');
        if ($toId) {
            $topic = M('Topic')->where(array('to_id' => array('IN', implode(',', $toId))))->select();
            foreach ($topic as $value) {
                extract($value);
                M()->query("INSERT INTO dkt_class_topic_log VALUES ($s_id,$s_year,$s_xq,$class_id,$to_id,$a_id,'" . addslashes($to_title) . "',$to_type,'$to_option','$to_answer','" . addslashes($to_note) . "',$to_right_peoples,$to_created,$to_updated,$to_deleted," . time() . ")");
            }
        }

    }

    /*
     * 学校系统统计
     * 1 => '课程',
     * 2 => '资源',
     * 3 => '班级',
     * 4 => '群组',
     * 5 => '教师',
     * 6 => '学生',
     */
    public function schoolSystem() {

        // 获取所有学校
        $school = M('School')->where(array('s_status' => 1))-> field('s_id')->select();

        foreach ($school as $v) {

            $s_id = $v['s_id'];

            /* 课程类型 */
            $course = M('Course')->query('SELECT co_subject, COUNT(*) AS num FROM dkt_course WHERE s_id = ' . $s_id . ' AND co_subject <> 0 GROUP BY co_subject ORDER BY num DESC LIMIT 0,3');

            // 获取总课程数
            $courseSum = M('Course')->where(array('s_id' => $s_id))->count();

            $course[3]['co_subject'] = 0;
            $course[3]['num'] = $courseSum;

            // 验证是否数据已存在
            $ssNameArray = M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 1))->getField('ss_name', TRUE);

            // 组织数据添加到数据库
            $courseData['s_id'] = $s_id;
            $courseData['ss_type'] = 1;

            foreach ($course as $cv) {

                $courseData['ss_name'] = $cv['co_subject'];
                $courseData['ss_value'] = $cv['num'];

                if (!in_array($cv['co_subject'], $ssNameArray)) {

                    M('SchoolSystem')->add($courseData);

                } else {

                    M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 1, 'ss_name' => $cv['co_subject']))->save($courseData);

                }

            }

            /* 资源类型 */
            $resource = M('Resource')->query('SELECT m_id, COUNT(*) AS num FROM dkt_resource WHERE s_id = ' . $s_id . ' GROUP BY m_id ORDER BY num DESC');

            // 获取资源总数
            $resourceSum = M('Resource')->where(array('s_id' => $s_id))->count();

            $resourceAll[0]['m_id'] = 0;
            $resourceAll[0]['num'] = $resourceSum;

            $newResource = array_merge($resource, $resourceAll);

            // 验证是否数据已存在
            $resNameArray = M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 2))->getField('ss_name', TRUE);

            $resourceData['s_id'] = $s_id;
            $resourceData['ss_type'] = 2;

            foreach ($newResource as $rk => $rv) {

                $resourceData['ss_name'] = $rv['m_id'];
                $resourceData['ss_value'] = $rv['num'];

                if (!in_array($rv['m_id'], $resNameArray)) {

                    M('SchoolSystem')->add($resourceData);

                } else {

                    M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 2, 'ss_name' => $rv['m_id']))->save($resourceData);

                }
            }

            /* 班级类型 */
            $class = M('Class')->query('SELECT c_type, c_grade, COUNT(*) as num FROM dkt_class WHERE s_id = ' . $s_id . ' GROUP BY c_type,c_grade');

            $Arr = array();

            foreach ($class as $clk => $clv) {
                $grade = $clv['c_type'] . YearToGrade($clv['c_grade']);
                $Arr[$grade] = array('grade' => $grade, 'num' => $clv['num']);
            }

            // 获取资源总数
            $classSum = M('Class')->where(array('s_id' => $s_id))->count();

            $classAll[0]['grade'] = 0;
            $classAll[0]['num'] = $classSum;

            $newClass = array_merge($Arr, $classAll);

            // 验证是否数据已存在
            $classNameArray = M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 3))->getField('ss_name', TRUE);

            $classData['s_id'] = $s_id;
            $classData['ss_type'] = 3;

            foreach ($newClass as $nk => $nv) {

                $classData['ss_name'] = $nv['grade'];
                $classData['ss_value'] = $nv['num'];

                if (!in_array($nv['grade'], $classNameArray)) {

                    M('SchoolSystem')->add($classData);

                } else {

                    M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 3, 'ss_name' => $nv['grade']))->save($classData);

                }
            }


            /* 群组类型 */
            $crowd = M('Crowd')->where(array('s_id' => $s_id))->order('cro_id DESC')->limit('0, 10')->field('cro_id, cro_created')->select();

            // 获取群组总数
            $crowdSum = M('Crowd')->where(array('s_id' => $s_id))->count();

            $crowdAll[0]['cro_id'] = 0;
            $crowdAll[0]['cro_created'] = $crowdSum;

            $newCrowd = array_merge($crowd, $crowdAll);

            M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 4))->delete();

            $crowdData['s_id'] = $s_id;
            $crowdData['ss_type'] = 4;

            foreach ($newCrowd as $crk => $crv) {

                $crowdData['ss_name'] = $crv['cro_id'];
                $crowdData['ss_value'] = $crv['cro_created'];

                M('SchoolSystem')->add($crowdData);

            }

            /* 教师类型 */
            $teacherCourse = M('ClassSubjectTeacher')->query('SELECT cst_course, COUNT(*) AS num FROM dkt_class_subject_teacher WHERE s_id = ' . $s_id . ' GROUP BY cst_course ORDER BY num DESC LIMIT 0,3');

            // 获取本学校的教师总人数
            $teacherSum = M('Auth')->where(array('s_id' => $s_id, 'a_type' => 2))->count();

            $teacherAll[3]['cst_course'] = 0;
            $teacherAll[3]['num'] = $teacherSum;

            $newTeacher = array_merge($teacherCourse, $teacherAll);

            // 验证是否数据已存在
            $teaNameArray = M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 5))->getField('ss_name', TRUE);

            // 组织数据添加到数据库
            $teacherData['s_id'] = $s_id;
            $teacherData['ss_type'] = 5;

            foreach ($newTeacher as $tk => $tv) {

                $teacherData['ss_name'] = $tv['cst_course'];
                $teacherData['ss_value'] = $tv['num'];

                if (!in_array($tv['cst_course'], $teaNameArray)) {

                    M('SchoolSystem')->add($teacherData);

                } else {

                    M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 5, 'ss_name' => $tv['cst_course']))->save($teacherData);

                }

            }

            /* 学生类型 */
            $auth = M('Auth')->where(array('s_id' => $s_id, 'a_type' => 1))->order('a_bean DESC')->limit('0, 10')->field('a_id, a_bean')->select();

            // 获取该校学生总数
            $authSum = M('Auth')->where(array('s_id' => $s_id, 'a_type' => 1))->count();

            $authAll[0]['a_id'] = 0;
            $authAll[0]['a_bean'] = $authSum;

            $newAuth = array_merge($auth, $authAll);

            M('SchoolSystem')->where(array('s_id' => $s_id, 'ss_type' => 6))->delete();

            $authData['s_id'] = $s_id;
            $authData['ss_type'] = 6;

            foreach ($newAuth as $ak => $av) {

                $authData['ss_name'] = $av['a_id'];
                $authData['ss_value'] = $av['a_bean'];

                M('SchoolSystem')->add($authData);

            }

        }

    }

}