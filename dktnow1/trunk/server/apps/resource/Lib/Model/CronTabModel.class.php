<?php
/**
 * CronTabModel
 * 自动执行删除模型
 * 作者: 黄蕊
 * 创建时间: 2013-6-3
 *
 */
class CronTabModel extends CommonModel{

    public function autoCache() {

        import('@.ORG.Io.Dir');
        Dir::del(C('HTML_PATH'));

        $where['re_is_pass'] = 1;
        $where['re_is_transform'] = 1;
        $where['s_id'] = 0;

        // 最新
        $newRes = M('Resource')->where($where)->order('re_id DESC')->limit(12)->select();

        // 最热
        $hotRes = M('Resource')->where($where)->order('re_hits DESC')->limit(12)->select();

        // 推荐
        $where['re_recommend'] = 1;
        $recommendRes = M('Resource')->where($where)->order('re_id DESC')->limit(12)->select();

        // 获取上传人
        $aIds = getValueByField($newRes, 'a_id');
        $aIds = array_merge($aIds, getValueByField($hotRes, 'a_id'));
        $aIds = array_merge($aIds, getValueByField($recommendRes, 'a_id'));

        $auth = M('Auth')->where(array('a_id' => array('IN', $aIds)))->field('a_id,a_nickname')->select();
        $auth = setArrayByField($auth, 'a_id');

        foreach ($newRes as $nKey => $nValue) {
            $newRes[$nKey]['a_nickname'] = $nValue['a_id'] == 0 ? '' : $auth[$nValue['a_id']]['a_nickname'];
            $newRes[$nKey]['re_img'] = getResourceImg($nValue, 1);
        }
        file_put_contents(C('HTML_PATH').'new.txt', json_encode((array)$newRes));

        foreach ($hotRes as $hKey => $hValue) {
            $hotRes[$hKey]['a_nickname'] = $hValue['a_id'] == 0 ? '' : $auth[$hValue['a_id']]['a_nickname'];
            $hotRes[$hKey]['re_img'] = getResourceImg($hValue, 1);
        }
        file_put_contents(C('HTML_PATH').'hot.txt', json_encode((array)$hotRes));

        foreach ($recommendRes as $rKey => $rValue) {
            $recommendRes[$rKey]['a_nickname'] = $rValue['a_id'] == 0 ? '' : $auth[$rValue['a_id']]['a_nickname'];
            $recommendRes[$rKey]['re_img'] = getResourceImg($rValue, 1);
        }
        file_put_contents(C('HTML_PATH').'recommend.txt', json_encode((array)$recommendRes));

        $map['ras_from'] = 0;

        // 本月
        $time = strtotime("-".(date('j', time()) - 1)." day");
        $month = M()->query('SELECT *, sum(ras_created > ' . $time . ') AS ras_sum FROM `dkt_resource_access_stat` WHERE s_id = 0 AND ras_from = 0 GROUP BY re_id HAVING ras_sum > 0 ORDER BY ras_sum DESC LIMIT 0, 10');

        // 本周
        $time = strtotime("-".(date('N', time()) - 1)." day");
        $week = M()->query('SELECT *, sum(ras_created > ' . $time . ') AS ras_sum FROM `dkt_resource_access_stat` WHERE s_id = 0 AND ras_from = 0 GROUP BY re_id HAVING ras_sum > 0 ORDER BY ras_sum DESC LIMIT 0, 10');

        // 本日
        $time = strtotime(date('Ymd', time())) - 24 * 3600;
        $today = M()->query('SELECT *, sum(ras_created > ' . $time . ') AS ras_sum FROM `dkt_resource_access_stat` WHERE s_id = 0 AND ras_from = 0 GROUP BY re_id HAVING ras_sum > 0 ORDER BY ras_sum DESC LIMIT 0, 10');

        // 获取上传人
        $reIds = getValueByField($month, 're_id');
        $reIds = array_merge($reIds, getValueByField($week, 're_id'));
        $reIds = array_merge($reIds, getValueByField($today, 're_id'));

        if ($reIds) {
            $resource = M('Resource')->where(array('re_id' => array('IN', $reIds), 're_is_pass' => 1, 's_id' => 0))->field('re_id,re_title')->select();
        }

        if (!$resource) {
            $resource = M('Resource')->where(array('re_is_pass' => 1, 's_id' => 0))->field('re_id,re_title')->limit(10)->select();
            $month = $week = $today = $resource;
        }

        $resource = setArrayByField($resource, 're_id');

        foreach ($month as $mKey => $mValue) {
            $month[$mKey]['re_title'] = $resource[$mValue['re_id']]['re_title'];
        }
        file_put_contents(C('HTML_PATH').'month.txt', json_encode((array)$month));

        foreach ($week as $wKey => $wValue) {
            $week[$wKey]['re_title'] = $resource[$wValue['re_id']]['re_title'];
        }
        file_put_contents(C('HTML_PATH').'week.txt', json_encode((array)$week));

        foreach ($today as $tKey => $tValue) {
            $today[$tKey]['re_title'] = $resource[$tValue['re_id']]['re_title'];
        }
        file_put_contents(C('HTML_PATH').'today.txt', json_encode((array)$today));
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